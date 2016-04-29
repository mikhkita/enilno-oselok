<?php

/**
 * This is the model class for table "good_attribute".
 *
 * The followings are the available columns in table 'good_attribute':
 * @property string $id
 * @property string $good_id
 * @property string $attribute_id
 * @property integer $int_value
 * @property string $varchar_value
 * @property string $text_value
 * @property double $float_value
 * @property string $variant_id
 */
class GoodAttribute extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'good_attribute';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_id, attribute_id', 'required'),
			array('int_value', 'numerical', 'integerOnly'=>true),
			array('float_value', 'numerical'),
			array('good_id, attribute_id, variant_id', 'length', 'max'=>10),
			array('varchar_value', 'length', 'max'=>255),
			array('text_value', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, good_id, attribute_id, int_value, varchar_value, text_value, float_value, variant_id', 'safe', 'on'=>'search'),
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
			'good_filter' => array(self::BELONGS_TO, 'GoodFilter', 'good_id'),
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
			'id' => 'ID',
			'good_id' => 'Товар',
			'attribute_id' => 'Атрибут',
			'int_value' => 'Числовое значение',
			'varchar_value' => 'Фраза',
			'text_value' => 'Текстовое значение',
			'float_value' => 'Дробное значение',
			'variant_id' => 'Вариант из списка',
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
		$criteria->compare('good_id',$this->good_id,true);
		$criteria->compare('attribute_id',$this->attribute_id,true);
		$criteria->compare('int_value',$this->int_value);
		$criteria->compare('varchar_value',$this->varchar_value,true);
		$criteria->compare('text_value',$this->text_value,true);
		$criteria->compare('float_value',$this->float_value);
		$criteria->compare('variant_id',$this->variant_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoodAttribute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function afterFind()
	{
		parent::afterFind();

		if( $this->attributes["variant_id"] === NULL ){
			$val = ($this->attributes["int_value"] === NULL)?( ($this->attributes["float_value"] === NULL)?( ($this->attributes["varchar_value"] === NULL)?($this->attributes["text_value"]):($this->attributes["varchar_value"]) ):($this->attributes["float_value"]) ):($this->attributes["int_value"]);
		
			$this->setAttribute("value",($val !== NULL)?$val:false,true);
		}else{
			$this->setAttribute("value",($this->variant->value !== NULL)?$this->variant->value:false,true);
		}
	}

	public function getCodebyId($good_ids){
		$attribute = Attribute::model()->with("type")->find("t.id=3");

		$criteria = new CDbCriteria();
		$criteria->condition = "attribute_id=3";
		$criteria->addInCondition('good_id',$good_ids); 
		$temp = array();
		$model = GoodAttribute::model()->findAll($criteria);
		foreach($model as $good) {
			$temp[$good->good_id] = $good[$attribute->type->code."_value"];
		}
		return $temp;
	}
}
