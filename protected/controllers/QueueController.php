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

	public function actionAdminStart(){
		file_put_contents(Yii::app()->basePath."/data/queue.txt", "1");
		file_put_contents(Yii::app()->basePath."/data/queue_time.txt", "0");
	}

	public function actionAdminStop(){
		file_put_contents(Yii::app()->basePath."/data/queue.txt", "2");
	}

	public function actionAdminReturnAll(){
		Queue::model()->updateAll(['state_id' => 1], 'state_id = 3');
	}

	public function actionAdminFreezeFree(){
		$queue = Queue::model()->with(array("advert"=>array("select"=>array("id","type_id"))))->findAll('state_id = 1 AND advert.type_id=869');
		$ids = array();
        foreach ($queue as $key => $item) {
            array_push($ids, "'".$item->id."'");
        }

        if( count($ids) )
			Queue::model()->updateAll(['state_id' => 4], "id IN ( ".implode(",", $ids)." )");
	}

	public function actionAdminUnfreezeAll(){
		Queue::model()->updateAll(['state_id' => 1], 'state_id = 4');
	}

	public function actionAdminToWaiting($id = NULL){
		if( $id ){
			$item = Queue::model()->findByPk($id);
			$item->state_id = 1;
			$item->save();
		}
	}

	public function actionAdminIndex($partial = false)
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

        $criteria->order = 't.id ASC';
        $criteria->condition = 'state_id!=4';
        $criteria->limit = 50;

        $model = Queue::model()->with("advert.good.fields.variant","advert.good.fields.attribute","advert.place.category","advert.city","advert.type","state")->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels(),
				'count'=>Queue::model()->count(),
				'waiting_count'=>Queue::model()->count("state_id=1"),
				'error_count'=>Queue::model()->count("state_id=3"),
				'freeze_count'=>Queue::model()->count("state_id=4"),
				'start_count'=>Queue::model()->count("start IS NOT NULL")
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels(),
				'count'=>Queue::model()->count(),
				'waiting_count'=>Queue::model()->count("state_id=1"),
				'error_count'=>Queue::model()->count("state_id=3"),
				'freeze_count'=>Queue::model()->count("state_id=4"),
				'start_count'=>Queue::model()->count("start IS NOT NULL")
			));
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
