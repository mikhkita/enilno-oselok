<?php

/**
 * This is the model class for table "contact".
 *
 * The followings are the available columns in table 'contact':
 * @property string $id
 * @property string $name
 * @property integer $sex
 * @property string $car
 * @property string $source_id
 * @property string $city
 * @property string $client_type_id
 * @property string $create_date
 * @property string $link
 * @property string $other
 */
class Contact extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contact';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sex', 'numerical', 'integerOnly'=>true),
			array('name, city, link', 'length', 'max'=>100),
			array('car', 'length', 'max'=>50),
			array('source_id, client_type_id', 'length', 'max'=>10),
			array('other', 'length', 'max'=>1000),
			array('create_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, sex, car, source_id, city, client_type_id, create_date, link, other', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Order', 'contact_id'),
			'phones' => array(self::HAS_MANY, 'ContactPhone', 'contact_id'),
			'emails' => array(self::HAS_MANY, 'ContactEmail', 'contact_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'ФИО',
			'sex' => 'Пол',
			'car' => 'Автомобиль',
			'source_id' => 'Источник',
			'city' => 'Город',
			'client_type_id' => 'Тип клиента',
			'create_date' => 'Дата создания',
			'link' => 'Соц. сеть',
			'other' => 'Доп. информация',
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
		$criteria->compare('sex',$this->sex);
		$criteria->compare('car',$this->car,true);
		$criteria->compare('source_id',$this->source_id,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('client_type_id',$this->client_type_id,true);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('other',$this->other,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function add($attributes){
		$attributes['phone'] = str_replace(array("(",")"," ","-","+"),"", $attributes['phone']);
		$phone = ContactPhone::model()->find("phone='".$attributes['phone']."'");
		if($phone) {
			$contact = Contact::model()->findbyPk($phone->contact_id);
		} else {
			$contact = new Contact; 
			$contact->create_date = date_format(date_create(), 'Y-m-d H:i:s');
		}
		if( !isset($attributes['source_id']) || $attributes['source_id'] == "" ) $attributes['source_id'] = NULL;
		if( !isset($attributes['client_type_id']) || $attributes['client_type_id'] == "" ) $attributes['client_type_id'] = NULL;
		$contact->attributes = $attributes;
		if( $contact->save() ){	
			if(!$phone) {
				$phone = new ContactPhone;
			}
			$phone->phone = $attributes['phone'];
			$phone->contact_id = $contact->id;
			$phone->save();
			if($attributes['email']) {
				if(!$email = ContactEmail::model()->find("contact_id=".$contact->id." AND email='".$attributes['email']."'")) {
					$email = new ContactEmail;
				}
				$email->email = $attributes['email'];
				$email->contact_id = $contact->id;
				$email->save();
			}
			return $contact->id;
		}else throw new CHttpException(500, 'Не удалось создать клиента');
	}

	public function updateContact($attributes,$contact_id){
		$contact = Contact::model()->findbyPk($contact_id);
		$attributes['phone'] = str_replace(array("(",")"," ","-","+"),"", $attributes['phone']);
		if( !isset($attributes['source_id']) || $attributes['source_id'] == "" ) $attributes['source_id'] = NULL;
		if( !isset($attributes['client_type_id']) || $attributes['client_type_id'] == "" ) $attributes['client_type_id'] = NULL;
		$contact->attributes = $attributes;
		if( $contact->save() ){	
			$contactPhone = ContactPhone::model()->find('contact_id='.$contact_id);
			$contactPhone->phone = $attributes['phone'];
			$contactPhone->save();
			if($attributes['email']) {
				if(!$email = ContactEmail::model()->find("contact_id=".$contact_id." AND email='".$attributes['email']."'")) {
					$email = new ContactEmail;
				}
				$email->email = $attributes['email'];
				$email->contact_id = $contact_id;
				$email->save();
			} else {
				ContactEmail::model()->delete("contact_id=".$contact_id." AND email='".$attributes['email']."'");
			}
			return $contact->id;
		}else throw new CHttpException(500, 'Не удалось создать клиента');
	}

	public function beforeDelete() {
		ContactPhone::model()->deleteAll("contact_id=".$this->id);
		ContactEmail::model()->deleteAll("contact_id=".$this->id);
		return parent::beforeDelete();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
