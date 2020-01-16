<?php

class TextFileFieldOpcionalesCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Actores" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Actores" debe tener la siguiente
	 * estructura:
	 *
	 * nombre1 apellido1||nombre2 apellido2||nombre3 apellido3
	 * 
	 * @return void
	 */
	public function testCheckFieldOpcionalesCheckerOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldOpcionalesChecker();
		$field="ep|14803";
		$field="ep|8";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldOpcionalesCheckerError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldOpcionalesChecker();
		$field="ep14803";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

	public function testCheckFieldOpcionalesCheckerErrorByHyphen()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldOpcionalesChecker();
		$field="ep|14803--";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/Se han dejado caracteres separadores de campo \(guiones\)/',$res->notes);
	}

}
