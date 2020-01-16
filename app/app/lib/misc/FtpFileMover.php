<?php
 namespace Misc;

class FtpFileMover {

	private $host;
	private $usr;
	private $pwd;

	function __construct(string $host, string $usr,string $pwd){
		$this->host = $host;
		$this->usr = $usr;
		$this->pwd = $pwd;
	}

	function moveToRemoteFolder($file,$remotePath,Response $response){

		// set up basic connection
		$conn_id = ftp_connect($this->host);
		// login with username and password
		$login_result = ftp_login($conn_id, $this->usr, $this->pwd);
		//Moviendo a la carpeta remota
		$remote = $remotePath."/".$this->getFileName($file);
		//echo $remote."\n";
		//echo $file."\n";
		//$ret = ftp_nb_put($conn_id, $this->getFileName($file), $file, $mode);
		$ret = ftp_rename ( $conn_id , $file , $remotePath."/".$this->getFileName($file));
		if ($ret){
			//echo "Archivo movido con exito\n";
			$response->status = 'ok';
			$response->value = 1;
			$response->notes = 'Success status: El archivo se ha movido con éxito';
		}
		else {
			//echo "Error moviendo el archivo\n";
			$response->status = 'error';
			$response->value = 0;
			$response->notes = 'Error status: Error moviendo el archivo';
		}

		// close the connection and the file handler
		ftp_close($conn_id);
		return $response;
	}

	private function getFileName($file){

		$arrPath = preg_split("%\/%",$file);
		$index = (int)(count($arrPath) - 1);
		return ($arrPath[$index]);

	}

}