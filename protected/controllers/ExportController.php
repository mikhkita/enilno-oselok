<?php

class ExportController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminGetFields','adminPreview','adminDynamic','adminExport'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function getFields($goodTypeId = false){
		if( !$goodTypeId ){
			$model = GoodType::model()->findAll(array("limit"=>1));
			$goodTypeId = $model[0]->id;
		}
		$model = GoodType::model()->with("fields.attribute","interpreters")->findByPk($goodTypeId);
		$attributes = array();

		foreach ($model->fields as $key => $value) {
			$value = $value->attribute;
			$attributes[$value->id."a"] = $value;
		}
		foreach ($model->interpreters as $key => $value) {
			$attributes[$value->id."i"] = $value;
		}

		return $attributes;
	}

	public function getModelFields($model){
		$attributes = array();
		$sorter = array();

		$prevMin = -9999999;
		$minElem = NULL;

		// Ебаная сортировка
		for( $i = 0 ; $i < count($model->fields)+count($model->interpreters) ; $i++ ){
			$min = 9999999;

			foreach ($model->fields as $key => $value) {
				if( (int)$value->sort < $min && (int)$value->sort > $prevMin ){
					$min = (int)$value->sort;
					$minElem = array("key"=>$value->attribute_id."a", "value" => $value->attribute);
				}
			}

			foreach ($model->interpreters as $key => $value) {
				if( (int)$value->sort < $min && (int)$value->sort > $prevMin ){
					$min = (int)$value->sort;
					$minElem = array("key"=>$value->interpreter_id."i", "value" => $value->interpreter);
				}
			}

			$prevMin = $min;
			$attributes[$minElem["key"]] = $minElem["value"];
		}

		return $attributes;
	}

	public function actionAdminGetFields($goodTypeId){
		$this->renderPartial('adminGetFields',array(
			'allAttr'=>$this->getFields($goodTypeId)
		));
	}

	public function actionAdminCreate()
	{
		$model=new Export;

		if(isset($_POST['Export']))
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
		$model=Export::model()->with("fields.attribute","interpreters.interpreter")->findByPk($id);

		if(isset($_POST['Export']))
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

	public function setAttr($model){
		$model->attributes=$_POST['Export'];
		if($model->save()){
			$this->updateAttributes($model);
			$this->actionAdminIndex(true);
		}
	}

	public function updateAttributes($model){
		ExportAttribute::model()->deleteAll('export_id='.$model->id);
		ExportInterpreter::model()->deleteAll('export_id='.$model->id);

		if( isset($_POST["sorted"]) ){
			$values = array("attributes"=>array(),"interpreters"=>array());

			$sort = 10;
			if( isset($_POST["sorted"]) ){
				foreach ($_POST["sorted"] as $key => $value) {
					$tmpArr = explode("-", $value);

					$values[trim($tmpArr[0])][] = "('".$model->id."','".$tmpArr[1]."','".$sort."')";
					$sort+=10;
				}
			}
			

			if( count($values["attributes"]) )
				$this->insertAll($tableName = ExportAttribute::tableName(),$values["attributes"]);

			if( count($values["interpreters"]) )
				$this->insertAll($tableName = ExportInterpreter::tableName(),$values["interpreters"]);
		}
	}

	public function insertAll($tableName,$values){
		$sql = "INSERT INTO `$tableName` VALUES ";

		$sql .= implode(",", $values);

		Yii::app()->db->createCommand($sql)->execute();
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
			$this->scripts[] = 'export';
		}
		$filter = new Export('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Export']))
        {
            $filter->attributes = $_GET['Export'];
            foreach ($_GET['Export'] AS $key => $val)
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

        $model = Export::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Export::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Export::attributeLabels()
			));
		}
	}

	public function actionAdminDynamic($id = false){
		$this->scripts[] = "export";
		$export = Export::model()->findByPk($id);

		$criteria = new CDbCriteria();
		$criteria->condition = "good_type_id=".$export->good_type_id." AND attribute.dynamic=1";
		$criteria->with = array("attribute.variants");

        $model = GoodTypeAttribute::model()->findAll($criteria);

		$this->render('adminDynamic',array(
			'data'=>$model,
			'id'=>$id
		));
	}

	public function actionAdminPreview($id = false){
		$this->scripts[] = "export";
		$this->scripts[] = "filterTable";

		if( $id ){
			$export = Export::model()->with('fields.attribute','interpreters.interpreter')->findByPk($id);
			$GoodType = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($export->good_type_id);
		}

		$dynObjects = array();

		if( isset($_POST["dynamic"]) ){
			$dynObjects = $this->getDynObjects($_POST["dynamic"],$export->good_type_id);
		}

		$arr = array();

		foreach ($export->fields as $key => $value) {
			$arr[intval($value->sort)] = array("TYPE"=>"attr", "VALUE"=>$value->attribute);
			
			if( $value->attribute->list && !$value->attribute->dynamic ){
				$variants = array();

				foreach ($GoodType->goods as $good) {
					if( isset($good->fields_assoc[$value->attribute->id]) ){
						$obj = $good->fields_assoc[$value->attribute->id];
						if( is_array($obj) ){
							foreach ($obj as $key => $v) {
								if( !isset($variants[$v->value]) )
									$variants[$v->variant_id] = $v->value;
							}
						}else{
							if( !isset($variants[$obj->value]) )
								$variants[$obj->variant_id] = $obj->value;
						}
					}else{
						$variants[$obj->variant_id] = "";
					}
				}

				$arr[intval($value->sort)]["VARIANTS"] = $variants;
			}
		}

		foreach ($export->interpreters as $key => $value) {
			$arr[intval($value->sort)] = array("TYPE"=>"inter", "VALUE"=>$value->interpreter);
		}

		ksort($arr);

		$this->render('adminPreview',array(
			'id' => $id,
			'data'=>$GoodType,
			'fields' => $arr,
			'name'=>$export->name,
			'dynObjects'=>$dynObjects,
			'dynValues'=>(isset($_POST["dynamic_values"]))?$_POST["dynamic_values"]:array()
		));
	}

	public function actionAdminExport($id){
		if( !isset($_POST["ids"]) || $_POST["ids"] == "" ) 
			throw new CHttpException(500,"Не выбран ни один товар");

		$export = Export::model()->with('fields.attribute','interpreters.interpreter')->findByPk($id);
		$goods = Good::model()->with('fields.attribute')->findAllByPk(explode(",",$_POST["ids"]));

		$fields = array();
		$excel = array();
		$dynamic_values = array();

		foreach ($export->fields as $key => $value) {
			$fields[intval($value->sort)] = array("TYPE"=>"attr", "VALUE"=>$value->attribute);
		}
		foreach ($export->interpreters as $key => $value) {
			$fields[intval($value->sort)] = array("TYPE"=>"inter", "VALUE"=>$value->interpreter);
		}
		ksort($fields);

		$titles = array();
		foreach ($fields as $value) {
			array_push($titles, $value["VALUE"]->name);
		}
		array_push($excel, $titles);

		if( isset($_POST["dynamic_values"]) ){
			foreach ($_POST["dynamic_values"] as $key => $value) {
				$vals = explode(",", $value);
				// $_POST["dynamic_values"][$key] = $vals;
				if( !count($dynamic_values) ){
					foreach ($vals as $val) {
						array_push($dynamic_values, array($key => intval($val)));
					}
				}else{
					$tmp = array();
					foreach ($vals as $val) {
						foreach ($dynamic_values as $i => $a) {
							$a[$key] = $val;
							array_push($tmp, $a);
						}
					}
					$dynamic_values = $tmp;
				}
			}

			foreach ($dynamic_values as $dynVal) {
				$excel = $this->generateFields($excel, $goods, $fields, $this->getDynObjects($dynVal,$export->good_type_id));
			}

		}else{
			$excel = $this->generateFields($excel, $goods, $fields);
		}

		$file = $this->writeExcel($excel, $export->name);

		$this->DownloadFile($file, $export->name);
	}

	public function generateFields($excel, $goods, $fields, $dynObjects = NULL){
		foreach ($goods as $good) {
			$row = array();
			foreach ($fields as $field) {
				if( $field["TYPE"] == "attr" ){
					$obj = ( isset($good->fields_assoc[intval($field["VALUE"]->id)]) )?$good->fields_assoc[intval($field["VALUE"]->id)]:( isset($dynObjects[intval($field["VALUE"]->id)])?$dynObjects[intval($field["VALUE"]->id)]:NULL );
					if( isset($obj) ){
						if( is_array($obj) ){
							array_push($row, $this->implodeValues($obj));
						}else{
							array_push($row, $obj->value);
						}
					}else{
						array_push($row, "");
					}
				}else{
					array_push($row, Interpreter::generate($field["VALUE"]->id,$good,$dynObjects));
				}
			}
			array_push($excel, $row);
		}
		return $excel;
	}

	public function writeExcel($data,$title = "Новый экспорт"){
		include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel.php';
		// include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel/IOFactory.php';

		$excelDir = Yii::app()->params['excelDir'];

		$phpexcel = new PHPExcel(); // Создаём объект PHPExcel
		$filename = "example.xlsx";

		/* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
		$page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
		foreach($data as $i => $ar){ // читаем массив
			foreach($ar as $j => $val){
				$page->setCellValueByColumnAndRow($j,$i+1,$val); // записываем данные массива в ячейку
				$page->getStyleByColumnAndRow($j,$i+1)->getAlignment()->setWrapText(true);
			}
		}
		$page->setTitle($title); // Заголовок делаем "Example"
		
		for($col = 'A'; $col !== 'Z'; $col++) {
		    $page->getColumnDimension($col)->setAutoSize(true);
		}

		/* Начинаем готовиться к записи информации в xlsx-файл */
		$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
		/* Записываем в файл */
		$objWriter->save($excelDir."/".$filename);

		return $excelDir."/".$filename;
	}

	public function loadModel($id)
	{
		$model=Export::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
