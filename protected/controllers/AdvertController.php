<?php

class AdvertController extends Controller
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
				'actions'=>array('adminIndex','adminUpDrom','adminUpAvito','adminAction','adminFindById'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminUpDrom(){
		if( !isset($_SESSION["advert_filter"]) ) $_SESSION["advert_filter"] = array();

		$filter = $_SESSION["advert_filter"];

		$model = Attribute::model()->with('variants')->findByPk(37);
		$allowed = array();
		foreach ($model->variants as $variant)
			if( $variant->variant_id != 869 )
				array_push($allowed, $variant->variant_id);

		if(isset($filter["Attr"])){
			if( isset($filter["Attr"][37]) ){
				foreach ($filter["Attr"][37] as $i => $val)
					if( $val == "869" ) 
						unset($filter["Attr"][37][$i]);
			}else
				$filter["Attr"][37] = $allowed;
		}else
			$filter["Attr"] = array(37 => $allowed);

		
		if( isset($_POST["data"]) ){
			$adverts = Advert::filter($filter,array('type','city','place.category'),array("good_id","id"))->getData();
			$interval = isset($_POST["interval"])?intval($_POST["interval"])*60:0;
			$offset = isset($_POST["offset"])?intval($_POST["offset"])*60:0;

			Queue::addAll($adverts,"payUp",$offset,$interval);

			$this->actionAdminIndex(true);
		}else{
			$advert_count = Advert::filter($filter,array('type','city','place.category'),array("good_id","id"))->totalItemCount;

			$this->renderPartial('adminUpDrom',array(
				'advert_count' => $advert_count
			));
		}
	}

	public function actionAdminUpAvito(){
		if( !isset($_SESSION["advert_filter"]) ) $_SESSION["advert_filter"] = array();

		$filter = $_SESSION["advert_filter"];

		$model = Place::model()->findAll("category_id=2048");
		$place_ids = array();
		foreach ($model as $i => $place)
			array_push($place_ids, $place->id);

		if(isset($filter["Place"])){
			foreach ($filter["Place"] as $i => $val)
				if( !in_array($val, $place_ids)) 
					unset($filter["Place"][$i]);
		}else
			$filter["Place"] = array(11);
		
		$adverts = Advert::filter($filter,array('type','city','place.category'),array("good_id","id"))->getData();

		Queue::addAll($adverts, "up", 0, 0);

		Queue::refreshTime(2048, true);

		echo json_encode(array("result" => "success"));
	}

	public function actionAdminAction($action){
		if( !isset($_SESSION["advert_filter"]) ) $_SESSION["advert_filter"] = array();

		$action_model = Action::model()->find("code='$action'");
		if( !$action_model ) return false;
		
		if( isset($_POST["data"]) ){
			$adverts = Advert::filter($_SESSION["advert_filter"],array('type','city','place.category'),array("*"))->getData();

			$random_offset = isset($_POST["random_offset"])?intval($_POST["random_offset"]):24;
			$offset = isset($_POST["offset"])?intval($_POST["offset"]):0;

			if( $action == "delete" ){
				if( count($adverts) )
    				Queue::model()->deleteAll("action_id!=".Queue::model()->codes[$action]." AND state_id != 2 AND advert_id IN (".implode(",", $this->getIds($adverts)).")");
			}

			$adverts = Queue::checkExist($adverts, $action);

			Queue::addAll($adverts, $action, 0, 0, $offset, $random_offset);
			
			$this->actionAdminIndex(true);
		}else{
			$advert_count = Advert::filter($_SESSION["advert_filter"],array('type','city','place.category'),array("good_id","id"))->totalItemCount;

			$this->renderPartial('adminAction',array(
				'advert_count' => $advert_count,
				'action_name' => $action_model->name
			));
		}

	}

	public function actionAdminIndex($partial = false, $good_type_id = false)
	{
		if( $good_type_id !== false ){
			$_GET["Codes"] = implode("\n", Good::getCheckboxes($good_type_id));
			unset($_GET["good_type_id"]);
		}
		
		$model = Place::model()->with('category','goodType')->findAll();
		$data = array();
		foreach ($model as $key => $item) {
			$data['Place'][$item->id] = $item->category->value." ".$item->goodType->name;
		}
		$model = Attribute::model()->with(array('variants.variant'=>array("order"=>"variant.sort ASC")))->findAllByPk(array(37,58,59,60,61));
		$keys = array(37=>37,58=>58,59=>58,60=>58,61=>58);
		foreach ($model as $key => $item) {
			$data['AttrName'][$item->id] = $item->name;
			foreach ($item->variants as $variant) {
				$data['Attr'][$keys[$variant->attribute_id]][$variant->variant_id] = $variant->value;		
			}
		}
		// $data['Attr'][58] = $this->splitByCols(5,$data['Attr'][58]);
		
		if(!$_GET) 
			$_GET = array();
		$_SESSION["advert_filter"] = $_GET;
		$dataProvider = Advert::filter($_GET,array('type','city','place.category','place.goodType'));
		$pages = $dataProvider->getPagination();
		$temp = array();
		foreach ($dataProvider->getData() as $advert) {
			array_push($temp, $advert->good_id);
		}
		$temp = GoodAttribute::getCodeById($temp);
		$advert_count = $dataProvider->totalItemCount;
		foreach ($dataProvider->getData() as $i => $advert) {
			if( !isset($adverts_arr[$advert->place->category->value]) ) $adverts_arr[$advert->place->category->value] = array();
			if( !isset($adverts_arr[$advert->place->category->value][$temp[$advert->good_id]]) ) $adverts_arr[$advert->place->category->value][$temp[$advert->good_id]] = array();
			array_push($adverts_arr[$advert->place->category->value][$temp[$advert->good_id]], $advert);
		}

		if( !$partial ){
			$this->render('adminIndex',array(
				'adverts_arr' => $adverts_arr,
				'data'=>$data,
				"pages" => $pages,
				'advert_count' => $advert_count,
				'labels'=> Advert::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'adverts_arr' => $adverts_arr,
				'data'=>$data,
				"pages" => $pages,
				'advert_count' => $advert_count,
				'labels'=> Advert::attributeLabels()
			));
		}
	}

	public function actionAdminFindById($find_advert_id){
		$codes = array(NULL, "shiny", "diski", "kolesa");
		$advert = Advert::model()->find("url='$find_advert_id'");
		if( $advert ){
			$good = Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk($advert->good_id);
			if( $good ){
				$dictionary = DictionaryVariant::model()->find("attribute_1='".$advert->city_id."' AND dictionary_id=125");
            	$city_code = (!$dictionary)?"tomsk":$dictionary->value;

            	header("Location: http://".$city_code.".".Yii::app()->params["host"]."/".$codes[$good->good_type_id]."/".$good->fields_assoc[3]->value);
			}else{
				echo "Товар не найден";
			}
		}else{
			echo "Объявление не найдено";
		}
	}

	public function loadModel($id)
	{
		$model=Advert::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
