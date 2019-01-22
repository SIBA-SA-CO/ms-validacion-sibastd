<?php

namespace Siba\apirest\Classes;

use Siba\apirest\Interfaces\iFieldsMapper;

/*
	Est clase sirve de mapeador de valores que se almacenan en un arreglo asociativo (mapa), 

	Ejemplo:

	Datos de entrada:
	$map = array ('hand' => 'mano','begin'=>'fecha_hora','name'=>'nombre_completo');

*/

class ApiModelAttrMapper implements iFieldsMapper{

	protected $map = array();
	
	public function __construct($map){

		$this->map = $map;

	}


	/*

		Recibe una cadena (cualquier valor) y retorna el valor correspondiente
		en el mapa, po ejemplo:

		$mapa = array ('hand' => 'mano','begin'=>'fecha_hora','name'=>'nombre_completo');
		getMappedValue('begin') : 'fecha_hora'

		@param
		$actual: (String) valor actual que se buscará como indice en el mapa)

		@return:
		(string) valor corresponiente en el mapa para el valor indexado


	*/
	public function getMappedValue($actual){

		if (isset ($this->map[$actual]))
			return $this->map[$actual];
		else
			return null;
	}

}