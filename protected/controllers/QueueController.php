<?php

class QueueController extends Controller
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
				'actions'=>array('adminIndex','adminToWaiting','adminDelete', 'adminDeleteAll','adminStart','adminStop','adminChangeState','adminFreezeFree','adminUnfreezeAll','adminReturnAll','refreshTime'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminDelete($id = NULL){
		if( $id )
			Queue::model()->deleteByPk($id);
	}

	public function actionAdminStart($category_id = 2047){
		$this->setParam( Place::model()->categories[$category_id], "TOGGLE", "on" );
		$this->setParam( Place::model()->categories[$category_id], "TIME", "0" );

		Queue::refreshTime($category_id);
	}

	public function actionAdminStop($category_id = 2047){
		$this->setParam( Place::model()->categories[$category_id], "TOGGLE", "off" );
	}

	public function changeCurTime( $category_id ){
		$queue = Queue::model()->with("advert.place")->nextStart()->findAll(array("group"=>"advert.city_id","together"=>true,"limit"=>10000,"order"=>"advert.city_id ASC, start ASC","condition"=>"place.category_id=$category_id"));

		// foreach ($queue as $key => $item) {
		// 	echo $item->id." ".$item->start." ".$item->advert->city_id."<br>";
		// };
		// die();

		if( count($queue) ){
			foreach ($queue as $key => $item) {
				$razn = time() - strtotime($item->start);
				
				if( $razn > 0 ){
					$ids = $this->getIdsByCondition("state_id = 1 AND place.category_id=$category_id AND advert.city_id=".$item->advert->city_id);
					if( count($ids) ){
						$tableName = Queue::tableName();
						$sql = "UPDATE `$tableName` SET start = start + INTERVAL $razn SECOND WHERE id IN (".implode(",", $ids).")";
						Yii::app()->db->createCommand($sql)->execute();
					}
				}
			}
		}
	}

	public function getIdsByCondition($condition){
		$queue = Queue::model()->with("advert.place")->findAll(array("limit"=>999999,"condition"=>$condition));
		$out = array();
		foreach ($queue as $key => $item)
			array_push($out, $item->id);
		return $out;
	}

	public function actionAdminFreezeFree($category_id = 2047){
		$queue = Queue::model()->with(array("advert"=>array("select"=>array("id","type_id")),"advert.place"))->findAll("state_id = 1 AND advert.type_id=869 AND place.category_id=$category_id");
		$ids = array();
        foreach ($queue as $key => $item) {
            array_push($ids, "'".$item->id."'");
        }

        if( count($ids) )
			Queue::model()->updateAll(['state_id' => 4], "id IN (".implode(",", $ids).")");
	}

	public function actionAdminUnfreezeAll($category_id = 2048){
		$ids = $this->getIdsByCondition("state_id = 4 AND place.category_id=$category_id");
		if( count($ids) )
			Queue::model()->updateAll(['state_id' => 1], "id IN (".implode(",", $ids).")" );
	}

	public function actionAdminToWaiting($id = NULL, $change = true){
		if( $id ){
			$item = Queue::model()->with("advert.place")->findByPk($id);

			$category_id = $item->advert->place->category_id;

			if( $item->start !== NULL ){
				$city_id = $item->advert->city_id;

				$queue = Queue::model()->with("advert.place")->nextStart()->find(array("order"=>"start ASC","condition"=>"place.category_id=$category_id AND advert.city_id=$city_id"));

				$item->start = ( $queue )?date("Y-m-d H:i:s",(strtotime($queue->start)-rand(33*60,39*60))):date("Y-m-d H:i:s",time());
			}

			$item->state_id = 1;
			$item->save();

			if( $change )
				$this->changeCurTime($category_id);
		}
	}

	public function actionAdminReturnAll($category_id){
		$filter = (isset($_SESSION["queue_filter_".$category_id]))?$_SESSION["queue_filter_".$category_id]:array("category_id"=>$category_id);
		$queue = Queue::filter($_GET, true);
		if( count($queue) ){
			Queue::model()->updateAll(['state_id' => 1], "id IN (".implode(",", $queue).")" );
			Queue::refreshTime($category_id, $queue);
		}
		echo json_encode(array("result" => "success", "action" => "updateQueue"));
	}

	public function actionAdminDeleteAll($category_id){
		$filter = (isset($_SESSION["queue_filter_".$category_id]))?$_SESSION["queue_filter_".$category_id]:array("category_id"=>$category_id);
		$queue = Queue::filter($_GET, true);
		if( count($queue) ){
			Queue::model()->deleteAll("id IN (".implode(",", $queue).")");
			Queue::refreshTime($category_id, $queue);
		}
		echo json_encode(array("result" => "success", "action" => "updateQueue"));
	}

	public function actionAdminChangeState($category_id, $state){
		$filter = (isset($_SESSION["queue_filter_".$category_id]))?$_SESSION["queue_filter_".$category_id]:array("category_id"=>$category_id);
		$queue = Queue::filter($_GET, true);
		if( count($queue) )
			Queue::model()->updateAll(['state_id' => $state], "id IN (".implode(",", $queue).")" );

		echo json_encode(array("result" => "success", "action" => "updateQueue"));
	}

	public function actionAdminIndex($partial = false, $category_id = 2047)
	{
		if( !$partial ){
			$this->layout='admin';
		}

		$this->pageTitle = "Очередь: ".( ($category_id==2047)?"Дром":"Авито" );

		$model_filter = Place::model()->with('category','goodType')->findAll("category_id=$category_id");
		$data = array();
		foreach ($model_filter as $key => $item) {
			$data['Place'][$item->id] = $item->category->value." ".$item->goodType->name;
		}
		$model_filter = Attribute::model()->with(array('variants.variant'=>array("order"=>"variant.sort ASC")))->findAllByPk(array(37,58,59,60,61));
		$keys = array(37=>37,58=>58,59=>58,60=>58,61=>58);
		foreach ($model_filter as $key => $item) {
			$data['AttrName'][$item->id] = $item->name;
			foreach ($item->variants as $variant) {
				$data['Attr'][$keys[$variant->attribute_id]][$variant->variant_id] = $variant->value;		
			}
		}
		$queue_action = Action::model()->findAll();
		foreach ($queue_action as $item) {
			$data['Attr']['action'][$item->id] = $item->name;
		}
		$queue_state = QueueState::model()->findAll();
		foreach ($queue_state as $item) {
			$data['Attr']['state'][$item->id] = $item->name;
		}
		
		if(isset($_GET["Codes"]) || isset($_GET["Attr"]) || isset($_GET["Place"])){
			$_SESSION["queue_filter_".$category_id] = $_GET;
		}else{
			$_GET = ( isset($_SESSION["queue_filter_".$category_id]) )?$_SESSION["queue_filter_".$category_id]:array("category_id"=>$category_id);
		}

        $filter = new Queue('filter');
		
        $with = array("advert.good.type"=>array("select"=>"good_type.name","alias"=>"good_type"),"advert.good.fields"=>array("condition"=>"fields.attribute_id=3"),"advert.good.fields.variant","advert.good.fields.attribute","advert.place.category","advert.city","advert.type","state","action");
        $dataProvider = Queue::filter($_GET, $with, NULL, 150);

        $options = array(
			'data'=>$dataProvider->getData(),
			'filter'=>$filter,
			'labels'=>Queue::attributeLabels(),
			'category'=>Variant::model()->findByPk($category_id),
			'count_filter' => $dataProvider->totalItemCount,
			'count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id"),
			'waiting_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=1"),
			'error_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=3"),
			'freeze_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=4"),
			'start_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND start IS NOT NULL"),
			'data_filter'=>$data
		);

		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('_table',$options);
		}
	}

	public function loadModel($id)
	{
		$model=Queue::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
