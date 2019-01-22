<?php

namespace Siba\apirest\Classes;

use Misc\Response;


class ApiRestParams{
	
	private $params;

	public function __construct($rawArrHttpParams){

		$this->params = $rawArrHttpParams;

	}


	public function getApiRestParamObject($className){

		switch($className){

			case 'HttpParamLimit':
				break;
			case 'HttpParamFields':
				break;
			case 'HttpParamFilter':
				break;
			default:
				return null;

		}
	}


}