<?php

class DataController extends Controller
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
				'actions'=>array('adminIndex','adminVars','adminVarsUpdate','adminVarsCreate','adminVarsDelete','adminVarsEdit','adminDictionary','adminDictionaryUpdate','adminDictionaryCreate','adminDictionaryDelete','adminDictionaryEdit','adminTable','adminTableUpdate','adminTableCreate','adminTableDelete','adminTableEdit','adminCube','adminCubeUpdate','adminCubeCreate','adminCubeDelete','adminCubeEdit'),
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

	public function actionAdminVarsCreate()
	{
		$model=new Vars;

		if(isset($_POST['Vars']))
		{
			$model->attributes=$_POST['Vars'];
			if($model->save()){
				$this->actionAdminVars(true);
				return true;
			}
		}

		$this->renderPartial('adminVarsCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminVarsUpdate($id)
	{
		$model=Vars::model()->findByPk($id);

		if(isset($_POST['Vars']))
		{
			$model->attributes=$_POST['Vars'];
			if($model->save())
				$this->actionAdminVars(true);
		}else{
			$this->renderPartial('adminVarsUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminVarsDelete($id)
	{
		Vars::model()->findByPk($id)->delete();

		$this->actionAdminVars(true);
	}

	public function actionAdminVars($partial = false)
	{
		if( !$partial )
			$this->layout='admin';

		$filter = new Vars('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Vars']))
        {
            $filter->attributes = $_GET['Vars'];
            foreach ($_GET['Vars'] AS $key => $val)
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

        $criteria->order = 'name DESC';

        $model = Vars::model()->findAll($criteria);

        if( !$partial ){
        	$this->render('adminVars',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Vars::attributeLabels()
			));
        }else{
        	$this->renderPartial('adminVars',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Vars::attributeLabels()
			));
        }
	}

	public function actionAdminDictionaryCreate()
	{
		$model=new Dictionary;

		if(isset($_POST['Dictionary']))
		{
			$model->attributes=$_POST['Dictionary'];
			if($model->save()){
				$this->actionAdminDictionary(true);
				return true;
			}
		}

		$this->renderPartial('adminDictionaryCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminDictionaryUpdate($id)
	{
		$model=Dictionary::model()->findByPk($id);

		if(isset($_POST['Dictionary']))
		{
			$this->checkAccess($model);

			$model->attributes=$_POST['Dictionary'];
			if($model->save())
				$this->actionAdminDictionary(true);
		}else{
			$this->renderPartial('adminDictionaryUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDictionaryEdit($id)
	{
		$this->scripts[] = "table";

		$this->checkAccess( Dictionary::model()->findByPk($id) );

		if(isset($_POST['json']))
		{
			DictionaryVariant::model()->deleteAll("dictionary_id=".$id);

			$data = json_decode($_POST["json"],true);

			foreach ($data['Values'] as $key => &$value) {
				if( !isset($value["value"]) || $value["value"] == "" ){
					unset($data['Values'][$key]);
				}else{
					$value["dictionary_id"] = $id;
				}
			}

			if($this->insertValues(DictionaryVariant::tableName(), array_values($data['Values']) )){
				echo "1";
			}else{
				echo "0";
			}
		}else{
			$model=Dictionary::model()->with("attribute_1.variants")->findByPk($id);

			$values = array();
			foreach ($model->values as $key => $value) {
				$values[$value->attribute_1] = $value->value;
			}

			$this->render('adminDictionaryEdit',array(
				'model'=>$model,
				'values'=>$values,
				'data'=>$model->attribute_1->variants,
			));
		}
	}

	public function actionAdminDictionaryDelete($id)
	{
		$this->checkAccess( Dictionary::model()->findByPk($id) );

		Dictionary::model()->findByPk($id)->delete();

		$this->actionAdminDictionary(true);
	}

	public function actionAdminDictionary($partial = false)
	{
		if( !$partial )
			$this->layout='admin';

		$filter = new Dictionary('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Dictionary']))
        {
            $filter->attributes = $_GET['Dictionary'];
            foreach ($_GET['Dictionary'] AS $key => $val)
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

        $model = Dictionary::model()->findAll($criteria);

        $model = $this->removeExcess($model);       

        if( !$partial ){
        	$this->render('adminDictionary',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Dictionary::attributeLabels()
			));
        }else{
        	$this->renderPartial('adminDictionary',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Dictionary::attributeLabels()
			));
        }
	}

	public function actionAdminTableCreate()
	{
		$model=new Table;

		if(isset($_POST['Table']))
		{
			$model->attributes=$_POST['Table'];
			if($model->save()){
				$this->actionAdminTable(true);
				return true;
			}
		}

		$this->renderPartial('adminTableCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminTableUpdate($id)
	{
		$model=Table::model()->findByPk($id);

		if(isset($_POST['Table']))
		{
			$this->checkAccess($model);

			$model->attributes=$_POST['Table'];
			if($model->save())
				$this->actionAdminTable(true);
		}else{
			$this->renderPartial('adminTableUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminTableEdit($id)
	{
		$this->scripts[] = "table";

		$this->checkAccess( Table::model()->findByPk($id) );

		if(isset($_POST['json']))
		{
			TableVariant::model()->deleteAll("table_id=".$id);

			$data = json_decode($_POST["json"],true);

			foreach ($data['Values'] as $key => &$value) {
				if( !isset($value["value"]) || $value["value"] == "" ){
					unset($data['Values'][$key]);
				}else{
					$value["table_id"] = $id;
				}
			}

			if($this->insertValues(TableVariant::tableName(), array_values($data['Values']) )){
				echo "1";
			}else{
				echo "0";
			}
		}else{
			$model=Table::model()->findByPk($id);

			$x = AttributeVariant::model()->findAll("attribute_id=".$model->attribute_id_1);
			$y = AttributeVariant::model()->findAll("attribute_id=".$model->attribute_id_2);

			$values = array();
			foreach ($model->values as $key => $value) {
				if( !isset($values[$value->attribute_1]) ) $values[$value->attribute_1] = array();
				$values[$value->attribute_1][$value->attribute_2] = $value->value;
			}
			$this->render('adminTableEdit',array(
				'model'=>$model,
				'values'=>$values,
				'x'=>$x,
				'y'=>$y,
			));
		}
	}

	public function actionAdminTableDelete($id)
	{
		$this->checkAccess( Table::model()->findByPk($id) );

		Table::model()->findByPk($id)->delete();

		$this->actionAdminTable(true);
	}

	public function actionAdminTable($partial = false)
	{
		if( !$partial )
			$this->layout='admin';

		$filter = new Table('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Table']))
        {
            $filter->attributes = $_GET['Table'];
            foreach ($_GET['Table'] AS $key => $val)
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

        $model = Table::model()->findAll($criteria);

        $model = $this->removeExcess($model);

        if( !$partial ){
        	$this->render('adminTable',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Table::attributeLabels()
			));
        }else{
        	$this->renderPartial('adminTable',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Table::attributeLabels()
			));
        }
	}

	public function actionAdminCubeCreate()
	{
		$model=new Cube;

		if(isset($_POST['Cube']))
		{
			$model->attributes=$_POST['Cube'];
			if($model->save()){
				$this->actionAdminCube(true);
				return true;
			}
		}

		$this->renderPartial('adminCubeCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminCubeUpdate($id)
	{
		$model=Cube::model()->findByPk($id);

		if(isset($_POST['Cube']))
		{
			$this->checkAccess($model);

			$model->attributes=$_POST['Cube'];
			if($model->save())
				$this->actionAdminCube(true);
		}else{
			$this->renderPartial('adminCubeUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminCubeEdit($id)
	{
		$this->scripts[] = "table";

		$this->checkAccess( Cube::model()->findByPk($id) );

		if(isset($_POST['json']))
		{
			CubeVariant::model()->deleteAll("cube_id=".$id);

			$data = json_decode($_POST["json"],true);

			foreach ($data['Values'] as $key => &$value) {
				if( !isset($value["value"]) || $value["value"] == "" ){
					unset($data['Values'][$key]);
				}else{
					$value["cube_id"] = $id;
				}
			}

			if($this->insertValues(CubeVariant::tableName(), array_values($data['Values']) )){
				echo "1";
			}else{
				echo "0";
			}
		}else{
			$model=Cube::model()->findByPk($id);

			$x = AttributeVariant::model()->findAll("attribute_id=".$model->attribute_id_1);
			$y = AttributeVariant::model()->findAll("attribute_id=".$model->attribute_id_2);
			$z = AttributeVariant::model()->findAll("attribute_id=".$model->attribute_id_3);

			$values = array();
			foreach ($model->values as $key => $value) {
				if( !isset($values[$value->attribute_1]) ) $values[$value->attribute_1] = array();
				if( !isset($values[$value->attribute_1][$value->attribute_2]) ) $values[$value->attribute_1][$value->attribute_2] = array();
				$values[$value->attribute_1][$value->attribute_2][$value->attribute_3] = $value->value;
			}

			$this->render('adminCubeEdit',array(
				'model'=>$model,
				'values'=>$values,
				'x'=>$x,
				'y'=>$y,
				'z'=>$z
			));
		}
	}

	public function actionAdminCubeDelete($id)
	{
		$this->checkAccess( Cube::model()->findByPk($id) );

		Cube::model()->findByPk($id)->delete();

		$this->actionAdminCube(true);
	}

	public function actionAdminCube($partial = false)
	{
		if( !$partial )
			$this->layout='admin';

		$filter = new Cube('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Cube']))
        {
            $filter->attributes = $_GET['Cube'];
            foreach ($_GET['Cube'] AS $key => $val)
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

        $model = Cube::model()->findAll($criteria);

        $model = $this->removeExcess($model);

        if( !$partial ){
        	$this->render('adminCube',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Cube::attributeLabels()
			));
        }else{
        	$this->renderPartial('adminCube',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Cube::attributeLabels()
			));
        }
	}

	public function actionAdminIndex(){
		$this->render('adminIndex');
	}

	public function insertVariants($tableName,$values){
		$this->insertValues($tableName,$values);
	}
}
