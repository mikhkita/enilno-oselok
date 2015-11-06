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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminTableCreate'),
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
				$this->actionAdminIndex(true,$model->parent_id);
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

		// $this->checkAccess($model);

		if(isset($_POST['Desktop']))
		{
			$model->attributes=$_POST['Desktop'];
			if($model->save())
				$this->actionAdminIndex(true,$model->parent_id,true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		// $this->checkAccess( Desktop::model()->findByPk($id) );

		$model = $this->loadModel($id);
		$model->delete();

		$this->actionAdminIndex(true,$model->parent_id,true);
	}

	public function actionAdminIndex($partial = false, $id = 1, $editable = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}

        $model = Desktop::model()->with("childs")->findByPk($id);

        // foreach ($model as $key => $value) {
        // 	if(!$this->checkAccess($value,true)) unset($model[$key]);
        // }

		if( !$partial ){
			$this->render('adminIndex',array(
				'folder'=>$model,
				'editable'=>$editable
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'folder'=>$model,
				'editable'=>$editable
			));
		}
	}

	public function actionAdminTableCreate()
	{
		$model=new DesktopTable;

		if(isset($_POST['DesktopTable']))
		{
			$model->attributes=$_POST['DesktopTable'];
			if($model->save()){
				$this->actionAdminIndex(true,$model->folder_id);
				return true;
			}
		}

		$this->renderPartial('adminTableCreate',array(
			'model'=>$model,
		));

	}

	public function loadModel($id)
	{
		$model=Desktop::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
