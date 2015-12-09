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
				'actions'=>array('adminIndex','adminTest','updatePrices','updateAuctionLinks','adminCreate','adminUpdate','adminDelete','adminEdit','getAttrType','getAttr','adminAdverts','adminUpdateImages',"adminAddCheckbox","adminRemoveCheckbox","adminAddAllCheckbox","adminRemoveAllCheckbox",'adminUpdateAll','adminAddSomeCheckbox','adminUpdateAdverts','adminViewSettings','adminSold','adminArchive'),
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

	public function actionAdminArchive($id = NULL, $good_type_id) 
	{
		$goodType = GoodType::model()->with("fields")->findByPk($good_type_id);
		if($id) {
			$model = $this->loadModel($id);
			$model->archive = 0;
			$model->date = NULL;
			$model->save();
		}
		$goods = Good::model()->findAll("good_type_id=".$good_type_id." AND archive=1");
		$options = array(
			'data'=>$goods,
			'name'=>$goodType->name
		);

		$this->render('adminArchive',$options);

	}

	public function actionAdminSold($id,$good_type_id)
	{
		$model = $this->loadModel($id);
		$model->archive = 1;
		$model->date = date("Y-m-d H:i:s", time());
		$model->save();
		GoodAttribute::model()->deleteAll('good_id='.$id.' AND attribute_id IN (58,59,60,61)');

		
		$model->updateAdverts();

		$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true)) );
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

			// Good::updatePrices(array($model->id));

			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true)) );

		}else{

			$this->renderPartial('adminCreate',array(
				'model'=>$model,
				'result' => $result,
				'cities' => $this->cityGroup()
			));
		}

	}

	public function actionAdminUpdate($id,$good_type_id)
	{
		$model = $this->loadModel($id);
		$result = $this->getAttr($model);
		if(isset($_POST['Good_attr']))
		{
			GoodAttribute::model()->deleteAll('good_id='.$id);
			$values = array();
			foreach ($_POST['Good_attr'] as $attr_id => $value) {
				if(!is_array($value) || isset($value['single']) ) {
					// if(!is_array($value) && $value == ""){
					// 	continue;
					// }
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
			// Log::debug(print_r($values, true));
			$this->insertValues(GoodAttribute::tableName(),$values);

			$model->updateAdverts();

			// Good::updatePrices(array($id));

			Good::updateAuctionLinks();

			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true,'GoodFilter_page' => $_GET["GoodFilter_page"])) );

		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'result' => $result,
				'cities' => $this->cityGroup()
			));
		}
	}

	public function actionAdminUpdateAll($good_type_id)
	{
		$good_ids = Good::getCheckboxes($good_type_id);
		$good_ids_key = array();
		if( !count($good_ids) ) return false;

		foreach ($good_ids as $key => $value) {
			$tmp = $key;
			array_push($good_ids_key, $key);
		}

		$model = $this->loadModel($tmp);
		$result = NULL;
		
		if(isset($_POST['Good_attr']))
		{
			$goods = Good::model()->filter(
				array(
					"good_type_id"=>$good_type_id,
				),
				$good_ids_key
			)->getPage(
				array(
			    	'pageSize'=>10000,
			    )
			);
			$goods = $goods["items"];

			$attrs_id = array();
			foreach ($_POST['Good_attr'] as $attr_id => $val) {
				array_push($attrs_id, "attribute_id=".$attr_id);
			}
			// $goods_id = array();
			// foreach ($good_ids as $key => $good_id) {
			// 	array_push($goods_id, "good_id='".$key."'");
			// }	

			if( isset($_POST['Good_attr']) ){
				$values = array();
				foreach ($good_ids as $key => $id) {
					$delete_variants = array();
					foreach ($_POST['Good_attr'] as $attr_id => $value) {
						if( isset($value[0]) && $value[0] == "-" ) {
							GoodAttribute::model()->deleteAll("good_id=".$key." AND (".implode(" OR ", $attrs_id).")");
							continue;
						}
						if(!empty($value))
							foreach ($value as $variant) {
								$values[] = array("good_id"=>$key,"attribute_id"=>$attr_id,"int_value"=>NULL,"varchar_value"=>NULL,"float_value"=>NULL,"text_value"=>NULL,"variant_id"=>$variant);
								array_push($delete_variants, $variant);
							}
					}
					GoodAttribute::model()->deleteAll("good_id=".$key." AND (".implode(" OR ", $attrs_id).") AND variant_id IN (".implode(",", $delete_variants).")");
				}
				$this->insertValues(GoodAttribute::tableName(),$values);
			}

			foreach ($goods as $i => $good) {
				$good->updateAdverts();
			}

			// list($queryCount, $queryTime) = Yii::app()->db->getStats();
			// echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
			$this->redirect( Yii::app()->createUrl('good/adminindex',array('good_type_id'=>$good_type_id,'partial'=>true,'GoodFilter_page' => $_GET["GoodFilter_page"])) );

		}else{
			$this->renderPartial('adminUpdateAll',array(
				'model'=>$model,
				'result' => $result,
				'cities' => $this->cityGroup()
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

	public function actionAdminDelete($id,$shop = false)
	{
		$this->loadModel($id)->delete();
		if($shop) echo "1"; else $this->actionAdminIndex(true);
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

	public function actionAdminIndex($partial = false, $good_type_id = false,$sort_field = NULL,$sort_type = "ASC")
	{
		unset($_GET["partial"]);

		if( isset($_GET["delete"]) ){
			$this->loadModel($_GET["delete"])->delete();
			unset($_GET["delete"]);
		}

		if( isset($_GET["deleteAdvert"]) ){
			Advert::model()->findByPk($_GET["deleteAdvert"])->delete();
			unset($_GET["deleteAdvert"]);
		}			

		if( isset($_GET["result"]) && $_GET["result"] == "false" ){
			return true;
		}

		$goodType = GoodType::model()->with("fields")->findByPk($good_type_id);

		$attr_arr = 'filter';
		$int_attr_arr = "int";
		$params = array(
			1 => array(
					"FILTER" => array(43,26,23,27,16),
					"FILTER_NAMES" => array(43=>41),
					"SORT" => array(3,20),
				),
			2 => array(
					"FILTER" => array(43,26,27,70),
					"FILTER_NAMES" => array(43=>41),
					"SORT" => array(3,20),
				),
			3 => array(
					"FILTER" => array(43,26,23,27,16),
					"FILTER_NAMES" => array(43=>41),
					"SORT" => array(3,20),
				),
		);
		$sort_fields = $this->getLabels($params[$good_type_id]["SORT"]);
		$attributes = $this->getFilterVariants($params[$good_type_id]["FILTER"],$params[$good_type_id]["FILTER_NAMES"],$good_type_id);
		$labels = $this->getLabels($params[$good_type_id]["FILTER"]);

		// if(!isset($_SESSION)) session_start();
		// if( !isset($_POST["sort"]) ){
		// 	if( isset($_SESSION["POST"][$good_type_id]) ){
		// 		$_POST = $_SESSION["POST"][$good_type_id];
		// 	}else{
		// 		$_POST["sort"] = array("field"=>20,"type"=>"ASC");
		// 		$_POST["filter"] = array();
		// 	}
		// }else{
		// 	$_SESSION["POST"][$good_type_id] = $_POST;
		// }

		if( !$partial ){
			$this->layout='admin';
		}

		$sort = array();
		if($sort_field) {		
			$sort['field'] = $sort_field;
			$sort['type'] = $sort_type;
			$sort_type = ($sort_type == "ASC") ? "DESC" : "ASC";
			$this->setUserParam("good_sort_".$good_type_id,$sort);
		} elseif($this->getUserParam("good_sort_".$good_type_id) ) {
			$temp = $this->getUserParam("good_sort_".$good_type_id);
			$sort['field'] = $temp->field;
			$sort['type'] = $temp->type;
			$sort_type = ($sort['type'] == "ASC") ? "DESC" : "ASC";
		}

		if( $good_type_id ){
			unset($_GET["id"]);

			if( isset( $_POST[$attr_arr] ) ){
				$filter_values = $_POST[$attr_arr];
				$this->setUserParam("good_filter_".$good_type_id,$filter_values);
			} else {
				$filter_values = $this->getUserParam("good_filter_".$good_type_id) ? $this->getUserParam("good_filter_".$good_type_id) : array();
			}

			$goods = Good::model()->filter(
				array(
					"good_type_id"=>$good_type_id,
					"attributes"=>$filter_values,
					"int_attributes"=>isset( $_POST[$int_attr_arr] )?$_POST[$int_attr_arr]:array()
				)
			)->sort( 
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
		

		$options = array(
			'data'=>$goods["items"],
			'fields' => $fields,
			'name'=>$goodType->name,
			'pages' => $goods["pages"],
			'attributes' => $attributes,
			'labels' => $labels,
			'arr_name' => $attr_arr,
			'filter_values' => $filter_values,
			'good_count' => $goods["count"],
			'sort_fields' => $sort_fields,
			'codes' => $codes,
			'sort_field' => $sort['field'],
			'sort_type' => $sort_type
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
		$criteria->with = array("good_filter"=>array("select"=>"good_type_id"));
		$criteria->condition = "good_filter.good_type_id = ".$good_type_id;
	    $criteria->addInCondition("t.attribute_id",$array);
	    $criteria->group = "t.variant_id";

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
