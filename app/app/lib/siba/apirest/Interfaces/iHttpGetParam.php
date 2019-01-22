<?php

namespace Siba\apirest\Interfaces;

interface iHttpGetParam{
	
	//This function checks if the raw http get value given is in correct format
	//For example: If there is a param called: limit, and it must be next format: limit=0,50 or limit=50
	//this function must return false if: limit=10A
	public static function checkRawParamValue($paramVal);

	//This function transforms a string (given by GET method) to an object or a valid php's data structure 
	//private function processRawValue();

	//This function returns the data structure stored in the object
	public function getData();

}