<?php

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property string $id
 * @property string $from_user_id
 * @property string $to_user_id
 * @property string $category_id
 * @property string $date
 * @property string $order_id
 * @property string $result_id
 * @property string $other
 */
class Note extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('from_user_id, to_user_id', 'required'),
			array('from_user_id, to_user_id, category_id, order_id, result_id', 'length', 'max'=>10),
			array('other', 'length', 'max'=>1000),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from_user_id, to_user_id, category_id, date, order_id, result_id, other', 'safe', 'on'=>'search'),
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
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'from_user' => array(self::BELONGS_TO, 'User', 'from_user_id'),
			'to_user' => array(self::BELONGS_TO, 'User', 'to_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from_user_id' => 'Назначил',
			'to_user_id' => 'Исполнитель',
			'category_id' => 'Категория',
			'date' => 'Дата',
			'order_id' => 'Номер заказа',
			'result_id' => 'Результат',
			'other' => 'Комментарий',
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
		$criteria->compare('from_user_id',$this->from_user_id,true);
		$criteria->compare('to_user_id',$this->to_user_id,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('result_id',$this->result_id,true);
		$criteria->compare('other',$this->other,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Note the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
