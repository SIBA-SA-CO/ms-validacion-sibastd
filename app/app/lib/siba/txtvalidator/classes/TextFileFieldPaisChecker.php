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
class TextFileFieldPaisChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;

    public function checkFieldIntegrity($field) {
        

        $paises = array('Ang','Col','Grb','Ecu','Afg','Esp','Por','Ita','Arg','Per','Ven','Bra','Chi','Par','Uru','Bol','Mex','Pan','Ger','Fra','Aut','Bel','Den','Bul','Cyp','Cro','Esc','Svk','Slo','Fin','Gbr','Geo','Gre','Hol','Hun','Grb','Isr','Lux','Nor','Pol','Cze','Rom','Rus','Swe','Sui','Tur','Ukr','Irq','Can','Cmr','Civ','Nam','Usa','Bih','Jpn','Chn','Mkd','Alb','Gha','Rsa','Mar','Yug','Sch','Cod','Aus','Sen','Mli','Ngr','Hon','Ben','Tun','Isl','Sle','Crc','Tri','Ksa','Iri','Tog','Kor','Zam','Kor','Egi','Gui','Est','Blr','Mri','Mda','Sur','Gab','Gam','Nzl','Cpv','Gbs','Moz','Bur','Uga','Jam','Abw','Bhs','Dom');

        $this->return = new \Misc\Response();

        if ($field==' '){
            return $this->return;
        }




        if (preg_match("/^([A-Za-z]){3,3}$/",$field)){

            $field = strtolower($field);
            $field = ucfirst($field);
            if (in_array($field,$paises))
                return $this->return;   
            else{

                $this->return->status = false;
                $this->return->value = 0;
                $this->return->notes = "El código del campo pais no está definido en el sistema".": ".$field;
                return $this->return;
            }

        }


        $this->return->status = false;
        $this->return->value = 0;
        $this->return->notes = "El tipo de dato registrado en el campo Pais no es valido".": ".$field;
        return $this->return;
    }
}
