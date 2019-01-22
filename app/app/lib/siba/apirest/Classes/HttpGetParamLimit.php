<?php

namespace Siba\apirest\Classes;
use Siba\apirest\Interfaces\iHttpGetParam;

class HttpGetParamLimit implements iHttpGetParam{

	private $rawParamValue,$data;
	
	public function __construct($rawLimitHttpParamField){

		$this->rawParamValue = $rawLimitHttpParamField;
		$this->data = array(
			"index"=>0,
			"total"=>\Config::get('sibaapirest.MAX_RESULTS_PER_QUERY')
		);
		$this->processRawValue();

	}

	public static function checkRawParamValue($paramVal){

		if(preg_match("/[^0-9\,]/",$paramVal)) {
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
			$arrTmp = preg_split("/\,/",$this->rawParamValue);
			$this->data["index"] = $arrTmp[0];
			$this->data["total"] = $arrTmp[1];
		}
		else{
			$this->data["total"] = $this->rawParamValue;
		}
	}

	public function getData(){
		return $this->data;
	}

}