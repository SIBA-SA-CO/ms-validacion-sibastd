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
		$checker = new \Siba\loadstd\classes\TextFileFieldSinopsisChecker();
		$field="Para dar a conocer diferentes temas relacionados con el esoterismo, manejo de las energías, el mundo astral y consejos para que los televidentes puedan afrontar sus problemas de la vid";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldSinopsisCheckerError()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldSinopsisChecker();
		$field="UNITED STATES|TV-14";//
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}



}
