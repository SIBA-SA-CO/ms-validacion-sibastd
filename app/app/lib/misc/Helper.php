<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Misc;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
*/

class Helper{
	
	public static function normalizeText ($txt){
    
	    $txt = preg_replace("/á/","a", $txt);
	    $txt = preg_replace("/é/","e", $txt);
	    $txt = preg_replace("/í/","i", $txt);
	    $txt = preg_replace("/ó/","o", $txt);
	    $txt = preg_replace("/ú/","u", $txt);
	    $txt = preg_replace("/Á/","A", $txt);
	    $txt = preg_replace("/É/","E", $txt);
	    $txt = preg_replace("/Í/","I", $txt);
	    $txt = preg_replace("/Ó/","O", $txt);
	    $txt = preg_replace("/Ú/","U", $txt);
	    $txt = preg_replace("/ñ/","n", $txt);
	    $txt = preg_replace("/Ñ/","N", $txt);
	    $txt = preg_replace("/\ +/"," ", $txt);
	    $txt = strtolower($txt);
	    $txt = trim ($txt);
	    return ($txt);
    
    
	}

} 