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
		"updateWithImages" => 6
	);

	public $states = array(
		"waiting" => 1,
		"processing" => 2,
		"error" => 3,
		"freeze" => 4,
		"titleNotUnique" => 5,
		"textNotUnique" => 6,
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
					if( $offset > 0 || $interval > 0 )
						$item["start"] = date("Y-m-d H:i:s", $start);

					if( $code == "delete" && $advert->url === NULL ){
						array_push($toDelete, $advert);	
						continue;
					}

					if( $advert->place->category_id == 2048 ){
						switch ( $code ) {
							case 'add':
								$last = Queue::model()->with("advert.place")->find(array("limit"=>1,"order"=>"start DESC","condition"=>"place.category_id=2048 AND start IS NOT NULL AND advert.city_id=".$advert->city_id));
								$from = (( isset($city_settings[$advert->city_id]) )?$city_settings[$advert->city_id]->avito_delay:30)*0.9;
								$to = (( isset($city_settings[$advert->city_id]) )?$city_settings[$advert->city_id]->avito_delay:30)*1.1;
								$item["start"] = date("Y-m-d H:i:s", ($last)?(strtotime($last->start)+rand($from*60,$to*60)):$start );
								break;
							
							default:
								$item["start"] = date("Y-m-d H:i:s", intval(rand(intval($offset_avito*60*60), intval($random_offset*60*60))) + time() );
								break;
						}
					}

					array_push($values, $item);
					$start += $interval;
				}

				Advert::delAll($toDelete);
				
				Controller::insertValues(Queue::tableName(),$values);
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
				$criteria->condition = "action_id=".Queue::model()->codes[$code]." AND state_id != 2";
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
		if( isset($this->states[$code]) ){
			$this->state_id = $this->states[$code];
			return $this->save();
		}else{
			return Log::error("Не найдено состояние с кодом \"$code\"");
		}
	}

	public function getNext($category_id){
		$queue = Queue::model()->with(array("advert.good.type","advert.place","action"))->nextStart()->find(array("condition"=>"place.category_id=$category_id","order"=>"t.start ASC"));
		if( !count($queue) ){
			$queue = Queue::model()->with(array("advert.good.type","advert.place","action"))->next()->find(array("condition"=>"place.category_id=$category_id","order"=>"t.id ASC"));
		}
		return $queue;
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
