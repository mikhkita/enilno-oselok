<?php

class DesktopController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Desktop;

		if(isset($_POST['Desktop']))
		{
			$model->attributes=$_POST['Desktop'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}

		$this->renderPartial('adminCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		$this->checkAccess($model);

		if(isset($_POST['Desktop']))
		{
			$model->attributes=$_POST['Desktop'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->checkAccess( Desktop::model()->findByPk($id) );

		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Desktop('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Desktop']))
        {
            $filter->attributes = $_GET['Desktop'];
            foreach ($_GET['Desktop'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 'sort ASC, name ASC';

        $model = Desktop::model()->findAll($criteria);

        foreach ($model as $key => $value) {
        	if(!$this->checkAccess($value,true)) unset($model[$key]);
        }

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Desktop::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Desktop::attributeLabels()
			));
		}
	}

	public function loadModel($id)
	{
		$model=Desktop::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
