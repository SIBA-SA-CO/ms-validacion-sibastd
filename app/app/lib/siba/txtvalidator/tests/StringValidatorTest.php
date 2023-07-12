<?php

use \Siba\txtvalidator\classes\StringValidator;
use \Siba\txtvalidator\classes\TextFileDataChecker;

class StringValidatorTest extends \TestCase
{
    public function testvalidatePipeSequenceError()
    {

        $validator = new StringValidator();

        $strings = [
            "09:00---Visión Futuro---La emisión de DW sobre ciencia. Dirigido a todos los que se interesan por la ciencia y la investigación en Alemania y Europa. En un lenguaje asequible, con una atractiva presentación y siempre al tanto de las nuevas tendencias.---USA|TV-PG|||COL|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural||SIBA_BASE||SIBA_BASE|Ciencia--- --- ---GER---VISION FUTURO--- ---SIN_CTI|La emisión de DW sobre ciencia. Dirigido a todos los que se interesan por la ciencia y la investigación en Alemania y Europa. En un lenguaje asequible, con una atractiva presentación y siempre al tanto de las nuevas tendencias.--- --- ---ep|28601---",
            "09:30---Los Portales---Programa Inmobiliario que ofrecen terrenos y casas en Lima.---USA|TV-PG---SIBA_TIPO|SERIE||||SIBA_BASE|Infomerciales--- --- ---PER---LOS PORTALES--- ---SIN_CTI|Programa Inmobiliario que ofrecen terrenos y casas en Lima.--- --- ---ep|28603---",
            "10:00---Petramás---Programa especializado en el cuidado del medio ambiente.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural---precio|2000|||idcompra|546780--- ---PER---PETRAMAS--- ---SIN_CTI|Programa especializado en el cuidado del medio ambiente.--- --- ---ep|28601---",
            "10:30---Patrimonio Mundial---Descubra los paraísos de la naturaleza y los monumentos culturales de todo el mundo. Narra historias de lugares legendarios, ciudades míticas y paisajes únicos, ilustradas con impresionantes imágenes.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural||SIBA_BASE||SIBA_BASE|Ciencia--- --- ---GER---PATRIMONIO MUNDIAL--- ---SIN_CTI|Descubra los paraísos de la naturaleza y los monumentos culturales de todo el mundo. Narra historias de lugares legendarios, ciudades míticas y paisajes únicos, ilustradas con impresionantes imágenes.|||||SIN_CTI|Descubra los paraísos de la naturaleza y los monumentos culturales de todo el mundo.--- --- ---ep|28601---",
            "11:00---Eco Latinoamérica---Eco Latinoamérica se enfoca en la preservación de la diversidad biológica y del suministro energético del mañana---USA|TV-G---SIBA_TIPO|SERIE||SIBA_BASE|Noticias||SIBA_BASE|Periodismo--- ---2023---PER---ECO LATINOAMERICA--- ---SIN_CTI|Eco Latinoamérica se enfoca en la preservación de la diversidad biológica y del suministro energético del mañana---Jessica Biel|Estados Unidos|F||||Dustin Hoffman|Estados Unidos|M--- ---ep|28601---",
            "11:30---Los Portales---Programa Inmobiliario que ofrecen terrenos y casas en Lima.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Infomerciales--- --- ---PER---LOS PORTALES--- ---SIN_CTI|Programa Inmobiliario que ofrecen terrenos y casas en Lima.--- ---Stanley Kubric|Estados Unidos|M|||Woody Allen|Estados Unidos|M ---ep|28604---",
            "12:00---Medicina Extraordinaria---Medicina extraordinaria.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Salud--- ---2022---PER---MEDICINA EXTRAORDINARIA--- ---SIN_CTI|Medicina extraordinaria.--- --- ---ep|28601||||estreno|s---"
        ];

        #Se revisa todo el registro 1, este tiene error el campo Rating (Campo #3)
        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[0]) ? $arrLineData[0] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[1]) ? $arrLineData[1] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[2]) ? $arrLineData[2] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[3]) ? $arrLineData[3] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(3,$totalPipesCount);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[4]) ? $arrLineData[4] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[5]) ? $arrLineData[5] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[6]) ? $arrLineData[6] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[7]) ? $arrLineData[7] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[8]) ? $arrLineData[8] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[9]) ? $arrLineData[9] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[10]) ? $arrLineData[10] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[11]) ? $arrLineData[11] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[12]) ? $arrLineData[12] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[13]) ? $arrLineData[13] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);

        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[14]) ? $arrLineData[14] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true, $result['success']);
        
        #Se revisa el segundo registro, este tiene cuatro '|' en el campo Categoria (Campo #4)
        $arrLineData = preg_split("/\-\-\-/", $strings[1]);
        $inputString = isset($arrLineData[4]) ? $arrLineData[4] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(4,$totalPipesCount);
        
        #Se revisa el tercer registro, este tiene tres '|' en el campo PPV (Campo #5)
        $arrLineData = preg_split("/\-\-\-/", $strings[2]);
        $inputString = isset($arrLineData[5]) ? $arrLineData[5] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(3,$totalPipesCount);

        #Se revisa el cuarto registro, este tiene cinco '|' en el campo Sinopsis  (Campo #10)
        $arrLineData = preg_split("/\-\-\-/", $strings[3]);
        $inputString = isset($arrLineData[10]) ? $arrLineData[10] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(5,$totalPipesCount);

        #Se revisa el quinto registro, este tiene cuatro '|' en el campo Actores  (Campo #11)
        $arrLineData = preg_split("/\-\-\-/", $strings[4]);
        $inputString = isset($arrLineData[11]) ? $arrLineData[11] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(4,$totalPipesCount);

        #Se revisa el sexto registro, este tiene tres '|' en el campo Directores  (Campo #12)
        $arrLineData = preg_split("/\-\-\-/", $strings[5]);
        $inputString = isset($arrLineData[12]) ? $arrLineData[12] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(3,$totalPipesCount);

        #Se revisa el septimo registro, este tiene cuatro '|' en el campo Opcionales  (Campo #13)
        $arrLineData = preg_split("/\-\-\-/", $strings[6]);
        $inputString = isset($arrLineData[13]) ? $arrLineData[13] : '';
        $result = $validator->validatePipeSequence($inputString);
        $consecutivePipesCount = preg_match_all('/\|+/', $result['error'], $matches);
        $totalPipesCount = 0;
        foreach ($matches[0] as $match) {
            $pipeCount = strlen($match);
            $totalPipesCount += $pipeCount;
        }
        $this->assertSame(false,$result['success']);
        $this->assertSame(4,$totalPipesCount);
    }

    #Es el mismo test de la funcion anterior pero este no tiene triple o mas pipes
    public function testvalidatePipeSequenceOk()
    {

        $validator = new StringValidator();

        $strings = [
            "09:00---Visión Futuro---La emisión de DW sobre ciencia. Dirigido a todos los que se interesan por la ciencia y la investigación en Alemania y Europa. En un lenguaje asequible, con una atractiva presentación y siempre al tanto de las nuevas tendencias.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural||SIBA_BASE||||SIBA_BASE|Ciencia--- --- ---GER---VISION FUTURO--- ---SIN_CTI|La emisión de DW sobre ciencia. Dirigido a todos los que se interesan por la ciencia y la investigación en Alemania y Europa. En un lenguaje asequible, con una atractiva presentación y siempre al tanto de las nuevas tendencias.--- --- ---ep|28601---",
            "09:30---Los Portales---Programa Inmobiliario que ofrecen terrenos y casas en Lima.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Infomerciales--- --- ---PER---LOS PORTALES--- ---SIN_CTI|Programa Inmobiliario que ofrecen terrenos y casas en Lima.--- --- ---ep|28603---",
            "10:00---Petramás---Programa especializado en el cuidado del medio ambiente.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural--- --- ---PER---PETRAMAS--- ---SIN_CTI|Programa especializado en el cuidado del medio ambiente.--- --- ---ep|28601---",
            "10:30---Patrimonio Mundial---Descubra los paraísos de la naturaleza y los monumentos culturales de todo el mundo. Narra historias de lugares legendarios, ciudades míticas y paisajes únicos, ilustradas con impresionantes imágenes.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Cultural||SIBA_BASE|||SIBA_BASE|Ciencia--- --- ---GER---PATRIMONIO MUNDIAL--- ---SIN_CTI|Descubra los paraísos de la naturaleza y los monumentos culturales de todo el mundo. Narra historias de lugares legendarios, ciudades míticas y paisajes únicos, ilustradas con impresionantes imágenes.--- --- ---ep|28601---",
            "11:00---Eco Latinoamérica---Eco Latinoamérica se enfoca en la preservación de la diversidad biológica y del suministro energético del mañana---USA|TV-G---SIBA_TIPO|SERIE||SIBA_BASE|Noticias||SIBA_BASE|Periodismo--- ---2023---PER---ECO LATINOAMERICA--- ---SIN_CTI|Eco Latinoamérica se enfoca en la preservación de la diversidad biológica y del suministro energético del mañana--- --- ---ep|28601---",
            "11:30---Los Portales---Programa Inmobiliario que ofrecen terrenos y casas en Lima.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Infomerciales--- --- ---PER---LOS PORTALES--- ---SIN_CTI|Programa Inmobiliario que ofrecen terrenos y casas en Lima.--- --- ---ep|28604---",
            "12:00---Medicina Extraordinaria---Medicina extraordinaria.---USA|TV-PG---SIBA_TIPO|SERIE||SIBA_BASE|Salud--- ---2022---PER---MEDICINA EXTRAORDINARIA--- ---SIN_CTI|Medicina extraordinaria.--- --- ---ep|28601---"
        ];

        
        #Se revisa el campo Rating (Campo #3)
        $arrLineData = preg_split("/\-\-\-/", $strings[0]);
        $inputString = isset($arrLineData[3]) ? $arrLineData[3] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);
        

        #Se revisa el campo Categoria (Campo #4)
        $arrLineData = preg_split("/\-\-\-/", $strings[1]);
        $inputString = isset($arrLineData[4]) ? $arrLineData[4] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);
        
        #Se revisa el campo PPV (Campo #5)
        $arrLineData = preg_split("/\-\-\-/", $strings[2]);
        $inputString = isset($arrLineData[5]) ? $arrLineData[5] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);

        #Se revisa el campo Sinopsis  (Campo #10)
        $arrLineData = preg_split("/\-\-\-/", $strings[3]);
        $inputString = isset($arrLineData[10]) ? $arrLineData[10] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);
        

        #Se revisa el campo Actores  (Campo #11)
        $arrLineData = preg_split("/\-\-\-/", $strings[4]);
        $inputString = isset($arrLineData[11]) ? $arrLineData[11] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);

        #Se revisa el campo Directores  (Campo #12)
        $arrLineData = preg_split("/\-\-\-/", $strings[5]);
        $inputString = isset($arrLineData[12]) ? $arrLineData[12] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);

        #Se revisa el campo Opcionales  (Campo #13)
        $arrLineData = preg_split("/\-\-\-/", $strings[6]);
        $inputString = isset($arrLineData[13]) ? $arrLineData[13] : '';
        $result = $validator->validatePipeSequence($inputString);
        $this->assertSame(true,$result['success']);

    }

    public function testvalidatePipeSequenceFileOk()
    {
        $fileDataChecker = new TextFileDataChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/PANAMERICANA_OK.TXT';
		$fileDataCheckResult = $fileDataChecker->checkDataIntegrity($filePath);
		$this->assertTrue($fileDataCheckResult->status);

    }

    public function testvalidatePipeSequenceFileError()
    {
        $fileDataChecker = new TextFileDataChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/PANAMERICANA_ERROR.TXT';
		$fileDataCheckResult = $fileDataChecker->checkDataIntegrity($filePath);
		$this->assertFalse($fileDataCheckResult->status);
        $this->assertSame("Error en el campo del evento: Se encontró una secuencia de ||| consecutivos.",$fileDataCheckResult->notes[0]["desc"]);

    }

    
}