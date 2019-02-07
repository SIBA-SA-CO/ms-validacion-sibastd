<?php

use \Siba\txtvalidator\classes\TextFileNameFromContentDiscoverer;
use \Siba\txtvalidator\classes\TextFileHttpPostUploaderHandler;

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

		//1. Gestiona el archivo cargado
		$uploadedTxtFile = Input::file('archivo');
		$uploaderHandler = new TextFileHttpPostUploaderHandler();
		$fileNameDiscover = new TextFileNameFromContentDiscoverer();
		$newFilePath = $uploaderHandler->onUploadedHttpPostFile($uploadedTxtFile,$fileNameDiscover);

		//2. Procesa el archivo (verifica el archivo)
		
		//2.1. Se verifica primero la estructura
		
		
		

		return "Hola Mundo...";
		
	}

}
