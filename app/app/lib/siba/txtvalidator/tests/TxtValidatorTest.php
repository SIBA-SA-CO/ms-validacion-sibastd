<?php

use \Siba\txtvalidator\classes\TextFileNameFromContentDiscoverer;
use \Siba\txtvalidator\classes\TextFileHttpPostUploaderHandler;
use \Siba\txtvalidator\classes\TextFileStructureChecker;
use \Siba\txtvalidator\classes\TextFileDataChecker;
use \Siba\txtvalidator\classes\TextFileChecker;

class TxtValidatorTest extends \TestCase {

	/**
	 * Este test, verifica que el generardor de nombre de archivo a partir
	 * de un algoritmo hash funciona correctamente
	 * @return void
	 */

	public function testGetHashedFileName(){

		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO.TXT';
		$fh = fopen($filePath,'r');
		$fileContent = fread($fh,filesize($filePath));
		fclose($fh);
		$fileNameDiscover = new TextFileNameFromContentDiscoverer();
		$this->assertEquals('a6477823eb82c86a53f1dad4ad872219',$fileNameDiscover->getHashedFileName($fileContent));
	}

	/**
	 * Este test, verifica que se manipula correctamente el archivo luego de ser subido
	 * vía HTTP POST
	 * @return void
	 */
	public function testUploadTxtFile(){
		$httpPostFile = new \Symfony\Component\HttpFoundation\File\UploadedFile(base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO.TXT','AMC COMPLETO.TXT','text/plain',null,null,true);
		$uploaderHandler = new TextFileHttpPostUploaderHandler();
		$fileNameDiscover = new TextFileNameFromContentDiscoverer();
		$newFilePath = $uploaderHandler->onUploadedHttpPostFile($httpPostFile,$fileNameDiscover);
		$this->assertFileExists($newFilePath,"El archivo ".$newFilePath." no existe");
		/* Returning file to original position to test again the methods */
		$httpPostFile2 = new \Symfony\Component\HttpFoundation\File\UploadedFile($newFilePath,null,'text/plain',null,null,true);
		$httpPostFile2->move(base_path().'/app/lib/siba/txtvalidator/tests/Docs/','AMC COMPLETO.TXT');
	}

	public function testCheckFileStructureOk(){
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO.TXT';
		$textFileStructureChecker = new TextFileStructureChecker();
		$checking = $textFileStructureChecker->checkStructureIntegrity($filePath);
		$this->assertTrue($checking->status);
	}

	public function testCheckFileStructureErrorDate(){
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO-ErrorDate.TXT';
		$textFileStructureChecker = new TextFileStructureChecker();
		$checking = $textFileStructureChecker->checkStructureIntegrity($filePath);
		$this->assertFalse($checking->status);
		$this->assertRegExp('/^Error en la linea: 151/',$checking->notes);
	}

	public function testCheckFileStructureData1Error(){
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO-Data1Error.TXT';
		$textFileStructureChecker = new TextFileStructureChecker();
		$checking = $textFileStructureChecker->checkStructureIntegrity($filePath);
		$this->assertFalse($checking->status);
		$this->assertRegExp('/^Error en la linea: 42/',$checking->notes);
	}

	public function testCheckFileStructureData2Error(){
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO-Data2Error.TXT';
		$textFileStructureChecker = new TextFileStructureChecker();
		$checking = $textFileStructureChecker->checkStructureIntegrity($filePath);
		$this->assertFalse($checking->status);
		$this->assertRegExp("/^Error en la linea: 51/",$checking->notes);
	}

	public function testCheckFileStructureData3Error(){
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/AMC COMPLETO-Data3Error.TXT';
		$textFileStructureChecker = new TextFileStructureChecker();
		$checking = $textFileStructureChecker->checkStructureIntegrity($filePath);
		$this->assertFalse($checking->status);
		$this->assertRegExp("/^Error en la linea: 438/",$checking->notes);
	}	

	public function testTextFileDataTesting(){
		$fileDataChecker = new TextFileDataChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/SONY ANDES COMPLETO.TXT';
		//echo $filePath."\n";
		$fileDataCheckResult = $fileDataChecker->checkDataIntegrity($filePath);
		//print_r($fileDataCheckResult);
		$this->assertTrue($fileDataCheckResult->status);
	}


	public function testTextFileDataTestingWrongEventsTimeDefintion(){
		$fileChecker = new TextFileChecker();
		$fileDataChecker = new TextFileDataChecker();
		$textFileStructureChecker = new TextFileStructureChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/SONY ANDES COMPLETO-error2.TXT';
		//echo $filePath."\n";
		$fileCheckResult = $fileChecker->check($filePath,$fileDataChecker,$textFileStructureChecker);
		$this->assertFalse($fileCheckResult->status);
		$this->assertEquals(6,count($fileCheckResult->notes));
	}


	public function testTextFileFullTesting(){
		$fileChecker = new TextFileChecker();
		$fileDataChecker = new TextFileDataChecker();
		$textFileStructureChecker = new TextFileStructureChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/SONY ANDES COMPLETO.TXT';
		//echo $filePath."\n";
		$fileCheckResult = $fileChecker->check($filePath,$fileDataChecker,$textFileStructureChecker);
		//print_r($fileCheckResult);
		$this->assertTrue($fileCheckResult->status);
	}

	public function testTextFileFullTestingError1(){
		$fileChecker = new TextFileChecker();
		$fileDataChecker = new TextFileDataChecker();
		$textFileStructureChecker = new TextFileStructureChecker();
		$filePath = base_path().'/app/lib/siba/txtvalidator/tests/Docs/SONY ANDES COMPLETO-error1.TXT';
		//echo $filePath."\n";
		$fileCheckResult = $fileChecker->check($filePath,$fileDataChecker,$textFileStructureChecker);
		//print_r($fileCheckResult);
		$this->assertFalse($fileCheckResult->status);
	}

}
