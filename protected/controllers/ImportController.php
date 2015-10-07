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
		$this->scripts[] = "import";

		if(isset($_POST["excel_path"]) && isset($_POST["excel"]) && isset($_POST["GoodTypeId"])) {
			$model = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($_POST["GoodTypeId"]);
			$sorted_titles = $_POST["excel"];// Массив соответствующих "ID атрибута" каждому столбцу

			$titles = array();

			// Получаем массив заголовков с их вариантами
			foreach ($model->fields as $key => $field) {
				$variants = NULL;

				if( $field->attribute->list ){
					$variants = array();
					foreach ($field->attribute->variants as $i => $variant) {
						$variants[mb_strtolower($variant->value,'UTF-8')] = true;
					}
				}
            	$titles[intval($field->attribute->id)] = array(
            		"NAME" => $field->attribute->name,
            		"TYPE" => $field->attribute->type->code,
            		"VARIANTS" => $variants
            	);
            }	
            // Получаем матрицу считанного экселя в отсортированном по столбцами виде
			$xls = $this->getXLS($_POST["excel_path"],$sorted_titles,$titles);

			// Генерация структурированного ассоциативного массива для вью.
			$arResult = $this->getArResult($xls, $model->goods, $sorted_titles, $titles);

			// print_r($arResult);
			// die();
			$this->render('adminStep3',array(
				'arResult'=>$arResult
			));
		}
	}

	public function getArResult($xls, $goods, $sorted_titles, $titles){
		$all_goods = array();
		$exist_codes = array();
		$arResult = array(
			"TITLES"=>NULL,
			"ROWS" => array(),
		);

		// Составление массива кодов элементов для проверки на наличие элемента из экселя в БД
		foreach ($goods as $key => $good) {
			$fields = array();
			$code = NULL;

			foreach ($good->fields as $field) {
				$fieldId = $field->attribute->id;
				if( !isset($fields[$fieldId]) ) $fields[$fieldId] = array();
				$fields[$fieldId][] = $field->value;

				if( $field->attribute->id == $this->codeId ) $code = $field->value;
			}
			$all_goods[$code] = array("ID" => $good->id, "FIELDS" => $fields);
		}

		$arResult["TITLES"] = $xls[0];

		$sorted_titles = array_values($sorted_titles);

		for($i = 1; $i < count($xls); $i++) {
			$code = $xls[$i][array_search($this->codeId, $sorted_titles)];
			$isset = isset($all_goods[$code]);

			// Кладем в каждую ячейку матрицы массив данных об этой ячейке вида:
			// array("ID" => "ID атрибута", "VALUE" => "Значение этого атрибута из экселя", "HIGHLIGHT" => "Тип подсветки ячейки");
        	foreach ($xls[$i] as $j => $cell) {
        		$id = $sorted_titles[$j]; // ID атрибута, в который будет вставляться значение
        		$field = ($isset)?( (isset($all_goods[$code]["FIELDS"][$id]))?($all_goods[$code]["FIELDS"][$id]):false ):false;
        		$cellValueAndHighlight = $this->getCellValueAndHighlight($cell,$titles[$id]["TYPE"],$field,$titles[$id]["VARIANTS"]);

        		$xls[$i][$j] = array(
        			"ID" => $id,
        			"VALUE" => $cellValueAndHighlight["VALUE"],
        			"HIGHLIGHT" => $cellValueAndHighlight["HIGHLIGHT"]
        		);
        	}

        	$arResult["ROWS"][] = array(
        		// Если уже есть элемент с таким кодом, то выделяем всю строку
				"HIGHLIGHT" => ($isset)?"exist":NULL,
				"COLS" => $xls[$i],
				"ID" => ($isset)?$all_goods[$code]["ID"]:NULL
			);
        }
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
				if( $item != "" && $item != NULL ){
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

		if( $highlight == NULL && is_array($variants) ){
			foreach ($value as $i => $item) {
				if( !isset($variants[mb_strtolower($item,'UTF-8')]) ) $highlight = "new-variant";
				// if( count(preg_grep("/". str_replace("/", "\/", preg_quote($item))."/i" , $variants )) < 1 ) $highlight = "new-variant";
				// print_r($item);
				// echo "<br>";
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
		$result = "error";
		$message = "";

		if( isset($_POST["IMPORT"]["GOODTYPEID"]) ){
			if( isset($_POST["IMPORT"]["ITEMS"]) ){
				$model = GoodType::model()->findByPk($_POST["IMPORT"]["GOODTYPEID"]);
				$import = $_POST["IMPORT"];
				$titles = array();
				$newFields = array();
				$AttributeVariantTableName = AttributeVariant::tableName();
				$GoodAttributeTableName = GoodAttribute::tableName();

				$addVariants = array();
				$addFields = array();
				$goodCode;

				// Получаем массив заголовков с их вариантами
				$titles = $this->getTitlesWithVariants($model->fields);

		        // Добавляем варианты новые варианты атрибутов, если таковые присутствуют.
		        $sql = "INSERT INTO `$AttributeVariantTableName` (`attribute_id`,`int_value`,`varchar_value`,`float_value`,`sort`) VALUES ";
		        foreach ($import["ITEMS"] as $i => $fields){
		        	foreach ($fields as $key => $value){
		        		if( intval($key) == $this->codeId ) $goodCode = $value;
		        		$title = $titles[intval($key)];
		        		$newFields[] = $key;
		        		if( is_array($title["VARIANTS"]) ){
		        			$variants = $title["VARIANTS"];
		        			if( !isset($variants[mb_strtolower($value,'UTF-8')]) )
								$addVariants[] = "('".$key."',".(($title["TYPE"] == "int")?("'".mysql_real_escape_string($value)."'"):"NULL").",".(($title["TYPE"] == "varchar")?("'".mysql_real_escape_string($value)."'"):"NULL").",".(($title["TYPE"] == "float")?("'".mysql_real_escape_string($value)."'"):"NULL").",'1000')";
		        		}
		        	}
		        }
		        if( count($addVariants) > 0 ){
			        $sql .= implode(",", $addVariants);
					Yii::app()->db->createCommand($sql)->execute();

					// Получаем обновленный массив заголовков с их вариантами
					$model = GoodType::model()->findByPk($_POST["IMPORT"]["GOODTYPEID"]);
					$titles = $this->getTitlesWithVariants($model->fields);
				}

				// Если есть ID товара, то удаляем атрибуты, которые будем перезаписывать
				if( isset($import["ID"]) ){
					$id = $import["ID"];
					$good = Good::model()->findByPk($import["ID"]);
					$pks = array();

					foreach ($good->fields as $field) {
						if( in_array($field->attribute_id, $newFields) )
							$pks[] = $field->id;
					}
					if( count($pks) > 0 )
						GoodAttribute::model()->deleteByPk($pks);

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

				// Добавляем атрибуты к товару
		        $sql = "INSERT INTO `$GoodAttributeTableName` (`good_id`,`attribute_id`,`int_value`,`varchar_value`,`text_value`,`float_value`,`variant_id`) VALUES ";
		        foreach ($import["ITEMS"] as $i => $fields){
		        	foreach ($fields as $key => $value){
		        		if( trim($value) != "-" ){
			        		$title = $titles[intval($key)];

			        		$val = array("int"=>"NULL","varchar"=>"NULL","text"=>"NULL","float"=>"NULL");

			        		if( !is_array($title["VARIANTS"]) ){
			        			$val[$title["TYPE"]] = "'".mysql_real_escape_string($value)."'";
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

			if( $field->attribute->list ){
				$variants = array();
				foreach ($field->attribute->variants as $i => $variant) {
					$variants[mb_strtolower($variant->value,'UTF-8')] = $variant->id;
				}
			}
        	$titles[intval($field->attribute->id)] = array(
        		"TYPE" => $field->attribute->type->code,
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
