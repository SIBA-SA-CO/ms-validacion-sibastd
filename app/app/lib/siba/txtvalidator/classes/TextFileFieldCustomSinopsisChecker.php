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
class TextFileFieldCustomSinopsisChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();

        if ($field==' '){
        	return $this->return;
        }


        if (preg_match("/^[^\|0-9]{5,12}\|[^\|]{3,1200}(\|\|){0,1}/",$field)){

            return $this->return;

        }
        else{

            if (preg_match("/\|/",$field)){

                if (preg_match("/^[^\|0-9]{5,12}\|/",$field)){

                    $this->return->status = false;
                    $this->return->value = 0;
                    $this->return->notes = "El tipo de dato registrado en el campo Custom Sinopsis no es valido".": ".$field;
                    return $this->return;

                }
                else{

                    $this->return->status = false;
                    $this->return->value = 0;
                    $this->return->notes = "El tipo de sinopsis debe ser una cadena de caracteres de longitud no menor a 5 caracteres y no mayor a 12 caracteres: ".$field;
                    return $this->return;
                }
                
            }
            else{
                $this->return->status = false;
                $this->return->value = 0;
                $this->return->notes = 'No existen los caracteres pipe (|) que forman la estructura del campo';
                return $this->return;
            }

        }

    }
}
