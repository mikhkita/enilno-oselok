<?php

class ImportController extends Controller
{
	public $codeId = 3;

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
				'actions'=>array('adminIndex','adminStep2','adminStep3','adminImport'),
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

	public function actionAdminIndex($partial = false)
	{
		$this->scripts[] = "import";
		$model = GoodType::model()->findAll();
		$this->render('adminIndex',array(
			'model'=>$model
		));

	}
	

	public function actionAdminStep2($partial = false)
	{
		$this->scripts[] = "import";

		if(isset($_POST["GoodTypeId"]) && isset($_POST["excel_name"])) {
			$model = GoodType::model()->findByPk($_POST["GoodTypeId"]);

			$excel_path = Yii::app()->params['tempFolder']."/".$_POST["excel_name"];

			$xls = $this->getXLS($excel_path,1);

			$this->render('adminStep2',array(
				'model'=>$model,
				'xls'=>$xls,
				'excel_path'=>$excel_path,
				'GoodTypeId'=>$_POST["GoodTypeId"]
			));
		}
	}

	public function actionAdminStep3($partial = false)
	{
		$start = microtime(true);
		$this->scripts[] = "import";

		if(isset($_POST["excel_path"]) && isset($_POST["excel"]) && isset($_POST["GoodTypeId"])) {
			$model = Attribute::model()->with('variants.variant','type','goodTypes')->findAll("goodTypes.good_type_id=".$_POST["GoodTypeId"]);

			$goods = $data["items"];

			$sorted_titles = $_POST["excel"];// Массив соответствующих "ID атрибута" каждому столбцу

			$titles = array();

			// Получаем массив заголовков с их вариантами
			foreach ($model as $key => $field) {
				$variants = NULL;

				if( $field->list ){
					$variants = array();
					foreach ($field->variants as $i => $variant) {
						$variants[mb_strtolower($variant->value,'UTF-8')] = true;
					}
				}
            	$titles[intval($field->id)] = array(
            		"NAME" => $field->name,
            		"TYPE" => $field->type->code,
            		"VARIANTS" => $variants
            	);
            }	

            // Получаем матрицу считанного экселя в отсортированном по столбцами виде
			$xls = $this->getXLS($_POST["excel_path"],$sorted_titles,$titles);

			// Генерация структурированного ассоциативного массива для вью.
			$arResult = $this->getArResult($xls, $_POST["GoodTypeId"], $sorted_titles, $titles);

			$this->render('adminStep3',array(
				'arResult'=>$arResult
			));
		}
	}

	public function getArResult($xls, $good_type_id, $sorted_titles, $titles){
		$all_goods = array();
		$ids = array();
		$exist_codes = array();
		$arResult = array(
			"TITLES"=>NULL,
			"ROWS" => array(),
		);
		$report = array();

		$codes = array();
		for($i = 1; $i < count($xls); $i++) {
			$codes[] = $xls[$i][array_search($this->codeId, $sorted_titles)];
		}

		$criteria = new CDbCriteria();
    	$criteria->addInCondition("varchar_value",$codes);

		$attrs = GoodAttributeFilter::model()->findAll($criteria);
		foreach ($attrs as $item) {
			$ids[] = $item->good_id;
		}

		$data = Good::model()->filter(
			array(
				"good_type_id"=>$good_type_id,
			)
		)->getPage(
			array(
		    	'pageSize'=>100000,
		    )
		);

		$goods = $data["items"];

		foreach ($goods as $key => $good) {
			$all_goods[$good->fields_assoc[3]->value] = $good;
		}

		$arResult["TITLES"] = $xls[0];

		$sorted_titles = array_values($sorted_titles);

		for($i = 1; $i < count($xls); $i++) {
			$code = $xls[$i][array_search($this->codeId, $sorted_titles)];
			$isset = isset($all_goods[$code]);
			$error = false;

			// Кладем в каждую ячейку матрицы массив данных об этой ячейке вида:
			// array("ID" => "ID атрибута", "VALUE" => "Значение этого атрибута из экселя", "HIGHLIGHT" => "Тип подсветки ячейки");
        	foreach ($xls[$i] as $j => $cell) {
        		$id = $sorted_titles[$j]; // ID атрибута, в который будет вставляться значение
        		$field = ($isset)?( (isset($all_goods[$code]->fields_assoc[$id]))?($all_goods[$code]->fields_assoc[$id]->value):false ):false;
        		$field = ( is_array($field) || $field == false )?$field:array($field);
        		$cellValueAndHighlight = $this->getCellValueAndHighlight($cell,$titles[$id]["TYPE"],$field,$titles[$id]["VARIANTS"]);

        		if( $cellValueAndHighlight["HIGHLIGHT"] == "new-variant" ){
        			$item_code = $code;
        			if( !isset($report[$item_code]) ) $report[$item_code] = array();
        			$report[$item_code][$titles[$id]["NAME"]] = $cell;
        			$error = true;
        		}

        		$xls[$i][$j] = array(
        			"ID" => $id,
        			"VALUE" => $cellValueAndHighlight["VALUE"],
        			"HIGHLIGHT" => $cellValueAndHighlight["HIGHLIGHT"]
        		);
        	}

        	$arResult["ROWS"][] = array(
        		// Если уже есть элемент с таким кодом, то выделяем всю строку
				"HIGHLIGHT" => ($error)?("error"):(($isset)?"exist":NULL),
				"COLS" => $xls[$i],
				"ID" => ($isset)?$all_goods[$code]->id:NULL
			);
        }

        $arResult["REPORT"] = $report;

        return $arResult;
	}

	public function getCellValueAndHighlight($value,$type,$fieldValues = false,$variants = NULL){
		$highlight = NULL;
		$isEmpty = false;
		$isNotValid = false;

		if( $value == NULL ){
			$isEmpty = true;
		}else{
			$values = array();
			$tmpValues = explode("|", $value);

			foreach ($tmpValues as $key => $item) {
				$item = trim($item);
				if( $item == "-" ){
					$values[] = $item;
				}elseif( $item != "" && $item != NULL ){
					$v = $this->validate($type, $item);
					$isNotValid = (!$isNotValid)?(!$v):true;
					$values[] = ( $type == "int" )?round($item):$item;
				}
			}
			if( count($values) < 1 ){
				$isEmpty = true;
			}else{
				$value = $values;
			}

			if( is_array($fieldValues) ){
				if( mb_strtolower($fieldValues[0],'UTF-8') != mb_strtolower($values[0],'UTF-8') ){
					$highlight = "overwrite";
				}else{
					$highlight = "equal";
				}
			}
		}
		
		$highlight = ($isEmpty)?"empty":$highlight;
		$highlight = ($isNotValid)?"not-valid":$highlight;

		if( is_array($variants) && is_array($value) ){
			foreach ($value as $i => $item) {
				if( $item != "-" && !isset($variants[mb_strtolower($item,'UTF-8')]) ) $highlight = "new-variant";
			}
		}
		
		return array("VALUE" => $value, "HIGHLIGHT" => $highlight );
	}

	public function validate($type, $value){
		$valid = false;
		if( $type == "float" || $type == "int" ){
			if( is_numeric($value) ){
				$valid = true;
				if( $type == "int" ){
					$value = intval($value);
				}
			}
		}else $valid = true;
		return $valid;
	}

	public function actionAdminImport()
	{
		$start = microtime(true);
		$result = "error";
		$message = "";

		if( isset($_POST["IMPORT"]["GOODTYPEID"]) ){
			if( isset($_POST["IMPORT"]["ITEMS"]) ){
				$model = Attribute::model()->with("goodTypes","variants.variant","type")->findAll("goodTypes.good_type_id=".$_POST["IMPORT"]["GOODTYPEID"]);
				$import = $_POST["IMPORT"];
				$titles = array();
				$newFields = array();
				$AttributeVariantTableName = AttributeVariant::tableName();
				$GoodAttributeTableName = GoodAttribute::tableName();

				$addVariants = array();
				$addFields = array();
				$goodCode;

				// Получаем массив заголовков с их вариантами
				$titles = $this->getTitlesWithVariants($model);


		        foreach ($import["ITEMS"] as $i => $fields)
		        	foreach ($fields as $key => $value)
		        		$newFields[] = $key;

				// Если есть ID товара, то удаляем атрибуты, которые будем перезаписывать
				if( isset($import["ID"]) ){
					$id = $import["ID"];
					
					$criteria = new CDbCriteria();
					$criteria->condition = "good_id=".$id;
			    	$criteria->addInCondition("attribute_id",$newFields);
					GoodAttribute::model()->deleteAll($criteria);

					$goodCode = GoodAttribute::model()->find("good_id=".$id." AND attribute_id=".$this->codeId)->value;

					$message = "Обновился товар с кодом: ";
				}else{
					// Если нет ID товара, то создаем его и получаем его ID
					$newGood = new Good;
					$newGood->attributes = array("good_type_id"=>$import["GOODTYPEID"]);
					if($newGood->save()){
						$id = $newGood->id;
					}else die(json_encode(array("result"=>"error","message"=>"Не удалось создать товар")));

					$message = "Добавился товар с кодом: ";
				}



				// // Добавляем атрибуты к товару
		        $sql = "INSERT INTO `$GoodAttributeTableName` (`good_id`,`attribute_id`,`int_value`,`varchar_value`,`text_value`,`float_value`,`variant_id`) VALUES ";
		        foreach ($import["ITEMS"] as $i => $fields){
		        	foreach ($fields as $key => $value){
		        		if( trim($value) != "-" ){
			        		$title = $titles[intval($key)];

			        		$val = array("int"=>"NULL","varchar"=>"NULL","text"=>"NULL","float"=>"NULL");

			        		if( !is_array($title["VARIANTS"]) ){
			        			$val[$title["TYPE"]] = "'".addslashes($value)."'";
			        		}

							$addFields[] = "('".$id."','".$key."',".implode(",", $val).",".((is_array($title["VARIANTS"]))?("'".$title["VARIANTS"][mb_strtolower($value,'UTF-8')]."'"):"NULL").")";
						}
		        	}
		        }
		        if( count($addFields) > 0 ){
			        $sql .= implode(",", $addFields);
					Yii::app()->db->createCommand($sql)->execute();
				}

				$result = "success";
				$message .= $goodCode;
			}else{
				$result = "success";
				$model = Good::model()->findByPk($_POST["IMPORT"]["ID"]);
				$message = "Пропуск товара с кодом: ".$model->fields_assoc[3]->value;
			}

		}else{
			$result = "error";
			$message = "Отсутствует ID типа товара";
		}

		echo json_encode(array("result"=>$result,"message"=>$message));
	}

	public function getTitlesWithVariants($fields){
		$titles = array();
		foreach ($fields as $key => $field) {
			$variants = NULL;

			if( $field->list ){
				$variants = array();
				foreach ($field->variants as $i => $variant) {
					$variants[mb_strtolower($variant->value,'UTF-8')] = $variant->variant_id;
				}
			}
        	$titles[intval($field->id)] = array(
        		"TYPE" => $field->type->code,
        		"VARIANTS" => $variants
        	);
        }	
        return $titles;
	}

	public function loadModel($id)
	{
		$model=Import::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	private	function getXLS($xls,$rows = false,$titles = false){
		if( is_array($rows) && $titles === false )
			throw new CHttpException(404,'Отсутствуют наименования столбцов');

		include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel.php';
		include_once  Yii::app()->basePath.'/phpexcel/Classes/PHPExcel/IOFactory.php';
		
		$objPHPExcel = PHPExcel_IOFactory::load($xls);
		$objPHPExcel->setActiveSheetIndex(0);
		$aSheet = $objPHPExcel->getActiveSheet();
		
		$array = array();
		$cols = 1;

		for ($i = 1; $i <= $aSheet->getHighestRow(); $i++) {  
		    $item = array();
		    for ($j = 0; $j < $cols; $j++) {
		        $val = $aSheet->getCellByColumnAndRow($j, $i)->getCalculatedValue()."";

	        	// Этот кусок кода ограничивает матрицу по столбцам смотря на первую строку.
				// Если в первой строке 3 ячейки заполенных, 
				// то и во всех остальных он будет смотреть только по первым трем ячейкам.
		        if( !($val === "" && $i == 1) && $j < $cols ){
					array_push($item, ($val === "")?NULL:trim($val) );
					if( $i == 1 ) $cols++;
				}
		    }

		    // Если мы в переменной передаем массив отсортированных наименований столбцов
			// то происходит сортировка столбцов по этому массиву
			if(is_array($rows)) {
				$tmp = array();
				foreach ($rows as $key => $value) {
					if($value!="no-id") {
						if( $i == 1 ){
							array_push($tmp,$titles[intval($value)]["NAME"]);
						}else{
							array_push($tmp,$item[$key]);
						}
					}
				}
				$item=$tmp;
			}

			// Если нам нужна только первая строка
			if($rows === 1) return $item;

			array_push($array, $item);
		}
		return $array;
	} 


}
