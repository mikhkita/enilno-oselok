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

		$this->pageTitle = "Экспорт: ".$export->name;

		$criteria = new CDbCriteria();
		$criteria->with = array("variants.variant"=>array("order"=>"variant.sort ASC"));
		$criteria->condition = "t.id=57 OR t.id=37 OR t.id=38";

        $model = Attribute::model()->findAll($criteria);

        $defaults = array(
        	57 => 2047,
        	37 => 869,
        	38 => 1081
        );
        
		$this->render('adminDynamic',array(
			'export' => $export,
			'data' => $model,
			'id' => $id,
			'defaults' => $defaults
		));
	}

	public function actionAdminExport($id){
		$export = Export::model()->with('fields.attribute','interpreters.interpreter')->findByPk($id);
		$good_ids = Good::getCheckboxes($export->good_type_id);
		$ids = array();
		foreach ($good_ids as $i => $value)
			array_push($ids, $i);

		$goods = Good::model()->filter(
			array(
				"good_type_id"=>$export->good_type_id,
			),
			$ids
		)->getPage(
			array(
		    	'pageSize'=>100000,
		    )
		);
		$goods = $goods["items"];

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

		if( isset($_POST["dynamic"]) ){
			$excel = $this->generateFields($excel, $goods, $fields, $this->getDynObjects($_POST["dynamic"]));
		}else{
			$excel = $this->generateFields($excel, $goods, $fields);
		}

		$file = $this->writeExcel($excel, $export->name);

		$this->DownloadFile($file, $export->name);
	}

	public function generateFields($excel, $goods, $fields, $dynObjects = NULL){
		if( !$goods ) return $excel;
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
