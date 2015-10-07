<?php

/**
 * This is the model class for table "cube".
 *
 * The followings are the available columns in table 'cube':
 * @property string $id
 * @property string $name
 * @property string $attribute_id_1
 * @property string $attribute_id_2
 * @property string $attribute_id_3
 * @property string $rule_code
 */
class Cube extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cube';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, attribute_id_1, attribute_id_2, attribute_id_3', 'required'),
			array('name, rule_code', 'length', 'max'=>255),
			array('attribute_id_1, attribute_id_2, attribute_id_3', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, attribute_id_1, attribute_id_2, attribute_id_3, rule_code', 'safe', 'on'=>'search'),
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
			'attribute_1' => array(self::BELONGS_TO, 'Attribute', 'attribute_id_1'),
			'attribute_2' => array(self::BELONGS_TO, 'Attribute', 'attribute_id_2'),
			'attribute_3' => array(self::BELONGS_TO, 'Attribute', 'attribute_id_3'),
			'values' => array(self::HAS_MANY, 'CubeVariant', 'cube_id'),
			'rule' => array(self::BELONGS_TO, 'Rule', 'rule_code'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'attribute_id_1' => 'Ось X',
			'attribute_id_2' => 'Ось Y',
			'attribute_id_3' => 'Ось разбиения',
			'rule_code' => 'Доступ',
		);
	}

	public function beforeSave(){
		parent::beforeSave();
		$this->rule_code = ( !isset($this->rule_code) )?Yii::app()->params['defaultRule']:$this->rule_code;
		return true;
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
		$criteria->compare('attribute_id_1',$this->attribute_id_1,true);
		$criteria->compare('attribute_id_2',$this->attribute_id_2,true);
		$criteria->compare('attribute_id_3',$this->attribute_id_3,true);
		$criteria->compare('rule_code',$this->rule_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cube the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
