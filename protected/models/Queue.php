<?php

/**
 * This is the model class for table "queue".
 *
 * The followings are the available columns in table 'queue':
 * @property string $id
 * @property string $advert_id
 * @property string $action_id
 * @property integer $state_id
 */
class Queue extends CActiveRecord
{
	public $codes = array(
		"add" => 1,
		"update" => 2,
		"delete" => 3,
		"updateImages" => 4,
		"payUp" => 5,
		"updateWithImages" => 6,
		"up" => 7,
		"updatePrice" => 8
	);

	static public $states = array(
		"waiting" => 1,
		"processing" => 2,
		"error" => 3,
		"freeze" => 4,
		"titleNotUnique" => 5,
		"textNotUnique" => 6,
		"partner" => 7,
		"noImages" => 8,
		"limit" => 9,
		"notProxy" => 10
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'queue';
	}

	public function scopes()
    {
        return array(
        	'next'=>array(
                'condition'=>'state_id = 1 AND start IS NULL',
                'limit' => 1
            ),
            'nextStart'=>array(
                'condition'=>"state_id = 1 AND start < '".date("Y-m-d H:i:s", time())."'",
                'limit' => 1
            ),
            'nextAvito'=>array(
                'condition'=>"state_id = 1",
                'limit' => 1
            ),
            // 'next'=>array(
            //     'condition'=>'action_id != 3 AND state_id = 1',
            // ),
            // 'toDelete'=>array(
            //     'condition'=>'action_id = 3 AND state_id = 1',
            // ),
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('advert_id, action_id', 'required'),
			array('state_id', 'numerical', 'integerOnly'=>true),
			array('advert_id, action_id', 'length', 'max'=>10),
			array('start', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, advert_id, action_id, state_id, start', 'safe', 'on'=>'search'),
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
			'advert' => array(self::BELONGS_TO, 'Advert', 'advert_id'),
			'action' => array(self::BELONGS_TO, 'Action', 'action_id'),
			'state' => array(self::BELONGS_TO, 'QueueState', 'state_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'advert_id' => 'Объявление',
			'action_id' => 'Действие',
			'state_id' => 'Состояние',
			'start' => 'Время',
		);
	}

	public function filter($params, $with = NULL, $select = NULL, $pagination = NULL){
		$temp = Advert::filter_ids($params,NULL,array("id"));

		$command = Yii::app()->db->createCommand()
            ->select('t.id')
            ->from(Queue::tableName().' t')
            ->join(Advert::tableName().' a', 't.advert_id=a.id')
            ->join(Place::tableName().' p', 'a.place_id=p.id')
            ->where("p.category_id=".$params["category_id"].((count($temp))?(" AND t.advert_id IN (".implode(",", $temp).")"):"").((isset($params['Attr']['state']))?(" AND t.state_id IN (".implode(",", $params['Attr']['state']).")"):"").((isset($params['Attr']['action']))?(" AND t.action_id IN (".implode(",", $params['Attr']['action']).")"):""))
            ->order("t.start ASC, t.id ASC")
            ->limit( ($pagination === NULL)?999999:$pagination );
        $queue = $command->queryAll();

        $queue = Controller::getIds($queue, "id");
        if( $with === true ) return $queue;

		$criteria = new CDbCriteria();
		$options = array();

		if($select){
			$criteria->select = $select;
		}
		if($with){
			$criteria->with = $with;
		}

		if( $pagination ){
			$options['pagination'] = array('pageSize' => $pagination);
		} else {
			$options['pagination'] = false;
		}
  		
  		$criteria->addInCondition("t.id", $queue);
  		$criteria->order = "start ASC, t.id ASC";

	   	$options['criteria'] = $criteria;
		$dataProvider = new CActiveDataProvider(Queue::tableName(), $options);

		$count = $command = Yii::app()->db->createCommand()
            ->select('COUNT(t.id)')
            ->from(Queue::tableName().' t')
            ->join(Advert::tableName().' a', 't.advert_id=a.id')
            ->join(Place::tableName().' p', 'a.place_id=p.id')
            ->where("p.category_id=".$params["category_id"].((count($temp))?(" AND t.advert_id IN (".implode(",", $temp).")"):"").((isset($params['Attr']['state']))?(" AND t.state_id IN (".implode(",", $params['Attr']['state']).")"):"").((isset($params['Attr']['action']))?(" AND t.action_id IN (".implode(",", $params['Attr']['action']).")"):""))
            ->order("t.id ASC")
			->queryScalar();

		$dataProvider->totalItemCount = ($count)?$count:0;
		return $dataProvider;
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
		$criteria->compare('advert_id',$this->advert_id,true);
		$criteria->compare('action_id',$this->action_id,true);
		$criteria->compare('state_id',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function add($advert_id = false, $code = false)
	{	
		if( $advert_id && $code ){
			if( isset($this->codes[$code]) ){
				$model = new Queue();
				$model->advert_id = $advert_id;
				$model->code = $code;
				$model->save();
				return $model;
			}else{
				return Log::error("Не найдено действие с кодом \"".$code."\"");
			}
		}else{
			return Log::error("Отсутствует ID объявления или код действия");
		}
	}

	public function addAll($adverts = array(), $code = false, $offset = 0, $interval = 0, $offset_avito = 0, $random_offset = 24){
		if( count($adverts) && $code ){
			$city_settings = Controller::getCitySettings();
			$start = time() + $offset;
			if( isset(Queue::model()->codes[$code]) ){
				$values = array();
				$toDelete = array();
				foreach ($adverts as $advert){
					$item = array("advert_id" => isset($advert->id)?$advert->id:$advert, "action_id" => Queue::model()->codes[$code], "start" => NULL );
						$item["start"] = NULL;

					// if( $advert->place->category_id == 2048 && in_array($item["action_id"], array(2,6)) )
					// 	$item["action_id"] = 8;

					if( $code == "delete" && $advert->url === NULL ){
						array_push($toDelete, $advert);	
						continue;
					}

					// if( $advert->place->category_id == 2048 ){
					// 	// switch ( $code ) {
					// 	// 	case 'add':
					// 	// 		$item["start"] = NULL;
					// 	// 		break;

					// 	// 	case 'up':
					// 			$item["start"] = NULL;
					// 	// 		break;
							
					// 	// 	default:
					// 	// 		$item["start"] = NULL;
					// 	// 		break;
					// 	// }
					// }

					array_push($values, $item);
					$start += $interval;
				}

				Advert::delAll($toDelete);
				
				Controller::insertValues(Queue::tableName(),$values);

				Queue::refreshTime(2048, true);
				return true;
			}else{
				return Log::error("Не найдено действие с кодом \"".$code."\" для добавления в очередь");
			}
		}
	}

	public function checkExist($adverts, $action){
		$adverts_id = $this->getIds($adverts);
		$action_id = Queue::model()->codes[$action];
		$actions = array(Queue::model()->codes["delete"]);

		if( !count($adverts_id) ) return array();

		if( $action != "delete" )
			array_push($actions, Queue::model()->codes["add"]);

		if( !in_array($action_id, $actions) )
			array_push($actions, $action_id);

		if( $action == "updateImages" || $action == "update" )
			array_push($actions, Queue::model()->codes["updateWithImages"]);

		$queue = Yii::app()->db->createCommand()
            ->select('*')
            ->from(Queue::tableName().' t')
            ->where("advert_id IN (".implode(",", $adverts_id).") AND action_id IN (".implode(",", $actions).")")
            ->queryAll();

        $queue = $this->getIds($queue, "advert_id");

       	foreach ($adverts as $i => $advert)
       		if( in_array($advert->id, $queue) )
       			unset($adverts[$i]);

       	return $adverts;
	}

	public function delAll($adverts = array(), $code = false){
		if( count($adverts) && $code ){
			if( isset(Queue::model()->codes[$code]) ){
				$advert_ids = array();
				$add_arr = array();
				$update_arr = array();

				foreach ($adverts as $advert){
					array_push($advert_ids, isset($advert->id)?$advert->id:$advert);
					if( $advert->url == NULL ){
						array_push($add_arr, $advert);
					}else{
						array_push($update_arr, $advert);
					}
				}
				
				$criteria = new CDbCriteria();
				$criteria->condition = "action_id=".Queue::model()->codes[$code];
	    		$criteria->addInCondition("advert_id", $advert_ids);

	    		Queue::model()->deleteAll($criteria);
	    		if( $code == "delete" ){
	    			Queue::addAll($add_arr,"add");
	    			Queue::addAll($update_arr,"update");
	    		}
				return true;
			}else{
				return Log::error("Не найдено действие с кодом \"".$code."\" для добавления в очередь");
			}
		}
	}

	public function	setState($code){
		if( Queue::getState($code) ){
			$this->state_id = Queue::getState($code);
			return $this->save();
		}else{
			return Log::error("Не найдено состояние с кодом \"$code\"");
		}
	}

	public function getNext($category_id, $exclude = NULL){
		if( $category_id == 2048 ){
			$queue = Queue::model()->with(array("advert.good.fields.variant","advert.good.fields.attribute","advert.good.type","advert.place","action"))->nextStart()->find(array("condition"=>"place.category_id=$category_id AND t.action_id!=1".( (is_array($exclude) && count($exclude))?(" AND advert.city_id NOT IN (".implode(",", $exclude).")"):"" ),"order"=>"t.start ASC"));
		}else{
			$queue = Queue::model()->with(array("advert.good.fields.variant","advert.good.fields.attribute","advert.good.type","advert.place","action"))->nextStart()->find(array("condition"=>"place.category_id=$category_id".( (is_array($exclude) && count($exclude))?(" AND advert.city_id NOT IN (".implode(",", $exclude).")"):"" ),"order"=>"t.start ASC"));
		}
		if( !count($queue) && $category_id != 2048 ){
			$queue = Queue::model()->with(array("advert.good.fields.variant","advert.good.fields.attribute","advert.good.type","advert.place","action"))->next()->find(array("condition"=>"place.category_id=$category_id","order"=>"t.id ASC"));
		}
		return $queue;
	}

	public function getAvitoAddNext(){
		$queue = Queue::model()->with(array("advert.good.fields.variant","advert.good.fields.attribute","advert.good.type","advert.place","action"))->nextAvito()->find(array("condition"=>"place.category_id=2048 AND action_id=1","order"=>"t.start ASC"));
		return $queue;
	}

	public function refreshTime($category_id, $queue = NULL){
		if( $category_id == 2048 ){
			// Ищем города в которых есть объявы, которые нужно добавить или поднять
			$model = Yii::app()->db->createCommand()
	            ->select('a.city_id')
	            ->from(Queue::tableName().' t')
	            ->join(Advert::tableName().' a', 't.advert_id=a.id')
	            ->where((is_array($queue)?("t.id IN (".implode(",", $queue).") AND "):(($queue === true)?("t.start IS NULL AND "):""))."t.action_id IN (1,7) AND t.state_id=1")
	            ->order("t.id ASC")
	            ->group("a.city_id")
	            ->queryAll();

	        $city_ids = array();
			foreach ($model as $key => $value)
				array_push($city_ids, $value["city_id"]);

			// Обновляем время выполнения объявлений, которые нужно добавить или поднять
			Queue::refreshAddTime($category_id, $city_ids, (($queue === true)?true:false) );

			// Ищем города в которых есть объявы, которые нужно обновить или удалить
			$model = Yii::app()->db->createCommand()
	            ->select('a.city_id')
	            ->from(Queue::tableName().' t')
	            ->join(Advert::tableName().' a', 't.advert_id=a.id')
	            ->where((is_array($queue)?("t.id IN (".implode(",", $queue).") AND "):(($queue === true)?("t.start IS NULL AND "):""))."t.action_id IN (2,3,4,6,8) AND t.state_id=1")
	            ->order("t.id ASC")
	            ->group("a.city_id")
	            ->queryAll();

	        $city_ids = array();
			foreach ($model as $key => $value)
				array_push($city_ids, $value["city_id"]);

			// Обновляем время выполнения объявлений, который нужно обновить или удалить
			Queue::refreshUpdateTime($category_id, $city_ids, (($queue === true)?true:false) );
		}
	}

	public function refreshAddTime($category_id, $city_ids, $not_full = false){
		$cities = DesktopTable::getTable(13, array(
			56 => "id",
			107 => "start",
			108 => "end",
			109 => "interval",
		), "id");

		$values = array();
		foreach ($city_ids as $i => $city_id) {
			$queue = Queue::model()->with(array("advert.place"))->findAll("advert.city_id=$city_id AND action_id IN (1,7) AND state_id=1 AND place.category_id=$category_id".( ($not_full)?(" AND t.start IS NULL"):("") ));
			if( $queue ){
				$city_params = $cities[$city_id];
				$city_params["interval"] = intval($city_params["interval"]);
				$days = Queue::getDays($city_params);

				// $cur_time = time()+rand(0, 1*60*60);
				$cur_time = time();
				$cur_day = 0;
				if( $not_full ){
					$last_time = Queue::getLastAddTime($category_id, $city_id);
					if( $last_time == NULL ) $last_time = $cur_time;
					if( $last_time ){
						$cur_time = $last_time;
						$cur_day = Queue::getDayByTime($cur_time, $city_params["end"]);
					}
				}

				shuffle($queue);

				foreach ($queue as $key => $item) {
					$cur_time += $city_params["interval"]*60+(rand($city_params["interval"]*(-0.3)*60, $city_params["interval"]*0.3*60));
					if( $cur_time < $days[$cur_day]["start"] ){
						$cur_time = $days[$cur_day]["start"];
					}else if( $cur_time > $days[$cur_day]["end"]){
						if( isset($days[$cur_day+1]) ){
							$cur_day++;
							$cur_time = $days[$cur_day]["start"]; 
						}
					}
					array_push($values, array($item->id, $item->advert_id, $item->action_id, $item->state_id, date("Y-m-d H:i:s", $cur_time) ));
				}
			}
		}
		Controller::updateRows(Queue::tableName(), $values, array("start"));
	}

	public function getLastAddTime($category_id, $city_id){
		$queue = Queue::model()->with(array("advert.place"))->find(array(
			"condition" => "advert.city_id=$city_id AND action_id IN (1,7) AND state_id=1 AND place.category_id=$category_id AND start IS NOT NULL",
			"order" => "start DESC",
			"limit" => 1
		));
		if( !$queue ) return NULL;
		return strtotime($queue->start);
	}

	public function refreshUpdateTime($category_id, $city_ids, $not_full = false){
		$cities = DesktopTable::getTable(13, array(
			56 => "id",
			107 => "start",
			108 => "end",
			109 => "interval",
		), "id");

		$values = array();
		foreach ($city_ids as $i => $city_id) {
			$queue = Queue::model()->with(array("advert.place"))->findAll("advert.city_id=$city_id AND action_id IN (2,3,4,6,8) AND state_id=1 AND place.category_id=$category_id".( ($not_full)?(" AND start IS NULL"):("") ));

			if( $queue ){
				shuffle($queue);

				$city_params = $cities[$city_id];
				$city_params["interval"] = 1;
				$days = Queue::getDays($city_params);

				$cur_time = time()+rand(0, 10);
				$cur_day = 0;
				if( $not_full ){
					$last_time = Queue::getLastUpdateTime($category_id, $city_id);
					if( $last_time == NULL ) $last_time = $cur_time;
					if( $last_time ){
						$cur_time = $last_time;
						$cur_day = Queue::getDayByTime($cur_time, $city_params["end"]);
					}
				}

				foreach ($queue as $key => $item) {
					$cur_time += rand(10,60);
					if( $cur_time < $days[$cur_day]["start"] ){
						$cur_time = $days[$cur_day]["start"];
					}else if( $cur_time > $days[$cur_day]["end"]){
						if( isset($days[$cur_day+1]) ){
							$cur_day++;
							$cur_time = $days[$cur_day]["start"]; 
						}
					}
					array_push($values, array($item->id, $item->advert_id, $item->action_id, $item->state_id, date("Y-m-d H:i:s", $cur_time) ));
				}
			}
		}
		Controller::updateRows(Queue::tableName(), $values, array("start"));
	}

	public function getLastUpdateTime($category_id, $city_id){
		$queue = Queue::model()->with(array("advert.place"))->find(array(
			"condition" => "advert.city_id=$city_id AND action_id IN (2,3,4,6,8) AND state_id=1 AND place.category_id=$category_id AND start IS NOT NULL",
			"order" => "start DESC",
			"limit" => 1
		));
		if( !$queue ) return NULL;
		return strtotime($queue->start);
	}

	public function getDayByTime($time, $end){
		$end = strtotime(str_replace("#", $end, date("Y-m-d #:00", $time)));
		$i = 0;
		while ( 1 ) {
			if( $time <= $end ) return $i;
			$i++;
			$end += 24*60*60;
		}
	}

	public function getDays($params){
		$days = array();
		$start = strtotime(str_replace("#", $params["start"], date("Y-m-d #:00", time())));
		$end = strtotime(str_replace("#", $params["end"], date("Y-m-d #:00", time())));
		if( $start > $end ) $end += (24*60*60);

		for( $i = 0; $i < 100; $i++ )
			array_push($days, array("start" => $start+($i*24*60*60)+rand(0, 60*60), "end" => $end+($i*24*60*60)+rand(0, 60*60)));

		if( $days[0]["start"] < time() ) $days[0]["start"] = time();
		if( $days[0]["end"] < time() ) array_shift($days);

		return $days;
	}

	public function returnAdverts($code, $state_to, $state_from = NULL){
		$filter = array(
			"Codes" => $code,
			"Place" => array(9, 10),
			"Attr" => array(
				37 => array(869),
				58 => array(1081)
			),
			"category_id" => 2047
		);
		if( $state_from !== NULL )
			$filter["Attr"]["state"] = array($state_from);

		$queue = Queue::filter($filter, true);
		if( count($queue) ){
			Queue::model()->updateAll(['state_id' => $state_to], "id IN (".implode(",", $queue).")" );
			Queue::refreshTime(2047, $queue);
		}

		$filter["Place"] = array(11, 12);
		$filter["category_id"] = 2048;

		$queue = Queue::filter($filter, true);
		if( count($queue) ){
			Queue::model()->updateAll(['state_id' => $state_to], "id IN (".implode(",", $queue).")" );
		}
	}

	public function addByFilter($tire_filter = NULL, $disc_filter = NULL, $good_attr, $tire_int_filter = NULL, $disc_int_filter = NULL){
		$model = array();

		if( $tire_filter !== NULL ){
			$filter_arr = array(
                "good_type_id" => 1,
                "attributes" => $tire_filter,
                "int_attributes" => $tire_int_filter,
            );
			if( Yii::app()->params["site"] == "koleso" ){
				$filter_arr["not_contain"] = 117;
			}
			$goods = Good::model()->filter(
	            $filter_arr
	        )->getPage(
	            array(
	                'pageSize'=>10000,
	            )
	        );
	        if( is_array($goods["items"]) )
	            $model = array_merge($model, $goods["items"]);
		}

		if( $disc_filter !== NULL ){
			$filter_arr = array(
                "good_type_id"=>2,
                "attributes"=>$disc_filter,
                "int_attributes" => $disc_int_filter,
            );
			if( Yii::app()->params["site"] == "koleso" ){
				$filter_arr["not_contain"] = 117;
			}
	        $goods = Good::model()->filter(
	            $filter_arr
	        )->getPage(
	            array(
	                'pageSize'=>10000,
	            )
	        );
	        if( is_array($goods["items"]) )
	            $model = array_merge($model, $goods["items"]);
	    }

        $links = array();
        foreach ($model as $i => $good)
            array_push($links, "http://".Yii::app()->params['ip'].Controller::createUrl('/good/adminupdatecities',array('id'=> $good->id, 'Good_attr' => $good_attr)));

        if( count($links) )
        	Cron::addAll($links);
	}

	public function checkReady($only_delete = false){
		if( !$only_delete ){
			$queue = Queue::model()->with("advert.place")->findAll("place.category_id=2048 AND advert.ready=0 AND advert.title IS NOT NULL AND action_id IN (1,2,6,8) AND state_id=1");
			if( $queue )
				Queue::model()->updateAll(array("state_id" => Queue::getState("titleNotUnique")), "id IN (".implode(",", Controller::getIds($queue)).")");

			$queue = Queue::model()->with("advert.place")->findAll("place.category_id=2048 AND advert.ready=1 AND action_id IN (1,2,6,8) AND state_id=5");
			if( $queue )
				Queue::model()->updateAll(array("state_id" => Queue::getState("waiting")), "id IN (".implode(",", Controller::getIds($queue)).")");
		}

		$queue = Queue::model()->with("advert")->findAll("advert.good_id=0 AND action_id != 3");
		if( $queue )
			Queue::model()->deleteAll("id IN (".implode(",", Controller::getIds($queue)).")");
	}

	public function getState($code){
		return self::$states[$code];
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Queue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
