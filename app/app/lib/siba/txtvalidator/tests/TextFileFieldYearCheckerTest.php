<?php

class TextFileFieldYearCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Categorias" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Categorias" debe tener la siguiente
	 * estructura:
	 *
	 * SIBA_TIPO|UNICO||SIBA_BASE|Deportivo
	 * 
	 * @return void
	 */
	public function testCheckFieldYearCheckerOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldYearChecker();
		$field="2014";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldYearCheckerError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldYearChecker();
		$field="14";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}



}
