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
    public function checkFieldIntegrity($field) {
        $return = new \Misc\Response();
        $ratingRepo = new \Siba\txtvalidator\models\ratings\RatingRepo();
        if ($field==' '){
            return $this->return;
        }
        if (preg_match("/^(COL|USA|MEX)\|/i",$field)){

            if (preg_match("%\|%", $field))
            {
                $jsonString = '[';
                $arrRating = preg_split("%\|\|%",$field);
                for ($j=0;$j<count($arrRating);$j++){
                    $arrRatingFields = preg_split("%\|%",$arrRating[$j]);
                    
                    if ($j == 0){
                        $jsonString .= '{"lc":"","ele":[{"field":"country","operator":"=","value":"'.$arrRatingFields[0].'"},{"field":"rating","operator":"=","value": "'.$arrRatingFields[1].'","lc":"and"}]}';
                    }
                    else{
                        $jsonString .= '{"lc": "or","ele":[{"field": "country","operator": "=","value": "'.$arrRatingFields[0].'"},{"field": "rating","operator": "=","value": "'.$arrRatingFields[1].'","lc" : "and"}]}';
                    }
                    $jsonString .= ']';
                    //echo "\n".$jsonString."\n";
                    $jsonString = urlencode($jsonString);
                    //echo "\n".$jsonString."\n";
                    $findParams = array(
                        'filter'=>$jsonString,
                    );
                    $rating = $ratingRepo->find($findParams)->first();
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
