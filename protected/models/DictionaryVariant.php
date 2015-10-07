<?php

/**
 * This is the model class for table "dictionary_variant".
 *
 * The followings are the available columns in table 'dictionary_variant':
 * @property string $dictionary_id
 * @property string $attribute_1
 * @property string $value
 */
class DictionaryVariant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dictionary_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dictionary_id, attribute_1, value', 'required'),
			array('dictionary_id, attribute_1', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dictionary_id, attribute_1, value', 'safe', 'on'=>'search'),
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
			'variant' => array(self::BELONGS_TO, 'AttributeVariant', 'attribute_1'),
			'dictionary' => array(self::BELONGS_TO, 'Dictionary', 'dictionary_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dictionary_id' => 'List',
			'attribute_1' => 'Attribute 1',
			'value' => 'Value',
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

		$criteria->compare('dictionary_id',$this->dictionary_id,true);
		$criteria->compare('attribute_1',$this->attribute_1,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictionaryVariant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
