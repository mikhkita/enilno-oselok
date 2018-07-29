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
			array('user_id, action_id', 'required'),
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

	public function filter($user_id = NULL, $order_type = "ASC"){
		$model = Yii::app()->db->createCommand()
            ->select('t.id, t.data, t.good_id, t.action_id, t.user_id, ta.name')
            ->from(Task::tableName().' t')
            ->join(TaskAction::tableName().' ta', 'ta.id=t.action_id')
            ->where( (($user_id !== NULL)?"t.user_id=$user_id AND ":"")."1=1")
            ->order("t.id ".$order_type)
            ->limit(1000)
            ->queryAll();

        if( count($model) ){
        	$ids = array();
        	foreach ($model as $i => $task)
        		if( $task["good_id"] )
        			array_push($ids, $task["good_id"]);

        	if( count($ids) ){
        		$goods = Yii::app()->db->createCommand()
		            ->select('t.varchar_value, t.good_id, g.good_type_id, g.archive')
		            ->from(GoodAttribute::tableName().' t')
		            ->join(Good::tableName().' g', "t.good_id=g.id")
		            ->where("t.attribute_id=3 AND t.good_id IN (".implode(",", $ids).")")
		            ->queryAll();

		       	$goods = Controller::getAssocByAssoc($goods, "good_id");
        	}
        }

        $goodTypes = Controller::getAssoc(GoodType::model()->findAll(), "id");

        $attributes = array();
        foreach ($model as $i => $value) {
        	if($value["data"] !== NULL){
        		$model[$i]["data"] = json_decode($value["data"]);
        		if( !isset($model[$i]["data"]->message) && !is_array($model[$i]["data"]) ){
        			foreach (explode(",", $model[$i]["data"]) as $key => $val)
	        			if( !in_array($val, $attributes) )
	        				array_push($attributes, $val);
        		}
        	}

        	if( $value["good_id"] ){
        		$model[$i]["good"] = (object) array(
	        		"id" => $value["good_id"],
	        		"code" => (isset($goods[$value["good_id"]])?$goods[$value["good_id"]]["varchar_value"]:""),
	        		"good_type_id" => (isset($goods[$value["good_id"]])?$goods[$value["good_id"]]["good_type_id"]:""),
	        		"archive" => (isset($goods[$value["good_id"]])?$goods[$value["good_id"]]["archive"]:"")
	        	);
        	}
        	$model[$i]["good_type"] = $goodTypes[$model[$i]["good"]->good_type_id]->name;
        	unset($model[$i]["varchar_value"]);
        	unset($model[$i]["good_type_id"]);
        	$model[$i] = (object) $model[$i];
        }

        if( count($attributes) ){
        	$attributes = Yii::app()->db->createCommand()
	            ->select('t.id, t.name')
	            ->from(Attribute::tableName().' t')
	            ->where("t.id IN (".implode(",", $attributes).")")
	            ->queryAll();

	        $attributes = Controller::getAssocByAssoc($attributes, "id");

	        foreach ($model as $i => $value) {
	        	if( in_array($value->action_id, array(1,2,3,4)) && $value->data !== NULL && !is_array($value->data)){
	        		$names = array();

	        		$tmp = explode(",", $value->data);
	        		foreach ($tmp as $key => $val)
	        			array_push($names, mb_strtolower($attributes[$val]["name"],"UTF-8"));

		        	$model[$i]->data = (object) array(
		        		"ids" => $value->data,
		        		"names" => implode(", ", $names)
		        	);
	        	}
	        }
        }

        return $model;
	}

	public function testGood($good){
		switch (Yii::app()->params["site"]) {
			case 'koleso':
				if( is_object($good->fields_assoc[117]) && $good->fields_assoc[117]->variant_id == 4312 ){
					Task::remove($good->id);
					break;
				}

				if( is_object($good->fields_assoc[27]) && $good->fields_assoc[27]->variant_id == 1056 ){
					$params = $this->getParams($good->good_type_id);

					// Проверка первичных атрибутов
					$not_exist = $this->checkFields($good, $params->necessary);
					$necessary_exist = count($not_exist)?false:true;
					if( !$necessary_exist ){
						Task::addMulti($good->id, "necessary", $not_exist);
					}else{
						Task::remove($good->id, "necessary");
					}

					// Проверка цены
					$not_exist = $this->checkFields($good, $params->price);
					$price_exist = count($not_exist)?false:true;
					if( !$price_exist ){
						if( $necessary_exist ){
							Task::addMulti($good->id, "price", $not_exist);
						}else{
							Task::edit($good->id, "price", $not_exist);
						}
					}else{
						Task::remove($good->id, "price");
					}

					$required = $this->getRequired($good, "koleso");

					// Проверка обязательных атрибутов
					$not_exist = $this->checkFields($good, array_diff($required, $params->price, $params->necessary));
					if( count($not_exist) ){
						if( $necessary_exist && $price_exist ) {
							Task::addMulti($good->id, "required", $not_exist);
						}else{
							Task::edit($good->id, "required", $not_exist);
						}
					}else{
						Task::remove($good->id, "required");
					}

					// Проверка фотографий
					if( !($photo_exist = $this->checkPhoto($good,2)) ){
						Task::add($good->id, "photo");
					}else{
						Task::remove($good->id, "photo");

						Queue::returnAdverts( $good->fields_assoc[3]->value, 1, 8 );
					}

					// Проверка дополнительных фотографий
					$caps = Cap::model()->findAll();
					$data = array();
					foreach ($caps as $i => $cap) {
						if( !$this->checkCap($good,$cap->id) && !in_array($cap->id, array(7,8,9,10)) ){
							array_push($data, $cap->name);
						}
					}

					if( count($data) && $photo_exist ){
						Task::add($good->id, "extra", array("message" => mb_strtolower(implode(", ", $data),"UTF-8")));
					}else{
						Task::remove($good->id, "extra");

						Queue::returnAdverts( $good->fields_assoc[3]->value, 1, 8 );
					}
				}
				break;
			
			case 'shikon':
				$params = $this->getParams($good->good_type_id);

				// Проверка первичных атрибутов
				$not_exist = $this->checkFields($good, $params->necessary);
				$necessary_exist = count($not_exist)?false:true;
				if( !$necessary_exist ){
					Task::addMulti($good->id, "necessary", $not_exist);
				}else{
					Task::remove($good->id, "necessary");
				}

				$required = $this->getRequired($good);

				// Проверка обязательных атрибутов
				$not_exist = $this->checkFields($good, array_diff($required, $params->necessary));
				if( count($not_exist) ){
					if( $necessary_exist && $price_exist ) {
						Task::addMulti($good->id, "required", $not_exist);
					}else{
						Task::edit($good->id, "required", implode(",", $not_exist));
					}
				}else{
					Task::remove($good->id, "required");
				}

				// Проверка фотографий
				if( !$this->checkPhoto($good) ){
					Task::add($good->id, "photo");
				}else{
					Task::remove($good->id, "photo");

					Queue::returnAdverts( $good->fields_assoc[3]->value, 1, 8 );
				}

				break;
		}
	}

	public function checkFields($good, $fields){
		$not_exist = array();
		$rules = array(
			20 => array(0),
			108 => array(0),
			46 => array(0),
			111 => array(0)
		);
		foreach ($fields as $i => $attr_id)
			if( !isset($good->fields_assoc[$attr_id]) || (isset($rules[$attr_id]) && is_array($rules[$attr_id]) && in_array($good->fields_assoc[$attr_id]->value, $rules[$attr_id])) || ( !is_array($good->fields_assoc[$attr_id]) && $good->fields_assoc[$attr_id]->value === NULL ) ){
				array_push($not_exist, $attr_id);
			}

		return $not_exist;
	}

	public function checkPhoto($good){
		return count($good->getImages(NULL, NULL, "all"))?true:false;
	}

	public function checkCap($good,$cap){
		return count($good->getImages(NULL, NULL, $cap))?true:false;
	}

	public function getRequired($good, $site = NULL){
		$good_type_id = $good->good_type_id;
		$shtamp = ( $site == "koleso" && $good_type_id == 2 && in_array($good->fields_assoc[6]->value, array("Штамповки", "Штамповка")) );
		$model = Yii::app()->db->createCommand()
            ->select('a.id')
            ->from(Attribute::tableName().' a')
            ->join(GoodTypeAttribute::tableName().' t', 'a.id=t.attribute_id')
            ->where("a.required=1 AND t.good_type_id=$good_type_id".( ($shtamp)?" AND a.id != 32":"" ))
            ->order("t.sort ASC")
            ->queryAll();

        return Controller::getIds($model, "id");
	}

	public function add($good_id, $action, $data = NULL, $user_id = NULL){
		$action = Task::getAction($action);

		$user_id = ($user_id !== NULL)?$user_id:$action->user_id;

		if( $task = Task::model()->find("good_id=$good_id AND action_id=".$action->id." AND user_id=".$user_id) ){
			if( !$data && (!isset($task->data) || !$task->data) )
				return $task->id;
		}else{
			$task = new Task;
			$task->good_id = $good_id;
			$task->action_id = $action->id;
			$task->user_id = $user_id;
		}
		
		$task->data = ($data === NULL)?$data:json_encode($data);

		if( $task->save() )
			return $task->id;
		
		return false;
	}

	public function addMulti($good_id, $act, $dataMulti = NULL, $user_id = NULL){
		$action = Task::getAction($act);

		$users = array();
		$user_id_not = array();
		foreach ($dataMulti as $key => $field) {
			if( isset($action->fields[$field]) ){
				$curUser = $action->fields[$field];
			}else{
				$curUser = $action->user_id;
			}

			if( !isset($users[$curUser]) )
				$users[$curUser] = array();

			array_push($users[$curUser], $field);
			array_push($user_id_not, $curUser);
		}

		foreach ($users as $user => $data)
			Task::add($good_id, $act, implode(",", $data), $user);

		Task::remove($good_id, $act, $user_id_not);
	}

	public function edit($good_id, $action, $data = NULL){
		$action = Task::getAction($action);

		if( $task = Task::model()->find("good_id=$good_id AND action_id=".$action->id) ){
			$task->data = ($data === NULL)?$data:json_encode($data);

			if( $task->save() )
				return $task->id;

			return false;
		}
	}

	public function remove($good_id, $action = false, $user_id_not = false){
		if( $action === false ){
			Task::model()->deleteAll("good_id=$good_id");
		}else{
			$action = Task::getAction($action);
			Task::model()->deleteAll("good_id=$good_id AND action_id=".$action->id.( (is_array($user_id_not) && count($user_id_not))?" AND user_id NOT IN (".implode(",", $user_id_not).")":"" ));
		}
	}

	public function getParams($good_type_id){
		switch (Yii::app()->params["site"]) {
			case 'koleso':
				$params = array(
					1 => array(
						"necessary" => array(16,17,9,8,7,28,43,29),
						"price" => array(20,36,111),
					),
					2 => array(
						"necessary" => array(9,6,28,43),
						"price" => array(20,36,111),
					),
					3 => array(
						"necessary" => array(16,17,9,8,7,28,6,43),
						"price" => array(20,36,111),
					)
				);
				break;
			
			case 'shikon':
				$params = array(
					1 => array(
						"necessary" => array(20,16,17,9,8,7,28),
					),
					2 => array(
						"necessary" => array(20,9,6,28),
					),
					3 => array(
						"necessary" => array(20,16,17,9,8,7,28,6),
					)
				);
				break;
			default:
				echo "Сайт не найден. Task.php";
				die();
				break;
		}
		return (object) $params[$good_type_id];
	}

	public function toDelete($task){
		if( $task->action_id == 8 ){
			if( !$task->data || $task->data == "" ) return true;
			if( !Advert::model()->count("id='".$task->data."' AND good_id!=0") ) return true;
		}
		return false;
	}

	public function getAction($code){
		switch (Yii::app()->params["site"]) {
			case 'koleso':
				$actions = array(
					"photo" => array(
						"id" => 1,
						"user_id" => 10,
					),
					"necessary" => array(
						"id" => 2,
						"user_id" => 10,
					),
					"price" => array(
						"id" => 3,
						"user_id" => 9,
					),
					"required" => array(
						"id" => 4,
						"fields" => array(
							110 => 14,
							23 => 16,
						),
						"user_id" => 10,
					),
					"extra" => array(
						"id" => 5,
						"user_id" => 10,
					),
					"title" => array(
						"id" => 8,
						"user_id" => 9,
					),
				);
				break;

			case 'shikon':
				$actions = array(
					"photo" => array(
						"id" => 1,
						"user_id" => 14,
					),
					"necessary" => array(
						"id" => 2,
						"user_id" => 14,
					),
					"required" => array(
						"id" => 4,
						"user_id" => 14,
					),
					"title" => array(
						"id" => 8,
						"user_id" => 14,
					),
				);
				break;
			default:
				echo "Сайт не найден. Task.php";
				die();
				break;
		}
		return (object) $actions[$code];
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
