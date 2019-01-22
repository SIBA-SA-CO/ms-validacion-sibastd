<?php

class TextFileFieldPpvCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Actores" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Actores" debe tener la siguiente
	 * estructura:
	 *
	 * nombre1 apellido1||nombre2 apellido2||nombre3 apellido3
	 * 
	 * @return void
	 */
	public function testCheckFieldPpvCheckerOk()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldPpvChecker();
		$field="eventprices|6900|";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldPpvCheckerError()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldPpvChecker();
		$field="eventprices69000";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

}
