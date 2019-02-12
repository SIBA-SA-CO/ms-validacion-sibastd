<?php

namespace Siba\txtvalidator\models\ratings;

use \Misc\Interfaces\IBaseRepo;
use \Misc\curl\Curl;
use \Illuminate\Database\Eloquent\Collection;
use \Siba\txtvalidator\models\ratings\Rating;

class RatingRepo implements IBaseRepo{	

	//protected $apiUrl = 'https://apistd.siba.com.co/api/events';//Production
	protected $apiUrl = '';//Dev
	protected $db = '';


	public function __construct (){
		$this->apiUrl = \Config::get('sibastdtxtvalidador.WS_RATINGS');
		/* Genera la base de datos solo una vez */
		$data = (array) json_decode(Curl::urlGet($this->apiUrl.'?limit=150'));
		$this->db = $data['ratings'];
	}
	
	public function create($data){

		$rating = new Rating ($data);
		return $rating;
	}

	public function get($id){
		foreach ($this->db as $dbRating){

			if ($dbRating['id'] == $id){
				$ratingRaw = array(
					'id' => $this->db[$i]->id,
					'rating' => $this->db[$i]->rating,
					'country' => $this->db[$i]->country,
					'age' => $this->db[$i]->age,
				);
				$rating = $this->create($ratingRaw);
				return $rating;
			}

		}
		return null;
	}

	public function find($data){
		$collection = Collection::make(array());
		$totalQty = count($this->db);
		for ($i=0; $i< $totalQty;$i++){

			$isRating = false;
			foreach ($data as $key=>$val){
				//$reqQuery .= $key."=".$val."&";
				if (isset($this->db[$i]->$key) && $this->db[$i]->$key==$val){
					$isRating = true;
				}
				else{
					$isRating = false;
					break;
				}
			}

			if ($isRating){
				$ratingRaw = array(
					'id' => $this->db[$i]->id,
					'rating' => $this->db[$i]->rating,
					'country' => $this->db[$i]->country,
					'age' => $this->db[$i]->age,
				);
				$rating = $this->create($ratingRaw);
				$collection->add($rating);
			}
		}

		return $collection;
	}

	public function save($rating){
		return null;
	}


	private function serachFor($key,$val){

	}

}
