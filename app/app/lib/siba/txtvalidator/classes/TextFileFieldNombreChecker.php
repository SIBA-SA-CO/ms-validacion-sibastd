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
class TextFileFieldNombreChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();


        if ($field==' '){
            return $this->return;
        }

        if ($field=='' || strlen($field) == 0){
            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El tipo de dato registrado en el campo Nombre está vacio: ".$field;
        }

        

        if (preg_match("/[\|;]/",$field) || strlen($field) > 90 ){
            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El tipo de dato registrado en el campo Nombre no es valido: ".$field;
        }

        
        return $this->return;

    }
}
