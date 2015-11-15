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
 */
class Advert extends CActiveRecord
{
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
			array('good_id, place_id, type_id, city_id', 'length', 'max'=>10),
			array('url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, good_id, place_id, url, type_id, city_id', 'safe', 'on'=>'search'),
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
			'place' => array(self::BELONGS_TO, 'Place', 'place_id'),
			'city' => array(self::BELONGS_TO, 'Variant', 'city_id'),
			'type' => array(self::BELONGS_TO, 'Variant', 'type_id'),
			'queue' => array(self::HAS_MANY, 'Queue', 'advert_id'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function addAll($items = array()){
		if( count($items) ){
			Controller::insertValues(Advert::tableName(),$items);
			return Controller::getValues(Advert::model(),$items);
		}
	}

	public function delAll($items = array()){
		if( count($items) ){
			$delete_arr = array();

			foreach ($items as $item)
				array_push($delete_arr, $item->id);

			$criteria = new CDbCriteria();
    		$criteria->addInCondition("id", $delete_arr);
    		Advert::model()->deleteAll($criteria);		
    			
			return true;
		}
	}

	public function	setUrl($url = NULL){
		$this->url = $url;
		return $this->save();
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
}
