<?php

class NotificationController extends Controller
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
		$model=new Notification;

		if(isset($_POST['Notification']))
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
		$model=Notification::model()->with("fields.attribute","interpreters.interpreter")->findByPk($id);

		if(isset($_POST['Notification']))
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
			$this->scripts[] = 'Notification';
		}
		$filter = new Notification('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Notification']))
        {
            $filter->attributes = $_GET['Notification'];
            foreach ($_GET['Notification'] AS $key => $val)
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

        $model = Notification::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Notification::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Notification::attributeLabels()
			));
		}
	}
	
	public function loadModel($id)
	{
		$model=Notification::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
