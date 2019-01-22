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
class TextFileDataChecker implements \Siba\loadstd\interfaces\FileDataChecker {
    //put your code here
    private $arrData = array();
    private $return;
    private $nombreTest;
    private $sinopsisTest;
    private $ratingTest;
    private $categoriasTest;
    private $ppvTest;
    private $yearTest;
    private $paisTest;
    private $serieMarkTest;
    private $temporadaTest;
    private $sinopsisCustomTest;
    private $actoresTest;
    private $directoresTest;
    private $opcionalesTest;

    public function __construct(){


        $this->return = new \Misc\Response();
        $this->nombreTest = new \Siba\loadstd\classes\TextFileFieldNombreChecker();
        $this->sinopsisTest = new \Siba\loadstd\classes\TextFileFieldSinopsisChecker();
        $this->ratingTest = new \Siba\loadstd\classes\TextFileFieldRatingChecker();
        $this->categoriasTest = new \Siba\loadstd\classes\TextFileFieldCategoriasChecker();
        $this->ppvTest = new \Siba\loadstd\classes\TextFileFieldPpvChecker();
        $this->yearTest = new \Siba\loadstd\classes\TextFileFieldYearChecker();
        $this->paisTest = new \Siba\loadstd\classes\TextFileFieldPaisChecker();
        $this->serieMarkTest = new \Siba\loadstd\classes\TextFileFieldSerieMarkChecker();
        $this->temporadaTest = new \Siba\loadstd\classes\TextFileFieldTemporadaChecker();
        $this->sinopsisCustomTest = new \Siba\loadstd\classes\TextFileFieldCustomSinopsisChecker();
        $this->actoresTest = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
        $this->directoresTest = new \Siba\loadstd\classes\TextFileFieldDirectoresChecker();
        $this->opcionalesTest = new \Siba\loadstd\classes\TextFileFieldOpcionalesChecker();


    }

    public function checkDataIntegrity(\Siba\loadstd\Models\FileDataSource $file) {
        
        switch ($file->status){
            case 'uploading': 
                                $path = 'sibastd.RUTA_LOADDATA_READY';
                                break;
            case 'ready':
                                $path = 'sibastd.RUTA_LOADDATA_READY';
                                break;
            case 'inprocess':
                                $path = 'sibastd.RUTA_LOADDATA_INPROCESS';
                                break;
            case 'done': 
                                $path = 'sibastd.RUTA_LOADDATA_OK';
                                break;
            case 'error':
                                $path = 'sibastd.RUTA_LOADDATA_ERROR';
                                break;
        }
        //======================================================================
        if (file_exists(\Config::get($path)."/".$file->name)){
            
            $arrDataFile = file(\Config::get($path)."/".$file->name);
            $ctrLines = 0;
            $actualDate = "";

            foreach ($arrDataFile as $line) {

                $line = trim($line);
                $lineObj = new \Siba\loadstd\classes\TextFileRecord(trim($line));
                if ($lineObj->lineType()=='date'){

                    if (isset($this->arrData[$line])){

                        //Valida que una fecha no esté ya registrada
                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea: ".($ctrLines + 1).", se está repitiendo la fecha: ".$line." \n";


                    }
                    else {

                        $this->arrData[trim($line)] = array();
                        $actualDate = $line;

                    }

                }
                else if ($lineObj->lineType()=='data') {

                    $arrLineData = preg_split("/\-\-\-/",$line);
                    $horaLine = $lineObj->getLineTime();

                    //1. Primera revisión es de formato adecuado de hora
                    if ($horaLine == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea: ".($ctrLines + 1).", el formato de la hora no es válido: ".$line." \n";

                    }
                    //2. Segunda revisión es de horarios trocados, es decir registro de programas en horarios 
                    //   equivocados.
                    $horario = $actualDate." ".$horaLine.":00";
                    if ($this->checkCorrectTime($horario) == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea: ".($ctrLines + 1).", el programa tiene horario repetido y/o se ha puesto un horario posterior antes de otro: ".$line." \n";

                    }
                    //3. Valida correctitud de campos
                    $resNombre = $this->nombreTest->checkFieldIntegrity($arrLineData[1]);
                    $resSinopsis = $this->sinopsisTest->checkFieldIntegrity($arrLineData[2]);
                    $resRating = $this->ratingTest->checkFieldIntegrity($arrLineData[3]);
                    $resCategorizacion = $this->categoriasTest->checkFieldIntegrity($arrLineData[4]);
                    $resPpv = $this->ppvTest->checkFieldIntegrity($arrLineData[5]);
                    $resYear = $this->yearTest->checkFieldIntegrity($arrLineData[6]);
                    $resPais = $this->paisTest->checkFieldIntegrity($arrLineData[7]);
                    $resMarcadorSerie = $this->serieMarkTest->checkFieldIntegrity($arrLineData[8]);
                    $resTemporada = $this->temporadaTest->checkFieldIntegrity($arrLineData[9]);
                    $resCustomSinopsis = $this->sinopsisCustomTest->checkFieldIntegrity($arrLineData[10]);
                    $resActores = $this->actoresTest->checkFieldIntegrity($arrLineData[11]);
                    $resDirectores = $this->directoresTest->checkFieldIntegrity($arrLineData[12]);
                    $resOpcionales = $this->opcionalesTest->checkFieldIntegrity($arrLineData[13]);

                    if ($resNombre->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Nombre), ".$resNombre->notes." \n";

                    }

                    if ($resSinopsis->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Sinopsis), ".$resSinopsis->notes." \n";

                    }
                    
                    if ($resRating->status== false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Rating), ".$resRating->notes." \n";

                    } 

                    if ($resCategorizacion->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Categorizacion), ".$resCategorizacion->notes." \n";

                    } 
                    
                    if ($resPpv->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Ppv), ".$resPpv->notes." \n";

                    } 

                    if ($resYear->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Año), ".$resYear->notes." \n";
                    } 

                    if ($resPais->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Pais), ".$resPais->notes." \n";
                    } 

                    if ($resMarcadorSerie->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Marcador Serie), ".$resMarcadorSerie->notes." \n";
                    }
                            
                    if ($resTemporada->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Temporada), ".$resTemporada->notes." \n";
                    } 

                    if ($resCustomSinopsis->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Custom Sinopsis), ".$resCustomSinopsis->notes." \n";

                    }
                
                    if ($resActores->status == false){

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Actores), ".$resActores->notes." \n";
                    } 

                    if ($resDirectores->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Directores), ".$resDirectores->notes." \n";
                    }
                            
                    if ($resOpcionales->status == false) {

                        $this->return->status = false;
                        $this->return->value = 0;
                        $this->return->notes = $this->return->notes."Error en la linea #".($ctrLines + 1)." (Opcionales), ".$resOpcionales->notes." \n";
                    }
                }
                $ctrLines++;
            }
            
        }
        else {
    
            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = $this->return->notes."El archivo ".\Config::get($path)."/".$file->name." no existe\n";
            
        }


        //It checks if the return->notes is larger than 5000 chars
        if (strlen($this->return->notes)>5000)
            $this->return->notes = substr($this->return->notes,0,5000);
        
        return $this->return;
    }


    public function checkCorrectTime($dateTime){

        $tmeStamp = strtotime($dateTime);
        $date = date("Y-m-d",$tmeStamp);
        if (isset($this->arrData[$date])){

            foreach($this->arrData[$date] as $horario){

                if ($horario >= $tmeStamp){

                    return false;
                }

            }
            return true;

        }
        else {
            return false;
        }
    }
}
