<?php

class DromUserController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminPreview','adminList'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new DromUser;

		if(isset($_POST['DromUser']))
		{
			$_POST["DromUser"]["category_id"] = 5;
			$model->attributes=$_POST['DromUser'];
			if($model->save()){
				$this->actionAdminList(true);
				return true;
			}
		}

		if(isset($_GET["DromUser"]) && isset($_GET["DromUser"]["good_type_id"]) ) $model->good_type_id = $_GET["DromUser"]["good_type_id"];

		$this->renderPartial('adminCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		$this->checkAccess($model);

		if(isset($_POST['DromUser']))
		{
			$model->attributes=$_POST['DromUser'];
			if($model->save())
				$this->actionAdminList(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false, $sort_type = "ASC", $sort_field="count")
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new DromUser('filter');
		$criteria = new CDbCriteria();

		if( isset($_GET["delete"]) ){
			$this->loadModel($_GET["delete"])->delete();
			unset($_GET["delete"]);
		}

		if (isset($_GET['DromUser']))
        {
            $filter->attributes = $_GET['DromUser'];
            foreach ($_GET['DromUser'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = "$sort_field $sort_type";

        $inter = DromUser::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'inter'=>$inter,
				'sort_type'=>$sort_type,
				'sort_field'=>$sort_field,
				'service'=>$service,
				'filter'=>$filter,
				'labels'=>DromUser::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'inter'=>$inter,
				'sort_type'=>$sort_type,
				'sort_field'=>$sort_field,
				'service'=>$service,
				'filter'=>$filter,
				'labels'=>DromUser::attributeLabels()
			));
		}
	}

	public function loadModel($id)
	{
		$model=DromUser::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
