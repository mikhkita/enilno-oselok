<?php

class KemerovoController extends Controller
{
	public function filters()
	{
		return array(
				'accessControl'
			);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex(){
		
	}

	public function actionParsePhoto($id){
		$attributes = array(
			1001 => array($id)
		);

		$data = Good::model()->filter(
			array(
				"good_type_id"=>101,
				"attributes"=>$attributes,
			)
		)->getPage(
			array(
		    	'pageSize'=>10000,
		    )
		);

		$goods = $data["items"];

		$ind = 0;
		foreach ($goods as $i => $good) {
			if( !$good->fields_assoc[1013] ) continue;
			$ind++;

			$images = explode(",", $good->fields_assoc[1013]->value);

			$good_code = $good->fields_assoc[3]->value;
			$dir = Yii::app()->params["imageFolder"]."/parts/".$good_code; 
	        if (!is_dir($dir)){
	        	mkdir($dir, 0777, true);
	        }else{
	        	$this->cleanDir($dir);
	        }

			foreach ($images as $j => $image) {
				$image = @file_get_contents($image);
				if( $image !== false ){
					$new_id = Image::add($good->id, "jpg", 0, $i+1);
					file_put_contents($dir."/".$new_id.".jpg", $image);
				}
			}
		}

		print_r(count($goods)." ".$ind);
	}

	public function actionPhoto(){
		$images = Image::model()->with("caps")->findAll("site=1");
		$ids = Controller::getIds($images, "good_id");

		$goods = Good::model()
			->filter( array("good_type_id"=>101), $ids )
			->getPage( array('pageSize'=>10000) );
		$goods = $goods["items"];
		
		print_r(count($images));

		foreach ($goods as $i => $good) {
			$images = $good->getImages();
			foreach ($images as $i => $image) {
				echo "<img src='".$image["big"]."' height=400>";
			}
		}

		// Image::model()->updateAll(array("site" => 1), "id IN (".implode(",", $ids).")");
		// ImageCap::model()->deleteAll();
		// echo "<br>";
		// print_r(Image::model()->count("site=1"));
	}
}
