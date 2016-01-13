<?php

/**
 * This is the model class for table "lead".
 *
 * The followings are the available columns in table 'lead':
 * @property string $id
 * @property string $name
 * @property string $first_phone
 * @property string $date
 * @property string $first_city
 * @property string $second_city
 * @property string $second_phone
 * @property integer $tk_id
 * @property integer $tk_pay_id
 * @property integer $tk_price
 * @property string $order_number
 * @property integer $price
 * @property integer $referrer_id
 * @property string $photo
 * @property integer $state_id
 */
class Lead extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lead';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_phone, state_id', 'required'),
			array('tk_id, tk_pay_id, tk_price, price, referrer_id, state_id', 'numerical', 'integerOnly'=>true),
			array('name, photo', 'length', 'max'=>255),
			array('first_phone, second_phone, order_number', 'length', 'max'=>25),
			array('first_city, second_city', 'length', 'max'=>50),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, first_phone, date, first_city, second_city, second_phone, tk_id, tk_pay_id, tk_price, order_number, price, referrer_id, photo, state_id', 'safe', 'on'=>'search'),
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
			'name' => 'Ф.И.О.',
			'first_phone' => 'Телефон клиента',
			'date' => 'Дата продажи',
			'first_city' => 'Город 1',
			'second_city' => 'Город 2',
			'second_phone' => 'Второй телефон',
			'tk_id' => 'ТК',
			'tk_pay_id' => 'Оплата ТК',
			'tk_price' => 'Стоимость доставки',
			'order_number' => 'Номер накладной',
			'price' => 'Оплачено',
			'referrer_id' => 'реферал',
			'photo' => 'Фото накладной',
			'state_id' => 'Статус',
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
		$criteria->compare('first_phone',$this->first_phone,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('first_city',$this->first_city,true);
		$criteria->compare('second_city',$this->second_city,true);
		$criteria->compare('second_phone',$this->second_phone,true);
		$criteria->compare('tk_id',$this->tk_id);
		$criteria->compare('tk_pay_id',$this->tk_pay_id);
		$criteria->compare('tk_price',$this->tk_price);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('referrer_id',$this->referrer_id);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('state_id',$this->state_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lead the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
