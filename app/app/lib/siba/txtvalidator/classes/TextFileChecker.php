<?php

namespace Siba\txtvalidator\classes;
/**
 * This class is responsible to run everything needed to make a full txt file checking
 *
 * @author @maomuriel
 * mauricio.muriel@calitek.net
 */
class TextFileChecker {
    //put your code here


    /**
    * This method checks the full data integrity of a SIBA's STD content txt file
    * 
    * Notes about error response codes:
    *
    * 10: File structure error (group: 10 - 19)
    * 20: Data error (group: 20 - 29)
    * 30: File structure and Data error (group: 30 - 39)
    * 
    *
    * @param (string) $filePath, Path to txt file in the local file system
    * @return (\Misc\Response) $return, a \Misc\Response type object
    *     
    */
    public function check($filePath,\Siba\txtvalidator\classes\TextFileDataChecker $dataChecker,\Siba\txtvalidator\classes\TextFileStructureChecker $structureChecker) {
        
        //======================================================================
        $ret = new \Misc\Response();
        $ret->notes = array();

        //1. It validates file structure first.
        $resStructureCheck = $structureChecker->checkStructureIntegrity($filePath);
        if ($resStructureCheck->status == false){
            $ret->status = false;
            $ret->value = 10;
            array_push($ret->notes,$resStructureCheck->notes);
        }
        //2. It validates data consistency
        $resDataCheck = $dataChecker->checkDataIntegrity($filePath);
        if ($resDataCheck->status == false){
            $ret->status = false;
            if ($ret->value == 10)
                $ret->value = 30;
            else
                $ret->value = 20;
            $ret->notes = array_merge($ret->notes,$resDataCheck->notes);
        }
        return $ret;
    }

    
}
