<?php

class TextFileFieldNombreCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Actores" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Actores" debe tener la siguiente
	 * estructura:
	 *
	 * nombre1 apellido1||nombre2 apellido2||nombre3 apellido3
	 * 
	 * @return void
	 */
	public function testCheckFieldNombreCheckerOk()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto Astral";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}

	public function testCheckFieldNombreCheckerError()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto A stral C  l C Contacto Astral C CofghfgCo ntacto Ast ral C Cont acto Astrantacto Astral C mmmmmmmmm ";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}

}
