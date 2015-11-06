<?php

/**
 * This is the model class for table "desktop_table_col_variant".
 *
 * The followings are the available columns in table 'desktop_table_col_variant':
 * @property integer $id
 * @property integer $col_id
 * @property integer $int_value
 * @property string $varchar_value
 */
class DesktopTableColVariant extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'desktop_table_col_variant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('col_id, int_value, varchar_value', 'required'),
			array('col_id, int_value', 'numerical', 'integerOnly'=>true),
			array('varchar_value', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, col_id, int_value, varchar_value', 'safe', 'on'=>'search'),
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
			'col_id' => 'Col',
			'int_value' => 'Int Value',
			'varchar_value' => 'Varchar Value',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('col_id',$this->col_id);
		$criteria->compare('int_value',$this->int_value);
		$criteria->compare('varchar_value',$this->varchar_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DesktopTableColVariant the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
