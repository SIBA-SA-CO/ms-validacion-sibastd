<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Siba\txtvalidator\classes;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class TextFileFieldPaisChecker implements \Siba\loadstd\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();

        if ($field==' '){
        	return $this->return;
        }

        if (preg_match("/^([A-Za-z]){3,3}$/",$field)){
            return $this->return;   
        }


        $this->return->status = false;
        $this->return->value = 0;
        $this->return->notes = "El tipo de dato registrado en el campo Pais no es valido".": ".$field;
        return $this->return;

    }
}
