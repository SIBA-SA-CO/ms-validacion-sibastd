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
class TextFileFieldActoresChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();

        if ($field==' '){
        	return $this->return;
        }

        if (preg_match("/([a-zA-Z0-9\ \'\"\x{00A0}]){3,200}(\|{2,2}){1,1}/",$field)){

            $arrRecords = preg_split("/\|\|/",$field);
            foreach ($arrRecords as $record){

                if (preg_match("/\|/",$record)) {
                    # code...
                    $arrRecord = preg_split("/\|/",$record);
                    //echo $record."\n";
                    if (count($arrRecord)<3){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = "Se ha definido mal un campo de actores: ".$field;
                        return $this->return;

                    }
                }

            }
        	return $this->return;

        }

        if (preg_match("/[a-zA-Z0-9\ \'\"]\|[a-zA-Z0-9\ \'\"]/",$field)){

            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El separador de campo no es valido".": ".$field;
            return $this->return;

        }

        if (preg_match("/[\x{00AB}\x{00BB}\x{0080}-\x{009F}\x{2028}]/u",$field)){

            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "Existen caracteres no válidos en el campo actores".": ".$field;
            return $this->return;

        }

        if (preg_match("/[a-zA-Z0-9\ \'\"]{3,120}/",$field)){

            return $this->return;

        }
        else {

            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "Existe un error en el campo actores".": ".$field;
            return $this->return;

        }

        

    }

}
