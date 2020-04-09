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
		$field="Contacto A stral C  l C Contacto Astral C CofghfgCo ntacto A mddfmf mdfmmf m dmsmm mfddf mddf df m dmfmf mdfm fmdf  st ral C Cont acto Astrantacto Astral C mmmmmmmmm ";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
	}



	public function testCheckFieldNombreCheckerErrorByLong()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto A stral C  l C Contacto Astral C Cofgh k kdkdkkdk k kdkdk fgCo ntkdkkfm kdkmkdmkm kmddacto Ast ral C Cont acto Astrantacto Astral C mmmmmmmmm ";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/(longitud permitida)/',$res->notes);
	}



	public function testCheckFieldNombreCheckerErrorBySpecialChars()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral &";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/(caracteres no permitidos)/',$res->notes);
	}


	public function testCheckFieldNombreCheckerErrorBySpecialCharAmp()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral &";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/(caracteres no permitidos)/',$res->notes);
	}

	public function testCheckFieldNombreCheckerErrorBySpecialCharComilla()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral 'Lobo'";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/contiene caracteres/',$res->notes);
	}


	public function testCheckFieldNombreCheckerErrorBySpecialCharComillaYAmp()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral 'Lobo' &  the music";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/contiene caracteres/',$res->notes);
	}

	public function testCheckFieldNombreCheckerErrorBySpecialCharLessThan()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral <Lobo  the music";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);//Validando que en la nota de error venga el caracter "<"
	}

	public function testCheckFieldNombreCheckerErrorBySpecialCharMoreThan()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral >Lobo  the music";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}

	public function testCheckFieldNombreCheckerErrorBySpecialCharApostrofe()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral 'Lobo  the music";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}


	public function testCheckFieldNombreCheckerErrorBySpecialCharLeftDoubleQuotation()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral  Lobo “ the music";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
		//$this->assertRegExp('/\(“\)/',$res->notes);
	}


	public function testCheckFieldNombreCheckerErrorBySpecialCharLeftPointingDoubleQuotation()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral Lobo the music «";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
		//$this->assertRegExp('/\(“\)/',$res->notes);
	}
	
	public function testCheckFieldNombreCheckerErrorBySpecialCharRightPointingDoubleQuotation()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="Contacto astral Lobo the music »";//Campo errado, se intenta simular el campo "Opcionales"
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
		//$this->assertRegExp('/\(“\)/',$res->notes);
	}

	public function testCheckFieldNombreCheckerErrorWithSpecialChars0x2028()
	{
		$checker = new \Siba\txtvalidator\classes\TextFileFieldNombreChecker();
		$field="En los próximos por completo. El ciclo de las pandemias";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertRegExp('/caracteres no permitidos/',$res->notes);
	}

}
