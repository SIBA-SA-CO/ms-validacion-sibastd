<?php

class TextFileFieldCustomSinopsisCheckerTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Categorias" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Categorias" debe tener la siguiente
	 * estructura:
	 *
	 * SIBA_TIPO|UNICO||SIBA_BASE|Deportivo
	 * 
	 * @return void
	 */
	public function testCheckFieldCustomSinopsisCheckerOk()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldCustomSinopsisChecker();
		$field="SIN_CTI|Canal RCN";
		$field="SIN_CTI|Cada día, cinco emisiones en directo con el mayor cubrimiento en los sucesos nacionales e internacionales. Enviados especiales, entrevistas, opinión, debate y análisis con los protagonistas de la realidad";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(true,$res->status);
	}


	public function testCheckFieldCustomSinopsisCheckerError()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldCustomSinopsisChecker();
		$field="SIN_CTI_NNNNN|Programacion Canal RCN";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertSame('El tipo de sinopsis debe ser una cadena de caracteres de longitud no menor a 5 caracteres y no mayor a 12 caracteres: '.$field,$res->notes);
	}


	public function testCheckFieldCustomSinopsisNoPipesCharsThere()
	{
		$checker = new \Siba\loadstd\classes\TextFileFieldCustomSinopsisChecker();
		$field="México al Día es un noticiario que brinda información oportuna, clara y veraz de los sucesos y acontecimientos que están transformando a nuestro país. Su compromiso es ofrecer al auditorio un recuento diario de los avances y logros de México y su gente, presentando secciones de información internacional, deportes, cultura y espectáculos, entre otros.";
		$res = $checker->checkFieldIntegrity($field);
		$this->assertSame(false,$res->status);
		$this->assertSame('No existen los caracteres pipe (|) que forman la estructura del campo',$res->notes);
	}


}
