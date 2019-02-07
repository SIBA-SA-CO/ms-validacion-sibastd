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
class TextFileFieldCategoriasChecker implements \Siba\txtvalidator\interfaces\FileDataFieldChecker {
    //put your code here
    private $return;
    private $categorias = array('Accion',
                            'Adultos',
                            'Animacion',
                            'Arqueologia',
                            'Arte',
                            'Aventura',
                            'Biblico',
                            'Biografia',
                            'Boxeo',
                            'Catastrofe',
                            'Ciencia',
                            'Ciencia Ficcion',
                            'Cocina',
                            'Comedia',
                            'Comedia Romantica',
                            'Concursos',
                            'Cortometraje',
                            'Crimen',
                            'Cuerpo Humano',
                            'Cultural',
                            'Deporte',
                            'Dibujos Animados',
                            'Documental',
                            'Drama',
                            'Entretenimiento',
                            'Entrevistas',
                            'epica',
                            'Erotico',
                            'Especial',
                            'Exploracion',
                            'Familiar',
                            'Fantasia',
                            'Fenomenos Naturales',
                            'Finanzas',
                            'Futbol',
                            'Guerra',
                            'Heroismo',
                            'Historia',
                            'Hogar',
                            'Infantil',
                            'Infomerciales',
                            'Investigacion',
                            'Juvenil',
                            'Melodrama',
                            'Misterio',
                            'Musica',
                            'Musical',
                            'Naturaleza',
                            'Noticias',
                            'Oeste',
                            'Otro',
                            'Pelicula',
                            'Periodismo',
                            'Policiaca',
                            'Ranchera',
                            'Reality Show',
                            'Religioso',
                            'Revolucion',
                            'Romance',
                            'Salud',
                            'Serie',
                            'Suspenso',
                            'Tecnologia',
                            'Telenovela',
                            'Tematico',
                            'Terror',
                            'Variedades',
                            'Viajes',
                            'Vida Salvaje',
                            'Videos',
                            'Western');

    public function checkFieldIntegrity($field) {
        
        $this->return = new \Misc\Response();


        if (!preg_match("/\|/",$field)){
            $this->return->status = false;
            $this->return->value = 0;
            $this->return->notes = "El campo categoria está vacio";
            return $this->return;
        }

        $arrCats = preg_split("/\|\|/",$field);
        foreach ($arrCats as $cat){

            if (preg_match("/^SIBA_TIPO/",$cat)){

                $resTipo = $this->checkTipoEvento($cat);
                //Verifica el campo SIBA_TIPO
                if ($resTipo == false){

                    $this->return->status = false;
                    $this->return->value = 0;
                    $this->return->notes = $this->return->notes."No se ha definido un SIBA_TIPO para el programa".": ".$field;

                }        
            }

            if (preg_match("/^SIBA_BASE/",$cat)){

                $resGenero = $this->checkGenero($cat);
                if ($resGenero['res']==false){

                    $this->return->status = false;
                    $this->return->value = 0;
                    $this->return->notes = $this->return->notes.$resGenero['note'].": ".$field;
                }

            }

        }
        
        return $this->return;

    }


    public function checkGenero($field){


        if (!preg_match("/SIBA_BASE\|/",$field)){

            return array("res" => true);

        }


        if (preg_match("/SIBA_BASE\|([^0-9áéíóúñÑÁÉÍÓÚ]{4,80})/",$field,$res)){

            if (in_array($res[1],$this->categorias)){

                return array("res" => true);

            }
            else{

                return array("res" => false, "note" => "El valor de la categoria no es válido (".$res[1].")");

            }

        }
        else{

            return array("res" => false, "note" => "No se ha definido una categoria");

        }

    }

    public function checkTipoEvento($field){

        if (preg_match("/(SIBA_TIPO\|SERIE|SIBA_TIPO\|UNICO)/",$field)){

            return true;

        }
        else{

            return false;

        }

    }
}
