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

        if (preg_match('/([\|;&\"\'><'.chr(8).''.chr(9).''.chr(10).''.chr(13).''.chr(26).''.chr(133).''.chr(147).''.chr(148).''.chr(150).''.chr(151).''.chr(156).''.chr(157).''.chr(161).''.chr(166).''.chr(171).''.chr(174).''.chr(187).''.chr(191).''.chr(226).']){1,100}/',$field,$matches)){

            array_shift($matches);
            $caracteresNoPermitidos = implode(", ",$matches);

            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El tipo de dato registrado en el campo Sinopsis contiene caracteres no permitidos (".$caracteresNoPermitidos."): ".$field;
            return $this->return;

        }

        
        return $this->return;

    }
}
