<?php

/**
 * This is the model class for table "attribute_variant".
 *
 * The followings are the available columns in table 'attribute_variant':
 * @property string $attribute_id
 * @property string $variant_id
 * @property integer $sort
 */
class AttributeVariant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'attribute_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attribute_id, variant_id, sort', 'required'),
			array('sort', 'numerical', 'integerOnly'=>true),
			array('attribute_id, variant_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('attribute_id, variant_id, sort', 'safe', 'on'=>'search'),
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
			'attribute' => array(self::BELONGS_TO, 'Attribute', 'attribute_id'),
			'variant' => array(self::BELONGS_TO, 'Variant', 'variant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'attribute_id' => 'Attribute',
			'variant_id' => 'Variant',
			'sort' => 'Sort',
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

		$criteria->compare('attribute_id',$this->attribute_id,true);
		$criteria->compare('variant_id',$this->variant_id,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterFind()
	{
		parent::afterFind();
		 
		// $val = ($this->attributes["int_value"] == NULL)?( ($this->attributes["float_value"] == NULL)?($this->attributes["varchar_value"]):($this->attributes["float_value"]) ):($this->attributes["int_value"]);
		
		$this->setAttribute("value",$this->variant->value,true);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AttributeVariant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
