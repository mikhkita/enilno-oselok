<?php

class LeadController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Lead;
		$model->date = date("d.m.Y", time());
		if(isset($_POST['Lead']))
		{
			$_POST['Lead']['date'] = date_format(date_create_from_format('m.d.Y',$_POST['Lead']['date']), 'Y-m-d H:i:s');
			$model->attributes=$_POST['Lead'];
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
		$date = date_create($model->date);
		$model->date = date_format($date, 'm.d.Y');
		if(isset($_POST['Lead']))
		{
			$_POST['Lead']['date'] = date_format(date_create_from_format('m.d.Y',$_POST['Lead']['date']), 'Y-m-d H:i:s');
			$model->attributes=$_POST['Lead'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}

		$this->renderPartial('adminUpdate',array(
			'model'=>$model,
		));
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

		$filter = new Lead('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Lead']))
        {
            $filter->attributes = $_GET['Lead'];
            foreach ($_GET['Lead'] AS $key => $val)
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

        $model = Lead::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Lead::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Lead::attributeLabels()
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
		$model=Lead::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
