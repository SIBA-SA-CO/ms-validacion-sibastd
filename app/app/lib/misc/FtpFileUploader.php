<?php

namespace Misc;

class FtpFileUploader {

	private $host;
	private $usr;
	private $pwd;

	function __construct($host,$usr,$pwd){
		$this->host = $host;
		$this->usr = $usr;
		$this->pwd = $pwd;
	}

	function uploadToRemoteServer($file,$remotePath,$mode,Response $response){
		// set up basic connection
		try{
			$conn_id = ftp_connect($this->host);
			//ftp_pasv($conn_id, true) or die("Cannot switch to passive mode");
			// login with username and password
			$login_result = ftp_login($conn_id, $this->usr, $this->pwd);
			//Moviendo a la carpeta remota
			if ($remotePath != '')
				ftp_chdir($conn_id, $remotePath);

			$remote = $remotePath."/".$this->getFileName($file);
			$ret = ftp_nb_put($conn_id, $this->getFileName($file), $file, $mode);
			while ($ret == FTP_MOREDATA) {
			   // Do whatever you want
			   $ret = ftp_nb_continue($conn_id);
			}
			if ($ret != FTP_FINISHED) {
				//echo "\nStatus: There was an error uploading the file...\n";
				$response->status = 'error';
				$response->value = 0;
				$response->notes = 'Error status: There was an error uploading the file...';
				//system("mv ".$file." ".RUTA_FTP."/error/");
			}
			else {
				//echo "\nStatus: El archivo fué subido con exito al ftp...\n";
				//system("mv ".$file." ".RUTA_FTP."/success/");
				$response->status = 'ok';
				$response->value = 1;
				$response->notes = 'Success status: El archivo fué subido con exito al ftp...';
			}
			// close the connection and the file handler
			ftp_close($conn_id);
		}
		catch (\Exception $e){

			$response->status = 'error';
			$response->value = 0;
			$response->notes = 'Error status: Something get wrong uploading file over FTP, '.$e->getMessage();

		}
		return $response;
	}

	private function getFileName($file){

		$arrPath = preg_split("%\/%",$file);
		$index = (int)(count($arrPath) - 1);
		return ($arrPath[$index]);

	}

}