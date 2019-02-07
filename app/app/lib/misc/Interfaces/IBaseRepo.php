<?php

namespace Misc\Interfaces;

/**
* Esta interfaz define la base para impletar las clases que gestionan 
* el acceso a datos (sin importar el origen de esto), retornando siempre
* Modelos tipo laravel.
* 
* Esta interfaz se define siguiendo el patrón "Repositorio" para acceso
* agnostico a datos (https://deviq.com/repository-pattern/)
*/

Interface IBaseRepo{
	
	/* 
		Define las mismas funciones de recuperacion de Eloquent
	*/
	public function create($entity);	
	public function get($id);
	public function find($data);
	public function save($entity);

}