<?php

/**
 * This is the model class for table "cache".
 *
 * The followings are the available columns in table 'cache':
 * @property string $class
 * @property string $name
 * @property string $value
 * @property string $hash
 */
class Cache extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cache';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, value, hash', 'required'),
			array('class, hash', 'length', 'max'=>32),
			array('name, value', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('class, name, value, hash', 'safe', 'on'=>'search'),
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
			'class' => 'Class',
			'name' => 'Name',
			'value' => 'Value',
			'hash' => 'Hash',
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

		$criteria->compare('class',$this->class,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('hash',$this->hash,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function check($values){
		$queries = array();
		foreach ($values as $i => $value)
			array_push($queries, "(class='".$value["class"]."' AND name='".$value["name"]."' AND hash='".$value["hash"]."')");

		$cache = Yii::app()->db->createCommand()
		    ->select("class, name")
		    ->from(Cache::tableName().' t')
		    ->where(implode(" OR ", $queries))
		    ->queryAll();

		foreach ($cache as $i => $item)
			$cache[$i] = $item["class"]."_".$item["name"];

		foreach ($values as $i => $value)
			if( in_array($value["class"]."_".$value["name"], $cache) )
				unset($values[$i]);

		return $values;
	}

	public function get($class){
		return Yii::app()->db->createCommand()
		    ->select("name, value")
		    ->order("name ASC")
		    ->from(Cache::tableName().' t')
		    ->where("class='$class'")
		    ->queryAll();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cache the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
