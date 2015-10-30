<?php

class CompareController extends Controller
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
				'actions'=>array('adminIndex','adminPut'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex()
	{
		$this->render('adminIndex');
	}

	public function actionAdminPut($left=null,$right=null)
	{
		if( $left === null ){
			file_put_contents(Yii::app()->basePath."/data/right.txt", $right);
		}else{
			file_put_contents(Yii::app()->basePath."/data/left.txt", $left);
		}
		
	}
}
