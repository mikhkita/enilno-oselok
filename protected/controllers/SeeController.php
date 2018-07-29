<?php

class SeeController extends Controller
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
				'actions'=>array('adminIndex', 'adminList'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array('admintitle'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex(){
		$this->render('adminSee',array(
			'model' => GoodType::model()->findAll()
		));
	}

	public function actionAdminList($good_type_id, $problem_only = false){ 
		$caps = array(
			"Дром Платные" => 2,
			"Дром Бесплатные" => 4,
			"Авито Томск" => 3
		);
		$place = array(
			"Дром Платные" => array( "code" => 2047, "type" => array(868, 2129), "double" => 0, "grey" => 0, "count" => 0 ),
			"Дром Бесплатные" => array( "code" => 2047, "type" => array(869), "double" => 0, "grey" => 0, "count" => 0 ),
			"Авито Томск" => array( "code" => 2048, "double" => 0, "grey" => 0, "count" => 0 ),
			"ВК" => array( "code" => 3875, "double" => 0, "grey" => 0, "count" => 0 )
		);

		$goods = Good::model()
			->filter( array("good_type_id"=>$good_type_id, "attributes"=>array( 
				// 43 => array(1860, 1857, 1419, 1418) 
				27 => array(1056)
			)) )
			->sort(array(
				"field"=>3,
				"type"=>"ASC",
			))->getPage(
				array(
			    	'pageSize'=>10000,
			    )
			);
		$goods = Controller::getAssoc($goods["items"], "id");

		$dataProvider = Advert::filter(array("ids" => Controller::getIds($goods), "Attr" => array(58 => array(3885, 1081)) ), array('type','city','place.category','place.goodType'), "*");

		$data = $dataProvider->getData();

		// $tasks_extra_images = Controller::getIds(Task::model()->findAll("action_id=5"), "good_id");
		// $tasks_main_images = Controller::getIds(Task::model()->findAll("action_id=1"), "good_id");
		$tasks_params = Controller::getIds(Task::model()->findAll("action_id IN (2,3,4) AND data != '\"110\"'"), "good_id");

		// $temp = GoodAttribute::getCodeById( Controller::getIds($data, "good_id") );

		foreach ($goods as $key => $value) {
			$goods[ $key ] = array(
				"adverts" => $place,
				"good" => $goods[ $key ]
			);
		}

		$double = 0;
		$double_arr = array();
		foreach ($data as $key => $value){
			foreach ($place as $i => $item) {
				if( $item["code"] == $value->place->category->id && (!isset($item["type"]) || in_array($value->type_id, $item["type"])) ){
					if( isset($goods[$value->good_id]["adverts"][$i]["url"]) ){

						if( !in_array($goods[$value->good_id]["good"]->fields_assoc[3]->value, $double_arr) )
							array_push($double_arr, $goods[$value->good_id]["good"]->fields_assoc[3]->value);

						$place[$i]["double"] = $place[$i]["double"]+1;
						$goods[$value->good_id]["adverts"][$i]["double"] = true;
					}else{
						if( !$value->url ){
							if( !$value->ready && $value->title != NULL && $item["code"] == 2048  ){
								$goods[$value->good_id]["adverts"][$i]["title"] = true;
							}

							if( ($value->title == NULL || $value->title == "") && $item["code"] == 2048  ){
								$goods[$value->good_id]["adverts"][$i]["title_empty"] = true;
							}

							if( isset($caps[$i]) && !count(Image::model()->with("caps")->find("caps.cap_id=".$caps[$i]." AND t.good_id=".$value->good_id)) )
								$goods[$value->good_id]["adverts"][$i]["image"] = true;	
							// if( in_array($value->good_id, $tasks_extra_images) && ( in_array($i, array("Дром Бесплатные", "Авито Томск")) ) ){
							// 	$goods[$value->good_id]["adverts"][$i]["image"] = true;	
							// }
							// if( in_array($value->good_id, $tasks_main_images) && $i == "Дром Платные" ){
								// $goods[$value->good_id]["adverts"][$i]["image"] = true;	
							// }
						}else{
							$goods[$value->good_id]["adverts"][$i]["url"] = $value->url;
						}
						$goods[$value->good_id]["adverts"][$i]["id"] = $value->id;
						$goods[$value->good_id]["adverts"][$i]["not_active"] = ( ($item["code"] == 2047 && $value->url && $value->active == 0)?true:false ) ;
						$place[$i]["count"] = $place[$i]["count"]+1;
					}
				}
			}
		}

		foreach ($goods as $key => &$good){
			$problem = false;
			foreach ($good["adverts"] as $city => $item){
				if( $city == "Дром Платные" && $good["good"]->fields_assoc[27]->value != "Томск" )
					$item["city"] = true;

				if( in_array($good["good"]->id, $tasks_params) )
					$item["params"] = true;	

				if( $city == "Авито Томск" && intval($good["good"]->fields_assoc[111]->value) == 1 || $item["code"] == 3875 )
					$good["adverts"][$city]["grey"] = true;

				if( is_object($good["good"]->fields_assoc[117]) && $good["good"]->fields_assoc[117]->variant_id == 4312 )
					$good["adverts"][$city]["grey"] = true;

				switch ($good_type_id) {
					case 1:
						if( (( in_array($city, array("Дром Бесплатные", "Авито Томск")) ) && $good["good"]->fields_assoc[27]->value != "Томск") ){
							$good["adverts"][$city]["grey"] = true;
							if( !isset($good["adverts"][$city]["url"]) ) $place[$city]["grey"] = $place[$city]["grey"]+1;
						}	
					break;
					case 2:
						if( ( in_array($city, array("Дром Бесплатные", "Авито Томск")) ) && $good["good"]->fields_assoc[27]->value != "Томск" ){
							$good["adverts"][$city]["grey"] = true;
							if( !isset($good["adverts"][$city]["url"]) ) $place[$city]["grey"] = $place[$city]["grey"]+1;
						}				
					break;
				}	

				if( ($item["title"] || $item["image"] || $item["city"] || $item["params"] || $item["title_empty"]) && !$item["url"] && !$good["adverts"][$city]["grey"] ){
					$error = array();
					if( $item["title"] ) array_push($error, "<a href='".$this->createUrl('/advert/admintitleedit', array('advert_id'=> $item["id"]))."' class='ajax-form ajax-update'>Не уникальный заголовок</a>");
					if( $item["title_empty"] ) array_push($error, "<a href='".$this->createUrl('/advert/admintitleedit', array('advert_id'=> $item["id"]))."' class='ajax-form ajax-update'>Отсутствует заголовок</a>");
					if( $item["image"] ) array_push($error, "<a href=".Yii::app()->createUrl('/good/adminphoto',array('id'=>$good['good']->id))." target='_blank'>Нет фото</a>");
					if( $item["city"] ) array_push($error, "Не в Томске");
					if( $item["params"] ) array_push($error, "Не заполнены параметры");
					$good["adverts"][$city]["error"] = $error;

					// if( $good["good"]->fields_assoc[3]->value == "1320" ){
					// 		var_dump($item["title"] || $item["image"] || $item["city"] || $item["params"] || $item["title_empty"]);
					// 		echo "<br><br>";
					// 		var_dump($good["adverts"][$city]["grey"]);
					// 		echo "<br><br>";
					// 		var_dump($item);
					// 		die();
					// 	}

					$problem = true;
				}
				if( (!$item["url"] || $item["not_active"]) && !$good["adverts"][$city]["grey"] ) {
					$problem = true;
				}					
			}

			if( $problem_only && !$problem ) unset($goods[$key]);
		}

		$this->render('adminSeeList',array(
			'place' => $place,
			'goods' => $goods,
			'double_arr' => $double_arr
		));
	}

}
