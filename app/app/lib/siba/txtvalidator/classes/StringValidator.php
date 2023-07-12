<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Siba\txtvalidator\classes;
/**
 * Description of newPHPClass
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class StringValidator
{
    public function validatePipeSequence($inputString) {
        $consecutivePipesCount = preg_match_all('/\|{3,}/', $inputString, $matches);
    
        if ($consecutivePipesCount > 0) {
            $errorDesc = 'Error en el campo del evento: Se encontró una secuencia de '.$matches[0][0].' consecutivos.';
            return [
                'success' => false,
                'error' => $errorDesc
            ];
        }
    
        return ['success' => true];
    }
}