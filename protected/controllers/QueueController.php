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
				'actions'=>array('adminIndex','adminToWaiting','adminDelete'),
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

		if (isset($_GET['Queue']))
        {
            $filter->attributes = $_GET['Queue'];
            foreach ($_GET['Queue'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 't.id ASC';
        $criteria->limit = 50;

        $model = Queue::model()->with("advert.good.fields.variant","advert.good.fields.attribute","advert.place.category","advert.city","advert.type","state")->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels(),
				'count'=>Queue::model()->count(),
				'error_count'=>Queue::model()->count("state_id=5")
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels(),
				'count'=>Queue::model()->count(),
				'error_count'=>Queue::model()->count("state_id=5")
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
