<?php

/**
 * This is the model class for table "table_variant".
 *
 * The followings are the available columns in table 'table_variant':
 * @property integer $table_id
 * @property integer $attribute_1
 * @property integer $attribute_2
 * @property string $value
 */
class TableVariant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'table_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table_id, attribute_1, attribute_2, value', 'required'),
			array('table_id, attribute_1, attribute_2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('table_id, attribute_1, attribute_2, value', 'safe', 'on'=>'search'),
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
			'table' => array(self::BELONGS_TO, 'Table', 'table_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'table_id' => 'Table',
			'attribute_1' => 'Attribute 1',
			'attribute_2' => 'Attribute 2',
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

		$criteria->compare('table_id',$this->table_id);
		$criteria->compare('attribute_1',$this->attribute_1);
		$criteria->compare('attribute_2',$this->attribute_2);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TableVariant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
