<?php

class GoodTypeController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminCodeDel'),
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

	public function getFields(){
		$model = Attribute::model()->findAll(array("order"=>"name ASC"));
		$attributes = array();

		foreach ($model as $key => $value) {
			$attributes[$value->id] = $value->name;
		}
		return $attributes;
	}

	public function getModelFields($model){
		$attributes = array();

		foreach ($model->fields as $key => $value) {
			$attributes[$value->attribute->id] = $value->attribute->name;
		}
		return $attributes;
	}

	public function actionAdminCreate()
	{
		$model=new GoodType;

		if(isset($_POST['GoodType']))
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
		$model=$this->loadModel($id);

		if(isset($_POST['GoodType']))
		{
			$this->setAttr($model);
		}else{
			$attr = $this->getModelFields($model);
			$allAttr = array_diff_key($this->getFields(), $attr);

			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'allAttr'=>$allAttr,
				'attr'=>$attr
			));
		}
	}

	public function actionAdminCodeDel($id)
	{
		
		if(isset($_POST['GoodType']['CodeDel']))
		{
			$arr = explode(PHP_EOL,$_POST['GoodType']['CodeDel']);

			foreach ($arr as $key => $value) {
				$arr[$key] = trim($value);
			}

			$criteria=new CDbCriteria();
			$criteria->condition = '(good_type_id='.$id.' AND fields.attribute_id=3)';
			$criteria->select = 'id';
			$criteria->with = array('fields' => array( 'select' => 'attribute_id, varchar_value'));
			$criteria->addInCondition('fields.varchar_value',$arr); 

			$model = Good::model()->findAll($criteria);

			foreach ($model as $good) {
				$good->delete();
			}
			$this->actionAdminIndex(true);
		}else{
			
			$this->renderPartial('adminCodeDel',array(
				
			));
		}
	}

	public function setAttr($model){
		$model->attributes=$_POST['GoodType'];
		if($model->save()){
			$this->updateAttributes($model);
			$this->actionAdminIndex(true);
		}
	}

	public function updateAttributes($model){
		if( isset($_POST["attributes"]) ){
			$attr = array_diff_key($this->getModelFields($model), $_POST["attributes"]);
			$pks = array();

			foreach ($attr as $key => $value) {
				$pks[] = array('good_type_id'=>$model->id,'attribute_id'=>$key);
			}
			GoodTypeAttribute::model()->deleteByPk($pks);

			$tmpName = "tmp_".md5(rand().time());

			Yii::app()->db->createCommand()->createTable($tmpName, array(
			    'good_type_id' => 'int NOT NULL',
			    'attribute_id' => 'int NOT NULL',
			    'sort' => 'int NOT NULL',
			    0 => 'PRIMARY KEY (`good_type_id`,`attribute_id`)'
			), 'ENGINE=InnoDB');

			$sql = "INSERT INTO `$tmpName` (`good_type_id`,`attribute_id`,`sort`) VALUES ";

			$values = array();
			$sort = 10;
			foreach ($_POST["attributes"] as $key => $value) {
				$values[] = "('".$model->id."','".$key."','".$sort."')";
				$sort+=10;
			}

			$sql .= implode(",", $values);

			if( Yii::app()->db->createCommand($sql)->execute() ){
				$tableName = GoodTypeAttribute::tableName();
				
				$sql = "INSERT INTO `$tableName` SELECT * FROM `$tmpName` ON DUPLICATE KEY UPDATE $tableName.sort = $tmpName.sort";
				$result = Yii::app()->db->createCommand($sql)->execute();
				
				Yii::app()->db->createCommand()->dropTable($tmpName);
			}
		}else{
			GoodTypeAttribute::model()->deleteAll('good_type_id = '.$model->id);
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
		// $ph = new Photodoska;
		// $ph->del_ads();
		// $arr = array("title","титл1");
		// print_r($ph->parse_ads($arr));
		$filter = new GoodType('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['GoodType']))
        {
            $filter->attributes = $_GET['GoodType'];
            foreach ($_GET['GoodType'] AS $key => $val)
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

        $model = GoodType::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>GoodType::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>GoodType::attributeLabels()
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
		$model=GoodType::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
