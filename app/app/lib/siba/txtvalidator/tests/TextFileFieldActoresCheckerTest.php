<?php

class TextFileFieldActoresCheckerTest extends \TestCase {

	/**
	 * Este test, verifica que el campo "Actores" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Actores" debe tener la siguiente
	 * estructura:
	 *
	 * nombre1 apellido1||nombre2 apellido2||nombre3 apellido3
	 * 
	 * @return void
	 */
	public function testCheckFieldActoresCheckerOk()
	{
		$actoresChecker = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
		$field="Claudia Gurisati||Jeisson Calderón||Carlos Sanabria||Carolina Hernández";
		$res = $actoresChecker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}


	public function testCheckFieldActoresChecker2Ok()
	{
		$actoresChecker = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
		$field="Claudia Gurisati||Jeisson Calderón|co|m||Carlos Sanabria||Carolina Hernández";
		$res = $actoresChecker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldActoresCheckerError()
	{
		$actoresChecker = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
		$field="ep|14803";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $actoresChecker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

	public function testBadFieldSeparatorError(){


		
		$actoresChecker = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
		$field="Emile Hirsch||Susan Sarandon||Melissa Holroyd||Matthew Fox||Christina Ricci|";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $actoresChecker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);

	}


	public function testBasicDirectorNameOK(){
		
		$actoresChecker = new \Siba\loadstd\classes\TextFileFieldActoresChecker();
		$field="Emile Hirsch";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $actoresChecker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);

	}

}
