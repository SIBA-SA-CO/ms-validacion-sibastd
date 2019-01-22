<?php

namespace Siba\loadstd\controllers;

class LoadStdController extends \BaseController {

	
	function makeFilesReadyToProcess (){

		$fileSeeker = new \Siba\loadstd\classes\TextSeekerFiles();
		$txtfiles = $fileSeeker->seekFiles(\Config::get('sibastd.RUTA_LOADDATA_UPLOADING'));

		foreach ($txtfiles as $txtfile){

			preg_match("/([^\/]{1,190}\.txt$)/i",$txtfile,$fileResults);
			//1. Busca un archivo con el mismo nombre y en estado "UPLOADING"
			$file = \Siba\loadstd\Models\FileDataSource::whereRaw("name='".$fileResults[1]."' AND status='uploading'")->first();
			if (file_exists(\Config::get('sibastd.RUTA_LOADDATA_UPLOADING')."/".$fileResults[1])){
				if ($file==null){

					//Note: No existe el archivo registrado en la DB, lo crea y lo deja en estado "uploading"

					$fileData = array (
						'checksum' => md5_file (\Config::get('sibastd.RUTA_LOADDATA_UPLOADING')."/".$fileResults[1]),
						'name' => $fileResults[1],
						'status' => 'uploading',
						'notes' => ''
					);
					$loadDataFile = new \Siba\loadstd\Models\FileDataSource($fileData);
					$loadDataFile->save();
					//==================================================
					//Escribe en el Log
					$logger = new \Misc\CustomLogger("LOADDATA");
					$msg = "Se ha registrado para carga el archivo: ".$loadDataFile->name.", Checksum (".$loadDataFile->checksum.")";
					$logger->writeToLog($msg,'INFO');


				}
				else {

					//Si existe el archivo en DB, comprueba el checksum del registro en DB con el del archivo fisico.
					//Si son iguales, es por que el archivo ya fué correctamente cargado por ftp, si no, es por que aún
					//está en carga y se deja igual
					if ($file->checksum == md5_file (\Config::get('sibastd.RUTA_LOADDATA_UPLOADING')."/".$fileResults[1])){

						//1. Mueve el archivo a la carpeta "READY" (Donde serán leidos para procesamiento).
						$fileMover = new \Siba\loadstd\classes\TextFileMover();
						$fileMover->moveFileToPath($file,\Config::get('sibastd.RUTA_LOADDATA_READY'));
						//2. Actualiza el estado del objeto, para que esté disponible para procesamiento
						$file->status = 'ready';
						$file->save();

						//==================================================
						//Escribe en el Log
						$logger = new \Misc\CustomLogger("LOADDATA");
						$msg = "La carga del archivo: ".$file->name.", Checksum (".$file->checksum.") ha finalizado, se registra el archivo para ser procesado";
						$logger->writeToLog($msg,'INFO');

					} else {

						//==================================================
						//Escribe en el Log

						$logger = new \Misc\CustomLogger("LOADDATA");
						$msg = "El archivo: ".$file->name.", Checksum (".$file->checksum.") aún no ha terminado de ser cargado al sistema, la diferencia entre checksums es: DB (".$file->checksum."); Físco (".md5_file (\Config::get('sibastd.RUTA_LOADDATA_UPLOADING')."/".$fileResults[1]).")";
						$logger->writeToLog($msg,'INFO');

						$file->checksum = md5_file (\Config::get('sibastd.RUTA_LOADDATA_UPLOADING')."/".$fileResults[1]);
						$file->save();

					}

				}
			}
			else{

				/*

					Mueve el archivo a la carpeta error y actualiza el status del objeto 
					\Siba\loadstd\Models\FileDataSource

				*/

				$arrFileData = preg_split("/\//", $txtfile);
				$fileName = $arrFileData[(count($arrFileData) - 1)];
				$file = \Siba\loadstd\Models\FileDataSource::whereRaw("name='".$fileName."' AND status='uploading'")->get();

				if (count($file)==0){

					$fileMover = new \Siba\loadstd\classes\TextFileMover();
					$fileMover->moveFileToPath($file[0],\Config::get('sibastd.RUTA_LOADDATA_ERROR'));
					//2. Actualiza el estado del objeto, para que esté disponible para procesamiento
					$file[0]->status = 'error';
					$file[0]->notes = 'El archivo de carga contiene errores en la definicion de nombre';
					$file[0]->save();				
				}	

				//Registra en el log de actividades
				$log = new \Monolog\Logger('LOADDATA');
                $log->pushHandler(new \Monolog\Handler\StreamHandler(base_path().'/app.log', \Monolog\Logger::WARNING));
                $log->addError("El archivo de carga ".$txtfile." no existe, o el nombre contiene caracteres no validos");

			}	

			

		}
		//==============================================
		//Lanza los archivos cargados al proceso
		$this->launchFileTxtProcesses();

	}


	function launchFileTxtProcesses(){

            $fileSeeker = new \Siba\loadstd\classes\TextSeekerFiles();
            $txtfiles = $fileSeeker->seekFiles(\Config::get('sibastd.RUTA_LOADDATA_READY'));

            if ($this->isStuckProcess()){

            	$logger = new \Misc\CustomLogger('LOADDATA');
				$logger->writeToLog("Existe un archivo que no ha podido ser procesado...","WARNING");
				$record = \Siba\loadstd\Models\FileDataSource::where('status','=','inprocess')->first();
				$record->status = 'error';
				$record->notes = 'El archivo no ha podido ser procesado en más de '.\Config::get('sibastd.TIME_TO_DEFINE_STUCK').'segs';
				$record->save();
            	return false;
            }


            if ($this->isBusyProcess()){

            	$logger = new \Misc\CustomLogger('LOADDATA');
				$logger->writeToLog("Info: Existen archivos en proceso, se aborta el proceso hasta que la cola de trabajo esté libre.","INFO");
				return false;
            }

            try {
	            if (is_array($txtfiles) && count($txtfiles)>0){

		            foreach ($txtfiles as $txtfile){

		                preg_match("/([^\/]{1,190}\.txt$)/i",$txtfile,$fileResults);
		                $file = \Siba\loadstd\Models\FileDataSource::whereRaw("name='".$fileResults[1]."' AND status='ready' AND checksum='".md5_file(\Config::get('sibastd.RUTA_LOADDATA_READY')."/".trim($fileResults[1]))."'")->first();
		                if ($file != null){
		                	//system("php ".base_path()."/artisan siba:LoadSTDTxtFile ".$file->id." > /dev/null &");
		                	//$file = \Siba\loadstd\Models\FileDataSource::where("id","=",$this->argument('idfile'))->first();
							$dataLoader = new \Siba\loadstd\classes\TextFileDataLoader($file);
		                }
		                else{
							

							$logger = new \Misc\CustomLogger('LOADDATA');
							$logger->writeToLog("Error: No existe un registro en la DB para el archivo ".$fileResults[1]." (".md5_file(\Config::get('sibastd.RUTA_LOADDATA_READY')."/".trim($fileResults[1])).")","ERROR");

		                }


		            }
	        	}
        	} catch (Exception $e){

        		$logger = new \Misc\CustomLogger('DEBUG');
				$logger->writeToLog("Error: ".$e->getMessage(),"INFO");
        	}

	}


	function isBusyProcess(){

		$qtyFilesInProcess = \Siba\loadstd\Models\FileDataSource::where('status','=','inprocess')->count();
		if ($qtyFilesInProcess==0){
			return false;
		} 
		else{
			return true;
		}

	}


	function isStuckProcess(){

		$qtyFilesInProcess = \Siba\loadstd\Models\FileDataSource::where('status','=','inprocess')->count();
		if ($qtyFilesInProcess==0){
			return false;
		} 
		else{

			$record = \Siba\loadstd\Models\FileDataSource::where('status','=','inprocess')->first();
			$recordTime = strtotime($record->created_at);
			$timeNow = strtotime("now");
			$recordTime = $recordTime + (int)(\Config::get('sibastd.TIME_TO_DEFINE_STUCK'));
			if ($timeNow > $recordTime )
				return true;
			else
				return false;
		}

	}


}