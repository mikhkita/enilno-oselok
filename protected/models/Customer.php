<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $city
 * @property integer $tk_id
 * @property integer $tk_pay_id
 * @property integer $tk_price
 * @property string $order_number
 * @property integer $price
 * @property integer $referrer_id
 * @property string $photo
 * @property integer $state_id
 * @property string $good_id
 */
class Customer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone', 'required'),
			array('tk_id, tk_pay_id, tk_price, price, referrer_id, state_id', 'numerical', 'integerOnly'=>true),
			array('name, photo', 'length', 'max'=>255),
			array('phone, order_number', 'length', 'max'=>25),
			array('city', 'length', 'max'=>100),
			array('good_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, phone, city, tk_id, tk_pay_id, tk_price, order_number, price, referrer_id, photo, state_id, good_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'phone' => 'Phone',
			'city' => 'City',
			'tk_id' => 'Tk',
			'tk_pay_id' => 'Tk Pay',
			'tk_price' => 'Tk Price',
			'order_number' => 'Order Number',
			'price' => 'Price',
			'referrer_id' => 'Referrer',
			'photo' => 'Photo',
			'state_id' => 'State',
			'good_id' => 'Good',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('tk_id',$this->tk_id);
		$criteria->compare('tk_pay_id',$this->tk_pay_id);
		$criteria->compare('tk_price',$this->tk_price);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('referrer_id',$this->referrer_id);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('good_id',$this->good_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
