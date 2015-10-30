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
				'actions'=>array('adminIndex','adminEdit','adminCreate','adminUpdate','adminDelete'),
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

	public function actionAdminEdit()
	{
		ini_set("memory_limit", "420M");
		// $criteria = new CDbCriteria();
		// $criteria->with = array("attribute");
		// $criteria->condition = "attribute.list=1 AND variant_id is NULL";
		// $criteria->condition = "attribute_id=6";
    	// $criteria->group = "variant_id";
    	// $criteria->limit = 10000;
    	// $model = GoodAttribute::model()->deleteAll($criteria);
		$model = AttributeVariant::model()->with("variant")->findAll();

		echo count($model);
		$count = 0;
		foreach ($model as $value) {
			// if($value->varchar_value)$count++;
			
			$value->variant->sort = $value->sort;
			$value->variant->save();
			echo $value->variant->sort."<br>";
		}
		echo $count;
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
