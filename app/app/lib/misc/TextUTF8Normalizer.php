<?php

namespace Misc;

/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */

class TextUTF8Normalizer{
	static function normalizeText($str,$toUpper=false){
	
		$str = TextUTF8Normalizer::acentos($str);
		$arrStr = str_split($str);
		for ($i=0;$i < count($arrStr);$i++){
			switch(ord($arrStr[$i])){
				case 209: $arrStr[$i] = 'Ñ';
							break;
				case 241:	if ($toUpper)
								$arrStr[$i] = 'Ñ';
							else
								$arrStr[$i] = 'ñ';
							break;
				case 233: 
							if ($toUpper)
								$arrStr[$i] = 'É';
							else
								$arrStr[$i] = 'é';
							break;
				case 225: 
							if ($toUpper)
								$arrStr[$i] = 'Á';
							else
								$arrStr[$i] = 'á';
							break;
				case 218: $arrStr[$i] = 'Ú';
							break;
				case 211: $arrStr[$i] = 'Ó';
							break;
				case 201: $arrStr[$i] = 'É';
							break;
				case 205: $arrStr[$i] = 'Í';
							break;
				case 237: 
							if ($toUpper)
								$arrStr[$i] = 'Í';
							else
								$arrStr[$i] = 'í';
							break;
				case 243: $arrStr[$i] = 'ó';
							if ($toUpper)
								$arrStr[$i] = 'Ó';
							else
								$arrStr[$i] = 'ó';
							break;
				case 193: $arrStr[$i] = 'Á';
							break;

				case 250: 
							if ($toUpper)
								$arrStr[$i] = 'Ú';
							else
								$arrStr[$i] = 'ú';
							break;
			}
		}
		$str = implode("",$arrStr);
		return $str;
	}


	static public function acentos($cadena){
		$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü,Â´,Ã");
		$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;,',Á");
		$cadena= str_replace($search, $replace, $cadena);

		return $cadena;
	}
}