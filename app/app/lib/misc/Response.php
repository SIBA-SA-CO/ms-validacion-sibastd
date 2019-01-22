<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Misc;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class Response {


    //put your code here
	public $value = '1';
	public $notes = '';
	public $status = true;


	public function __construct($value=1,$notes='',$status=true){

		$this->value = $value;
		$this->notes = $notes;
		$this->status = $status;

	}

}
