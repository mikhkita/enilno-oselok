<?php

class TaskController extends Controller
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
		$model=new Task;

		if(isset($_POST['Task']))
		{
			$this->setAttr($model);
		}else{
			$attr = array();

			$allAttr = $this->getFields();

			$this->renderPartial('adminCreate',array(
				'model'=>$model,
				'attr'=> $attr,
				'allAttr'=>$allAttr
			));
		}
	}

	public function actionAdminUpdate($id)
	{
		$model=Task::model()->with("fields.attribute","interpreters.interpreter")->findByPk($id);

		if(isset($_POST['Task']))
		{
			$this->setAttr($model);
		}else{

			$attr = $this->getModelFields($model);
			$allAttr = array_diff_key($this->getFields($model->good_type_id), $attr);

			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'allAttr'=>$allAttr,
				'attr'=>$attr
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
			$this->layout = 'admin';
			$this->scripts[] = 'Task';
		}
		$filter = new Task('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Task']))
        {
            $filter->attributes = $_GET['Task'];
            foreach ($_GET['Task'] AS $key => $val)
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

        $model = Task::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Task::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Task::attributeLabels()
			));
		}
	}
	
	public function loadModel($id)
	{
		$model=Task::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
