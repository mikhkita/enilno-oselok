<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property string $id
 * @property string $title
 * @property integer $category_id
 * @property string $data
 */
class Task extends CActiveRecord
{
	public $params = array(
		1 => array(
			"necessary" => array(16,17,9,8,7,28,43),
			"price" => array(20),
		),
		2 => array(
			"necessary" => array(9,6,28,43),
			"price" => array(20),
		),
		3 => array(
			"necessary" => array(16,17,9,8,7,28,6,43),
			"price" => array(20),
		)
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, category_id, data', 'required'),
			array('category_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>1000),
			array('data', 'length', 'max'=>10000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, category_id, data', 'safe', 'on'=>'search'),
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
			'title' => 'Заголовок',
			'category_id' => 'Категория',
			'data' => 'Данные',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function testGood($good){
		$params = $this->params[$good->good_type_id];

		// Проверка фотографий
		if( !$this->checkPhoto($good) ){
			echo "Добавить задание: добавить фотографии<br>";
		}else{
			echo "Удалить задание: добавить фотографии<br>";
		}

		// Проверка первичных атрибутов
		$not_exist = $this->checkFields($good, $params["necessary"]);
		$necessary_exist = count($not_exist)?false:true;
		if( !$necessary_exist ){
			echo "Добавить задание: заполнить недостающие первичные атрибуты<br>";
			print_r($not_exist);
		}else{
			echo "Удалить задание: заполнить недостающие первичные атрибуты<br>";
		}

		// Проверка цены
		$not_exist = $this->checkFields($good, $params["price"]);
		$price_exist = count($not_exist)?false:true;
		if( !$price_exist ){
			if( $necessary_exist ){
				echo "Добавить задание: заполнить цену<br>";
			}
		}else{
			echo "Удалить задание: заполнить цену<br>";
		}

		$required = $this->getRequired($good->good_type_id);

		// Проверка обязательных атрибутов
		$not_exist = $this->checkFields($good, $required);
		if( count($not_exist) ){
			if( $necessary_exist && $price_exist ) {
				echo "Добавить задание: заполнить обязательные параметры<br>";
				print_r($not_exist);
			}
		}else{
			echo "Удалить задание: заполнить обязательные параметры<br>";
		}
	}

	public function checkFields($good, $fields){
		$not_exist = array();
		foreach ($fields as $i => $attr_id)
			if( !isset($good->fields_assoc[$attr_id]) || $good->fields_assoc[$attr_id]->value === NULL )
				array_push($not_exist, $attr_id);

		return $not_exist;
	}

	public function checkPhoto($good){
		return count(Controller::getImages($good, NULL, false))?true:false;
	}

	public function getRequired($good_type_id){
		$model = Yii::app()->db->createCommand()
            ->select('a.id')
            ->from(Attribute::tableName().' a')
            ->join(GoodTypeAttribute::tableName().' t', 'a.id=t.attribute_id')
            ->where("a.required=1 AND t.good_type_id=$good_type_id")
            ->order("t.sort ASC")
            ->queryAll();

        return Controller::getIds($model, "id");
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
