<?php

class TextFileFieldCategoriasCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Categorias" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Categorias" debe tener la siguiente
	 * estructura:
	 *
	 * SIBA_TIPO|UNICO||SIBA_BASE|Deportivo
	 * 
	 * @return void
	 */
	public function testCheckFieldCategoriasCheckerOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Periodismo";
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Telenovela";
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Naturaleza";
		$field="SIBA_TIPO|UNICO||SIBA_BASE|Pelicula||SIBA_BASE|Accion";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldCategoriasCheckerError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Paragliding";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

	public function testCheckFieldCategoriasCheckerTipoValidatorOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Paragliding";
		$res = $checker->checkTipoEvento($field);
		$this->assertSame(true,$res);
	}

	public function testCheckFieldCategoriasCheckerTipoValidatorError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SE||SIBA_BASE|Paragliding";
		$res = $checker->checkTipoEvento($field);
		$this->assertSame(false,$res);
	}

	public function testCheckFieldCategoriasCheckerGeneroValidatorOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|Erotico";
		$res = $checker->checkGenero($field);
		$this->assertSame(true,$res['res']);
	}

	public function testCheckFieldCategoriasCheckerGeneroValidatorForTildesOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|épica";
		$res = $checker->checkGenero($field);
		$this->assertSame(true,$res['res']);
	}

	public function testCheckFieldCategoriasCheckerGeneroValidatorError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SE||SIBA_BASE|Paragliding";
		$res = $checker->checkGenero($field);
		$this->assertSame(false,$res['res']);
	}


	public function testCheckEmptyField(){

		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field=" ";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

	public function testCheckFieldCategoriasCheckerGeneroValidatorForTildesOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldCategoriasChecker();
		$field="SIBA_TIPO|SERIE||SIBA_BASE|épica";
		$res = $checker->checkGenero($field);
		$this->assertSame(true,$res['res']);
	}

}
