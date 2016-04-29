<?php

/**
 * This is the model class for table "advert".
 *
 * The followings are the available columns in table 'advert':
 * @property string $id
 * @property string $good_id
 * @property string $place_id
 * @property string $url
 * @property string $type_id
 * @property string $city_id
 * @property string $title
 * @property integer $ready
 */
class Advert extends CActiveRecord
{
	static public $similar_percent = 50;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'advert';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_id, place_id, type_id, city_id', 'required'),
			array('ready', 'numerical', 'integerOnly'=>true),
			array('good_id, place_id, type_id, city_id', 'length', 'max'=>10),
			array('url', 'length', 'max'=>255),
			array('title', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, good_id, place_id, url, type_id, city_id, title, ready', 'safe', 'on'=>'search'),
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
			'good' => array(self::BELONGS_TO, 'Good', 'good_id'),
			'good_filter' => array(self::BELONGS_TO, 'GoodFilter', 'good_id'),
			'place' => array(self::BELONGS_TO, 'Place', 'place_id'),
			'city' => array(self::BELONGS_TO, 'Variant', 'city_id'),
			'type' => array(self::BELONGS_TO, 'Variant', 'type_id'),
			'queue' => array(self::HAS_MANY, 'Queue', 'advert_id'),
			'unique_fields' => array(self::HAS_MANY, 'Unique', 'advert_id'),
			'words' => array(self::HAS_MANY, 'AdvertWord', 'advert_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'good_id' => 'Товар',
			'place_id' => 'Площадка',
			'url' => 'Ссылка',
			'type_id' => 'Тип объявления',
			'city_id' => 'Город',
			'title' => 'Название',
			'ready' => 'Готовность',
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
		$criteria->compare('good_id',$this->good_id,true);
		$criteria->compare('place_id',$this->place_id,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('ready',$this->ready);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function addAll($items = array()){
		if( count($items) ){
			Controller::insertValues(Advert::tableName(),$items);
			return Controller::getValues(Advert::model(),$items,array("place"));
		}
	}

	public function delAll($items = array()){
		if( count($items) ){
			$delete_arr = array();

			foreach ($items as $item)
				array_push($delete_arr, $item->id);

			$criteria = new CDbCriteria();
    		$criteria->addInCondition("id", $delete_arr);
    		$adverts = Advert::model()->findAll($criteria);		
    		foreach ($adverts as $i => $advert)
    			$advert->delete();
    			
			return true;
		}
	}

	public function	setUrl($url = NULL){
		$this->url = $url;
		return $this->save();
	}

	public function getUrl(){
		if( $this->place->category_id == 2047 ){
			return "http://baza.drom.ru/".$this->url.".html";
		}else if( $this->place->category_id == 2048 ){
			return "http://avito.ru/".$this->url;
		}else if( $this->place->category_id == 3875 ){
			return "https://vk.com/market-118079986?w=product-118079986_".$this->url;
		}
	}

	public function replaceUnique($array){
		Unique::model()->deleteAll("advert_id=".$this->id);

		$values = array();
		foreach ($array as $interpreter_id => $value){
			$unique = new Unique();
			$unique->advert_id = $this->id;
			$unique->interpreter_id = $interpreter_id;
			$unique->value = stripslashes(trim($value));
			$unique->save();
		}
	}

	public function filter($params, $with = NULL, $select = NULL){

		$good_type_id = array();
		$criteria = new CDbCriteria();
		$options = array();
		if($select) {
			$criteria->select = $select;
			$options['pagination'] = false;
		} else {
			$options['pagination'] = array('pageSize' => 300);
			$criteria->with = $with;
		}

		if(isset($params['Place'])) {
	    	$criteria->addInCondition("place_id",$params['Place']);
	    	$model = Place::model()->findAll('id IN ('.implode(",", $params['Place']).')');
			foreach ($model as $key => $place) {
				$good_type_id[$place->goodType->id] = $place->goodType->id;
			}
		}
		if(isset($params['Codes']) && $params['Codes']) {
			$arr = explode(PHP_EOL,$params['Codes']);
			foreach ($arr as $key => $value) {
				$arr[$key] = trim($value);
			}
			$good_ids = Good::getIdbyCode($arr,$good_type_id);
			$criteria->addInCondition("good_id", $good_ids);
			if( count($good_ids) )
				$criteria->order = "field(good_id,".implode(",", array_reverse($good_ids)).") DESC, t.id DESC";
		}
		if(isset($params['Attr'][37])) {
	    	$criteria->addInCondition("type_id",$params['Attr'][37]);
	    }
		if(isset($params['Attr'][58])) {
	    	$criteria->addInCondition("city_id",$params['Attr'][58]);
	   	}
	   	if( isset($params["url"]) && $params["url"] != "" )
	   		$criteria->addCondition("url ".(($params["url"] == 1)?"IS NOT NULL":"IS NULL"), "AND");

	   	$criteria->addCondition("good_id!=0","AND");

	   	$options['criteria'] = $criteria;
		$dataProvider = new CActiveDataProvider(Advert::tableName(), $options);
		return $dataProvider;
	}

	public function filter_ids($params, $with = NULL){
		$where = array();
		$order = "id DESC";

		$good_type_id = array();
		if(isset($params['Place'])) {
	    	array_push($where, "place_id IN (".implode(",", $params['Place']).")");

	    	$model = Place::model()->findAll('id IN ('.implode(",", $params['Place']).')');
			foreach ($model as $key => $place)
				$good_type_id[$place->goodType->id] = $place->goodType->id;
		}

		if(isset($params['Codes']) && $params['Codes']) {
			$arr = explode(PHP_EOL,$params['Codes']);
			foreach ($arr as $key => $value)
				$arr[$key] = trim($value);

			$good_ids = Good::getIdbyCode($arr,$good_type_id);
			array_push($where, "good_id IN (".implode(",", $good_ids).")");
			if( count($good_ids) )
				$order = "field(good_id,".implode(",", array_reverse($good_ids)).") DESC, t.id DESC";
		}
		if(isset($params['Attr'][37])) {
	    	array_push($where, "type_id IN (".implode(",", $params['Attr'][37]).")");
	    }
		if(isset($params['Attr'][58])) {
			array_push($where, "city_id IN (".implode(",", $params['Attr'][58]).")");
	   	}

		$advert = Yii::app()->db->createCommand()
            ->select('t.id')
            ->from(Advert::tableName().' t')
            ->where((count($where)?implode(" AND ", $where):""))
            ->order($order)
            ->queryAll();

        return Controller::getIds($advert, "id");
	}

	public function findSimilar(){
		$words = Word::model()->with("adverts")->findAll("adverts.advert_id=".$this->id);
		if( !count($words) ){
			$this->title = NULL;
			$this->save();
			return array();
		}
		$similar = (count($words) <= 3)?(count($words)-2):round(count($words)*Advert::getPercent()/100);

		foreach ($words as $i => $word)
			$words[$i] = $word->id;

		$criteria=new CDbCriteria();
		$criteria->group = "advert_id";
		$criteria->with = "advert";
		$criteria->condition = "advert_id != ".$this->id." AND advert.ready=1 AND advert.place_id=".$this->place_id." AND word_id IN (".implode(",", $words).")";
		$criteria->having = "COUNT(DISTINCT word_id) > $similar";
		$model = AdvertWord::model()->findAll($criteria);
		
		$titles = array();
		if( $model )
		foreach ($model as $key => $value)
			array_push($titles, $value->advert->title);

		return $titles;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Advert the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getPercent(){
		return self::$similar_percent;
	}

	public function validateTitle($title){
		$title = preg_replace('|([0-9]+),([0-9]+)|', '$1.$2', $title);
		$title = str_replace(array("#", "''", "*", "'", "\\", ",", "+", " ,"), array("N", '"', "x", '"', "/", ", ", "+ ", ", "), $title);
		$title = trim(str_replace(array("!", "@", "$", "%", "^", "&", ";", "'", ">", "<", "?", "№", "%", ":", "[", "]", "§", "{", "}", "|", "\n", "\r"), "", $title));
		if( mb_strripos($title, ".", 0, "UTF-8") == mb_strlen($title, "UTF-8")-1 ) 
			$title = mb_substr($title, 0, -1, "UTF-8");

    	$arr = explode(" ", $title);
        while (mb_strlen($title, "UTF-8") > 50) {
            array_shift($arr);
            $title = trim(implode(" ", $arr));
        }
        return preg_replace('| +|', ' ', mb_strtoupper(mb_substr($title, 0, 1, "UTF-8"), "UTF-8").mb_substr($title, 1, NULL, "UTF-8"));
	}
	
	public function beforeDelete(){
  		foreach ($this->queue as $key => $queue)
  			$queue->delete();
  		
  		$place = Place::model()->findByPk($this->place_id);
  		$field = Place::model()->getFieldByPlaceAndType($place->category_id,$this->type_id);

  		if( $field !== NULL )
  			GoodAttribute::model()->deleteAll("good_id=".$this->good_id." AND attribute_id=$field AND variant_id=".$this->city_id);

  		$this->good_id = 0;
  		$this->type_id = 0;
  		$this->city_id = 0;

  		$this->save();

  		return false;
  		// return parent::beforeDelete();
 	}
}
