<?php

/**
 * This is the model class for table "good".
 *
 * The followings are the available columns in table 'good':
 * @property string $id
 * @property string $code
 * @property string $good_type_id
 */
class Good extends GoodFilter
{
	public $ids = NULL;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'good';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_type_id', 'required'),
			array('archive', 'numerical', 'integerOnly'=>true),
			array('good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, good_type_id, archive, code', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'fields' => array(self::HAS_MANY, 'GoodAttribute', 'good_id'),
			'type' => array(self::BELONGS_TO, 'GoodType', 'good_type_id'),
			'adverts' => array(self::HAS_MANY, 'Advert', 'good_id'),
			'sale' => array(self::HAS_ONE, 'Sale', 'good_id'),
			'orders' => array(self::HAS_MANY, 'OrderGood', 'good_id'),
			'images' => array(self::HAS_MANY, 'Image', 'good_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Дата продажи',
			'good_type_id' => 'Тип товара',
			'archive' => 'Продано',
			'code' => 'Символьный код',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */

	public function updatePrices($ids = array()){
		$codes = array(
			1 => $this->getParam("OTHER","PRICE_INTERPRETER_TIRE"),
			2 => $this->getParam("OTHER","PRICE_INTERPRETER_DISC"),
			// 3 => 95,
		);

		// $start = microtime(true);
		
		if( !count($ids) ){
			GoodAttribute::model()->deleteAll("attribute_id=51");

			foreach ($codes as $key => $code) {
				$model = Good::model()->with(array("fields.attribute","fields.variant"))->findAll(array("condition"=>"good_type_id=".$key));

				Good::setPrices($model,$codes);
				Log::debug("Обновление цен ".$key);
				unset($model);
			}
		}else{
			$model = Good::model()->with(array("fields.attribute","fields.variant"))->findAllByPk($ids);

			$criteria = new CDbCriteria();
			$criteria->condition = "attribute_id=51";
	    	$criteria->addInCondition("good_id",$ids);
			GoodAttribute::model()->deleteAll($criteria);

			Good::setPrices($model,$codes);
		}

		// list($queryCount, $queryTime) = Yii::app()->db->getStats();
		// echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
		// printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);	
		return true;
	}

	public function updateAuctionLinks($ids = array()){
		$adverts = Advert::model()->with(array("place"=>array("select"=>array("id","category_id"))))->findAll("place.category_id=2047 AND type_id=2129 AND city_id=1081".( (count($ids))?" AND good_id IN (".implode(",", $ids).")":"" ));

		GoodAttribute::model()->deleteAll("attribute_id=62");

		$values = array();
		foreach ($adverts as $key => $advert) {
			if( ($advert->url != "") )
				array_push($values, array( "good_id" => $advert->good_id, "attribute_id" => 62, "varchar_value" => "http://baza.drom.ru/".$advert->url.".html" ));
		}

		return $this->insertValues(GoodAttribute::tableName(),$values);
	}

	public function setPrices($model,$codes){
		$tableName = GoodAttribute::tableName();
		$sql = "INSERT INTO `$tableName` (`good_id`,`attribute_id`,`int_value`) VALUES ";

		$dynamic = $this->getDynObjects(array(
            57 => 2047,
            38 => 1081,
            37 => 869
        ));

		$values = array();
		foreach ($model as $good) {
			$newPrice = Interpreter::generate($codes[$good->good_type_id],$good,$dynamic);
			
			$values[] = "('".$good->id."','51',".( ($newPrice != "")?("'".$newPrice."'"):"NULL" ).")";
		}

		$sql .= implode(",", $values);

		Yii::app()->db->createCommand($sql)->execute();
	}

	public function filter($options = array(),$ids = NULL,$exclude_ids = NULL){

		if(isset($options["not_contain"]) && count($options["not_contain"])){
			if( intval($options["not_contain"]) >= 0 ){
				$exclude_ids = Yii::app()->db->createCommand()
		            ->select('g.id')
		            ->from(Good::tableName().' g')
		            ->join(GoodAttribute::tableName().' a', 'a.good_id=g.id')
		            ->where("a.attribute_id=".$options["not_contain"])
		            ->queryAll();

		        foreach ($exclude_ids as $i => $value)
		        	$exclude_ids[$i] = $value["id"];
			}
		}

		$criteria=new CDbCriteria();
		$criteria->select = 't.id';
		$criteria->order = 't.id DESC';
		$criteria->group = 'fields.good_id';
        $criteria->with = array('fields' => array('select'=> array('variant_id','attribute_id')));

        $count = 0;

        if(isset($options["attributes"]) && count($options["attributes"]))
			foreach ($options["attributes"] as $id => $attribute_vals) {
				if( !is_array($attribute_vals) ) $attribute_vals = array($attribute_vals);
				foreach ($attribute_vals as $variant_id)
					$criteria->addCondition('(good_type_id='.$options["good_type_id"].' AND fields.variant_id=\''.$variant_id.'\')','OR');
				$count++;
			}

		if(isset($options["int_attributes"]) && count($options["int_attributes"]))
			foreach ($options["int_attributes"] as $id => $attribute)
				if( count($attribute) && ( (isset($attribute["min"]) && $attribute["min"] != "") || (isset($attribute["max"]) && $attribute["max"] != "") ) ){
					$criteria->addCondition('(good_type_id='.$options["good_type_id"].' AND fields.attribute_id='.$id.' '.((isset($attribute["min"]) && $attribute["min"] != "")?(' AND fields.int_value>='.$attribute["min"]):'').((isset($attribute["max"]) && $attribute["max"] != "")?' AND fields.int_value<='.$attribute["max"]:'').')','OR');
					$count++;
				}

		if(isset($options["varchar_attributes"]) && count($options["varchar_attributes"]))
			foreach ($options["varchar_attributes"] as $id => $attribute_vals){
				if( !is_array($attribute_vals) ) $attribute_vals = array($attribute_vals);
				foreach ($attribute_vals as $str)
					$criteria->addCondition('(good_type_id='.$options["good_type_id"].' AND fields.attribute_id='.$id.' AND fields.varchar_value LIKE \''.$str.'%\' )','OR');
				$count++;
			}

		if( $count == 0 )
			$criteria->condition = 'good_type_id='.$options["good_type_id"].' AND fields.attribute_id=3';

		$options["archive"] = (isset($options["archive"]) && $options["archive"] !== NULL) ? $options["archive"] : 0;
		if( !(is_string($options["archive"]) && $options["archive"] == "all") ){
			$criteria->addCondition("archive='".$options["archive"]."'", "AND");
		}
		
		
		$criteria->having = 'COUNT(DISTINCT fields.attribute_id)'.(( $count == 0 )?'>':'').'='.$count;

		if( $ids ){
			$criteria->addInCondition("fields.good_id", $ids);
			$criteria->order = "field(fields.good_id,".implode(",", array_reverse($ids)).") DESC, t.id DESC";
		}

		if( isset($exclude_ids) && is_array($exclude_ids) && count($exclude_ids) ) {
			$criteria->addCondition("t.id NOT IN (".implode(", ", $exclude_ids).")", "AND");
		}

		$this->ids = $this->getIds( GoodFilter::model()->findAll($criteria) );

		// print_r(count($this->ids));

    	return $this;
	}

	public function sort($options = array()){
		if( isset($options["field"]) ){
			if( !isset($options["type"]) ) $options["type"] = "ASC";

			if( $options["field"] == "id" ){
				if( $options["type"] == "ASC" ){
					$this->ids = array_reverse($this->ids);
				}
			}else{
				$attribute = Attribute::model()->with("type")->findByPk($options["field"]);

				if( !$attribute ){
					echo 'Не найден атрибут с кодом "'.$options["field"].'"';
					die();
				}

				$criteria=new CDbCriteria();
				$criteria->select = 't.id';
				$criteria->together = true;
				$criteria->group = 't.id';
				$criteria->condition = 'fields.attribute_id='.$attribute->id;

				if( $this->ids !== NULL )
					$criteria->addInCondition("t.id",$this->ids);

				if( $attribute->list ){
					$criteria->with = array('fields' => array('select'=> array('attribute_id')),"fields.variant"=> array('select' => array('sort')));
					$criteria->order = 'variant.sort '.$options["type"].', t.id ASC';
				}else{
					$criteria->with = array('fields' => array('select'=> array('variant_id','attribute_id',$attribute->type->code."_value")));
					$criteria->order = 'fields.'.$attribute->type->code.'_value '.$options["type"].', fields.id '.$options["type"];
				}

				$ids = $this->getIds( GoodFilter::model()->findAll($criteria) );

				$criteria=new CDbCriteria();

				if( count($ids) )
					$criteria->condition = "t.id NOT IN (".implode(",", $ids).")";

				if( $this->ids !== NULL )
					$criteria->addInCondition("t.id",$this->ids);

				$this->ids = array_merge($ids, $this->getIds( GoodFilter::model()->findAll($criteria) ) );
			}
	    }

	    return $this;
	}

	public function getPage($options, $fields = NULL, $with_adverts = false){
		if( $this->ids === NULL )
			$this->ids = $this->getIds( GoodFilter::model()->findAll(array("order"=>"id DESC")) );

		$criteria = new CDbCriteria();
	    $criteria->addInCondition("t.id", $this->ids );
	    $criteria->with = "type";
	    if( count($this->ids) )
	    	$criteria->order = "field(t.id,".implode(",", array_reverse($this->ids)).") DESC, t.id DESC";

		$dataProvider = new CActiveDataProvider('GoodFilter', array(
		    'criteria'=>$criteria,
		    'pagination'=>$options,
		));

		$data = $dataProvider->getData();
		$goods = (count($data))?$this->getGoods( $dataProvider->getData(), $fields, $with_adverts ):NULL;

		return array( "items" => $goods, "count" => $dataProvider->getTotalItemCount(), "pages" => $dataProvider->getPagination(), "dataProvider" => $dataProvider, "allCount" => $dataProvider->getTotalItemCount() );
	}

	public function getGoods($model, $fields = NULL, $with_adverts = false){
		$attributes = Yii::app()->db->createCommand()
		    ->select('*')
		    ->from(Attribute::tableName().' t')
		    ->queryAll();

		$attributes = $this->getAssocModel($attributes);

		$fields = Yii::app()->db->createCommand()
		    ->select('*')
		    ->from(GoodAttributeFilter::tableName().' t')
		    ->where('good_id IN ('.implode(",", $this->getIds($model)).')'.( ( $fields !== NULL && count($fields) )?(' AND attribute_id IN ('.implode(",", $fields).')'):("") ))
		    ->queryAll();

		$model = $this->getAssocModel($model);

		$variants = array();
		foreach ($fields as $i => $field)
			if( $field["variant_id"] !== NULL )
				array_push($variants, $field["variant_id"]);

		if( count($variants) ){
			$variants = Yii::app()->db->createCommand()
			    ->select('*')
			    ->from(Variant::tableName().' t')
			    ->where('id IN ('.implode(",", $variants).')')
			    ->queryAll();

			$variants = $this->getAssocModel($variants);
		}

		if( $with_adverts ){
			$all_adverts = Yii::app()->db->createCommand()
			    ->select(array("good_id as id", "count(*) as count"))
			    ->from(Advert::tableName().' t')
			    ->where('good_id IN ('.implode(",", $this->getIds($model)).')')
			    ->group('good_id')
			    ->queryAll();

			$all_adverts = $this->getAssocModel($all_adverts);

			$url_adverts = Yii::app()->db->createCommand()
			    ->select(array("good_id as id", "count(*) as count"))
			    ->from(Advert::tableName().' t')
			    ->where('good_id IN ('.implode(",", $this->getIds($model)).') AND url IS NOT NULL')
			    ->group('good_id')
			    ->queryAll();

			$url_adverts = $this->getAssocModel($url_adverts);

			foreach ($model as $i => $good) {
				if( isset($all_adverts[$good->id]) )
					$model[$i]->count_all_adverts = (isset($all_adverts[$good->id]))?$all_adverts[$good->id]["count"]:0;

				if( isset($url_adverts[$good->id]) )
					$model[$i]->count_url_adverts = (isset($url_adverts[$good->id]))?$url_adverts[$good->id]["count"]:0;
			}
		}

		// echo " ".count($fields);
		// echo " ".count($attributes);
		// echo " ".count($variants);

		foreach ($fields as $i => $field) {
			if( $field["variant_id"] === NULL ){
				$value = ($field["int_value"] === NULL)?( ($field["float_value"] === NULL)?( ($field["varchar_value"] === NULL)?($field["text_value"]):($field["varchar_value"]) ):($field["float_value"]) ):($field["int_value"]);
			}else
				$value = $variants[$field["variant_id"]]["value"];

			$attr_id = ( intval($attributes[$field["attribute_id"]]["dynamic"]) )?($field["attribute_id"]."-d"):$field["attribute_id"];

			$object = (object) array(
				"attribute_id" => $field["attribute_id"],
				"value" => $value,
				"attribute" => (object) $attributes[$field["attribute_id"]],
				"variant_id" => $field["variant_id"]
			);

			if( isset($model[$field["good_id"]]->fields_assoc[$attr_id]) ){

				if( !is_array($model[$field["good_id"]]->fields_assoc[$attr_id]) ){
					$model[$field["good_id"]]->fields_assoc[$attr_id] = array($model[$field["good_id"]]->fields_assoc[$attr_id]);
				}
				array_push($model[$field["good_id"]]->fields_assoc[$attr_id], $object);
			}else{
				$model[$field["good_id"]]->fields_assoc[$attr_id] = $object;
			}
		}

		return $model;
	}

	public function getAssocModel($model){
		$out = array();
		foreach ($model as $i => $item)
			$out[ (isset($item->id)?$item->id:$item["id"]) ] = $item;

		$model = NULL;
		return $out;
	}

	public function getCount(){
		return count($this->ids);
	}

	public function getIds($model = array()){
		$ids = array();

    	foreach ($model as $key => $value)
    		array_push($ids, $value->id);
    	return $ids;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Good the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function afterFind()
	{
		parent::afterFind();

		if( count($this->fields) ){
			$fields = array();

			foreach ($this->fields as $field) {
				$attr_id = ( $field->attribute->dynamic )?($field->attribute_id."-d"):$field->attribute_id;
				if( isset($fields[$attr_id]) ){
					if( !is_array($fields[$attr_id]) ){
						$fields[$attr_id] = array(0 => $fields[$attr_id]);
					}
					$fields[$attr_id][] = $field;
				}else{
					$fields[$attr_id] = $field;
				}
			}
			$this->setAttribute("fields_assoc",$fields,true);
		}
	}

	public function	getCheckboxes($good_type_id = NULL){
		if(!isset($_SESSION)) session_start();

		if( !$good_type_id ) throw new CHttpException(404,'Good::getCheckboxes() $good_type_id не задан');

		return ( isset($_SESSION["goods"])  && isset($_SESSION["goods"][$good_type_id]) )?$_SESSION["goods"][$good_type_id]:array();
	}

	public function addCheckbox($good){
		if(!isset($_SESSION)) session_start();

		if( $good ){
			if( !is_array($_SESSION["goods"]) ) $_SESSION["goods"] = array();
			if( !is_array($_SESSION["goods"][$good->good_type_id]) ) $_SESSION["goods"][$good->good_type_id] = array();
			$_SESSION["goods"][$good->good_type_id][$good->id] = $good->fields_assoc[3]->value;

			return true;
		}else{
			return false;
		}
	}
	public function addAllCheckbox($good_type_id, $codes = NULL, $many = NULL){
		if(!isset($_SESSION)) session_start();

		if( $good_type_id ){
			if( !is_array($_SESSION["goods"]) ) $_SESSION["goods"] = array();

			if( $many )
				$ids = explode(",", $many);

			if($codes) {
				$arr = explode(PHP_EOL,$codes);
				foreach ($arr as &$value) {
					$value = trim($value);
				}
				$ids = Good::getIdbyCode($arr,array($good_type_id));
			}

			$filter_values = $this->getUserParam("GOOD_FILTER_".$good_type_id) ? $this->getUserParam("GOOD_FILTER_".$good_type_id,false,true) : array();
			$filter_values_int = $this->getUserParam("GOOD_FILTER_INT_".$good_type_id) ? $this->getUserParam("GOOD_FILTER_INT_".$good_type_id,false,true) : array();

			foreach ($filter_values_int as $key => $value) {
				$filter_values_int[$key] = (array)$value;
			}		

			$goods = Good::model()->filter(
				array(
					"good_type_id"=>$good_type_id,
					"attributes"=>$filter_values,
					"int_attributes"=>$filter_values_int
				),$ids
			)->sort( 
				$this->getUserParam("good_sort_".$good_type_id) ? (array)$this->getUserParam("good_sort_".$good_type_id) : array()
			)->getPage(
				array(
			    	'pageSize'=>10000,
			    ),
			    array(3)
			);

			if( $many === NULL || !isset($_SESSION["goods"][$good_type_id]) )
				$_SESSION["goods"][$good_type_id] = array();

			if( isset($goods["items"]) )
				foreach ($goods['items'] as $key => $good)
					$_SESSION["goods"][$good->good_type_id][$good->id] = $good->fields_assoc[3]->value;

			return true;
		}else{
			return false;
		}
	}

	public function removeAllCheckbox($good_type_id, $many = NULL){
		if(!isset($_SESSION)) session_start();

		if( $good_type_id ){
			if( !is_array($_SESSION["goods"]) ) $_SESSION["goods"] = array(); 
			if( !is_array($_SESSION["goods"][$good_type_id]) ) $_SESSION["goods"][$good_type_id] = array();

			if( $many ){
				$ids = explode(",", $many);
				foreach ($ids as $i => $id)
					unset($_SESSION["goods"][$good_type_id][$id]);	
			}else{
				$_SESSION["goods"][$good_type_id] = array();
			}

			return true;
		}else{
			return false;
		}
	}

	public function removeCheckbox($good = NULL){
		if(!isset($_SESSION)) session_start();

		if( $good  ){
			if( is_array($_SESSION["goods"]) && is_array($_SESSION["goods"][$good->good_type_id]) && isset($_SESSION["goods"][$good->good_type_id][$good->id]) )
				unset($_SESSION["goods"][$good->good_type_id][$good->id]);

			return true;
		}else{
			return false;
		}
	}

	public function getIdbyCode($good_codes,$good_type_id = NULL){
		$attribute = Attribute::model()->with("type")->find("t.id=3");

		$criteria = new CDbCriteria();
		$criteria->with = array("fields");
		$criteria->condition = "fields.attribute_id=3";
		if($good_type_id && !empty($good_type_id)) $criteria->addInCondition('good_type_id',$good_type_id); 
		$criteria->addInCondition('fields.'.$attribute->type->code.'_value',$good_codes); 
		foreach ($good_codes as $key => $value)
			$good_codes[$key] = "'".$value."'";
		
		$criteria->order = "field(fields.".$attribute->type->code."_value,".implode(",", array_reverse($good_codes)).") DESC, t.id DESC";
		$temp = array();
		$model = GoodFilter::model()->findAll($criteria);
		foreach($model as $good) {
			array_push($temp, $good->id);
			// $temp[$good->fields[0][$attribute->type->code."_value"]] = $good->id;
		}
		return $temp;
	}

	public function addAttributes($params,$good_type_id,$images = NULL,$archive = 0)
	{
		$model = new Good;
		$model->archive = $archive;
		$model->good_type_id = $good_type_id;
		$model->save();
		$fields = array();
		array_push($fields,array(
			"good_id" => $model->id,
			"attribute_id" => 98,
			'int_value' => NULL,
			"varchar_value" => NULL,
			"text_value" => "",
			'float_value' => NULL,
			"variant_id" => NULL
		));

		foreach ($params as $attr_id => $value) {
			if($value!= "") {
				$attr_type = Attribute::model()->with("type")->findByPk($attr_id);
				if(is_array($value)) {
					foreach ($value as $key => $item) {
						$fields = Good::addAttribute($model->id,$attr_id,$attr_type,$item,$fields);
					}
				} else $fields = Good::addAttribute($model->id,$attr_id,$attr_type,$value,$fields);
			}
		}

		if( $images !== NULL && isset($params[3]) ){
			$good_code = $params[3];
			$type_code = GoodType::model()->findByPk($good_type_id)->code;
			$dir = Yii::app()->params["imageFolder"]."/".$type_code."s/".$good_code; 
	        if (!is_dir($dir)){
	        	mkdir($dir, 0777, true);
	        }else{
	        	$this->cleanDir($dir);
	        }
	        foreach ($images as $i => $link) {
	        	$new_id = Image::add($model->id, "jpg", 1, $i+1);
				copy( $link, $dir."/".$new_id.".jpg");
			}
		}
		$this->insertValues(GoodAttribute::tableName(),$fields);
		return $model->id;
	}

	public function addAttribute($good_id,$attr_id,$attr_type,$value,$fields) {

		$temp = array(
			"good_id" => $good_id,
			"attribute_id" => $attr_id,
			'int_value' => NULL,
			"varchar_value" => NULL,
			"text_value" => NULL,
			'float_value' => NULL,
			"variant_id" => NULL
		);
		if($attr_type->list) {
			if($attr_id == 27) {
				$model = DictionaryVariant::model()->find("dictionary_id=117 AND value='".addslashes($value)."'");
			} else $model = Attribute::model()->with('variants.variant')->find("attribute_id=".$attr_id." AND value='".addslashes($value)."'");
			if($model) {
				$temp["variant_id"] = ($attr_id == 27) ? $model->attribute_1 : $model->variants[0]->variant_id; 
				array_push($fields, $temp);
			} else {
				$allowed = ( Yii::app()->params["site"] == "shikon" )?array(6, 16, 17, 31, 32, 9):array(6, 16, 17);
				if( in_array($attr_id, $allowed) ) {
					$variant = new Variant;
					$variant->value = $value;
					$variant->sort = 999999;
					if($variant->save()) {
						$attribute_variant = new AttributeVariant;
						$attribute_variant->attribute_id = $attr_id;
						$attribute_variant->variant_id = $variant->id;
						if($attribute_variant->save()) {
							$temp["variant_id"] = $variant->id;
							array_push($fields, $temp);
						}
					}
				} else {
					$model = Attribute::model()->findbyPk($attr_id);
					$fields[0]["text_value"].= $model->name.": ".$value."\n\r";
				}
				
			}
		} else {
			if($attr_id != 98) {
				$temp[$attr_type->type->code."_value"] = $value; 
				array_push($fields, $temp);
			} else $fields[0]["text_value"].= $value."\n\r";
		}		

		return $fields;
	}

	public function createFromAuction($auction){
		$diametres = array(
			13 => "1317",
			14 => "1318",
			15 => "1319",
			16 => "1320",
			17 => "1321",
			18 => "1322",
			19 => "1323",
			20 => "1324",
		);
		$result = Injapan::getFieldsToCreate($auction->code);

		$good_code = Good::getNewAuctionCode($result["category"]);

		$this->downloadImages($result["images"],$good_code,3);

		$model = new Good;
		$model->good_type_id = 3;
		$model->save();

		$fields = array( 
			array(
				"good_id" => $model->id,
				"attribute_id" => 3,
				"varchar_value" => $good_code,
				"text_value" => NULL,
				"variant_id" => NULL,
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 97,
				"varchar_value" => date("d-m-Y", time()),
				"text_value" => NULL,
				"variant_id" => NULL,
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 96,
				"varchar_value" => NULL,
				"text_value" => addslashes($result["text"]),
				"variant_id" => NULL,
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 20,
				"varchar_value" => $result["price"],
				"text_value" => NULL,
				"variant_id" => NULL,
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 9,
				"varchar_value" => NULL,
				"text_value" => NULL,
				"variant_id" => $diametres[intval($result["category"])],
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 68,
				"text_value" => NULL,
				"varchar_value" => $result["seller"],
				"variant_id" => NULL,
			),
			array(
				"good_id" => $model->id,
				"attribute_id" => 69,
				"text_value" => NULL,
				"varchar_value" => $auction->code,
				"variant_id" => NULL,
			),
		);

		// print_r($fields);
		// die();

		$this->insertValues(GoodAttribute::tableName(),$fields);

		// print_r($result);
	}

	public function sold($archive = true,$type = 1){
		$this->archive = $type;
		// if($type == 1) {
		// 	OrderGood::model()->deleteAll("good_id=".$this->id);
		// }
		$this->date = date_format(date_create(), 'Y-m-d H:i:s');
		$code = $this->fields_assoc[3]->value;
		if($type == 1 && iconv_strlen($code) > 4 && stripos($code, "-") === false) {
			$goodType = GoodType::getCode($this->good_type_id);
			$cache = Yii::app()->params["cacheFolder"]."/".$goodType."/".$code;
			$imgs = Yii::app()->params["imageFolder"]."/".$goodType."/".$code;
			Controller::removeDirectory($cache);
			Controller::removeDirectory($imgs);
			$images = Image::model()->with("caps","cache")->findAll("good_id=".$this->id);
			foreach ($images as $key => $image) {
				$image->delete();
			}
			
		}
		if($archive) {
			if($this->good_type_id != 3) {
				$code = array_shift(explode("-", $code));
				if($model = Good::model()->with("fields")->find("good_type_id=3 AND archive=0 AND fields.attribute_id=3 AND fields.varchar_value='".$code."'"))	
					$model->sold(false);
			} else {
				if($model = Good::model()->with("fields")->find("good_type_id=2 AND archive=0 AND fields.attribute_id=3 AND fields.varchar_value='".$code."'"))	
					$model->sold(false);

				$code = array(
					$code,
					$code."-1",
					$code."-2",
					$code."-3",
					$code."-4",
					$code."-5"
				);

				foreach ($code as $i => $value)
					$code[$i] = "'".$value."'";

				if($model = Good::model()->with("fields")->findAll("good_type_id=1 AND archive=0 AND fields.attribute_id=3 AND fields.varchar_value IN (".implode(",",$code).")" ))
					foreach ($model as $value) 
						$value->sold(false);
							
			}
		}
		GoodAttribute::model()->deleteAll('good_id='.$this->id.' AND attribute_id IN (58,59,60,61)');
		$this->updateAdverts();
		return $this->save();
	}

	public function toTempArchive(){
		$this->date = date_format(date_create(), 'Y-m-d H:i:s');
		$this->archive = 2;
		return $this->save();
	}

	public function soldAllTemp(){
		for( $i = 1; $i <= 3; $i++ ){
			$ids = Good::getSoldIds($i);
			if( $ids ){
				$goods = Good::model()->filter(
		            array(
		                "good_type_id"=>$i
		            ),
		            $ids
		        )->getPage(
		            array(
		                'pageSize'=>10000,
		            )
		        );
		        $goods = $goods["items"];

		        foreach ($goods as $index => $good) {
		        	$good->sold(true, 1);
		        }
			}
		}
	}

	public function getSoldIds($good_type_id){
		$ids = GoodFilter::model()->findAll("good_type_id=$good_type_id AND archive=2 AND date < '".date("d.m.Y", time()-24*60*60)."'");
		if( $ids )
			return Controller::getIds($ids);
		return NULL;
	}

	public function getNewAuctionCode($diametr){
		$auction_codes = explode("\n", $this->getParam("OTHER","AUCTION_CODES"));

		foreach ($auction_codes as $i => $code) {
			if( substr(strval($diametr), -1, 1) == substr($code, 0, 1) ){
				unset($auction_codes[$i]);
				$this->setParam("OTHER","AUCTION_CODES",implode("\n", $auction_codes));
				return trim($code);
			}
		}
		return "Нет кода";
	}

	public function beforeDelete(){
  		$images = Image::model()->findAll("good_id=".$this->id);
  		if( $images )
	  		foreach ($images as $key => $value)
	  			$value->delete();

  		Task::model()->deleteAll("good_id=".$this->id);

  		Controller::removeDirectory(Yii::app()->params["imageFolder"]."/".Controller::getTypeCode($this->good_type_id)."/".$this->fields_assoc[3]->value);
  		Controller::removeDirectory(Yii::app()->params["cacheFolder"]."/".Controller::getTypeCode($this->good_type_id)."/".$this->fields_assoc[3]->value);

  		foreach ($this->fields as $key => $value) {
  			$value->delete();
  		}
  		
  		foreach ($this->adverts as $key => $value) {
  			$value->delete();
  		}

  		return parent::beforeDelete();
 	}
}
