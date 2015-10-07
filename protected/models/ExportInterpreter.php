<?php

/**
 * This is the model class for table "export_interpreter".
 *
 * The followings are the available columns in table 'export_interpreter':
 * @property string $export_id
 * @property string $interpreter_id
 * @property integer $sort
 */
class ExportInterpreter extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'export_interpreter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('export_id, interpreter_id, sort', 'required'),
			array('sort', 'numerical', 'integerOnly'=>true),
			array('export_id, interpreter_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('export_id, interpreter_id, sort', 'safe', 'on'=>'search'),
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
			'export' => array(self::BELONGS_TO, 'Export', 'export_id'),
			'interpreter' => array(self::BELONGS_TO, 'Interpreter', 'interpreter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'export_id' => 'Export',
			'interpreter_id' => 'Interpreter',
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

		$criteria->compare('export_id',$this->export_id,true);
		$criteria->compare('interpreter_id',$this->interpreter_id,true);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExportInterpreter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
