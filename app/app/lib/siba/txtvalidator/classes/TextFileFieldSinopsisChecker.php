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
class TextFileFieldSinopsisChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();

        if ($field==' '){
            return $this->return;
        }

        if (preg_match('/([\|&\"\'><]){1,100}/',$field,$matches)){


            //print_r($matches);
            $asciiCodes = "";
            array_shift($matches);
            foreach($matches as $match){

                $asciiCodes .= ord($match).", ";

            }
            $specialCharsMatched = implode(", ",$matches);
            $specialCharsMatched = utf8_encode($specialCharsMatched);
            $specialCharsMatched.= " Códigos ASCII: ".$asciiCodes;
            $specialCharsMatched.= preg_replace("/\,\ $/","",$specialCharsMatched);

            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El tipo de dato registrado en el campo Sinopsis contiene caracteres no permitidos (".$specialCharsMatched.")";
            return $this->return;

        }

        
        return $this->return;

    }
}
