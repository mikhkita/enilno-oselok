<?php

/**
 * This is the model class for table "parse_good".
 *
 * The followings are the available columns in table 'parse_good':
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
 * @property string $seller
 */
class ParseGood extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'parse_good';
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
			array('id, title, params, price, views, amount, img, date, type, state, price_type, seller', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'params' => 'Params',
			'price' => 'Price',
			'views' => 'Views',
			'amount' => 'Amount',
			'img' => 'Img',
			'date' => 'Date',
			'type' => 'Type',
			'state' => 'State',
			'price_type' => 'Price Type',
			'seller' => 'Seller',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ParseGood the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
