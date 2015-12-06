<?php

/**
 * This is the model class for table "place".
 *
 * The followings are the available columns in table 'place':
 * @property string $id
 * @property string $category_id
 * @property string $good_type_id
 */
class Place extends CActiveRecord
{
	public $cities = array(
		58 => array(
			"PLACE" => 2047,
			"TYPE" => 869
		),
		59 => array(
			"PLACE" => 2047,
			"TYPE" => 868
		),
		60 => array(
			"PLACE" => 2048,
			"TYPE" => 869
		),
		61 => array(
			"PLACE" => 2047,
			"TYPE" => 2129
		),
	);

	public $categories = array(
		2048 => "AVITO",
		2047 => "DROM",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'place';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id, good_type_id', 'required'),
			array('category_id, good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, category_id, good_type_id', 'safe', 'on'=>'search'),
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
			'adverts' => array(self::HAS_MANY, 'Advert', 'good_id'),
			'interpreters' => array(self::HAS_MANY, 'PlaceInterpreter', 'place_id'),
			'goodType' => array(self::BELONGS_TO, 'GoodType', 'good_type_id'),
			'category' => array(self::BELONGS_TO, 'Variant', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category_id' => 'Ресурс',
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('good_type_id',$this->good_type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getInters($category_id,$good_type_id,$unique = false)
	{
		$model = Place::model()->with("interpreters.interpreter")->find("t.category_id=$category_id AND t.good_type_id=$good_type_id");

		if( $model ){
			$fields = array();

			if( isset($model->interpreters) && count($model->interpreters) )
				foreach ($model->interpreters as $inter){
					if( !$unique || $inter->interpreter->unique )
						$fields[$inter->code] = $inter->interpreter_id;
				}
			return $fields;
		}

		return NULL;
	}

	public function getValues($inters, $advert, $dynObjects = NULL){
		if( $inters === NULL ){
			return Log::error("Пустой inters");
		}

		$out = $inters;
		foreach ($out as $code => $inter_id)
			$out[$code] = Interpreter::generate($inter_id, $advert->good, $dynObjects, $advert->id);

		return $out;
	}

	public function beforeDelete(){
  		foreach ($this->interpreters as $key => $inter) {
  			$inter->delete();
  		}
  		return parent::beforeDelete();
 	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Place the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
