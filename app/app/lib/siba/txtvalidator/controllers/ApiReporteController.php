<?php

namespace Siba\loadstd\controllers;

class ApiReporteController extends \BaseController {

	
	public function buscar(){

		$fecha = null;
		if (\Input::has('fecha') && \Input::has('fecha')!=''){
			$fecha = \Input::get('fecha');
			$fecha = $fecha." 00:00:00";
			
		}	
		else {

			$fecha = date("Y-m-d H:i:s",(strtotime("now") - (48 * 60 * 60)));

		}

		$fileName = null;
		if (\Input::has('archivo') && \Input::has('archivo')!=''){
			$fileName = \Input::get('archivo');
		}
		
		$canal = null;
		if (\Input::has('canal') && \Input::has('canal')!=''){

			$canal = \Input::get('canal');

		}	

		//=================================================================

		if ($fileName == null ){

			if ($canal == null){

				$archivos = \Siba\loadstd\Models\FileDataSource::where("created_at",">=",$fecha)->get();

			}
			else {

				$archivos = \Siba\loadstd\Models\FileDataSource::where("created_at",">=",$fecha)->where("idcanal","=",$canal)->get();
				
			}	

		}
		else {

			if ($canal == null){

				$archivos = \Siba\loadstd\Models\FileDataSource::where("created_at",">=",$fecha)->where("name","like","%".$fileName."%")->get();

			}
			else {

				$archivos = \Siba\loadstd\Models\FileDataSource::where("created_at",">=",$fecha)->where("idcanal","=",$canal)->where("name","like","%".$fileName."%")->get();
				
			}

		}

		//=================================================================

		//print_r($archivos);
		if (\Input::get('callback')!= '')
			return \Response::json($archivos)->setCallback(\Input::get('callback'));
		else
			return \Response::json($archivos);


	}


}