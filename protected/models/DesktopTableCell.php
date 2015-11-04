<?php

/**
 * This is the model class for table "desktop_table_cell".
 *
 * The followings are the available columns in table 'desktop_table_cell':
 * @property integer $row_id
 * @property integer $col_id
 * @property integer $int_value
 * @property string $varchar_value
 * @property string $text_value
 * @property string $time_value
 * @property string $variant_id
 */
class DesktopTableCell extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'desktop_table_cell';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('row_id, col_id, time_value', 'required'),
			array('row_id, col_id, int_value', 'numerical', 'integerOnly'=>true),
			array('varchar_value', 'length', 'max'=>255),
			array('variant_id', 'length', 'max'=>10),
			array('text_value', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('row_id, col_id, int_value, varchar_value, text_value, time_value, variant_id', 'safe', 'on'=>'search'),
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
			'row' => array(self::BELONGS_TO, 'DesktopTableRow', 'row_id'),
			'col' => array(self::BELONGS_TO, 'DesktopTableCol', 'col_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'row_id' => 'Row',
			'col_id' => 'Col',
			'int_value' => 'Int Value',
			'varchar_value' => 'Varchar Value',
			'text_value' => 'Text Value',
			'time_value' => 'Time Value',
			'variant_id' => 'Variant',
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

		$criteria->compare('row_id',$this->row_id);
		$criteria->compare('col_id',$this->col_id);
		$criteria->compare('int_value',$this->int_value);
		$criteria->compare('varchar_value',$this->varchar_value,true);
		$criteria->compare('text_value',$this->text_value,true);
		$criteria->compare('time_value',$this->time_value,true);
		$criteria->compare('variant_id',$this->variant_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DesktopTableCell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
