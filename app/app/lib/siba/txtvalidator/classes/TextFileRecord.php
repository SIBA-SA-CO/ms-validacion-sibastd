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
class TextFileRecord  {
    //put your code here
    
    private $lineString;
    private $arrDataRaw = array();
    private $duracion;
    private $date = '';

    public function __construct($str){

        $this->lineString = $str;

        if ($this->lineType()== 'data'){
            $this->loadData();
        }
        

    }

    public function loadData(){

        $this->arrDataRaw = preg_split("/\-\-\-/",$this->lineString);

    }

    public function setDuracion($duracion){

        $this->duracion = $duracion;
    }

    public function getDuracion(){

        return $this->duracion;

    }

    public function lineType () {

        if (preg_match("/^[0-9]{4,4}\-[0-9]{2,2}\-[0-9]{2,2}/",$this->lineString)){

            $arrDate = preg_split("/\-/",$this->lineString);
            if ($arrDate[1] < 1 || $arrDate[1] > 12)
                return 0;
            
            if ($arrDate[2] < 1 || $arrDate[2] > 31)
                return 0;
            
            return "date";

        }
        else if (preg_match("/(\-{3,3})/",$this->lineString)){

            return "data";

        }
        else {

            return 0;

        }

    }

    public function getLineTime(){

        if (preg_match("/^([0-9]{1,2}:[0-9]{2,2})/",$this->lineString,$res)){

            return $res[1];

        }

        return false;
    }

    /*
        @params:
        $timeNextProgram: Timestamp del instante de inicio del siguiente programa
        $timeActualProgram: Timestamp del instante de inicio del actual programa
    */

    public function getDurationInSecs ($timeNextProgram,$timeActualProgram){

        $duracion = ($timeNextProgram - $timeActualProgram);
        return $duracion;

    }   

    public function setDate($date){

        $this->date = $date;
    }


    public function getDate(){

        return $this->date;

    }

    public function getTimestampByLine ($date=null){

        if ($this->lineType()!= 'data'){
            return null;
        }

        if ($this->date == '' && $date!=null){
            $fechaHora = trim($date." ".$this->getLineTime().":00");
        }
        else {
            $fechaHora = trim($this->date." ".$this->getLineTime().":00");   
        }    
        return strtotime($fechaHora);

    }

    public function getNombre(){

        if ($this->lineType()!= 'data'){
            return null;
        }
        return $this->arrDataRaw[1];
    }

    public function getSinopsis(){

        if ($this->lineType()!= 'data'){
            return null;
        }
        return $this->arrDataRaw[2];
    }

    public function getYear(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        if (preg_match("/[0-9]{4,4}/",$this->arrDataRaw[6]))
            return $this->arrDataRaw[6];
        else
            return null;
    }

    public function getPais(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        if (preg_match("/^([A-Za-z]){3,3}$/",$this->arrDataRaw[7])){
            return $this->arrDataRaw[7];   
        }
        return null;
    }

    public function getSerie(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        if ($this->arrDataRaw[8] != ' '){

            return $this->arrDataRaw[8];

        }
        return null;
        
    }

    public function getTemporada(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        if (preg_match("/^[0-9]{1,1}[0-9]{0,1}[0-9]{0,1}$/",$this->arrDataRaw[9])){

            return $this->arrDataRaw[9];    

        }
        return null;
    }
    /*
        @params:
        
    */
    public function getRatings (){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $ratings = array();
        if (preg_match("%\|%", $this->arrDataRaw[3]))
        {
            $arrRating = preg_split("%\|\|%",$this->arrDataRaw[3]);
            for ($j=0;$j<count($arrRating);$j++){

                $arrTmp = preg_split("%\|%",$arrRating[$j]);
                array_push ($ratings,array("pais"=>$arrTmp[0],"rating"=>$arrTmp[1]));

            }

        }
        return $ratings;
    }

    /*
        @params:
        $str: Cadena de caracteres con la siguiente estructura "SIBA_TIPO|SERIE||SIBA_BASE|Peliculas"
    */
    public function getCategorias (){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $categorias = array();
        if (preg_match("%[\|a-zA-Z0-9]%",$this->arrDataRaw[4]))
        {
            $arrCat = preg_split("%\|\|%",$this->arrDataRaw[4]);
            if (is_array($arrCat) && count($arrCat) > 0)
            {
                for ($j=0;$j<count($arrCat);$j++){

                    try {
                        list($tipo,$nombre) = preg_split("/\|/",$arrCat[$j]);
                        $dataCat = \Siba\Dev2\Models\CategoriaEvento::getCategoriaEventoByKeyVal($nombre,$tipo);
                        array_push($categorias,$dataCat->idcategoria);
                    }
                    catch (Exception $e){

                        $msg = "Error en el script TextFileRecord, lineas 241-244: linea ".$this->getRawLine()." / Stack: ".$e->getMessage()."\n";
                        $logger = \Misc\CustomLogger("EXCEPTION");
                        $logger->writeToLog($msg,"ERROR");

                    }

                }

            }
        }

        return $categorias;

    }

    /*
        @params:
        
    */

    public function getPpvValues (){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $ppv = array();
        if (preg_match("%[\|]%",$this->arrDataRaw[5]))
        {
            $arrPpv = preg_split("%\|\|%",$this->arrDataRaw[5]);
            if (is_array($arrPpv) && count($arrPpv) > 0)
            {
                for ($j=0;$j<count($arrPpv);$j++)
                {
                    $arrTmp = preg_split("%\|%",$arrPpv[$j]);
                    array_push($ppv,array("clave"=>$arrTmp[0],"valor"=>$arrTmp[1]));
                }
            }
        }

        return $ppv;

    }

    /*
        @params:
        $str: Cadena de caracteres con la siguiente estructura "ClaveSinopsis1|Sinopsis 1||ClaveSinopsis2|Sinopsis 2"
    */
    public function getSinopsisCustom (){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $sinopsis = array();
        if (preg_match("/[a-zA-Z0-9\|]/",$this->arrDataRaw[10])){
            
            $arrSinopsisRaw = preg_split("/\|\|/",$this->arrDataRaw[10]);
            for ($j=0;$j<count($arrSinopsisRaw);$j++){
                list($tipoTmp,$sinopsisTmp) = preg_split("/\|/",$arrSinopsisRaw[$j]);
                if ($tipoTmp != '' && $sinopsisTmp != ''){
                    array_push($sinopsis,array($tipoTmp,$sinopsisTmp));
                }
            }
            
        }
        return $sinopsis;
    }

    /*
        @params:
    */
    public function getActores (){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $actores = array();

        if ($this->arrDataRaw[11]==' '){

            return $actores;

        }

        if (preg_match("/[a-zA-Z0-9\|]/",$this->arrDataRaw[11])){
            
            $arrActoresRaw = preg_split("/\|\|/",$this->arrDataRaw[11]);
            for ($j=0;$j<count($arrActoresRaw);$j++){
                
                echo $arrActoresRaw[$j]."\n";
                echo "============================\n";
                if (preg_match("/\|/",$arrActoresRaw[$j])){
                    list($nombresAct,$paisAct,$sexoAct) = preg_split("/\|/",$arrActoresRaw[$j]);
                    if ($nombresAct != ''){
                        array_push($actores,array($nombresAct,$paisAct,$sexoAct));
                    }
                }
                else{
                    
                    array_push($actores, $arrActoresRaw[$j]);
                }
            }
            
        } else {
            array_push($actores,$this->arrDataRaw[11]);
        }
        return $actores;

    }

    /*
        @params:
    */

    public function getDirectores(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $directores = array();

        if ($this->arrDataRaw[12]==' '){

            return $directores;

        }

        
        if (preg_match("/[a-zA-Z0-9\|]/",$this->arrDataRaw[12])){
            
            $arrDirectoresRaw = preg_split("/\|\|/",$this->arrDataRaw[12]);

            for ($j=0;$j<count($arrDirectoresRaw);$j++){
                
                if (preg_match("/\|/",$arrDirectoresRaw[$j])){
                    list($nombresDir,$paisDir,$sexoDir) = preg_split("/\|/",$arrDirectoresRaw[$j]);
                    if ($nombresDir != ''){
                        array_push($directores,array($nombresDir,$paisDir,$sexoDir));
                    }
                }
                else{
                    
                    array_push($directores, $arrDirectoresRaw[$j]);
                }
            }
            
        }
        else {
            array_push($directores,$this->arrDataRaw[12]);
        }
        return $directores;
    }


    public function getOpcionales(){

        if ($this->lineType()!= 'data'){
            return null;
        }

        $opcionales = array();
        if (preg_match("/[a-zA-Z0-9\|]/",$this->arrDataRaw[13])){
            
            $arrOpcRaw = preg_split("/\|\|/",$this->arrDataRaw[13]);
            for ($j=0;$j<count($arrOpcRaw);$j++){
                
                if (preg_match("/\|/",$arrOpcRaw[$j])){
                    list($claveOpc,$valorOpc) = preg_split("/\|/",$arrOpcRaw[$j]);
                    if ($valorOpc != ''){
                        array_push($opcionales,array($claveOpc,$valorOpc));
                    }
                }
            }
            
        } 
        return $opcionales;
    }

    public function getRawLine(){
        return $this->lineString;
    }

}
