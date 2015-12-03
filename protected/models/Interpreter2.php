<?php

/**
 * This is the model class for table "interpreter".
 *
 * The followings are the available columns in table 'interpreter':
 * @property string $id
 * @property string $name
 * @property string $template
 * @property string $good_type_id
 * @property string $rule_code
 * @property integer $width
 * @property integer $category_id
 * @property integer $service
 * @property integer $unique
 */
class Interpreter extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'interpreter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, template, good_type_id, rule_code, category_id', 'required'),
			array('width, category_id, service, unique', 'numerical', 'integerOnly'=>true),
			array('name, rule_code', 'length', 'max'=>255),
			array('template', 'length', 'max'=>2000),
			array('good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, template, good_type_id, rule_code, width, category_id, service, unique', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'template' => 'Template',
			'good_type_id' => 'Good Type',
			'rule_code' => 'Rule Code',
			'width' => 'Width',
			'category_id' => 'Category',
			'service' => 'Service',
			'unique' => 'Unique',
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
		$criteria->compare('template',$this->template,true);
		$criteria->compare('good_type_id',$this->good_type_id,true);
		$criteria->compare('rule_code',$this->rule_code,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('service',$this->service);
		$criteria->compare('unique',$this->unique);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Interpreter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
