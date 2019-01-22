<?php

class TextFileFieldRatingCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Actores" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Actores" debe tener la siguiente
	 * estructura:
	 *
	 * nombre1 apellido1||nombre2 apellido2||nombre3 apellido3
	 * 
	 * @return void
	 */
	public function testCheckFieldRatingCheckerOk()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldRatingChecker();
		$field="USA|TV-14";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldRatingCheckerError()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldRatingChecker();
		$field="UNITED STATES|TV-14";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}


	public function testCheckFieldRatingCheckerErrorBadRating()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldRatingChecker();
		$field="USA|TV HD-PG";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

}
