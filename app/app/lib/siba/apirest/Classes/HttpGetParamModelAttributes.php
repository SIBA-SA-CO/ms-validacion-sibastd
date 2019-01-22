<?php

namespace Siba\apirest\Classes;
use Siba\apirest\Interfaces\iHttpGetParam;
use Siba\apirest\Interfaces\iFieldsMapper;


/*

	Gestiona los parametros pasados por la URI (QUERY STRING) que se relacionan directamente
	con los atributos del Modelo 

	@params

	$rawReqquery: 	Query String crudo de la petición
	$fields: 		Arreglo con los atributos del modelo
	$mapeador: 		Arreglo asociativo  que relaciona 
					los atributos publicos del modelo con 
					los valores reales en el sistema de persistencia

	Ejemplo:

	$rawReqquery = 'clase=10&token=az98d8a0b0c09d8ee79a0103854&q=buscar&page=2'
	$fields = 		array('id','title','begin','desc');
	$mapeador =		array('id'=>'idmodelo','title'=>'nombre_modelo','desc'=>'descripcion','begin'=>'fecha_hora')

	new HttpGetParamModelAttributes($rawReqquery,fields,$mapeador)

*/


class HttpGetParamModelAttributes implements iHttpGetParam{

	protected $rawParamValue,$data,$fields,$mapeador;
	
	public function __construct($rawReqQuery,$fields,iFieldsMapper $mapeador){

		$this->rawParamValue = $rawReqQuery;
		$this->data = array();
		$this->fields = $fields;
		$this->mapeador = $mapeador;
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

		$arrGetQuery = preg_split("/&/",$this->rawParamValue);
		foreach ($arrGetQuery as $getParam){
			if (preg_match("/([a-z-A-]{1,100})=/",$getParam)){
				list($key,$val) = preg_split("/=/",$getParam);
				if (in_array($key,$this->fields)){
					$this->data[$key] = $val;
				}
			}
		}
	}

	public function getData(){
		return $this->data;
	}

	/*

		Genera el sql basado en los datos de los campos del modelo
		entregados en la URI (Query String)

	*/
	public function getSqlWhereString(){

		$sqlStr = '';
		$ctrlFields = 0;
		//var_dump($this->data);
		if (count($this->data) > 0){

			$sqlStr = ' (';
			foreach ($this->data as $key=>$val){

				if ($this->mapeador->getMappedValue($key) != null){
					$sqlStr .= $this->mapeador->getMappedValue($key)."='".$val."' & ";
					$ctrlFields++;
				}

			}
			$sqlStr = preg_replace("/&\ $/","",$sqlStr);
			$sqlStr .= ') ';

			if ($ctrlFields == 0)
				$sqlStr = '';
		}
		return $sqlStr;
	}

}