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
				'actions'=>array('adminIndex'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex($partial = false)
	{
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

        $model = Queue::model()->with("advert.good.fields.variant","advert.good.fields.attribute","advert.place.category","advert.city","advert.type","state")->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Queue::attributeLabels()
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
