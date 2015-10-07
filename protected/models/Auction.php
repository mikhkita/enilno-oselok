<?php

/**
 * This is the model class for table "auction".
 *
 * The followings are the available columns in table 'auction':
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $date
 * @property integer $state
 * @property string $image
 * @property integer $price
 * @property integer $current_price
 * @property integer $archive
 */
class Auction extends CActiveRecord
{	
	public $states = array(
		0 => "В очереди",
		1 => "Обрабатывается",
		2 => "Ставка поставлена",
		3 => "Торги завершены",
		4 => "Ставка не состоялась",
		5 => "Цена больше нашей",
		6 => "Лот выигран",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'auction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, name, date, image, price, current_price', 'required'),
			array('state, price, current_price, archive', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>100),
			array('name', 'length', 'max'=>1000),
			array('image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name, date, state, image, price, current_price, archive', 'safe', 'on'=>'search'),
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
			'code' => 'Номер лота',
			'name' => 'Заголовок',
			'date' => 'Дата окончания',
			'state' => 'Состояние',
			'image' => 'Изображение',
			'price' => 'Цена выкупа',
			'current_price' => 'Текущая цена',
			'archive' => 'Архивирован',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('current_price',$this->current_price);
		$criteria->compare('archive',$this->archive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Auction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
