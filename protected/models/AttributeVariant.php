<?php

/**
 * This is the model class for table "attribute_variant".
 *
 * The followings are the available columns in table 'attribute_variant':
 * @property string $id
 * @property integer $int_value
 * @property string $varchar_value
 * @property double $float_value
 * @property string $attribute_id
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
			array('attribute_id, sort', 'required'),
			array('int_value, sort', 'numerical', 'integerOnly'=>true),
			array('float_value', 'numerical'),
			array('varchar_value', 'length', 'max'=>255),
			array('attribute_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, int_value, varchar_value, float_value, attribute_id, sort', 'safe', 'on'=>'search'),
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
			'field' => array(self::HAS_MANY, 'GoodAttribute', 'variant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'int_value' => 'Int Value',
			'varchar_value' => 'Varchar Value',
			'float_value' => 'Float Value',
			'attribute_id' => 'Attribute',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('int_value',$this->int_value);
		$criteria->compare('varchar_value',$this->varchar_value,true);
		$criteria->compare('float_value',$this->float_value);
		$criteria->compare('attribute_id',$this->attribute_id,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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

	public function beforeDelete(){
 		GoodAttribute::model()->deleteAll("variant_id=".$this->id);
  		return parent::beforeDelete();
 	}

	public function afterFind()
	{
		parent::afterFind();
		 
		$val = ($this->attributes["int_value"] == NULL)?( ($this->attributes["float_value"] == NULL)?($this->attributes["varchar_value"]):($this->attributes["float_value"]) ):($this->attributes["int_value"]);
		
		$this->setAttribute("value",($val != NULL)?$val:false,true);
	}
}
