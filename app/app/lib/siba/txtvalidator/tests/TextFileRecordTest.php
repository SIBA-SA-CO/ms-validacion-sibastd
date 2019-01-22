<?php

class TextFileRecordTest extends TestCase {

	/**
	 * Este test, verifica que el campo "Categorias" de los archivos de carga
	 * de DEV2 (STD) estén correctos, el campo "Categorias" debe tener la siguiente
	 * estructura:
	 *
	 * SIBA_TIPO|UNICO||SIBA_BASE|Deportivo
	 * 
	 * @return void
	 */
	public function testLineTypeForDateOk()
	{
		$line = "2015-01-20";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->lineType();
		$this->assertSame("date",$res);
	}

	public function testLineTypeDateErrorByWrongDate()
	{
		$line = "2015-14-20";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->lineType();
		$this->assertSame(0,$res);
	}

	public function testLineTypeDateErrorByWrongFormat()
	{
		$line = "2015/01/15";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->lineType();
		$this->assertSame(0,$res);
	}

	public function testLineTypeDataOk()
	{
		$line = "01:00---Godzilla 2014 (Doblada)---El monstruo más famoso del mundo se enfrenta a criaturas creadas por la arrogancia de científicos humanos que ponen en riesgo la existencia de la humanidad.---USA|TV-14---SIBA_TIPO|UNICO||SIBA_BASE|Pelicula||SIBA_BASE|Accion---eventprices|6900|---2014---USA--- --- ---SIN_CTI|El monstruo más famoso del mundo se enfrenta a criaturas creadas por la arrogancia de científicos humanos que ponen en riesgo la existencia de la humanidad.---Aaron Taylor-Johnson||Elizabeth Olsen||Bryan Cranston---Gareth Edwards--- ---";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->lineType();
		$this->assertSame("data",$res);
	}

	public function testGetLineTime()
	{
		$line = "01:00---Godzilla 2014 (Doblada)---El monstruo más famoso del mundo se enfrenta a criaturas creadas por la arrogancia de científicos humanos que ponen en riesgo la existencia de la humanidad.---USA|TV-14---SIBA_TIPO|UNICO||SIBA_BASE|Pelicula||SIBA_BASE|Accion---eventprices|6900|---2014---USA--- --- ---SIN_CTI|El monstruo más famoso del mundo se enfrenta a criaturas creadas por la arrogancia de científicos humanos que ponen en riesgo la existencia de la humanidad.---Aaron Taylor-Johnson||Elizabeth Olsen||Bryan Cranston---Gareth Edwards--- ---";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->getLineTime();
		$this->assertSame("01:00",$res);
	}

	public function testLineTypeForDataError()
	{

		$line = "Indeterminada info, sin formato";
		$checker = new \Siba\loadstd\classes\TextFileRecord($line);
		$res = $checker->lineType();
		$this->assertSame(0,$res);
	}

}
