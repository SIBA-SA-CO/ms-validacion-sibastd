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


	public function testCheckFieldSinopsisCheckerOkWithTilde()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Un acto de compasión y arrogancia conduce a una guerra como ninguna y al origen del planeta de los simios. El equipo de efectos especiales ganador de un premio Óscar que dio vida a las películas Avatar y El señor de los anillos abre nuevos caminos creando un simio digital que realiza una interpretación dramática de una emoción y una inteligencia sin precedentes, y épicas batallas en las que descansa el destino de los hombres y de los simios.";//Programa de VH1 que nos muestra  sigue estando de moda.
		$res = $checker->checkFieldIntegrity($field);
		//print_r($res);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldSinopsisCheckerErrorWithSpecialChars()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldSinopsisChecker();
		$field="Un acto de compasión y arrogancia conduce a una guerra como ninguna y al origen del planeta de los simios. El equipo de efectos especiales ganador de un premio Óscar que dio vida a las películas Avatar y El señor de los anillos abre nuevos caminos creando un simio digital que realiza una interpretación dramática de una emoción y una inteligencia sin precedentes, y épicas batallas en las que descansa el destino de los hombres y de los simios»";//Programa de VH1 que nos muestra  sigue estando de moda.
		$res = $checker->checkFieldIntegrity($field);
		//print_r($res);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}
}
