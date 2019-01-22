<?php

namespace Siba\apirest\Classes;

/*
	Esta clase genera el URL QUERY STRING para cualquier petición http  apartir de un arreglo asociativo

	Ejemplo:

	Datos de entrada:
	$asocArr = array ('clase'=>'10','token'=>'az98d8a0b0c09d8ee79a0103854','q'=>'buscar','page'=>'2');

	Resultado:
	clase=10&token=az98d8a0b0c09d8ee79a0103854&q=buscar&page=2
*/

class HttpURIBuilder{

	private $get;
	
	public function __construct($rawGet){

		$this->get = $rawGet;

	}

	/*
	
		Retorna un arreglo asociativo como un URL QUERY STRING
		para ser usado en una URL

		@params: none

		@return: string

	*/
	public function getRawURIString(){

		$strToRet = '';
		foreach ($this->get as $key => $val){

			$strToRet .= $key."=".$val."&";

		}
		return $strToRet;

	}

}