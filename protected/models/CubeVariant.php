<?php

/**
 * This is the model class for table "cube_variant".
 *
 * The followings are the available columns in table 'cube_variant':
 * @property string $cube_id
 * @property string $attribute_1
 * @property string $attribute_2
 * @property string $attribute_3
 * @property string $value
 */
class CubeVariant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cube_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cube_id, attribute_1, attribute_2, attribute_3, value', 'required'),
			array('cube_id, attribute_1, attribute_2, attribute_3', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cube_id, attribute_1, attribute_2, attribute_3, value', 'safe', 'on'=>'search'),
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
			'variant_1' => array(self::BELONGS_TO, 'AttributeVariant', 'attribute_1'),
			'variant_2' => array(self::BELONGS_TO, 'AttributeVariant', 'attribute_2'),
			'variant_3' => array(self::BELONGS_TO, 'AttributeVariant', 'attribute_3'),
			'cube' => array(self::BELONGS_TO, 'Cube', 'cube_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cube_id' => 'Cube',
			'attribute_1' => 'Attribute 1',
			'attribute_2' => 'Attribute 2',
			'attribute_3' => 'Attribute 3',
			'value' => 'Значение',
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

		$criteria->compare('cube_id',$this->cube_id,true);
		$criteria->compare('attribute_1',$this->attribute_1,true);
		$criteria->compare('attribute_2',$this->attribute_2,true);
		$criteria->compare('attribute_3',$this->attribute_3,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CubeVariant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
