<?php

use Siba\apirest\Classes\HttpGetParamLimit;
use Siba\apirest\Classes\HttpGetParamFields;
use Siba\apirest\Classes\HttpGetParamFilter;
use Siba\apirest\Classes\HttpGetParamModelAttributes;
use Siba\apirest\Classes\HttpURIBuilder;
use Siba\apirest\Classes\ApiModelAttrMapper;
use Misc\curl\Curl;

class ApiRestHttpRequestTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */


	public function testHttpRequestExample(){

		$stubHttpRequestParams = array(
			"limit" => '0,50',
			"fields" => 'nombre,sinopsis,year',
			"filter" => "[[{field: 'year',operator: '>=',value: 2013},{field: 'year',operator: '<=',value: 2017,connector: 'and'},],{field: 'marca',operator: '==',value: 'bmw',connector: 'and'},[{field: 'year',operator: '>=',value: 2013},{field: 'year',operator: '<=',value: 2017,connector: 'and'},{field: 'year',operator: '<=',value: 2017,connector: 'and'},{field: 'year',operator: '<=',value: 2017,connector: 'and'},],{field: 'marca',operator: '==',value: 'bmw',connector: 'and'},]",
			"canal" => "32",
			"token" => "ab087a25d74c",
		);
		
	}


	/* Test for HttpGetParamLimit */

	public function testHttpGetParamLimit_fullLimitDefinition(){


		$stubLimit = "10,20";
		$limitParam = new HttpGetParamLimit($stubLimit);
		$this->assertArrayHasKey('index', $limitParam->getData());
		$this->assertArrayHasKey('total', $limitParam->getData());
		$this->assertEquals(10, $limitParam->getData()['index']);
		$this->assertEquals(20, $limitParam->getData()['total']);

	}

	public function testHttpGetParamLimit_shortLimitDefinition(){


		$stubLimit = "20";
		$limitParam = new HttpGetParamLimit($stubLimit);
		$this->assertArrayHasKey('index', $limitParam->getData());
		$this->assertArrayHasKey('total', $limitParam->getData());
		$this->assertEquals(0, $limitParam->getData()['index']);
		$this->assertEquals(20, $limitParam->getData()['total']);

	}


	/*Test for HttpGetParamFields */
	public function testHttpGetParamFields_fullFieldDefinition(){

		$stubFields = "nombre,sinopsis,canal";
		$fieldParam = new HttpGetParamFields($stubFields);
		$this->assertEquals('sinopsis',$fieldParam->getData()[1]);
		$this->assertEquals(3,count( $fieldParam->getData() ));

	}

	public function testHttpGetParamFields_shortFieldDefinition(){

		$stubFields = "nombre";
		$fieldParam = new HttpGetParamFields($stubFields);
		$this->assertEquals('nombre',$fieldParam->getData()[0]);
		$this->assertEquals(1,count($fieldParam->getData()));

	}
	

	/*Test for HttpGetParamFilter */
	public function testHttpGetParamFilter_FieldDefinition(){

		$map = array("chn"=>"idcanal","begin"=>"fecha_hora","title"=>"nombre");
		$stubFields = '[{"lc"  : "","ele" :[{"field": "year","operator": ">=","value": 2013}, {"field": "year","operator": "<=","value": 2017,"lc" : "and"}]},{"lc"  : "or","ele"  :[{"field": "marca","operator": "==","value": "bmw"}]},{"lc"  : "or","ele"  :[{"field": "year","operator": ">=","value": 2013}, {"field": "year","operator": "<=","value": 2017,"lc" : "and"}, {"field": "year","operator": "<=","value": 2017,"lc" : "and"}, {"field": "year","operator": "<=","value": 2017,"lc" : "and"}]},{"lc"  : "and","ele"  :[{"field": "marca","operator": "==","value": "bmw"}]}]';
		$filterParams = new HttpGetParamFilter($stubFields);
		$mapObj = new ApiModelAttrMapper($map);
		$filterParams->setMap($mapObj);

		
		$this->assertObjectHasAttribute('ele',$filterParams->getData()[0]);
		$this->assertObjectHasAttribute('lc',$filterParams->getData()[0]);
		$this->assertEquals(4,count( $filterParams->getData() ));
		$this->assertEquals(" ( ( ( year >= '2013'  )  and  ( year <= '2017'  )  )  or  ( ( marca == 'bmw'  )  )  or  ( ( year >= '2013'  )  and  ( year <= '2017'  )  and  ( year <= '2017'  )  and  ( year <= '2017'  )  )  and  ( ( marca == 'bmw'  )  ) ) ",$filterParams->getSqlWhereString(),"La cadena originas no es igual a la esperada");
		
	}



	/*Test for HttpGetParamFilter con mapeo*/
	public function testHttpGetParamFilter_FieldDefinitionMapper(){

		$map = array("chn"=>"idcanal","begin"=>"fecha_hora","title"=>"nombre");
		$mapObj = new ApiModelAttrMapper($map);
		$stubFields = '[{"lc": "","ele": [{"field": "begin","operator": ">=","value": "2017-09-01 00:00:00"}, {"field": "fecha_hora_real","operator": "<","value": "2017-09-01 23:59:59","lc": "and"}]},{"lc":"&&","ele":[{"field":"chn","operator":"=","value":"380"}]}]';
		$filterParams = new HttpGetParamFilter($stubFields);
		$filterParams->setMap($mapObj);

		
		$this->assertObjectHasAttribute('ele',$filterParams->getData()[0]);
		$this->assertObjectHasAttribute('lc',$filterParams->getData()[0]);
		$this->assertObjectHasAttribute('field',$filterParams->getData()[0]->ele[0]);

		$this->assertEquals(" ( ( ( fecha_hora >= '2017-09-01 00:00:00'  )  and  ( fecha_hora_real < '2017-09-01 23:59:59'  )  )  &&  ( ( idcanal = '380'  )  ) ) ",$filterParams->getSqlWhereString(),"La cadena originas no es igual a la esperada");
		
	}


	/*Test for HttpGetParamModelAttributes */
	public function testHttpGetParamFilter_ModelAttributes(){
		
		$stubFields = 'title=Los%20Reyes%20De%20South%20Beach&duration=7200&filter=xxxxxxxx&limit=10,2';
		$arrFields = ['title','begin','sinop','channel','duration'];
		$map = array('title'=>'nombre','begin'=>'fecha_hora','sinop'=>'descripcion','channel'=>'idcanal','duration'=>'duracion');
		$mapeador = new ApiModelAttrMapper($map);
		$attrParams = new HttpGetParamModelAttributes($stubFields,$arrFields,$mapeador);
		//var_dump($attrParams->getData());
		$this->assertEquals('7200',$attrParams->getData()['duration']);
	}

	/*Test for HttpGetParamModelAttributes */
	public function testHttpGetParamFilter_ModelAttributesNoAttr(){
		
		$stubFields = 'filter=xxxxxxxx&limit=10,2';
		$arrFields = ['title','begin','sinop','channel','duration'];
		$map = array('title'=>'nombre','begin'=>'fecha_hora','sinop'=>'descripcion','channel'=>'idcanal','duration'=>'duracion');
		$mapeador = new ApiModelAttrMapper($map);
		$attrParams = new HttpGetParamModelAttributes($stubFields,$arrFields,$mapeador);
		$this->assertEquals(0,count($attrParams->getData()));
	}

	public function testHttpGetParamFilter_ModelAttributesShortQuery(){
		
		$stubFields = 'title=xxxxxx';
		$arrFields = ['title','begin','sinop','channel','duration'];
		$map = array('title'=>'nombre','begin'=>'fecha_hora','sinop'=>'descripcion','channel'=>'idcanal','duration'=>'duracion');
		$mapeador = new ApiModelAttrMapper($map);
		$attrParams = new HttpGetParamModelAttributes($stubFields,$arrFields,$mapeador);
		$this->assertEquals(true,isset($attrParams->getData()['title'] ) );
		$this->assertEquals(" (nombre='xxxxxx' ) ",$attrParams->getSqlWhereString());
	}


	public function testHttpGetURIBuilder(){
		
		$stubFields = array('title' => 'xxxxxx','desc'=>'This is te the description','filter' => 'yyyyyyyyy');
		$attrParams = new HttpURIBuilder($stubFields);
		$this->assertEquals('title=xxxxxx&desc=This is te the description&filter=yyyyyyyyy&',$attrParams->getRawURIString());
		//$this->assertEquals(" (title='xxxxxx' ) ",$attrParams->getSqlWhereString());
	}


	public function testApiModelMapper(){
		
		$stubFields = array('title' => 'xxxxxx','desc'=>'This is te the description','filter' => 'yyyyyyyyy');
		$map = array('title'=>'nombre','sinop'=>'descripcion');
		$attrModelMapper = new ApiModelAttrMapper($map);
		$this->assertEquals(null,$attrModelMapper->getMappedValue('valor'));
		$this->assertEquals('nombre',$attrModelMapper->getMappedValue('title'));
	}

}
