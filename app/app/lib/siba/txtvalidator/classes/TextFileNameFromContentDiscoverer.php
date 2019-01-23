<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Siba\txtvalidator\classes;
/**
 * This class responsability is to deduce a hashed value from the content file.
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class TextFileNameFromContentDiscoverer {
    //put your code here

    /**
    * This method returns a hashed value from the content of the file,
    * the used hash function (algorithm) is passed as parameter
    *
    */

    public function getHashedFileName($fileContent,$hashAlgorithm = 'MD5') {

        $hashedFileName = '';
        switch($hashAlgorithm){
            case "MD5": 
                $hashedFileName = md5($fileContent);
                break;
            default:
                $hashedFileName = md5($fileContent);
                break;
        }
        return $hashedFileName;
    }


}
