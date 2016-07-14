<?php

/**
 * This is the model class for table "track".
 *
 * The followings are the available columns in table 'track':
 * @property string $id
 * @property string $title
 * @property string $params
 * @property string $price
 * @property string $views
 * @property integer $amount
 * @property string $img
 * @property string $date
 * @property integer $type
 * @property integer $state
 * @property integer $price_type
 * @property integer $platform
 * @property string $seller
 */
class Track extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, title, type', 'required'),
			array('amount, type, state, price_type', 'numerical', 'integerOnly'=>true),
			array('id, views', 'length', 'max'=>10),
			array('title, params, img, seller', 'length', 'max'=>255),
			array('price', 'length', 'max'=>6),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, params, price, views, amount, img, date, type, state, price_type, seller','platform', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'params' => 'Параметры',
			'price' => 'Цена',
			'views' => 'Просмотры',
			'amount' => 'Количество',
			'img' => 'Изображение',
			'date' => 'Дата',
			'type' => 'Тип',
			'state' => 'Состояние',
			'price_type' => 'Тип торгов',
			'seller' => 'Продавец',
			'platform' => "Платформа"
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('views',$this->views,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('price_type',$this->price_type);
		$criteria->compare('seller',$this->seller,true);
		$criteria->compare('platform',$this->platform);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function filter(){
		return array(
			"platform" => array("TYPE" => "CHECKBOX", "VIEW" => "CHECKBOX", "FROM" => array("1" => "Дром","2" => "Авито") ),
			"type" => array("TYPE" => "CHECKBOX", "VIEW" => "CHECKBOX", "FROM" => GoodType::tableName(), "FIELDS" => array("id","name") ),
			"amount" => array("TYPE" => "CHECKBOX", "VIEW" => "CHECKBOX", "FROM" => array("1" => 1,"2" => 2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8) ),
			"price" => array("TYPE"=>"FROMTO", "VIEW" => "FROMTO")
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Track the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
