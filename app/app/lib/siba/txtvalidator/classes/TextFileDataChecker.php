<?php

namespace Siba\txtvalidator\classes;
/**
 * This class is responsible for the data integrity check of a SIBA's STD txt file
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
use \Siba\txtvalidator\classes\StringValidator;

class TextFileDataChecker implements \Siba\txtvalidator\interfaces\FileDataChecker {
    //put your code here
    private $arrData = array();
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
    private $eventsTime = array();

    public function __construct(){
        
        $this->nombreTest = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
        $this->sinopsisTest = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
        $this->ratingTest = new \Siba\txtvalidator\classes\TextFileFieldRatingChecker();
        $this->categoriasTest = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
        $this->ppvTest = new \Siba\txtvalidator\classes\TextFileFieldPpvChecker();
        $this->yearTest = new \Siba\txtvalidator\classes\TextFileFieldYearChecker();
        $this->paisTest = new \Siba\txtvalidator\classes\TextFileFieldPaisChecker();
        $this->serieMarkTest = new \Siba\txtvalidator\classes\TextFileFieldSerieMarkChecker();
        $this->temporadaTest = new \Siba\txtvalidator\classes\TextFileFieldTemporadaChecker();
        $this->sinopsisCustomTest = new \Siba\txtvalidator\classes\TextFileFieldCustomSinopsisChecker();
        $this->actoresTest = new \Siba\txtvalidator\classes\TextFileFieldActoresChecker();
        $this->directoresTest = new \Siba\txtvalidator\classes\TextFileFieldDirectoresChecker();
        $this->opcionalesTest = new \Siba\txtvalidator\classes\TextFileFieldOpcionalesChecker();


    }


    /**
    * This method checks the data integrity of a SIBA's STD content txt file
    * 
    * @param (string) $filePath, Path to txt file in the local file system
    * @return (\Misc\Response) $ret, a \Misc\Response type object
    */
    public function checkDataIntegrity($filePath) {
        
        //======================================================================
        $validator = new StringValidator();
        $ret = new \Misc\Response();
        $ret->notes = array();

        if (file_exists($filePath)){
            $arrDataFile = file($filePath);
            $ctrLines = 0;
            $actualDate = "";
            //==================================================================
            $totalFileLines = count($arrDataFile);
            for ($i=0;$i<$totalFileLines;$i++) {
            //for ($i=0;$i<100;$i++) {

                $line = trim($arrDataFile[$i]);
                $lineObj = new \Siba\txtvalidator\classes\TextFileRecord($line);
                if ($lineObj->lineType()=='date'){

                    if (isset($this->arrData[$line])){

                        //Valida que una fecha no esté ya registrada
                        $ret->status = false;
                        $ret->value = 0;
                        array_push($ret->notes,array(
                            'linenumber' => ($ctrLines + 1), 
                            'desc' => 'Se está repitiendo la fecha'.$line,
                            'line' => $line
                        ));

                    }
                    else {

                        $this->arrData[$line] = array();
                        $actualDate = $line;

                    }

                }
                else if ($lineObj->lineType()=='data') {

                    $arrLineData = preg_split("/\-\-\-/",$line);
                    $horaLine = $lineObj->getLineTime();

                    $keysToValidate = [3, 4, 5, 10, 11, 12, 13];
                    foreach ($keysToValidate as $key) {
                        $inputString = isset($arrLineData[$key]) ? $arrLineData[$key] : '';
                        $result = $validator->validatePipeSequence($inputString);
                        if (!$result['success']) {

                            $errorMessage = $result['error'];
                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => $errorMessage,
                                'line' => $line
                            ));
                        }
                    }
                    
                    //1. Primera revisión es de formato adecuado de hora
                    if ($horaLine == false){

                        $ret->status = false;
                        $ret->value = 0;
                        array_push($ret->notes,array(
                            'linenumber' => ($ctrLines + 1), 
                            'desc' => 'El formato de la hora no es válido',
                            'line' => $line
                        ));

                    }
                    //2. Segunda revisión es de horarios trocados, es decir registro de programas en horarios 
                    //   equivocados.
                    $horario = $actualDate." ".$horaLine.":00";  



                    if ($this->checkAdjacentEventsTime($horario) == false){

                        $ret->status = false;
                        $ret->value = 0;
                        array_push($ret->notes,array(
                            'linenumber' => ($ctrLines + 1), 
                            'desc' => 'El evento tiene horario repetido o es un horario menor (más temprano) respecto del evento anterior (evento anterior en la linea: '.($ctrLines).')',
                            'line' => $line
                        ));

                    }


                    if ($this->checkCorrectTime($horario) == false){

                        $ret->status = false;
                        $ret->value = 0;
                        array_push($ret->notes,array(
                            'linenumber' => ($ctrLines + 1), 
                            'desc' => 'El evento tiene horario repetido respecto de otro evento o está definido en una fecha equivocada',
                            'line' => $line
                        ));

                    }
                    //3. Valida correctitud de campos
                    try{
                        array_push($this->arrData[$actualDate],strtotime($horario));
                        array_push ($this->eventsTime,strtotime($horario));
                    
                    
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

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en el campo Título del evento: '.$resNombre->notes,
                                'line' => $line
                            ));

                        }

                        if ($resSinopsis->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en el campo Sinopsis: '.$resSinopsis->notes,
                                'line' => $line
                            ));

                        }
                        
                        if ($resRating->status== false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en el campo Rating: '.$resRating->notes,
                                'line' => $line
                            ));

                        } 

                        if ($resCategorizacion->status == false){

                            $ret->status = false;
                            $ret->value = 0;

                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en el campo Categorización: '.$resCategorizacion->notes,
                                'line' => $line
                            ));

                        } 
                        
                        if ($resPpv->status == false){

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición de los valores PPV: '.$resPpv->notes,
                                'line' => $line
                            ));

                        } 

                        if ($resYear->status == false){

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Año: '.$resYear->notes,
                                'line' => $line
                            ));
                        } 

                        if ($resPais->status == false){

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo (Pais): '.$resPais->notes,
                                'line' => $line
                            ));
                        } 

                        if ($resMarcadorSerie->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Marcador de serie: '.$resMarcadorSerie->notes,
                                'line' => $line
                            ));
                        }
                                
                        if ($resTemporada->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Temporada: '.$resTemporada->notes,
                                'line' => $line
                            ));
                        } 

                        if ($resCustomSinopsis->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Custom Sinopsis: '.$resCustomSinopsis->notes,
                                'line' => $line
                            ));

                        }
                    
                        if ($resActores->status == false){

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Actores: '.$resActores->notes,
                                'line' => $line
                            ));
                        } 

                        if ($resDirectores->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Directores: '.$resDirectores->notes,
                                'line' => $line
                            ));
                        }
                                
                        if ($resOpcionales->status == false) {

                            $ret->status = false;
                            $ret->value = 0;
                            array_push($ret->notes,array(
                                'linenumber' => ($ctrLines + 1), 
                                'desc' => 'Error en la defición del campo Opcionales: '.$resOpcionales->notes,
                                'line' => $line
                            ));
                        }
                    }
                    catch(\Exception $e){

                        $ret->status = false;
                        $ret->value = 0;
                        array_push($ret->notes,array(
                            'linenumber' => ($ctrLines + 1), 
                            'desc' => 'Error indefinido: '.$e->getMessage(),
                            'line' => $line
                        ));

                    }
                }
                $ctrLines++;
            }
            
        }
        else {
    
            $ret->status = false;
            $ret->value = 0;
            array_push($ret->notes,array(
                            'desc' => "El archivo ".$filePath." no existe",
                        ));
            
        }

        return $ret;
    }

    /**
    * 
    */ 
    public function checkCorrectTime($dateTime){

        $timeStamp = strtotime($dateTime);
        $date = date("Y-m-d",$timeStamp);
        if (isset($this->arrData[$date])){

            foreach($this->arrData[$date] as $horario){

                if ($horario == $timeStamp){

                    return false;
                }

            }
            return true;
        }
        else {
            return false;
        }
    }


    public function checkAdjacentEventsTime($dateTime){

        $timeStamp = strtotime($dateTime);
        $indexTimeStamps = count($this->eventsTime);
        if ($indexTimeStamps > 0){
            if ($this->eventsTime[($indexTimeStamps - 1)] >= $timeStamp ){
                return false;
            }
        }
        return true;
    }

}
