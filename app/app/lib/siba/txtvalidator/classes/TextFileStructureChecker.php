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
class TextFileStructureChecker {
    //put your code here

    private $dates = array();
    
    public function checkStructureIntegrity($filePath) {

        $ret = new \Misc\Response();
        
        
        //======================================================================
        if (file_exists($filePath)){
            
            $arrDataFile = file($filePath);
            $ctrLines = 0;
            
            foreach ($arrDataFile as $line){

                $checkDate = $this->checkLineDate($line);
                $checkData = $this->checkLineData($line);

                if ($checkData->status == false && $checkDate->status == false){
                    $ret->status = false;
                    $ret->value = 0;
                    $ret->notes = $ret->notes."Error en la linea: ".($ctrLines+ 1);
                    if (preg_match("/^[0-9]{4,4}\-[0-9]{2,2}\-[0-9]{2,2}/",$line) &&  $checkDate->notes != ''){
                        $ret->notes = $ret->notes.", ".$checkDate->notes;
                    }
                    if (preg_match("/^[0-9]{1,2}:[0-9]{2,2}\-\-\-/",$line) && $checkData->notes != ''){
                        $ret->notes = $ret->notes.", ".$checkData->notes;
                    }

                    $ret->notes = $ret->notes."\n";
                    if ($checkDate->value == 24){
                        break;
                    }
                }
                $ctrLines++;
            }
            
        }
        else {
    
            $ret->status = false;
            $ret->value = 0;
            $ret->notes = $ret->notes."El archivo ".$filePath." no existe\n";

        }
        
        return $ret;
    }

    private function checkLineDate($line){

        $res = new \Misc\Response();

        if (preg_match("/^[0-9]{4,4}\-[0-9]{2,2}\-[0-9]{2,2}/",$line)){

            $arrDate = preg_split("/\-/",$line);

            if (!($arrDate[0] > 2000 && $arrDate[0] < 9999)){

                return new \Misc\Response('20','El año de la fecha está errado',false);
            }

            if (!($arrDate[1] >= 1  && $arrDate[1] <= 12)){

                return new \Misc\Response('21','El mes de la fecha está errado',false);
            }

            if (!($arrDate[2] >= 1  && $arrDate[1] <= 31)){
                return new \Misc\Response('22','El día de la fecha está errado',false);
            }

            $dateJumpValidation = $this->validateNoDateJump($line);
            if ($dateJumpValidation->status == false){
                return $dateJumpValidation;
            }

            return new \Misc\Response('1','',true);
        }
        else {
            return new \Misc\Response('23','La fecha no tiene un formato válido',false);
        }

    }

    private function checkLineData ($line){

        if (preg_match("/^[0-9]{1,2}:[0-9]{2,2}\-\-\-/",$line)){

            $arrFields = preg_split("/\-\-\-/",$line);
            $minFields = (int) \Config::get('sibastdtxtvalidador.MIN_FIELDS_PER_ROW');
            $maxFields = (int) ((int) \Config::get('sibastdtxtvalidador.MAX_FIELDS_PER_ROW') + 1);

            if ( (count($arrFields) >= $minFields) && (count($arrFields) <= $maxFields) ){
                return new \Misc\Response('1','',true);
            }
            else {
                return new \Misc\Response('10','El registro no contiene la cantidad de campos requerida',false);
            }


        }
        else {
            return new \Misc\Response('0','El registro de datos no inicia con el campo hora',false);
        }

    }

    private function validateNoDateJump($actualDate){

        if (preg_match("/^[0-9]{4,4}\-[0-9]{2,2}\-[0-9]{2,2}/",$actualDate)){

            if (count($this->dates) == 0){
                array_push($this->dates,$actualDate);
                return new \Misc\Response('1','',true);
            }
            
            $actualTime = strtotime($actualDate." 00:00:00");
            $lastTime = strtotime($this->dates[(count($this->dates)-1)]."00:00:00");
            $nextTime = $lastTime + (48 * 60 * 60);
            if ($actualTime > $lastTime && $actualTime < $nextTime){
                array_push($this->dates,$actualDate);
                return new \Misc\Response('1','',true);
            }
            else{
                return new \Misc\Response('24','Existe un salto en la definición de la fecha o es una fecha en el pasado',false);
            }
        }
        else{
            return new \Misc\Response('23','El formato de fecha no es válido',false);
        }

    }

}
