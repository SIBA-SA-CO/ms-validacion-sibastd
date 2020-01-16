<?php
use \Siba\txtvalidator\classes\TextFileNameFromContentDiscoverer;
use \Siba\txtvalidator\classes\TextFileHttpPostUploaderHandler;
/*
	Importa las clases que hacen el trabajo de validación
*/
use \Siba\txtvalidator\classes\TextFileStructureChecker;
use \Siba\txtvalidator\classes\TextFileDataChecker;
use \Siba\txtvalidator\classes\TextFileChecker;

/*
	Importa las clases para mover los archivos
*/
use \Misc\FtpFileMover;
use \Misc\FtpFileUploader;
use \Misc\Response;

class LoadValidatorApi extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	public function index(){

		$fileChecker = new TextFileChecker();
		$fileDataChecker = new TextFileDataChecker();		
		$textFileStructureChecker = new TextFileStructureChecker();

		//Procesado los datos, si llegan como un archivo.
		if (Input::hasFile('archivo')){
			$uploadedTxtFile = Input::file('archivo');
			$uploaderHandler = new TextFileHttpPostUploaderHandler();
			$fileNameDiscover = new TextFileNameFromContentDiscoverer();
			$newFilePath = $uploaderHandler->onUploadedHttpPostFile($uploadedTxtFile,$fileNameDiscover);
			$fileCheckResult = $fileChecker->check($newFilePath,$fileDataChecker,$textFileStructureChecker);
			





			//Borra el archivo temporal
			unlink($newFilePath);
			//Retorna la respuesta
			return \Response::json($fileCheckResult);
		}
		
		//Valida los datos llegados como un campo (fieldtext)
		if (\Input::has('data') && \Input::get('data')!=''){

			$data = \Input::get('data');
			if (\Input::has('fileName') && \Input::get('fileName')!=''){

				$fileName = \Input::get('fileName');
			}	
			else{
				$md5FileName = md5($data);
				$md5FileName = $md5FileName.'.txt';	
				$fileName = $md5FileName;
			}		
			
			$fp = fopen( app_path().'/storage/uploadedtxtfiles/'.$fileName , 'w+');
			fwrite ($fp ,$data);
			fclose($fp);
			$fileCheckResult = $fileChecker->check(app_path().'/storage/uploadedtxtfiles/'.$fileName,$fileDataChecker,$textFileStructureChecker);

			//Sube el archivo al FTP
			if ((\Input::has('upload') && \Input::get('upload')==1) && $fileCheckResult->value == 1){
				$pathToFile = app_path().'/storage/uploadedtxtfiles/'.$fileName;

				$ftpUploader = new FtpFileUploader(Config::get('sibastdtxtvalidador.HOST_FTP'),Config::get('sibastdtxtvalidador.USER_FTP'),Config::get('sibastdtxtvalidador.PWD_FTP'));
				$res = $ftpUploader->uploadToRemoteServer($pathToFile,"/uploading",FTP_ASCII,new Response());
				if ($res->status == 'error'){
					$fileCheckResult->status = false;
					$fileCheckResult->notes = [$res->notes];
					$fileCheckResult->value = 0;
				}
			}
			//Borra el archivo temporal
			unlink(app_path().'/storage/uploadedtxtfiles/'.$fileName);
			//Retorna la respuesta
			return \Response::json($fileCheckResult,200,['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
		}

		//If there is not any to process... Default error answer
		$ret = new \Misc\Response();
		$ret->status = false;
		$ret->value = 404;
		$ret->notes = ['No data to be processed'];
		return \Response::json($ret);	
	}
}
