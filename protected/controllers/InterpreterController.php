<?php

class InterpreterController extends Controller
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
		$model=new Interpreter;

		if(isset($_POST['Interpreter']))
		{
			$_POST["Interpreter"]["category_id"] = 5;
			$model->attributes=$_POST['Interpreter'];
			if($model->save()){
				$this->actionAdminList(true);
				return true;
			}
		}

		if(isset($_GET["Interpreter"]) && isset($_GET["Interpreter"]["good_type_id"]) ) $model->good_type_id = $_GET["Interpreter"]["good_type_id"];

		$this->renderPartial('adminCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		$this->checkAccess($model);

		if(isset($_POST['Interpreter']))
		{
			$model->attributes=$_POST['Interpreter'];
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
		$this->checkAccess( Interpreter::model()->findByPk($id) );

		$this->loadModel($id)->delete();

		$this->actionAdminList(true);
	}

	public function actionAdminList($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Interpreter('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Interpreter']))
        {
            $filter->attributes = $_GET['Interpreter'];
            foreach ($_GET['Interpreter'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 'id DESC';

        $criteria_inter = clone $criteria;
        $criteria_inter->addCondition("service=0");
        $inter = Interpreter::model()->findAll($criteria_inter);

        $criteria->addCondition("service=1");
        $service = Interpreter::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminList',array(
				'inter'=>$inter,
				'service'=>$service,
				'filter'=>$filter,
				'labels'=>Interpreter::attributeLabels()
			));
		}else{
			$this->renderPartial('adminList',array(
				'inter'=>$inter,
				'service'=>$service,
				'filter'=>$filter,
				'labels'=>Interpreter::attributeLabels()
			));
		}
	}

	public function actionAdminIndex(){
		$model = GoodType::model()->findAll();

		$this->render('adminIndex',array(
			'model'=>$model,
		));
	}

	public function actionAdminPreview($id)
	{
		$inter = Interpreter::model()->findByPk($id);

		$criteria = new CDbCriteria();
		$criteria->condition = "good_type_id=".$inter->good_type_id;
		$criteria->order = "rand()";
		$criteria->limit = 30;
		// $criteria->with = array("fields.variant");

        $model = Good::model()->findAll($criteria);
        
        $criteria = new CDbCriteria();
		$criteria->with = array("goodTypes","variants.variant"=>array("order"=>"variant.sort ASC"));
		$criteria->condition = "t.id=57 OR t.id=37 OR t.id=38";
		$criteria->order = "t.id DESC";
        $modelDyn = Attribute::model()->findAll($criteria);

        $dynamic = array();
        $dynObjects = array();


        foreach ($modelDyn as $key => $value) {
        	$current = ( isset($_POST["dynamic"][$value->id]) )?$_POST["dynamic"][$value->id]:$value->variants[0]->variant_id;
        	$curObj = Variant::model()->findByPk($current);
        	$dynamic[$value->id] = array("CURRENT" => $curObj->id, "ALL" => $value->variants);
        	$dynObjects[$value->id] = (object) array("value"=>$curObj->value,"variant_id"=>$curObj->id);
        }

        $data = array();
        foreach ($model as $item) {
        	$data[] = array("ID"=>$item->fields_assoc[3]->value,"VALUE"=>$this->replaceToBr(Interpreter::generate($id,$item,$dynObjects)));
        }

		$this->renderPartial('adminPreview',array(
			'data'=>$data,
			'dynamic'=>$dynamic
		));
	}

	public function loadModel($id)
	{
		$model=Interpreter::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
