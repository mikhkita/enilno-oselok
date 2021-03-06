<?php

/**
 * This is the model class for table "desktop".
 *
 * The followings are the available columns in table 'desktop':
 * @property string $id
 * @property string $name
 * @property string $parent_id
 */
class Desktop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'desktop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('parent_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, parent_id', 'safe', 'on'=>'search'),
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
			'childs' => array(self::HAS_MANY, 'Desktop', 'parent_id', 'order' => "childs.name ASC"),
			'parent' => array(self::BELONGS_TO, 'Desktop', 'parent_id'),
			'tables' => array(self::HAS_MANY, 'DesktopTable', 'folder_id', 'order' => "tables.name ASC"),
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
			'parent_id' => 'Родительская папка',
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
		$criteria->compare('parent_id',$this->parent_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeDelete(){
 		foreach ($this->childs as $key => $child)
 			$child->delete();

 		foreach ($this->tables as $key => $table)
 			$table->delete();

 		return parent::beforeDelete();
 	}

 	public function getList($table_col){
 		$model = DesktopTableCol::model()->with('cells')->findByPk($table_col);
 		return $model->cells;
 	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Desktop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
