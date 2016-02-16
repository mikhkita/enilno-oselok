<?php

/**
 * This is the model class for table "sale".
 *
 * The followings are the available columns in table 'sale':
 * @property string $good_id
 * @property integer $summ
 * @property integer $extra
 * @property string $date
 * @property integer $channel_id
 * @property string $city
 * @property string $order_number
 * @property integer $tk_id
 * @property string $comment
 * @property string $photo
 * @property string $customer_id
 */
class Sale extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sale';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_id, summ', 'required'),
			array('summ, extra, channel_id, tk_id', 'numerical', 'integerOnly'=>true),
			array('good_id', 'length', 'max'=>11),
			array('city', 'length', 'max'=>50),
			array('order_number', 'length', 'max'=>25),
			array('photo', 'length', 'max'=>255),
			array('customer_id', 'length', 'max'=>10),
			array('date, comment', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('good_id, summ, extra, date, channel_id, city, order_number, tk_id, comment, photo, customer_id', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'good_id' => 'Код товара',
			'summ' => 'Сумма',
			'extra' => 'Доп. издержки',
			'date' => 'Дата',
			'channel_id' => 'Канал',
			'city' => 'Город',
			'order_number' => 'Номер накладной',
			'tk_id' => 'ТК',
			'comment' => 'Комментарий',
			'photo' => 'Фото накладной',
			'customer_id' => 'Покупатель',
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

		$criteria->compare('good_id',$this->good_id,true);
		$criteria->compare('summ',$this->summ);
		$criteria->compare('extra',$this->extra);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('channel_id',$this->channel_id);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('order_number',$this->order_number,true);
		$criteria->compare('tk_id',$this->tk_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('customer_id',$this->customer_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function add($attributes,$id){
		if( isset($attributes["summ"]) ){
			if( !isset($attributes["date"]) )
				$attributes['date'] = time();

			$attributes['date'] = date_format($attributes['date'], 'Y-m-d H:i:s');
			if( !isset($attributes['channel_id']) || $attributes['channel_id'] == "" ) $attributes['channel_id'] = NULL;
			if( !isset($attributes['tk_id']) || $attributes['tk_id'] == "" ) $attributes['tk_id'] = NULL;

			if(!$sale = Sale::model()->findByPk($id)) $sale = new Sale();
			
			$sale->attributes = $attributes;
			return $sale->save();
		}else throw new CHttpException(404, 'Не указана сумма продажи');
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sale the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
