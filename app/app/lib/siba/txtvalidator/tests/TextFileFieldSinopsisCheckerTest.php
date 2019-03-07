<?php

class TextFileFieldSinopsisCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Categorias" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Categorias" debe tener la siguiente
	 * estructura:
	 *
	 * SIBA_TIPO|UNICO||SIBA_BASE|Deportivo
	 * 
	 * @return void
	 */
	public function testCheckFieldSinopsisCheckerOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vid";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldSinopsisCheckerErrorByPipe()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="UNITED STATES|TV-14";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}


	public function testCheckFieldSinopsisCheckerErrorByAmp()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vida & muerte";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}


	public function testCheckFieldSinopsisCheckerErrorByLessThan()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vida < muerte";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}

	public function testCheckFieldSinopsisCheckerErrorByMoreThan()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vida > muerte";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}


	public function testCheckFieldSinopsisCheckerErrorByApostrofe()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vida ' muerte";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}

}
