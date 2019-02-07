<?php

namespace Siba\txtvalidator\models\ratings;

use \Misc\Interfaces\IBaseRepo;
use \Misc\curl\Curl;
use \Illuminate\Database\Eloquent\Collection;
use \Siba\txtvalidator\models\ratings\Rating;

class RatingRepo implements IBaseRepo{	

	//protected $apiUrl = 'https://apistd.siba.com.co/api/events';//Production
	protected $apiUrl = '';//Dev


	public function __construct (){
		$this->apiUrl = \Config::get('sibastdtxtvalidador.WS_RATINGS');
	}
	
	public function create($data){

		$rating = new Rating ($data);
		return $rating;
	}

	public function get($id){

		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'/'.$id));
		if (isset($data["code"]) && $data["code"] == '404'){
			return null;
		}
		$rating = $this->create($data);
		return $rating;
	}

	public function find($data){
		$collection = Collection::make(array());
		$reqQuery = '';
		if (count($data)>0){
			foreach ($data as $key=>$val){
				$reqQuery .= $key."=".$val."&";
			}
			$reqQuery = preg_replace("/&$/","",$reqQuery);
		}
		//clock()->startEvent('get-eventos-ws', "Llamando microservicio eventos");
		//clock()->info("Eventos URL: ".$this->apiUrl.'?'.$reqQuery);
		//echo $this->apiUrl.'?'.$reqQuery."\n";
		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'?'.$reqQuery));
		//print_r($data);
		//clock()->endEvent('get-eventos-ws');
		if (isset($data['ratings']) && count($data['ratings']) > 0){
			for ($i=0;$i<count($data['ratings']);$i++){
				$evtRaw = (array) $data['ratings'][$i];
				$rating = $this->create($evtRaw);
				//print_r($rating);
				$collection->add($rating);
			}
		}
		return $collection;
	}

	public function save($rating){
		return null;
	}

}
