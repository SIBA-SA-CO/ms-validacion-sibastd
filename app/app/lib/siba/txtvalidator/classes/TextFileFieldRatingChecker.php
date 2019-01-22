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
class TextFileFieldRatingChecker implements \Siba\loadstd\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();

        if ($field==' '){
            return $this->return;
        }

        if (preg_match("/^(COL|USA|MEX)\|/i",$field)){

            $ratings = array();
            if (preg_match("%\|%", $field))
            {
                $arrRating = preg_split("%\|\|%",$field);
                for ($j=0;$j<count($arrRating);$j++){

                    $arrTmp = preg_split("%\|%",$arrRating[$j]);
                    $rating = \Siba\Dev2\Models\RatingEvento::getRatingEventoByKeyVal($arrTmp[0],$arrTmp[1]);
                    if ($rating==null){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = "El rating ".$arrRating[$j]." no existe (".$field.")";
                        return $this->return;

                    }


                }

            }
            


            return $this->return;

        }

        $this->return->status = false;
        $this->return->value = 0;
        $this->return->notes = "El tipo de dato registrado en el campo rating no es valido: ".$field;
        return $this->return;

    }
}
