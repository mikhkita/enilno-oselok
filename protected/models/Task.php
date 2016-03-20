<?php

/**
 * This is the model class for table "task".
 *
 * The followings are the available columns in table 'task':
 * @property string $id
 * @property string $good_id
 * @property string $data
 * @property integer $action_id
 */
class Task extends CActiveRecord
{
	static public $params = array(
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

	static public $actions = array(
		"photo" => array(
			"id" => 1,
			"user_id" => 1,
		),
		"necessary" => array(
			"id" => 2,
			"user_id" => 1,
		),
		"price" => array(
			"id" => 3,
			"user_id" => 1,
		),
		"required" => array(
			"id" => 4,
			"user_id" => 1,
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
			array('good_id, user_id, action_id', 'required'),
			array('action_id', 'numerical', 'integerOnly'=>true),
			array('good_id, user_id', 'length', 'max'=>10),
			array('data', 'length', 'max'=>10000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, good_id, data, action_id, user_id', 'safe', 'on'=>'search'),
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
			'action' => array(self::BELONGS_TO, 'TaskAction', 'action_id'),
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
			'data' => 'Данные',
			'action_id' => 'Действие',
			'user_id' => 'Пользователь',
		);
	}

	public function filter($user_id){
		$model = Yii::app()->db->createCommand()
            ->select('t.id, t.data, t.good_id, t.action_id, t.user_id, a.varchar_value, g.good_type_id, ta.name')
            ->from(Task::tableName().' t')
            ->join(TaskAction::tableName().' ta', 'ta.id=t.action_id')
            ->join(Good::tableName().' g', 'g.id=t.good_id')
            ->join(GoodAttribute::tableName().' a', 'g.id=a.good_id')
            ->where("t.user_id=$user_id AND a.attribute_id=3")
            ->order("t.id ASC")
            ->queryAll();

        $goodTypes = Controller::getAssoc(GoodType::model()->findAll(), "id");

        foreach ($model as $i => $value) {
        	if($value["data"] !== NULL)
        		$model[$i]["data"] = json_decode($value["data"]);

        	$model[$i]["good"] = (object) array(
        		"id" => $model[$i]["good_id"],
        		"code" => $model[$i]["varchar_value"],
        		"good_type_id" => $model[$i]["good_type_id"]
        	);
        	$model[$i]["good_type"] = $goodTypes[$model[$i]["good_type_id"]]->name;
        	unset($model[$i]["varchar_value"]);
        	unset($model[$i]["good_type_id"]);
        	$model[$i] = (object) $model[$i];
        }

        return $model;
	}

	public function testGood($good){
		$params = $this->getParams($good->good_type_id);

		// Проверка первичных атрибутов
		$not_exist = $this->checkFields($good, $params->necessary);
		$necessary_exist = count($not_exist)?false:true;
		if( !$necessary_exist ){
			Task::add($good->id, "necessary", implode(",", $not_exist));
		}else{
			Task::remove($good->id, "necessary");
		}

		// Проверка цены
		$not_exist = $this->checkFields($good, $params->price);
		$price_exist = count($not_exist)?false:true;
		if( !$price_exist ){
			if( $necessary_exist ){
				Task::add($good->id, "price", implode(",", $not_exist));
			}
		}else{
			Task::remove($good->id, "price");
		}

		$required = $this->getRequired($good->good_type_id);

		// Проверка обязательных атрибутов
		$not_exist = $this->checkFields($good, $required);
		if( count($not_exist) ){
			if( $necessary_exist && $price_exist ) {
				Task::add($good->id, "required", implode(",", $not_exist));
			}
		}else{
			Task::remove($good->id, "required");
		}

		// Проверка фотографий
		if( !$this->checkPhoto($good) ){
			Task::add($good->id, "photo");
		}else{
			Task::remove($good->id, "photo");
		}
	}

	public function checkFields($good, $fields){
		$not_exist = array();
		foreach ($fields as $i => $attr_id)
			if( !isset($good->fields_assoc[$attr_id]) || ( !is_array($good->fields_assoc[$attr_id]) && $good->fields_assoc[$attr_id]->value === NULL ) ){
				array_push($not_exist, $attr_id);
			}

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

	public function add($good_id, $action, $data = NULL){
		$action = Task::getAction($action);

		if( $task = Task::model()->find("good_id=$good_id AND action_id=".$action->id) ){
			if( $data === NULL )
				return $task->id;
		}else{
			$task = new Task;
			$task->good_id = $good_id;
			$task->action_id = $action->id;
			$task->user_id = $action->user_id;
		}
		
		if( $data )
			$task->data = json_encode($data);

		if( $task->save() )
			return $task->id;
		
		return false;
	}

	public function remove($good_id, $action){
		$action = Task::getAction($action);

		Task::model()->deleteAll("good_id=$good_id AND action_id=".$action->id);
	}

	public function getParams($good_type_id){
		return (object) self::$params[$good_type_id];
	}

	public function getAction($code){
		return (object) self::$actions[$code];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Task the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
