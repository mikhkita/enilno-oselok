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
				'actions'=>array('adminIndex','adminPhoto','adminCheckCode', 'adminPhotoUpdate','adminToArchive','adminChangeType','adminTest','updatePrices','updateAuctionLinks','adminCreate','adminUpdate','adminDelete','adminEdit','getAttrType','getAttr','adminAdverts','adminUpdateImages',"adminAddCheckbox","adminRemoveCheckbox","adminAddAllCheckbox","adminRemoveAllCheckbox",'adminUpdateAll','adminAddSomeCheckbox','adminUpdateAdverts','adminViewSettings','adminSold','adminArchive','adminJoin','adminDeleteAll','adminSale','adminCustomer','adminArchiveAll','adminSaleTable','adminSaleDelete'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array('adminIndex2','adminUpdateCities', 'adminDeleteAdverts'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminArchive($id = NULL, $good_type_id) 
	{
		$goodType = GoodType::model()->with("fields")->findByPk($good_type_id);
		if($id) {
			$model = $this->loadModel($id);
			$model->archive = 0;
			$model->date = NULL;
			$model->save();
			Sale::model()->deleteByPk($id);
		}
		$goods = Good::model()->with(array("fields.variant","fields.attribute","sale"))->findAll(array("condition" => "good_type_id=".$good_type_id." AND archive=1","order" => "sale.date DESC"));
		$options = array(
			'data'=>$goods,
			'name'=>$goodType->name
		);

		$this->render('adminArchive',$options);

	}

	public function actionAdminSaleTable($good_type_id = NULL,$partial = NULL) 
	{
		if($good_type_id) {
			$goodType = GoodType::model()->with("fields")->findByPk($good_type_id);
			$name = $goodType->name;
			$sale = Sale::model()->with(array("good.fields.variant","good.fields.attribute"))->findAll(array("condition" => "good_type_id=".$good_type_id,"order" => "t.date DESC"));
		} else {
			$sale = Sale::model()->with(array("good.fields.variant","good.fields.attribute"))->findAll(array("order" => "t.date DESC"));
			$name = "Общие";
		}
		$options = array(
			'data'=>$sale,
			'name'=>$name,
			'labels'=>Sale::attributeLabels()
		);
		if($partial){	
			$this->renderPartial('adminSaleTable',$options);
		} else $this->render('adminSaleTable',$options);

	}

	public function actionAdminSold($id,$good_type_id,$update = NULL)
	{
		if($_POST['Sale']) {
			if($_POST['Customer']['phone']) {
				$customer_id = Customer::addOrUpdate($_POST["Customer"]);	
				$_POST['Sale']['customer_id'] = $customer_id;
			}

			$_POST['Sale']['good_id'] = $id;
			$_POST['Sale']['date'] = date_create_from_format('d.m.Y', $_POST['Sale']['date']);

			if( Sale::add($_POST["Sale"],$id) ){
				$this->loadModel($id)->sold();
				if($update) {
					$this->actionAdminSaleTable($good_type_id,true);
				} else echo json_encode(array(
					"result" => "success",
					"action" => "delete", 
					"selector" => "#id-".$id
				));
			}		
		} else {
			if($model = Sale::model()->findByPk($id)) {				
				$model->date = date_format(date_create($model->date), 'd.m.Y');	
			} else {
				$model = new Sale;
				$model->date = date("d.m.Y", time());
			}
			
			$cities = AttributeVariant::model()->with("variant")->findAll("attribute_id=27");
	        foreach ($cities as &$item)
	        	$item = $item->value;

			$this->renderPartial('adminSale',array(
				'model'=>$model,
				'cities' => $cities
			));
		}
	}

	public function actionAdminSaleDelete($id,$good_type_id)
	{
		Sale::model()->deleteByPk($id);
		$this->actionAdminSaleTable($good_type_id,true);
	}

	public function actionAdminCustomer($phone) {
		$model = Customer::model()->find("phone='".$phone."'");
		$model = ($model) ? $model : new Customer;
		$this->renderPartial('adminCustomer',array(
			'model'=>$model
		));
	}

	public function actionAdminCreate($good_type_id)
	{
		$model = new Good;
		$model->good_type_id = $good_type_id;
		$result = array();
		
		if(isset($_POST['Good_attr']) && $model->save())
		{
			$values = array();
			foreach ($_POST['Good_attr'] as $attr_id => $value) {
				if(!is_array($value) || isset($value['single']) ) {
					$tmp = array("good_id"=>$model->id,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>NULL);
					if(!is_array($value) && $value != ""){
						$tmp[$this->getAttrType($model,$attr_id)] = $value;
						$values[] = $tmp;
					}else if(isset($value['single']) && $value['single']){
						$tmp["variant_id"] = $value['single'];
						$values[] = $tmp;
					}
				} else {
					if(!empty($value))
						foreach ($value as $variant)
							$values[] = array("good_id"=>$model->id,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>$variant);
				}
			}
			$this->insertValues(GoodAttribute::tableName(),$values);

			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true)) );

		}else{

			$this->renderPartial('adminCreate',array(
				'model'=>$model,
				'result' => $result,
				'cities' => $this->cityGroup(),
				'fields' => $model->type->fields,
				'good_type_id' => $good_type_id
			));
		}

	}

	public function actionAdminUpdate($id, $good_type_id, $attributes = NULL)
	{
		$view = array(
			2 => array(3,5,6,9,11,28,27,26,31,32,33,34,36,43),
		);
		$view_fields = array();

		$model = $this->loadModel($id);
		$result = $this->getAttr($model);
		if(isset($_POST['Good_attr']))
		{
			GoodAttribute::model()->deleteAll('good_id='.$id);
			$values = array();
			foreach ($_POST['Good_attr'] as $attr_id => $value) {
				if(!is_array($value) || isset($value['single']) ) {
					$tmp = array("good_id"=>$id,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>NULL);

					$attr_type = $this->getAttrType($model,$attr_id);
					if(!is_array($value) && $value != "" ){
						$tmp[$attr_type] = $value;
						$values[] = $tmp;
					}else if(isset($value['single']) && $value['single']){
						$tmp["variant_id"] = $value['single'];
						$values[] = $tmp;
					}
				} else {
					if(!empty($value))
						foreach ($value as $variant)
							$values[] = array("good_id"=>$id,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>$variant);
				}
			}
			$this->insertValues(GoodAttribute::tableName(),$values);

			$this->checkAdverts($model);
			$model->updateAdverts();

			Good::updateAuctionLinks();

			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true,'GoodFilter_page' => $_GET["GoodFilter_page"])) );
		}else{
			$fields = $model->type->fields;

			if( $attributes !== NULL ){
				$attributes = explode(",", $attributes);

				foreach ($fields as $i => $field) {
					if( !in_array($field->attribute_id, $attributes) ){
						if( in_array($field->attribute_id, $view[$good_type_id]) ){
							array_push($view_fields, $field);
						}
						unset($fields[$i]);
					}
				}
			}

			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'result' => $result,
				'cities' => $this->cityGroup(),
				'fields' => $fields,
				'view_fields' => $view_fields,
				'good_type_id' => $good_type_id
			));
		}
	}

	public function actionAdminDeleteAll($good_type_id){
		$good_ids = Good::getCheckboxes($good_type_id);
		$good_ids_key = array();
		if( !count($good_ids) ) return false;

		$selector = array();

		foreach ($good_ids as $key => $value) {
			array_push($selector, "#id-".$key);
			array_push($good_ids_key, $key);
		}
		$goods = Good::model()->with(array("type","fields.variant","fields.attribute"))->findAllByPk($good_ids_key);
		foreach ($goods as $key => $good) {
			$good->delete();
		}
		Good::removeAllCheckbox($good_type_id);

		echo json_encode(array(
			"result" => "success",
			"action" => "delete",
			"selector" => implode(",", $selector)
		));
	}

	public function actionAdminChangeType($id, $type){
		GoodAttribute::model()->delete("attribute_id=107 AND good_id=$id");
		$new = new GoodAttribute();
		$new->variant_id = $type;
		$new->good_id = $id;
		$new->attribute_id = 107;

		if( $new->save() ){
			echo json_encode(array(
				"result" => "success",
				"action" => "delete",
				"selector" => "#id-$id"
			));
		}else{
			echo json_encode(array(
				"result" => "error",
				"message" => "Ошибка добавления атрибута"
			));
		}
	}

	public function actionAdminDeleteAdverts($id){
		$model=Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk($id);
		if($model===null){
			echo json_encode(array("result" => "error","message" => "Не найден товар с ID = $id"));
		}else{
			$this->loadModel($id)->sold();	
			echo json_encode(array("result" => "success"));
		}
	}

	public function actionAdminArchiveAll($good_type_id){
		$good_ids = Good::getCheckboxes($good_type_id);
		$good_ids_key = array();
		if( !count($good_ids) ) return false;

		$selector = array();

		foreach ($good_ids as $key => $value) {
			array_push($selector, "#id-".$key);
			array_push($good_ids_key, $key);
		}
		Good::model()->updateAll(array("archive" => 1), "id IN (".implode(", ", $good_ids_key).")");

		$links = array();
		foreach ($good_ids_key as $i => $item) {
			array_push($links, "http://".Yii::app()->params['host'].$this->createUrl('/good/admindeleteadverts',array('id'=> $item)));
		}
		Cron::addAll($links);

		Good::removeAllCheckbox($good_type_id);

		echo json_encode(array(
			"result" => "success",
			"action" => "delete",
			"selector" => implode(",", $selector)
		));
	}

	public function actionAdminToArchive($id){
		if( GoodFilter::model()->updateByPk($id, array("archive" => "2")) ){
			echo json_encode(array(
				"result" => "success",
				"action" => "delete",
				"selector" => "#id-$id"
			));
		}else{
			echo json_encode(array(
				"result" => "error",
				"message" => "Ошибка изменения свойства archive"
			));
		}
	}

	public function	actionAdminUpdateCities($id = NULL){
		if( $id === NULL || !isset($_GET["Good_attr"]) ){
			if( $id === NULL ){
				echo json_encode(array("result" => "error","message" => "Не указан ID товара"));
			}else{
				echo json_encode(array("result" => "success"));
			}
			return true;
		}
		$Good_attr = $_GET["Good_attr"];

		$good = $this->loadModel($id);

		if( !$good ){
			echo json_encode(array("result" => "error", "message" => "Не найден товар с ID=$id"));
			return true;
		}
		
		$attrs_id = array();
		foreach ($Good_attr as $attr_id => $val)
			array_push($attrs_id, "attribute_id=".$attr_id);

		$values = array();
		$delete_variants = array();
		foreach ($Good_attr as $attr_id => $value) {
			if( array_search("-", $value) !== false )
				GoodAttribute::model()->deleteAll("good_id=".$good->id." AND (".implode(" OR ", $attrs_id).")");

			if(count($value))
				foreach ($value as $variant) {
					if( $variant == "-" ) continue;
					$values[] = array("good_id"=>$good->id,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>$variant);
					$delete_variants[] = "(good_id=".$good->id." AND attribute_id=$attr_id AND variant_id=$variant)";
				}
		}
		if( count($delete_variants) )
			GoodAttribute::model()->deleteAll(implode(" OR ", $delete_variants));
		$this->insertValues(GoodAttribute::tableName(),$values);

		$this->checkAdverts($good);
		$good->updateAdverts();

		echo json_encode(array("result" => "success"));
	}

	public function actionAdminUpdateAll($good_type_id)
	{
		$good_ids = Good::getCheckboxes($good_type_id);
		if( !count($good_ids) ) return false;
		
		if(isset($_POST['Good_attr']))
		{
			$links = array();
			foreach ($good_ids as $key => $value)
				array_push($links, "http://".Yii::app()->params['host'].$this->createUrl('/good/adminupdatecities',array('id'=> $key, 'Good_attr' => $_POST["Good_attr"])));

			Cron::addAll($links);

			echo json_encode(array("result" => "success", "action" => "updateCronCount", "count" => Cron::model()->count()));
		}else{
			$model = $this->loadModel(key($good_ids));

			$this->renderPartial('adminUpdateAll',array(
				'model'=>$model,
				'result' => NULL,
				'cities' => $this->cityGroup(),
				'fields' => $model->type->fields
			));
		}
	}

	public function actionAdminJoin()
	{
		$good_ids = Good::getCheckboxes(2);
		$good_type_attr = array();
		foreach (GoodTypeAttribute::model()->findAll("good_type_id=3") as $i => $attr)
			array_push($good_type_attr, $attr->attribute_id);

		$to_join = array();
		if( !count($good_ids) ) return true;

		foreach ($good_ids as $key => $value)
			$good_ids[$key] = array_shift(explode("-", $value));

		$goods = Good::model()
			->filter( array("good_type_id"=>2, "varchar_attributes"=>array(3 => $good_ids)) )
			->getPage( array('pageSize'=>10000) );
		$goods = $goods["items"];

		if(count($goods)){
			foreach ($goods as $i => $good) {
				$code = explode("-", $good->fields_assoc[3]->value);
				$to_join[$code[0]] = array($good);
			}

			$goods = Good::model()
				->filter( array("good_type_id"=>1, "varchar_attributes"=>array(3 => $good_ids)) )
				->getPage( array('pageSize'=>10000) );

			$goods = $goods["items"];

			if( count($goods) ){
				foreach ($goods as $i => $good) {
					$code = explode("-", $good->fields_assoc[3]->value);
					if( isset($to_join[$code[0]]) && is_array($to_join[$code[0]]) )
						array_push($to_join[$code[0]], $good);
				}
			}
		}

		foreach ($to_join as $code => $array) {
			if( count($array >= 2) && !GoodFilter::model()->with("fields")->count("fields.varchar_value='$code' AND good_type_id=3") ){
				$params = array();
				$images = array();
				foreach ($array as $i => $good) {
					foreach ($good->fields_assoc as $key => $value) {
						if( strpos($key, "-d") === false && in_array($key, $good_type_attr) ){
							if( is_array($value) ){
								$val = array();
								foreach ($value as $index => $v)
									array_push($val, $v->value);
							}else{
								$val = $value->value;
							}
							if( isset($params[$key]) ){
								if( $key != 3 && $key != 20 && $key != 35 && $key != 7 && $key != 8 && $key != 9 ){
									$params[$key] = ($params[$key]==$val)?$val:NULL;
								}elseif( $key == 20 ){
									$params[$key] = intval($params[$key])+intval($val);
								}elseif( $key == 35 ){
									$params[$key] = $params[$key]."\n".$val;
								}
							}else{
								$params[$key] = $val;
							}
						}
					}
					if( $good->good_type_id == 1 ){
						$marking = Interpreter::generate(30,$good);
						if( isset($params[99]) ){
							$params[99] = $params[99]."\n".$marking;
						}else
							$params[99] = $marking;
					}
					$images = array_merge($images, $this->getImages($good, NULL, false));
				}

				foreach ($images as $i => $image)
					$images[$i] = substr($image, 1);

				Good::addAttributes($params,3,$images);
			}
		}
		$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>3)) );
	}

	public function getAttr($model) {
		$result = array();
		foreach ($model->type->fields as $attr) {
			if($attr->attribute->multi)	$result[$attr->attribute_id] = array(); else $result[$attr->attribute_id] = "";
			foreach ($model->fields as $item) {
				if($attr->attribute_id == $item->attribute_id) {
					if($item->attribute->list) {
						if($item->attribute->multi) {
							$result[$attr->attribute_id][] = $item->variant_id;
						} else $result[$attr->attribute_id] = $item->variant_id;
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

	public function actionAdminTest($partial = false, $good_type_id = false)
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

	public function actionAdminDelete($id){
		Good::model()->findByPk($id)->delete();

		echo json_encode(array(
			"result" => "success",
			"action" => "delete",
			"selector" => "#id-".$id
		));
	}

	public function actionAdminIndex($partial = false, $good_type_id = false,$sort_field = NULL,$sort_type = "ASC",$with_photos = NULL)
	{
		$attr_arr = 'filter';
		$int_attr_arr = "int";

		if( isset($_POST["clear"]) )
			$_POST = array($attr_arr=>array(),$int_attr_arr=>array());

		unset($_GET["partial"]);

		if( isset($_GET["deleteAdvert"]) ){
			Advert::model()->findByPk($_GET["deleteAdvert"])->delete();
			unset($_GET["deleteAdvert"]);
		}			

		if( isset($_GET["result"]) && $_GET["result"] == "false" ){
			return true;
		}

		$goodType = GoodType::model()->with("fields")->findByPk($good_type_id);

		$params = array(
			1 => array(
				"FILTER" => array(43,36,26,23,27,16),
				"FILTER_NAMES" => array(43=>41),
			),
			2 => array(
				"FILTER" => array(43,36,26,27,70,66,67),
				"FILTER_NAMES" => array(43=>41),
			),
			3 => array(
				"FILTER" => array(43,36,26,23,27,16),
				"FILTER_NAMES" => array(43=>41),
			),
		);

		$attributes = $this->getFilterVariants($params[$good_type_id]["FILTER"],$params[$good_type_id]["FILTER_NAMES"],$good_type_id);
		$labels = $this->getLabels($params[$good_type_id]["FILTER"]);

		if( !$partial ){
			$this->layout='admin';
		}

		if( $with_photos !== NULL )
			$this->setUserParam("GOOD_PHOTOS_".$good_type_id,(($with_photos == "1")?true:false));
		
		$with_photos = $this->getUserParam("GOOD_PHOTOS_".$good_type_id);

		$sort = array();
		if($sort_field) {		
			$sort['field'] = $sort_field;
			$sort['type'] = $sort_type;
			$sort_type = ($sort_type == "ASC") ? "DESC" : "ASC";
			$this->setUserParam("GOOD_SORT_".$good_type_id,$sort);
		} elseif($this->getUserParam("GOOD_SORT_".$good_type_id) ) {
			$temp = $this->getUserParam("GOOD_SORT_".$good_type_id);
			$sort['field'] = $temp->field;
			$sort['type'] = $temp->type;
			$sort_type = ($sort['type'] == "ASC") ? "DESC" : "ASC";
		}

		if( $good_type_id ){
			unset($_GET["id"]);

			if(isset($_POST['filter-active'])) {
				$this->setUserParam("GOOD_FILTER_".$good_type_id,array());
				$this->setUserParam("GOOD_FILTER_NEW_".$good_type_id, NULL);
			}
			if(isset($_POST[$attr_arr])) $this->setUserParam("GOOD_FILTER_".$good_type_id,$_POST[$attr_arr]);
			if(isset($_POST[$int_attr_arr])) $this->setUserParam("GOOD_FILTER_INT_".$good_type_id,$_POST[$int_attr_arr]);
			if(isset($_POST["new_only"])) $this->setUserParam("GOOD_FILTER_NEW_".$good_type_id,$_POST["new_only"]);

			$filter_values = $this->getUserParam("GOOD_FILTER_".$good_type_id) ? $this->getUserParam("GOOD_FILTER_".$good_type_id,false,true) : array();
			$filter_values_int = $this->getUserParam("GOOD_FILTER_INT_".$good_type_id) ? $this->getUserParam("GOOD_FILTER_INT_".$good_type_id,false,true) : array();
			$filter_new_only = $this->getUserParam("GOOD_FILTER_NEW_".$good_type_id) ? $this->getUserParam("GOOD_FILTER_NEW_".$good_type_id,false) : NULL;

			foreach ($filter_values_int as $key => $value) {
				$filter_values_int[$key] = (array)$value;
			}			
			
			$filter_params = array(
				"good_type_id"=>$good_type_id,
				"attributes"=>$filter_values,
				"int_attributes"=>$filter_values_int,
			);
			if( $filter_new_only )
				$filter_params["not_contain"] = 107;

			$goods = Good::model()->filter($filter_params)->sort( 
				$sort
			)->getPage(
				array(
			    	'pageSize'=>10000,
			    ), 
			    $this->getUserParam("GOOD_TYPE_".$good_type_id),
			    true
			);
		}

		$fields = $goodType->fields;

		if( $this->getUserParam("GOOD_TYPE_".$good_type_id) ){
			$fields = GoodTypeAttribute::model()->findAll("attribute_id IN (".implode(",", $this->getUserParam("GOOD_TYPE_".$good_type_id)).") AND good_type_id=$good_type_id");
		}

		$type_variants = ( $filter_new_only )?AttributeVariant::model()->with("variant")->findAll("attribute_id=107"):NULL;

		$options = array(
			'data'=>$goods["items"],
			'fields' => $fields,
			'name'=>$goodType->name,
			'pages' => $goods["pages"],
			'attributes' => $attributes,
			'labels' => $labels,
			'arr_name' => $attr_arr,
			'arr_name_int' => $int_attr_arr,
			'filter_values' => $filter_values,
			'filter_values_int' => $filter_values_int,
			'filter_new_only' => $filter_new_only,
			'type_variants' => $type_variants,
			'good_count' => $goods["count"],
			'sort_field' => $sort['field'],
			'sort_type' => $sort_type,
			'with_photos' => $with_photos
		);

		if( !$partial ){
			$this->render('adminIndex',$options);
		}else{
			$this->renderPartial('adminIndex',$options);
		}
	}

	public function getFilterVariants($array,$array_names,$good_type_id){
		$result = array();

		foreach ($array as $value) {
			$result[$value] = array();
		}

		$criteria = new CDbCriteria();
		$criteria->with = array("good_filter"=>array("select"=>"good_type_id"),"variant"=>array("select"=>"variant.sort"));
		$criteria->condition = "good_filter.good_type_id = ".$good_type_id;
	    $criteria->addInCondition("t.attribute_id",$array);
	    $criteria->group = "t.variant_id";
	    $criteria->order = "variant.sort ASC";

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
				$list = $this->getListValue($val);
				if( isset($result[$key]["VARIANTS"]) )
					foreach ($result[$key]["VARIANTS"] as $i => &$variant) {
						$variant = $list[$i];
					}
			}
		}

		if( count($array) ){
			$fromto = Attribute::model()->findAll(array("condition"=>"list!=1 AND id IN (".implode(",", $array).")"));
			foreach ($fromto as $key => $attr)
				$result[$attr->id] = array("VIEW"=>"FROMTO");
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

	public function actionAdminIndex2($good_type_id = false){
		if( $good_type_id ){
			$GoodType = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($good_type_id);
		}

		$this->render('index',array(
			'data'=>$GoodType->goods
		));
	}

	public function actionAdminAdverts($id){
		$good = Good::model()->with(array("type"=>array("alias"=>"goodType"),"adverts.place.category","adverts.type","adverts.city"))->findByPk($id);

		$adverts = array();
		foreach ($good->adverts as $advert) {
			if( !isset($adverts[$advert->place->category->value]) ) $adverts[$advert->place->category->value] = array();
			array_push($adverts[$advert->place->category->value], $advert);
		}

		$this->renderPartial('adminAdverts',array(
			'adverts'=>$adverts,
			'labels'=>Advert::attributeLabels(),
			'good'=>$good
		));
	}

	public function actionAdminUpdateImages($id = NULL){
		if( $id ){
			$good = Good::model()->with(array("adverts.queue.action","adverts.queue.state"))->findByPk($id);
			if( $good ){
				$adverts = array();
				foreach ($good->adverts as $advert) {
					$allow = true;
					foreach ($advert->queue as $queue) {
						if( $queue->action->code == "updateImages" ) $allow = false;
					}
					if( $allow ) array_push($adverts, $advert);
				}
				Queue::addAll($adverts,"updateImages");
			}
		}
	}

	public function actionAdminPhoto($id, $partial = false){
		$good = $this->loadModel($id);

		$options = array(
			"good" => $good,
			"images" => $good->getImages(NULL, NULL, NULL, false),
			"partial" => $partial
		);

		if( $partial ){
			$this->renderPartial('adminPhoto', $options);
		}else{
			$this->render('adminPhoto', $options);
		}
	}

	public function actionAdminPhotoUpdate($id){
		$fields = Yii::app()->db->createCommand()
		    ->select('varchar_value')
		    ->from(GoodAttribute::tableName().' t')
		    ->where("t.attribute_id=3 AND t.good_id=$id")
		    ->queryAll();

		$goodType = Yii::app()->db->createCommand()
		    ->select('t.code')
		    ->from(Good::tableName().' g')
		    ->join(GoodType::tableName().' t', 'g.good_type_id=t.id')
		    ->where("g.id=$id")
		    ->queryAll();

		if( !is_array($goodType) || !isset($goodType[0]["code"]) || !is_array($fields) || !isset($fields[0]["varchar_value"]) ){
			echo json_encode(array("result" => "error", "message" => "Не найден товар с кодом $id"));
			return true;
		}

		if( isset($_POST["Delete"]) ){
			foreach ($_POST["Delete"] as $i => $image) {
				$filename = substr($image, 1);
				if( file_exists($filename) ){
					unlink($filename);
				}
			}
		}

		$code = $fields[0]["varchar_value"];
		$goodType = $goodType[0]["code"];
		$path = Yii::app()->params["imageFolder"]."/".$goodType."s/".$code."/";

		if (!is_dir($path)) mkdir($path, 0777, true);

		if( isset($_POST["Images"]) ){
			foreach ($_POST["Images"] as $i => &$image) {
				$filename = substr($image, 1);
				$image = array("ext" => array_pop(explode(".", array_pop(explode("/", substr($image, 1))))));
				$tmp = $path.$i.".".$image["ext"];
				if( file_exists($filename) ){
					if( strpos($filename, $path) !== false ){
						rename($filename, $tmp);
					}else{
						echo "string";
						copy($filename, $tmp);
					}
				}
			}
			foreach ($_POST["Images"] as $i => $image) {
				$tmp = $path.$i.".".$image["ext"];
				$new_filename = $path.$code."_".(($i < 10)?("0".strval($i)):strval($i)).".".$image["ext"];

				if( file_exists($tmp) )
					rename($tmp, $new_filename);
			}
		}
		$this->actionAdminPhoto($id, true);
	}

	public function actionAdminAddCheckbox($id = NULL){
		$goods = explode(",", $id);
		$good = $this->loadModel($goods[0]);	
		if(count($goods) > 1) {
			foreach ($goods as $key => $id) {
				$good = $this->loadModel($id);
				if($key != (count($goods)-1)) $this->displayCodes(Good::addCheckbox($good),$good->good_type_id);
			}	
		}
		echo $this->displayCodes(Good::addCheckbox($good),$good->good_type_id);
	}

	public function actionAdminCheckCode($good_type_id){
		if( !isset($_GET["Good_attr"]) || !isset($_GET["Good_attr"][3]) ) echo json_encode(array("result" => "error", "message" => "Не передан код"));
		$code = $_GET["Good_attr"][3];
		if( GoodAttribute::model()->with("good")->count("good.good_type_id=$good_type_id AND varchar_value=$code") ){
			echo json_encode(array("result" => "error", "message" => "Товар с таким кодом уже существует"));
		}else{
			echo json_encode(array("result" => "success"));
		}
	}

	public function actionAdminRemoveCheckbox($id = NULL){
		$goods = explode(",", $id);
		$good = $this->loadModel($goods[0]);	
		if(count($goods) > 1) {
			foreach ($goods as $key => $id) {
				$good = $this->loadModel($id);
				if($key != (count($goods)-1)) $this->displayCodes(Good::removeCheckbox($good),$good->good_type_id);
			}	
		}
		echo $this->displayCodes(Good::removeCheckbox($good),$good->good_type_id);
	}

	public function actionAdminAddAllCheckbox($good_type_id) {
		echo $this->displayCodes(Good::addAllCheckbox($good_type_id),$good_type_id);
	}

	public function actionAdminRemoveAllCheckbox($good_type_id) {
		echo $this->displayCodes(Good::removeAllCheckbox($good_type_id),$good_type_id);
	}

	public function actionAdminAddSomeCheckbox($good_type_id) {
		if(isset($_POST['Good']['ids']))
		{
			Good::addAllCheckbox($good_type_id,$_POST['Good']['ids']);
			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true)) );
		}else{
			$this->renderPartial('adminSomeCheckbox',array());
		}
		
	}

	
	public function displayCodes($success,$good_type_id) {
		$result = array();
		$result['result'] = "error";
		if( !isset($_SESSION['goods'][$good_type_id]) || !is_array($_SESSION['goods'][$good_type_id]) ) $_SESSION['goods'][$good_type_id] = array();
		if($success) {
			$result['result'] = "success";
			$result['codes'] = ((count($_SESSION['goods'][$good_type_id]))?("Выделено всего - ".count($_SESSION['goods'][$good_type_id]).": "):"").implode(", ",$_SESSION['goods'][$good_type_id]);			
		}
		return json_encode($result);
	}

	public function loadModel($id)
	{
		$model=Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionUpdatePrices(){
		if(Good::updatePrices()){
			header("HTTP/1.0 200 OK");
			echo "Цены успешно обновлены";
		}
	}

	public function actionUpdateAuctionLinks(){
		if(Good::updateAuctionLinks()){
			header("HTTP/1.0 200 OK");
			echo "Ссылки на объявления с торгом в Томске успешно обновлены";
		}
	}

	public function actionAdminUpdateAdverts($good_type_id = NULL, $images = NULL){
		if( $good_type_id ){
			$good_ids = Good::getCheckboxes($good_type_id);

			if( count($good_ids) ){
				$ids = array();
				foreach ($good_ids as $id => $value)
					array_push($ids, $id);

				$adverts = Advert::model()->findAll(array( "condition" => "good_id IN (".implode(",", $ids).") AND t.id NOT IN (SELECT `advert_id` FROM `".Queue::tableName()."` WHERE action_id=".( ($images)?4:2 ).")", "select" => "id" ));

				if( Queue::addAll($adverts, ($images)?"updateImages":"update" ) ){
					echo "Объявления успешно добавлены в очередь на ".( (($images))?"обновление фотографий":"редактирование" );
				}else{
					echo "Не было добавлено ни одного объявления в очередь на обновление ".( (($images))?"обновление фотографий":"редактирование" );
				}
			}else{
				echo "Не выделено ни одного товара";
			}
		}else{
			echo "Не указан тип товара";
		}
	}

	public function cityGroup() {
		$groups = Attribute::model()->with("variants")->findAll("folder=1");
		$cities = array();
		if($groups) {		
			foreach ($groups as $key => $group) {
				$temp = array();
				foreach ($group->variants as $city) {
					array_push($temp,$city->variant_id);
				}
				array_push($cities,(object)array("name"=>$group->name,"ids"=>implode(",", $temp)));
			}
		}
		return $cities;
	}

	public function actionAdminViewSettings($good_type_id = NULL){
		if( $good_type_id ){
			if( isset($_POST["view_fields"]) ){
				$this->setUserParam("GOOD_TYPE_".$good_type_id,$_POST["view_fields"]);

				$this->actionAdminIndex(true,$good_type_id);
			}else{
				$good_type = GoodType::model()->with("fields.attribute")->findByPk($good_type_id);

				$attributes = $this->splitByCols(2,CHtml::listData($good_type->fields, 'attribute_id', 'attribute.name'));

				$this->renderPartial('_viewSettings',array(
					'good_type'=>$good_type,
					'selected'=>$this->getUserParam("GOOD_TYPE_".$good_type_id),
					'attributes'=>$attributes
				));
			}
		}else{
			throw new CHttpException(404,'Не указан тип товара');
		}
	}
}
