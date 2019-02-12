<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Siba\txtvalidator\classes;

use \Siba\txtvalidator\models\ratings\RatingRepo;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class TextFileFieldRatingChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {    
    //put your code here
    private $ratingRepo;


    public function __construct(){

        $this->ratingRepo = new \Siba\txtvalidator\models\ratings\RatingRepo();        

    }

    /**
    * For a integrity rating field check, the content must be this: COUNTRY|RATING
    * for example: USA|TV-PG
    * 
    * @param (string) $field
    *
    * @return \Misc\Response $object.
    *
    */
    public function checkFieldIntegrity($field) {
        $return = new \Misc\Response();
        //$ratingRepo = new \Siba\txtvalidator\models\ratings\RatingRepo();
        if ($field==' '){
            return $this->return;
        }
        if (preg_match("/^(COL|USA|MEX)\|/i",$field)){

            if (preg_match("%\|%", $field)){

                $arrRating = preg_split("%\|\|%",$field);
                $arrParamToFind = array();
                for ($j=0;$j<count($arrRating);$j++){
                    $arrRatingFields = preg_split("%\|%",$arrRating[$j]);
                
                    $arrParamToFind['country'] = $arrRatingFields[0];
                    $arrParamToFind['rating'] = $arrRatingFields[1];

                    $rating = $this->ratingRepo->find($arrParamToFind)->first();
                    if ($rating==null){
                        $return->status = false;
                        $return->value = 0;
                        $return->notes = "El rating ".$arrRating[$j]." no existe (".$field.")";
                        return $return;
                    }
                }
            }
            return $return;
        }
        $return->status = false;
        $return->value = 0;
        $return->notes = "El tipo de dato registrado en el campo rating no es valido: ".$field;
        return $return;
    }
}
