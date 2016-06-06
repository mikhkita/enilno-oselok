<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property string $id
 * @property string $date
 * @property string $contact_id
 * @property string $channel_id
 * @property string $user_id
 * @property string $city
 * @property string $state_id
 */
class Order extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, state_id', 'required'),
			array('contact_id, channel_id, user_id, state_id', 'length', 'max'=>10),
			array('city', 'length', 'max'=>50),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, contact_id, channel_id, user_id, city, state_id', 'safe', 'on'=>'search'),
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
			'goods' => array(self::HAS_MANY, 'OrderGood', 'order_id'),
			'contact' => array(self::BELONGS_TO, 'Contact', 'contact_id'),
			'services' => array(self::HAS_MANY, 'Service', 'order_id'),
			'notes' => array(self::HAS_MANY, 'Note', 'order_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Код',
			'date' => 'Дата',
			'contact_id' => 'Контакт',
			'channel_id' => 'Канал продажи',
			'user_id' => 'Менеджер',
			'city' => 'Город',
			'state_id' => 'Состояние',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('contact_id',$this->contact_id,true);
		$criteria->compare('channel_id',$this->channel_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state_id',$this->state_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function add($attributes,$id){
			if( !isset($attributes["date"]) )
				$attributes['date'] = date('d.m.Y');

			$attributes['date'] = date_format($attributes['date'], 'Y-m-d H:i:s');
			if( !isset($attributes['channel_id']) || $attributes['channel_id'] == "" ) $attributes['channel_id'] = NULL;
			if( !isset($attributes['state_id']) || $attributes['state_id'] == "" ) $attributes['state_id'] = NULL;
			if(!$order = Order::model()->findByPk($id)) $order = new Order;
			
			$order->attributes = $attributes;
			$order->save();
			return $order->id;
		// }else throw new CHttpException(404, 'Не указана сумма продажи');
	}

	public function beforeDelete() {
		OrderGood::model()->deleteAll("order_id=".$this->id);
		return parent::beforeDelete();
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
