<?php

/**
 * This is the model class for table "desktop_table_col".
 *
 * The followings are the available columns in table 'desktop_table_col':
 * @property string $id
 * @property string $name
 * @property string $table_id
 * @property integer $type_id
 * @property integer $list
 * @property integer $required
 */
class DesktopTableCol extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'desktop_table_col';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, table_id, type_id', 'required'),
			array('type_id, list, required', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('table_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, table_id, type_id, list, required', 'safe', 'on'=>'search'),
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
			'table' => array(self::BELONGS_TO, 'DesktopTable', 'table_id'),
			'cells' => array(self::HAS_MANY, 'DesktopTableCell', 'col_id'),
			'type' => array(self::BELONGS_TO, 'DesktopTableColType', 'type_id'),
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
			'table_id' => 'Table',
			'type_id' => 'Type',
			'list' => 'List',
			'required' => 'Required',
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
		$criteria->compare('table_id',$this->table_id,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('list',$this->list);
		$criteria->compare('required',$this->required);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DesktopTableCol the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
