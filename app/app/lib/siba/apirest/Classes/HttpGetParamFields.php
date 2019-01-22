<?php

namespace Siba\apirest\Classes;
use Siba\apirest\Interfaces\iHttpGetParam;

class HttpGetParamFields implements iHttpGetParam{

	private $rawParamValue,$data;
	
	public function __construct($rawLimitHttpParamField){

		$this->rawParamValue = $rawLimitHttpParamField;
		$this->data = array();
		$this->processRawValue();

	}

	public static function checkRawParamValue($paramVal){

		if(preg_match("/(([a-zA-Z_\-]){5,200}\,{0,1})/",$paramVal)) {
			return false;
		}
		return true;
	}

	/*
		Convierte en un arreglo asociativo registrado en $this-data la
		información suministrada como una cadena

		@params
	*/

	private function processRawValue(){

		if (preg_match("/\,/",$this->rawParamValue)){
			$this->data = preg_split("/\,/",$this->rawParamValue);
		}
		else{
			array_push($this->data,$this->rawParamValue);
		}
	}

	public function getData(){
		return $this->data;
	}

}