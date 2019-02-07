<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Siba\txtvalidator\classes;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class TextFileHttpPostUploaderHandler {
    //put your code here


    /**
    * Esta función gestiona los archivos que son cargados mediante una petición HTTP POST
    * para revisión de integridad.
    *
    * @param \Symfony\Component\HttpFoundation\File\UploadedFile, El archivo subido vía HTTP POST
    * @param \Siba\txtvalidator\classes\TextFileNameFromContentDiscoverer, deductor del nombre del archivo a partir del contenido
    * 
    * @return String, path al archivo ya copiado.
    *
    */

    public function onUploadedHttpPostFile(\Symfony\Component\HttpFoundation\File\UploadedFile $httpPostUploadedFile,\Siba\txtvalidator\classes\TextFileNameFromContentDiscoverer $nameDiscoverer){

    	$fh = fopen($httpPostUploadedFile,"r");
    	$fileContent = fread($fh,filesize($httpPostUploadedFile));
    	fclose($fh);


    	$newFileName = $nameDiscoverer->getHashedFileName($fileContent).'.'.$httpPostUploadedFile->guessExtension();
    	$newPath = storage_path().'/'.\Config::get('sibastdtxtvalidador.UPLOADED_FILES')."/"; 

    	try{
    		$file = $httpPostUploadedFile->move($newPath,$newFileName);
    		return $newPath.''.$newFileName;
    	}
    	catch(\Symfony\Component\HttpFoundation\File\Exception\FileException $e){
    		return $e;
    	}
    	//return $file;
    }

}
