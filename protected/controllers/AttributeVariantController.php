<?php

class AttributeVariantController extends Controller
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
			array('allow',
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['AttributeVariant']))
		{
			$model->attributes=$_POST['AttributeVariant'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminEdit($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Variants']))
		{
			// $model->attributes=$_POST['Variants'];
			// if($model->save())
			// 	$this->actionAdminIndex(true);
			print_r($_POST["Variants"]);
		}else{
			$this->renderPartial('adminEdit',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new AttributeVariant('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['AttributeVariant']))
        {
            $filter->attributes = $_GET['AttributeVariant'];
            foreach ($_GET['AttributeVariant'] AS $key => $val)
            {
                if ($val != '')
                {
                    if( $key == "name" ){
                    	$criteria->addSearchCondition('name', $val);
                    }else{
                    	$criteria->addCondition("$key = '{$val}'");
                    }
                }
            }
        }

        $criteria->order = 'id DESC';

        $model = AttributeVariant::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>AttributeVariant::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>AttributeVariant::attributeLabels()
			));
		}
	}

	public function actionIndex($id){
		$model = Rubric::model()->findByPk($id);

		$this->render('index',array(
			'data'=>$model->houses,
			'title'=>$model->rub_name
		));
	}

	public function loadModel($id)
	{
		$model=AttributeVariant::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
