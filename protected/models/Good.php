<?php

/**
 * This is the model class for table "good".
 *
 * The followings are the available columns in table 'good':
 * @property string $id
 * @property string $code
 * @property string $good_type_id
 */
class Good extends CActiveRecord
{
	private $sortOptions = NULL;
	private $criteria = NULL;
	private $sorted = NULL;

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
			array('share', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>255),
			array('good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, good_type_id, share', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Код',
			'good_type_id' => 'Тип товара',
			'share' => 'Выкладывать',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('good_type_id',$this->good_type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

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

	public function setPrices($model,$codes){
		$tableName = GoodAttribute::tableName();
		$sql = "INSERT INTO `$tableName` (`good_id`,`attribute_id`,`int_value`) VALUES ";

		$values = array();
		foreach ($model as $good) {
			$newPrice = Interpreter::generate($codes[$good->good_type_id],$good);
			
			$values[] = "('".$good->id."','51',".( ($newPrice != "")?("'".$newPrice."'"):"NULL" ).")";
		}

		$sql .= implode(",", $values);

		Yii::app()->db->createCommand($sql)->execute();
	}

	public function filter($options = array(),$ids = NULL){

		$this->criteria = $this->filterItems($options,$ids);

    	return $this;
	}

	public function sort($options = array()){
		if( isset($options["field"]) ){
			if( !isset($options["type"]) ) $options["type"] = "ASC";

			$model = GoodFilter::model()->findAll($this->criteria);
	    	$ids = $this->getIds($model);

	    	$this->criteria = $this->sortItems($options,$ids);
	    	$this->sortOptions = $options;
	    	$this->sorted = true;
	    }

	    return $this;
	}

	public function getPage($options){
		if( !$this->sorted ){
			$model = GoodFilter::model()->findAll($this->criteria);
	    	$ids = $this->getIds($model);

	    	$this->criteria = new CDbCriteria();
	    	$this->criteria->addInCondition("t.id",$ids);
		}

		$dataProvider = new CActiveDataProvider('GoodFilter', array(
		    'criteria'=>$this->criteria,
		    'pagination'=>$options,
		));

		$goods = Good::model()->with("fields.attribute","fields.variant")->findAllByPk( $this->getIds( $dataProvider->getData() ) );

		if( $this->sortOptions != NULL ){
			$sortOptions = $this->sortOptions;
			usort($goods, function($a, $b) use($sortOptions) {
				$a = (is_array($a->fields_assoc[$sortOptions['field']])) ? $a->fields_assoc[$sortOptions['field']][0]->value : $a->fields_assoc[$sortOptions['field']]->value;
				$b = (is_array($b->fields_assoc[$sortOptions['field']])) ? $b->fields_assoc[$sortOptions['field']][0]->value : $b->fields_assoc[$sortOptions['field']]->value;
			    if ($a == $b) {
			        return 0;
			    }
			    if($sortOptions['type'] == "DESC") return ($a > $b) ? -1 : 1; else return ($a < $b) ? -1 : 1;
			});
		}
		return array( "items" => $goods, "count" => $dataProvider->getTotalItemCount(), "pages" => $dataProvider->getPagination(), "dataProvider" => $dataProvider, "allCount" => $dataProvider->getTotalItemCount() );
	}

	public function getCount(){
		return GoodFilter::model()->count($this->criteria);
	}

	public function filterItems($options,$ids = NULL){
		$criteria=new CDbCriteria();

		$criteria->select = 'id';
		$criteria->group = 'fields.good_id';
        $criteria->with = array('fields' => array('select'=> array('variant_id','attribute_id')));

        $count = 0;
        if(isset($options["attributes"]) && count($options["attributes"]))
			foreach ($options["attributes"] as $id => $attribute_vals) {		
				foreach ($attribute_vals as $variant_id) {
					$criteria->addCondition('good_type_id='.$options["good_type_id"].' AND fields.variant_id='.$variant_id,'OR');
				}
				$count++;
			}

		if(isset($options["int_attributes"]) && count($options["int_attributes"]))
			foreach ($options["int_attributes"] as $id => $attribute) {
				if( count($attribute) ){
					$criteria->addCondition('(good_type_id='.$options["good_type_id"].' AND fields.attribute_id='.$id.' '.(isset($attribute["min"])?(' AND fields.int_value>='.$attribute["min"]):'').(isset($attribute["max"])?' AND fields.int_value<='.$attribute["max"]:'').')','OR');
					$count++;
				}
			}

		// $criteria->addCondition('good_type_id='.$options["good_type_id"].' AND fields.attribute_id=3','OR');
		// $count++;

		if( $count == 0 ){
			$criteria->condition = 'good_type_id='.$options["good_type_id"].' AND fields.attribute_id=3';
			$criteria->having = 'COUNT(DISTINCT fields.attribute_id)>='.$count;
		}else{
			$criteria->having = 'COUNT(DISTINCT fields.attribute_id)='.$count;
		}

		if( $ids ) $criteria->addInCondition("fields.good_id", $ids);

    	return $criteria;
	}

	public function sortItems($options,$goods_id){
		$criteria=new CDbCriteria();	
		$criteria->together = true;

		$attribute = Attribute::model()->with("type")->findByPk($options["field"]);

		if( $attribute->list ){
			$with = array('fields' => array('select'=> array('attribute_id')),"fields.variant"=> array('select' => array('sort')));
			// $criteria->order = 'fields.attribute_id DESC';
			$criteria->order = 'variant.sort '.$options["type"];
		}else{
			$with = array('fields' => array('select'=> array('variant_id','attribute_id','varchar_value')));
			if( $attribute->type->code != "varchar" )
				array_push($with["fields"]["select"],$attribute->type->code."_value");
			$criteria->order = 'fields.'.$attribute->type->code.'_value '.$options["type"];
		}

		$criteria->select = "id";
		$criteria->with = $with;
		$criteria->together = true;
		$criteria->group = 't.id';
    	// $criteria->condition = 'fields.attribute_id='.$attribute->id.' OR fields.attribute_id=3';
    	$criteria->condition = 'fields.attribute_id='.$attribute->id;
    	// $criteria->order = 'variant.sort '.$options["type"];
        // $criteria->order = 'fields.varchar_value '.$options["type"];
	    $criteria->addInCondition("t.id",$goods_id);

		return $criteria;
	}

	public function getIds($model){
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

	public function update(){
		$newModel = Good::model()->findByPk($this->id);

		function compare($a,$b){ 
			$a = ((isset($a->city_id))?$a->city_id:$a->variant_id); 
			$b = ((isset($b->city_id))?$b->city_id:$b->variant_id); 

			return $a < $b ? -1 : ( $a > $b ? 1 : 0 ); 
		};

		$isDiffAdverts = $this->isDiff($newModel, true);
		$isDiff = $this->isDiff($newModel);

		// if($this->isDiff()){
		// 	echo "DIFF";
		// }else{
		// 	echo "NOT DIFF";
		// }
		// die();

		if( ($isDiff || $isDiffAdverts) && !$this->share ){

			$cities = Place::model()->cities;
			$places = $this->getPlaces();

			$add_arr = array();
			$update_arr = array();
			$delete_arr = array();
			$new_items = array();
			foreach ($cities as $attr_id => $city)
				$new_items = $new_items + $this->getArray(isset($newModel->fields_assoc[$attr_id."-d"])?$newModel->fields_assoc[$attr_id."-d"]:array());

			if( $isDiffAdverts ){
				$delete_arr = $delete_arr + array_udiff($this->adverts, $new_items, "compare");
				// Queue::addAll($delete_arr,"delete");
			}

			if( $isDiff ){
				$update_arr = array_udiff($this->adverts, $delete_arr, "compare");
				// Queue::addAll($update_arr,"update");
			}

			if( $isDiffAdverts ){
				$add = array_udiff($new_items, $this->adverts, "compare");

				foreach ($add as $key => $item)
					array_push($add_arr, array("good_id"=>$this->id,"place_id"=>$places[$this->good_type_id][$cities[$item->attribute_id]["PLACE"]]->id,"city_id"=>$item->variant_id,"type_id"=>$cities[$item->attribute_id]["TYPE"]));

				// $new_adverts = Advert::addAll($add_arr);
				// Queue::addAll($new_adverts,"add");
			}

			print_r($add_arr);
			echo "<br><br><br><br>";
			print_r($delete_arr);
			echo "<br><br><br><br>";
			print_r($update_arr);
			die();
			

			
			
			// 
		}
	}

	public function getPlaces(){
		$model = Place::model()->findAll();
		$out = array();
		foreach ($model as $key => $value) {
			if( !isset($out[$value->good_type_id]) ) $out[$value->good_type_id] = array();
			$out[$value->good_type_id][$value->category_id] = $value;
		}
		return $out;
	}

	public function getArray($items){
        $out = array();
        if( $items ){
            if( is_array($items) ){
                $out = $items;
            }else{
                array_push($out, $items);
            }
        }
        return $out;
    }

	public function isDiff($newModel,$dynamic = false){
		$newModel = Good::model()->findByPk($this->id);

		if( count($newModel->fields_assoc) != count($this->fields_assoc) ) return true;

		if( $this->compareModels($this,$newModel,$dynamic) ) return true;
		if( $this->compareModels($newModel,$this,$dynamic) ) return true;

		return false;
	}

	public function compareModels($model1, $model2, $dynamic){
		foreach ($model1->fields as $key => $value) {
			if( $value->attribute->dynamic && !$dynamic ) continue;
			if( !$value->attribute->dynamic && $dynamic ) continue;

			$key = (($value->attribute->dynamic)?($value->attribute_id."-d"):$value->attribute_id);
			$value = $model1->fields_assoc[$key];

			if( isset($model2->fields_assoc[$key]) ){
				if( is_array($model2->fields_assoc[$key]) || is_array($value) ){
					if( !is_array($model2->fields_assoc[$key]) || !is_array($value) ) return true;
					if( count(array_udiff($model2->fields_assoc[$key], $value, function ($a,$b){return $a->value == $b->value ? 0 : -1;})) || count(array_udiff($value, $model2->fields_assoc[$key], function ($a,$b){return $a->value == $b->value ? 0 : -1;})) ) return true;
				}else{
					if( $model2->fields_assoc[$key]->value != $value->value ) return true;
				}
			}else{
				return true;
			}
		}
		return false;
	}

	public function beforeDelete(){
  		foreach ($this->fields as $key => $value) {
  			$value->delete();
  		}
  		return parent::beforeDelete();
 	}
}
