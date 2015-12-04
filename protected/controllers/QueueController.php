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
				'actions'=>array('adminIndex','adminToWaiting','adminDelete','adminStart','adminStop','adminReturnAll','adminFreezeFree','adminUnfreezeAll'),
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
	}

	public function actionAdminStop($category_id = 2047){
		$this->setParam( Place::model()->categories[$category_id], "TOGGLE", "off" );
	}

	public function actionAdminReturnAll($category_id = 2047){
		$ids = $this->getIdsByCondition("state_id = 3 AND place.category_id=$category_id");
		if( count($ids) )
			Queue::model()->updateAll(['state_id' => 1], "id IN (".implode(",", $ids).")");
	}

	public function getIdsByCondition($condition){
		$queue = Queue::model()->with("advert.place")->findAll(array("limit"=>9999,"condition"=>$condition));
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

	public function actionAdminToWaiting($id = NULL){
		if( $id ){
			$item = Queue::model()->findByPk($id);
			$item->state_id = 1;
			$item->save();
		}
	}

	public function actionAdminIndex($partial = false, $category_id = 2047)
	{
		// $adverts = Advert::model()->findAll("place_id=10 AND (type_id=2129 OR type_id=868) AND city_id=1059");
		// foreach ($adverts as $key => $advert)
		// 	echo "http://baza.drom.ru/".$advert->url.".html<br>";
		// die;
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Queue('filter');
		$criteria = new CDbCriteria();

        $criteria->order = "t.id ASC";
        $criteria->condition = "place.category_id=$category_id";
        $criteria->addCondition("state_id!=4");
        $criteria->limit = 100;

        $model = Queue::model()->with("advert.good.fields.variant","advert.good.fields.attribute","advert.place.category","advert.city","advert.type","state")->findAll($criteria);

        $options = array(
			'data'=>$model,
			'filter'=>$filter,
			'labels'=>Queue::attributeLabels(),
			'category'=>Variant::model()->findByPk($category_id),
			'count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id"),
			'waiting_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=1"),
			'error_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=3"),
			'freeze_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND state_id=4"),
			'start_count'=>Queue::model()->with("advert.place")->count("place.category_id=$category_id AND start IS NOT NULL")
		);

		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
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
