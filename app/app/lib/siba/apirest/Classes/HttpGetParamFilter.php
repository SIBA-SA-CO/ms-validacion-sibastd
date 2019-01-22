<?php

namespace Siba\apirest\Classes;
use Siba\apirest\Interfaces\iHttpGetParam;
use Siba\apirest\Classes\ApiModelAttrMapper;

class HttpGetParamFilter implements iHttpGetParam{

	private $rawParamValue,$data,$mapper;
	
	public function __construct($rawLimitHttpParamFilter){

		$this->rawParamValue = $rawLimitHttpParamFilter;
		$this->data = array();
		$this->processRawValue();

	}

	public static function checkRawParamValue($paramVal){

		return true;
	}

	/*
		Convierte en un arreglo asociativo registrado en $this-data la
		información suministrada como una cadena

		@params
	*/

	private function processRawValue(){

		$this->data = json_decode($this->rawParamValue);
	}


	public function getSqlWhereString(){

		$sqlStr = '';

		if (count($this->data) > 0){

			$sqlStr = ' (';
			foreach ($this->data as $whereItem){

				if (isset($whereItem->lc) && $whereItem->lc != ''){

					$sqlStr .= ' '.$whereItem->lc.' ';

				}

				$sqlStr .= ' (';

				foreach ($whereItem->ele as $logicItem){

					if (isset($logicItem->lc) &&  $logicItem->lc != ''){

						$sqlStr .= ' '.$logicItem->lc.' ';

					}
					$sqlStr .= ' ( ';
					/* Define si el elemento viene mapeado */
					if ($this->mapper->getMappedValue($logicItem->field)==null)
						$sqlStr .= $logicItem->field.' ';
					else
						$sqlStr .= $this->mapper->getMappedValue($logicItem->field).' ';

					$sqlStr .= $logicItem->operator.' ';
					$sqlStr .= "'".$logicItem->value."' ";
					$sqlStr .= ' ) ';					

				}
				$sqlStr .= ' ) ';
			}
			$sqlStr .= ') ';
		}
		return $sqlStr;
	}

	public function getData(){
		return $this->data;
	}


	public function setMap(ApiModelAttrMapper $mapper){

		$this->mapper = $mapper;
	}

}