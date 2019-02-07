<?php

namespace Siba\txtvalidator\models\ratings;
use \Illuminate\Database\Eloquent\Model;
/**
*
* La clase Rating es un contenedor de datos que en json tienen esta forma:
*
* {
*   "id": 197417632,
*   "rating": "TV-MA",
*   "country": "USA",
*   "age": "12"
* }
* 
*/

class Rating extends Model
{
    //
    protected $fillable = ['id','country','rating','age'];
}
