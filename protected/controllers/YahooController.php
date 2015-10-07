<?php

class YahooController extends Controller
{
	private $sort_fields = array(
		"sort" => "По дате",
		"cur_price" => "По текущей цене",
		"bids" => "По количеству ставок",
		"end_time" => "По времени окончания",
	);

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
				'actions'=>array('adminIndex','adminAuctionCreate'),
				'roles'=>array('root'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex($partial = false)
	{	
		session_start();
		if( !($_POST["sort"]) ){
			if( isset($_SESSION["POST"]) ) $_POST = $_SESSION["POST"];
		}else{
			$_SESSION["POST"] = $_POST;
		}
		unset($_GET["partial"]);

		if( !isset($_POST["sort"]) ) $_POST["sort"] = "sort";
		if( !isset($_POST["order"]) ) $_POST["order"] = "DESC";

		if( isset($_POST['delete']) ){
			Yii::app()->db->createCommand()->update(YahooLot::tableName(), array(
			    'state'=>1,
			), array('in', 'id', json_decode($_POST['delete'])));
		}

		$filter = YahooLot::filter();
		$labels = YahooLot::attributeLabels();
		$arr_name = "YL";
		$filter_values = isset( $_POST[$arr_name] )?$_POST[$arr_name]:array();

		YahooLot::model()->deleteAll("end_time<'".date("Y-m-d H:i:s", time()-60*60*5)."'");
		$criteria=new CDbCriteria();

	   	if( count($filter_values) ){
	   		$criteria = $this->filterYL($criteria,$filter,$filter_values);
	   	}

	   	$criteria->addCondition("state='0'");
	   	$criteria->addCondition("end_time>'".date("Y-m-d H:i:s", time())."'");
	   	$criteria->order = $_POST["sort"].' '.$_POST["order"];

	   	$pagination = array('pageSize'=>40,'route' => 'yahoo/adminindex');

	   	$lot_count = YahooLot::model()->count($criteria);

		$dataProvider = new CActiveDataProvider('YahooLot', array(
		    'criteria'=>$criteria,
		    'pagination'=>$pagination
		));
		$data = $dataProvider->getData();
		foreach ($data as &$item) {
			$item->title = preg_replace("/[^A-z0-9]/", " ", $item->title);
			$item->end_time = $this->getTextTime((strtotime($item->end_time)-time()));
		}

		foreach ($filter as &$item) {
			if( isset($item["FROM"]) && !is_array($item["FROM"]) ){
				$item["FROM"] = CHtml::listData(Yii::app()->db->createCommand()->select(implode(",", $item["FIELDS"]))->from($item["FROM"])->queryAll(), $item["FIELDS"][0], $item["FIELDS"][1]);
			}
		}

		$options = array(
			'model'=>$dataProvider->getData(),
			'pages' => $dataProvider->getPagination(),
			'lot_count' => $lot_count,
			'sort_fields' => $this->sort_fields,
			'labels' => $labels,
			'arr_name' => $arr_name,
			'filter'=>$filter,
			'filter_values'=>$filter_values,
			'filter_list'=>$this->getFilterList($filter_values,$filter,$labels),
		);
		if($partial) {
			$this->renderPartial('adminIndex',$options);		
		} else {
			$this->render('adminIndex',$options);
		}
	}

	public function filterYL($criteria,$filter,$fields){

		foreach ($fields as $key => $value) {
			if( $filter[$key]["TYPE"] == "CHECKBOX" ){
				$criteria->addInCondition($key, $value, 'OR');
			}else if( $filter[$key]["TYPE"] == "FROMTO" ){
				if( $value["FROM"] != "" ) $criteria->addCondition($key.">='".$value["FROM"]."'");
				if( $value["TO"] != "" ) $criteria->addCondition($key."<='".$value["TO"]."'");
			}else if( $filter[$key]["TYPE"] == "CUSTOM_FROMTO" ){
				if( $value["FROM"] != "" ) $criteria->addCondition($key.">='".date("Y-m-d H:i:s", time()+60*60*$value["FROM"])."'");
				if( $value["TO"] != "" ) $criteria->addCondition($key."<='".date("Y-m-d H:i:s", time()+60*60*$value["TO"])."'");
			}
		}

		return $criteria;
	}

	public function getFilterList($filter_values,$filter,$labels){
		$result = array();

		foreach ($filter_values as $key => $value) {
			if( $filter[$key]["VIEW"] == "CHECKBOX" ){
				foreach ($value as $item) {
					array_push($result, $filter[$key]["FROM"][$item]);
				}
			}else if( $filter[$key]["VIEW"] == "FROMTO" ){
				if( $value["FROM"] != "" && $value["TO"] != "" ){
					if( $value["FROM"] == $value["TO"] ){
						if( $value["FROM"] != "" ) array_push($result, $labels[$key]." = ".$value["FROM"]);
					}else{
						if( $value["FROM"] != "" ) array_push($result, $labels[$key]." от ".$value["FROM"]." до ".$value["TO"]);
					}
				}else{
					if( $value["FROM"] != "" ) array_push($result, $labels[$key]." от ".$value["FROM"]);
					if( $value["TO"] != "" ) array_push($result, $labels[$key]." до ".$value["TO"]);
				}
			}
		}

		return $result;
	}

	public function actionAdminAuctionCreate($id = NULL){
		if( $id !== NULL ){
			if(isset($_POST['Auction']))
			{
				$model = new Auction();

				$model->attributes=$_POST['Auction']+Injapan::getFields($_POST['Auction']['code'],$_POST['Auction']['price'])["main"];
				if($model->save()){
					$lot = YahooLot::model()->findByPk($id);
					$lot->state = 2;
					$lot->save();

					$this->actionAdminIndex(true);
					return true;
				}
			}else{
				$model = new Auction();
				$model->code = $id;
			}

			$this->renderPartial('adminAuctionCreate',array(
				'model'=>$model,
			));
		}
	}

	public function actionCount()
	{
		$goods=Good::model()->findAllbyPk($goods_id,$criteria);
	}

	public function loadModel($id)
	{
		$model=Good::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
