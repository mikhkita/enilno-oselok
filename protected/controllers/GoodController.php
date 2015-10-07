<?php

class GoodController extends Controller
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
				'actions'=>array('adminIndex','adminTest','adminCreate','adminUpdate','adminDelete','adminEdit','getAttrType','getAttr'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array('adminIndex2'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate($goodTypeId)
	{
		$model = new Good;
		$model->good_type_id = $goodTypeId;
		$result = array();
		if(isset($_POST['Good_attr']) && $model->save())
		{
			foreach ($_POST['Good_attr'] as $attr_id => $value) {
				if(!is_array($value) ) {
					if($value) {
						$attr = new GoodAttribute;
						$attr->good_id = $model->id;
						$attr->attribute_id = $attr_id;
						$attr[$this->getAttrType($model,$attr_id)] = $value;
						$attr->save();
					}
				} else if(isset($value['single']) ){
					if($value['single']) {
						$attr = new GoodAttribute;
						$attr->good_id = $model->id;
						$attr->attribute_id = $attr_id;
						$attr->variant_id = $value['single'];
						$attr->save();
					}
				} else {
					if(!empty($value)) {
						foreach ($value as $variant) {
							$attr = new GoodAttribute;
							$attr->good_id = $model->id;
							$attr->attribute_id = $attr_id;
							$attr->variant_id = $variant;
							$attr->save();
						}
					}
				}
			}

			$this->redirect( Yii::app()->createUrl('good/adminindex',array('goodTypeId'=>$goodTypeId,'partial'=>true)) );

		}else{
			$this->renderPartial('adminCreate',array(
				'model'=>$model,
				'result' => $result
			));
		}

	}

	public function actionAdminUpdate($id,$goodTypeId)
	{
		$model = $this->loadModel($id);
		$result = $this->getAttr($model);
		if(isset($_POST['Good_attr']))
		{
			GoodAttribute::model()->deleteAll('good_id='.$id);
			foreach ($_POST['Good_attr'] as $attr_id => $value) {
				if(!is_array($value) ) {
					if($value) {
						$attr = new GoodAttribute;
						$attr->good_id = $id;
						$attr->attribute_id = $attr_id;
						$attr[$this->getAttrType($model,$attr_id)] = $value;
						$attr->save();
					}
				} else if(isset($value['single']) ){
					if($value['single']) {
						$attr = new GoodAttribute;
						$attr->good_id = $id;
						$attr->attribute_id = $attr_id;
						$attr->variant_id = $value['single'];
						$attr->save();
					}
				} else {
					if(!empty($value)) {
						foreach ($value as $variant) {
							$attr = new GoodAttribute;
							$attr->good_id = $id;
							$attr->attribute_id = $attr_id;
							$attr->variant_id = $variant;
							$attr->save();
						}
					}
				}
			}
			$this->redirect( Yii::app()->createUrl('good/adminindex',array('goodTypeId'=>$goodTypeId,'partial'=>true,'GoodFilter_page' => $_GET["GoodFilter_page"])) );

		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'result' => $result
			));
		}
	}
	public function getAttr($model) {
		$result = array();
		foreach ($model->type->fields as $attr) {
			if($attr->attribute->multi)	$result[$attr->attribute_id] = array(); else $result[$attr->attribute_id] = "";
			foreach ($model->fields as $item) {
				if($attr->attribute_id == $item->attribute_id) {
					if($item->attribute->list) {
						if($item->attribute->multi) {
							$result[$attr->attribute_id][] = $item->variant->id;
						} else $result[$attr->attribute_id] = $item->variant->id;
					} else $result[$attr->attribute_id] = $item->value;
				}
			}
		}
		return $result;
	}

	public function getAttrType($model, $attrId) {
		foreach ($model->type->fields as $attr) {
			if($attr->attribute_id == $attrId) return $attr->attribute->type->code."_value";
		}
	}
	public function actionAdminEdit($id)
	{
		$model=$this->loadModel($id);

		if( isset($_POST['Edit']) )
		{
			$this->updateVariants($model);
			$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminEdit',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminTest($partial = false, $goodTypeId = false)
	{
		$start = microtime(true);

		// ini_set("memory_limit", "420M");

		$attributes = array(
			9 => array(1317,1318,1319,1320,1321,1322,1323,1324),
			5 => array(1262,1263,1367,1264,1265,1266,1384,1267,1269,1270,1372,1473,1368,1271),
			31 => array(1307,1308,1309,1310,1311,1312,1313,1314,1315,1316,1432,1630),
		);

		// $attributes = array(
		// 	9 => array(1322),
		// 	5 => array(1264,1269),
		// 	31 => array(1312),
		// );

		$filter = array("good_type_id"=>2,"attributes"=>$attributes);	

		$data = Good::model()->filter(
			array(
				"good_type_id"=>2,
				"attributes"=>$attributes,
			)
		)->sort(
			array(
				"field"=>9,
				"type"=>"DESC",
			)
		)->getPage(
			array(
		    	'pageSize'=>13,
		    )
		);

		$model = $data["items"];

		echo count($model);

		foreach ($model as $item) {
			// print_r($item);
			// echo "<br>";
			// echo $item->fields_assoc[9]->value."<br>";
		}



		// $model = Good::model()->with(array("fields.attribute","fields.variant"))->findAll(array("condition"=>"good_type_id='1'"));

		// echo $this->filterGoods($filter);




		list($queryCount, $queryTime) = Yii::app()->db->getStats();
		echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
		printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);		
	}

	public function actionAdminIndex($partial = false, $goodTypeId = false)
	{
		unset($_GET["partial"]);

		if( isset($_GET["delete"]) ){
			$this->loadModel($_GET["delete"])->delete();
			unset($_GET["delete"]);
		}

		$goodType = GoodType::model()->findByPk($goodTypeId);

		$arr_name = "filter";
		$params = array(
			1 => array(
					"FILTER" => array(43,26,23,27,16),
					"FILTER_NAMES" => array(43=>41),
					"SORT" => array(3,20),
				),
			2 => array(
					"FILTER" => array(43,26,27),
					"FILTER_NAMES" => array(43=>41),
					"SORT" => array(3,20),
				),
		);
		$sort_fields = $this->getLabels($params[$goodTypeId]["SORT"]);
		$attributes = $this->getFilterVariants($params[$goodTypeId]["FILTER"],$params[$goodTypeId]["FILTER_NAMES"],$goodTypeId);
		$labels = $this->getLabels($params[$goodTypeId]["FILTER"]);

		if(!isset($_SESSION)) session_start();
		if( !isset($_POST["sort"]) ){
			if( isset($_SESSION["POST"][$goodTypeId]) ){
				$_POST = $_SESSION["POST"][$goodTypeId];
			}else{
				$_POST["sort"] = array("field"=>20,"type"=>"ASC");
				$_POST[$arr_name] = array();
			}
		}else{
			$_SESSION["POST"][$goodTypeId] = $_POST;
		}

		if( !$partial ){
			$this->layout='admin';
		}

		if( $goodTypeId ){
			unset($_GET["id"]);

			if( isset( $_POST[$arr_name] ) ){
				$filter_values = $_POST[$arr_name];
			}

			$goods = Good::model()->filter(
				array(
					"good_type_id"=>$goodTypeId,
					"attributes"=>$filter_values,
				)
			)->sort( 
				$_POST['sort'] 
			)->getPage(
				array(
			    	'pageSize'=>25,
			    )
			);
		}

		$options = array(
			'data'=>$goods["items"],
			'fields' => $goodType->fields,
			'name'=>$goodType->name,
			'pages' => $goods["pages"],
			'attributes' => $attributes,
			'labels' => $labels,
			'arr_name' => $arr_name,
			'filter_values' => $filter_values,
			'good_count' => $goods["count"],
			'sort_fields' => $sort_fields,
		);

		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
		}
	}

	public function getFilterVariants($array,$array_names,$goodTypeId){
		$result = array();

		foreach ($array as $value) {
			$result[$value] = array();
		}

		$criteria = new CDbCriteria();
		$criteria->with = array("good_filter"=>array("select"=>"good_type_id"));
		$criteria->condition = "good_filter.good_type_id = ".$goodTypeId;
	    $criteria->addInCondition("t.attribute_id",$array);
	    $criteria->group = "variant_id";

		$model = GoodAttribute::model()->with(array("attribute","variant"))->findAll($criteria);

		foreach ($model as $key => $field) {
			if( !count($result[$field->attribute_id]) ){
				$type = ($field->attribute->list)?"CHECKBOX":"FROMTO";
				$result[$field->attribute_id] = array("VIEW"=>$type,"VARIANTS"=>array());
			}
			$result[$field->attribute_id]["VARIANTS"][$field->variant_id] = $field->value;
		}

		foreach ($array_names as $key => $val) {
			if( isset($result[$key]) ){
				$list = $this->getListValue(41);
				foreach ($result[$key]["VARIANTS"] as $i => &$variant) {
					$variant = $list[$i];
				}
			}
		}

		return $result;
	}

	public function getLabels($array){
		$result = array();

		$model = Attribute::model()->findAllByPk($array);

		foreach ($model as $key => $attribute) {
			$result[$attribute->id] = $attribute->name;
		}

		return $result;
	}

	public function actionAdminIndex2($goodTypeId = false){
		if( $goodTypeId ){
			$GoodType = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($goodTypeId);
		}

		$this->render('index',array(
			'data'=>$GoodType->goods
		));
	}

	public function loadModel($id)
	{
		$model=Good::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function updateVariants($model){
		$tableName = GoodVariant::tableName();

		if( count($model->variants) ){
			$modelArr = array();
			foreach ($model->variants as $key => $value) {
				$modelArr[$value->id] = $value->sort;
			}


			if( isset($_POST["Variants"]) ){
				$delArr = array_diff_key($modelArr,$_POST["Variants"]);
			}else{
				$delArr = $modelArr;
			}
			$this->deleteVariants($delArr);

			if( isset($_POST["Variants"]) ){
				$tmpName = "tmp_".md5(rand().time());

				Yii::app()->db->createCommand()->createTable($tmpName, array(
				    'id' => 'int NOT NULL',
				    'int_value' => 'int NULL',
				    'varchar_value' => 'varchar(255) NULL',
				    'float_value' => 'float NULL',
				    'attribute_id' => 'int NULL',
				    'sort' => 'int NOT NULL',
				    0 => 'PRIMARY KEY (`id`)'
				), 'ENGINE=InnoDB');

				$sql = "INSERT INTO `$tmpName` (`id`,`attribute_id`,`sort`) VALUES ";

				$values = array();
				foreach ($_POST["Variants"] as $key => $value) {
					$values[] = "('".$key."','".$model->id."','".$value."')";
				}

				$sql .= implode(",", $values);

				if( Yii::app()->db->createCommand($sql)->execute() ){
					$sql = "INSERT INTO `$tableName` SELECT * FROM `$tmpName` ON DUPLICATE KEY UPDATE $tableName.sort = $tmpName.sort";
					$result = Yii::app()->db->createCommand($sql)->execute();
					
					Yii::app()->db->createCommand()->dropTable($tmpName);
				}
			}
		}	

		if( isset($_POST["VariantsNew"]) ){
			$sql = "INSERT INTO `$tableName` (`attribute_id`,`".$model->type->code."_value`,`sort`) VALUES ";

			$values = array();
			foreach ($_POST["VariantsNew"] as $key => $value) {
				$values[] = "('".$model->id."','".$key."','".$value."')";
			}

			$sql .= implode(",", $values);

			Yii::app()->db->createCommand($sql)->execute();
		}
	}

	public function deleteVariants($delArr){
		if( count($delArr) ){
			$pks = array();

			foreach ($delArr as $key => $value) {
				$pks[] = $key;
			}
			GoodVariant::model()->deleteByPk($pks);
		}
	}

	public function getArrayFromModel($model){

	}
}
