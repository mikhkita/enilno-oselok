<?php

class AttributeController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminEdit'),
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

	public function actionAdminCreate()
	{
		$model=new Attribute;

		if(isset($_POST['Attribute']))
		{
			$model->attributes=$_POST['Attribute'];
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

		if(isset($_POST['Attribute']))
		{
			$model->attributes=$_POST['Attribute'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model
			));
		}
	}

	public function actionAdminEdit($id)
	{
		$model = Attribute::model()->with(array("variants.variant"=>array("order"=>"variant.sort ASC")))->findByPk($id);

		if( isset($_POST['Edit']) )
		{
			$this->updateVariants($model);
			$this->actionAdminIndex(true);
		}else if(isset($_POST["Group"])){
			AttributeVariant::model()->deleteAll("attribute_id=".$model->id);

			if( count($_POST["VariantsGroup"]) ){
				$values = array();

				foreach ($_POST["VariantsGroup"] as $key => $value) {
					$values[] = array("attribute_id"=>$model->id,"variant_id"=>$value);
				}

				$this->insertValues(AttributeVariant::tableName(),$values);
			}
			$this->actionAdminIndex(true);
		}else{

			if( $model->group_id != 0 ){
				$variants = array();
				$selected = array();

				foreach ($model->group->variants as $variant) {
					$variants[$variant->variant_id] = $variant->variant->value;
				}

				foreach ($model->variants as $variant) {
					$selected[] = $variant->variant_id;
				}

				$this->renderPartial('adminEditGroup',array(
					'model'=>$model,
					'variants'=>$variants,
					'selected'=>$selected
				));
			}else{
				$this->renderPartial('adminEdit',array(
					'model'=>$model,
				));
			}
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
		$filter = new Attribute('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Attribute']))
        {
            $filter->attributes = $_GET['Attribute'];
            foreach ($_GET['Attribute'] AS $key => $val)
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

        $model = Attribute::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Attribute::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Attribute::attributeLabels()
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
		$model=Attribute::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function updateVariants($model){
		$tableName = Variant::tableName();

		$modelArr = array();
		foreach ($model->variants as $key => $value) {
			$modelArr[$value->variant->id] = $value->variant->sort;
		}


		if( isset($_POST["Variants"]) ){
			$delArr = array_diff_key($modelArr,$_POST["Variants"]);
		}else{
			$delArr = $modelArr;
		}
		$this->deleteVariants($delArr);

		if( isset($_POST["Variants"]) || isset($_POST["VariantsNew"]) ){
			$values = array();
			$attrVariants = array();

			$this->updateRows(Variant::tableName(),$values,array("sort"));

			if( isset($_POST["VariantsNew"]) )
				foreach ($_POST["VariantsNew"] as $key => $value) {
					$attrs = array($model->type->code."_value"=>$key,"sort"=>$value);
					$new = new Variant();
					$new->attributes = $attrs;
					$new->save();
					$attrVariants[$new->id] = $value;
				}


			$values = array();
			$values_attr = array();

			foreach ($attrVariants as $key => $value) {
				$values[] = array($key,NULL,NULL,NULL,$value);
				$values_attr[] = array("attribute_id"=>$model->id,"variant_id"=>$key);
			}

			$this->insertValues(AttributeVariant::tableName(),$values_attr);

			if( isset($_POST["Variants"]) )
				foreach ($_POST["Variants"] as $key => $value) {
					$values[] = array($key,NULL,NULL,NULL,$value);
				}

			$this->updateRows(Variant::tableName(),$values,array("sort"));
		}
	}

	public function deleteVariants($delArr){
		if( count($delArr) ){
			$pks = array();

			foreach ($delArr as $key => $value) {
				$pks[] = $key;
			}
			$model = Variant::model()->findAllByPk($pks);
			foreach ($model as $key => $value) {
				$value->delete();
			}
		}
	}

	public function getArrayFromModel($model){

	}
}
