<?php

/**
 * This is the model class for table "good".
 *
 * The followings are the available columns in table 'good':
 * @property string $id
 * @property string $code
 * @property string $good_type_id
 */
class GoodFilter extends CActiveRecord
{
	private $without = false;
	public $fields_assoc = array();
	public $count_all_adverts = NULL;
	public $count_url_adverts = NULL;

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
			array('code', 'length', 'max'=>255),
			array('good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, good_type_id', 'safe', 'on'=>'search'),
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
			'fields' => array(self::HAS_MANY, 'GoodAttributeFilter', 'good_id'),
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

	public function updateAdverts(){
		$newModel = Good::model()->with(array("fields.variant","fields.attribute"))->findByPk($this->id);		

		if( !function_exists("compare") ){
			function compare($a,$b){ 
				$cities = Place::model()->cities;

				if(!isset($a->city_id)) $aplace = $cities[$a->attribute_id];
				if(!isset($b->city_id)) $bplace = $cities[$b->attribute_id];
				$a = ((isset($a->city_id))?($a->city_id."_".$a->place->category_id."_".$a->type_id):($a->variant_id."_".$aplace["PLACE"]."_".$aplace["TYPE"])); 
				$b = ((isset($b->city_id))?($b->city_id."_".$b->place->category_id."_".$b->type_id):($b->variant_id."_".$bplace["PLACE"]."_".$bplace["TYPE"])); 
				// echo $a." ".$b."<br>";
				return $a < $b ? -1 : ( $a > $b ? 1 : 0 ); 
			};
		}

		$isDiffAdverts = $this->isDiff($newModel, true);
		$isDiff = $this->isDiff($newModel);

		if( ($isDiff || $isDiffAdverts) ){
			$cities = Place::model()->cities;
			$places = $this->getPlaces();
			$add_arr = array();
			$update_arr = array();
			$delete_arr = array();
			$new_items = array();
			$adverts_without_queue = $this->filterAdverts($this->adverts,array("add","update","delete"));
			$adverts_without_delete = $this->filterAdverts($this->adverts,array("delete"));
			$adverts_with_add = array_udiff($this->adverts, $this->filterAdverts($this->adverts,array("add")), "compare");
			$adverts_with_delete = array_udiff($this->adverts, $adverts_without_delete, "compare");
			$adverts_with_update = array_udiff($this->adverts, $this->filterAdverts($this->adverts,array("update")), "compare");

			foreach ($cities as $attr_id => $city){
				if( isset($newModel->fields_assoc[$attr_id."-d"]) ){
					if( is_array($newModel->fields_assoc[$attr_id."-d"]) ){
						foreach ($newModel->fields_assoc[$attr_id."-d"] as $key => $item)
							array_push($new_items, $item);
					}else{
						array_push($new_items, $newModel->fields_assoc[$attr_id."-d"]);
					}
				}

			}

			if( $isDiff ){
				// Добавление в очередь действий на редактирование объявлений (если у этого объявления есть в очереди действие на редактирование или добавление, то не добавится действие в очередь)
				$update_arr = array_uintersect($adverts_without_queue, $new_items, "compare");
				Queue::addAll($update_arr,"update");
			}

			if( $isDiffAdverts ){
				// Удаление из очереди действий на добавление объявлений, которые нужно удалить
				$delete_add_arr = array_udiff($adverts_with_add, $new_items, "compare");
				// Log::debug('$delete_add_arr = '.count($delete_add_arr));
				Queue::delAll($delete_add_arr,"add");
				Advert::delAll($delete_add_arr);

				// Удаление из очереди действий на удаление объявлений, которые нужно оставить (тут же происходит добавление в очеред действия на добавление или редактирование)
				$delete_delete_arr = array_uintersect($adverts_with_delete, $new_items, "compare");
				// Log::debug('$delete_delete_arr = '.count($delete_delete_arr));
				Queue::delAll($delete_delete_arr,"delete");

				// Удаление из очереди действий на редактирование объявлений, которые нужно удалить
				$delete_update_arr = array_udiff($adverts_with_update, $new_items, "compare");
				// Log::debug('$delete_update_arr = '.count($delete_update_arr));
				Queue::delAll($delete_update_arr,"update");

				// Добавление в очередь действий на удаление объявлений (если у этого объявления есть в очереди действие на удаление, то не добавится действие в очередь)
				$delete_arr = array_udiff($adverts_without_delete, $new_items, $delete_add_arr, "compare");
				// Log::debug('$delete_arr = '.count($delete_arr));
				Queue::addAll($delete_arr,"delete");

				$add = array_udiff($new_items, $this->adverts, "compare");
				foreach ($add as $key => $item)
					array_push($add_arr, array("good_id"=>$this->id,"place_id"=>$places[$this->good_type_id][$cities[$item->attribute_id]["PLACE"]]->id,"city_id"=>$item->variant_id,"type_id"=>$cities[$item->attribute_id]["TYPE"]));


				$new_adverts = Advert::addAll($add_arr);
				if( $new_adverts )
					Queue::addAll($new_adverts,"add");
			}
		}
		// die();
	}

	public function	filterAdverts($adverts, $actions){
		foreach ($adverts as $i => $advert) {
			if( isset($advert->queue) )
				foreach ($advert->queue as $key => $queue)
					if( in_array($queue->action->code, $actions) && $queue->state_id != 2 ){
						unset($adverts[$i]);
						break;
					}
		}
		return $adverts;
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
		if( !$dynamic ){
			// Log::debug(print_r($newModel->fields_assoc, true));
			// Log::debug(print_r($this->fields_assoc, true));
		}

		// Log::debug(count($newModel->fields_assoc)." ".count($this->fields_assoc));
		// if( count($newModel->fields_assoc) != count($this->fields_assoc) ) return true;

		if( $this->compareModels($this,$newModel,$dynamic) ) return true;
		if( $this->compareModels($newModel,$this,$dynamic) ) return true;

		return false;
	}

	public function compareModels($model1, $model2, $dynamic){
		foreach ($model1->fields as $key => $value) {
			if( ($value->attribute->dynamic && !$dynamic) || !$value->attribute->dynamic && $dynamic ){
				continue;
			}

			$key = (($value->attribute->dynamic)?($value->attribute_id."-d"):$value->attribute_id);
			$value = $model1->fields_assoc[$key];

			if( isset($model2->fields_assoc[$key]) ){
				if( is_array($model2->fields_assoc[$key]) || is_array($value) ){
					if( !is_array($model2->fields_assoc[$key]) || !is_array($value) ){
						Log::debug("10");
						return true;
					}
					// Log::debug(count(array_udiff($model2->fields_assoc[$key], $value, function ($a,$b){return $a->value == $b->value ? 1 : -1;}))." ".count(array_udiff($value, $model2->fields_assoc[$key], function ($a,$b){return $a->value > $b->value ? 1 : -1;})));
					if( isset($value->attribute) && $value->attribute->dynamic ){
						if( count(array_udiff($model2->fields_assoc[$key], $value, function ($a,$b){return $a->value == $b->value ? 1 : -1;})) || count(array_udiff($value, $model2->fields_assoc[$key], function ($a,$b){return $a->value > $b->value ? 1 : -1;})) ){
							Log::debug("20 ".$key);
							return true;
						}
					}else{
						if( count(array_udiff($model2->fields_assoc[$key], $value, function ($a,$b){return $a->value > $b->value ? 1 : (($a->value == $b->value)?0:-1);})) || count(array_udiff($value, $model2->fields_assoc[$key], function ($a,$b){return $a->value > $b->value ? 1 : (($a->value == $b->value)?0:-1);})) ){
							Log::debug("20 ".$key);
							return true;
						}
					}
				}else{
					if( $model2->fields_assoc[$key]->value != $value->value ){
						Log::debug("30");
						return true;
					}
				}
			}else{
				Log::debug("40 ".$value->attribute->id." ".(($value->value != "" || $dynamic)?"true":"false") );
				return ($value->value != "" || $value->value != "0" || $dynamic);
			}
		}
		return false;
	}

	public function getImages($count = NULL, $sizes = NULL, $good = NULL, $get_default = true,$extra = false){

		$default_sizes = array(
			"small" => "320",
			"big" => "640"
		);

		if( $sizes != NULL )
			foreach ($default_sizes as $i => $size)
				if( !in_array($i, $sizes) ) 
					unset($default_sizes[$i]);

		$sizes = $default_sizes;

		if( $good === NULL ){
			$good = array("code" => $this->fields_assoc[3]->value, "good_type_id" => $this->good_type_id);
		}else{
			if( is_object($good) ){
				$good = array("code" => $good->fields_assoc[3]->value, "good_type_id" => $good->good_type_id);
			}
		}
		$images = Controller::getImages($good, $count, $get_default);
		if($extra) {
			$images = Controller::getImages($good, $count, $get_default,true);
		}
		if( count($images) == 1 ){
			if( strpos($images[0], "default.jpg") ){
				foreach ($sizes as $i => $size)
					$sizes[$i] = $images[0];

				return array($sizes);
			}
		}
		$values = array();
		foreach ($images as $i => $image) {
			$name = ($i<10?"0":"").$i;

			$image = array(
				"class" => $good["code"]."#".$good["good_type_id"],
				"hash" => filesize(substr($image,1)),
				"path" => substr($image,1)
			);
			foreach ($sizes as $key => $size) {
				$image["name"] = $name."_".$size;
				array_push($values, $image);
			}
		}

		$values = Cache::model()->check($values);

		$update = array();
		foreach ($values as $i => $value) {
			$size = intval(array_pop(explode("_", $value["name"])));
			$new_link = Good::cropImage($value["path"], $size);
			if( $new_link )
				array_push($update, array($value["class"], $value["name"], "/".$new_link, $value["hash"]));
		}

		Controller::updateRows(Cache::tableName(), $update, array("value", "hash"));

		$values = Cache::get($good["code"]."#".$good["good_type_id"]);

		$delete = array();
		foreach ($values as $i => $value) {
			$arr = explode("_", $value["name"]);
			$key = intval($arr[0]);

			if( $images[$key] !== NULL ){
				if( !is_array($images[$key]) ) 
					$images[$key] = array("original" => $images[$key]);

				$images[$key][array_search(intval($arr[1]), $sizes)] = $value["value"];
			}else{
				array_push($delete, "'".$value["value"]."'");
				if( file_exists(substr($value["value"], 1)) )
					unlink(substr($value["value"], 1));
			}
		}

		if( count($delete) )
			Cache::model()->deleteAll("value IN (".implode(",", $delete).")");
		return $images;
	}

	public function cropImage($original, $width){
		$arr = explode("/", $original);
		if(strpos($original,"extra")) {
			$name_arr = explode(".", $arr[5]);
			$new_path = $arr[0]."/cache/".$arr[2]."/".$arr[3]."/".$arr[4]."/".$name_arr[0]."_".$width.".".strtolower($name_arr[1]);

			$dir = $arr[0]."/cache/".$arr[2]."/".$arr[3]."/".$arr[4]; 
		} else {
			$name_arr = explode(".", $arr[4]);
			$new_path = $arr[0]."/cache/".$arr[2]."/".$arr[3]."/".$name_arr[0]."_".$width.".".strtolower($name_arr[1]);

			$dir = $arr[0]."/cache/".$arr[2]."/".$arr[3]; 
		}
        if (!is_dir($dir)){
        	mkdir($dir, 0777, true);
        }

        $resizeObj = new Resize($original);
        $resizeObj -> resizeImage($width, $width);
        $resizeObj -> saveImage($new_path, 80);

        return $new_path;
	}

	public function isChecked(){
		if(!isset($_SESSION)) session_start();

		return ( is_array($_SESSION["goods"]) && is_array($_SESSION["goods"][$this->good_type_id]) && isset($this->id, $_SESSION["goods"][$this->good_type_id][$this->id]) );
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
}
