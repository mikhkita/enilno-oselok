<?php

/**
 * This is the model class for table "cron".
 *
 * The followings are the available columns in table 'cron':
 * @property string $id
 * @property string $link
 * @property string $start
 * @property integer $state_id
 * @property string $error
 */
class Cron extends CActiveRecord
{
	public $states = array(
		"waiting" => 1,
		"processing" => 2,
		"error" => 3,
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cron';
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
			array('link, state_id', 'required'),
			array('state_id', 'numerical', 'integerOnly'=>true),
			array('link', 'length', 'max'=>1000),
			array('error', 'length', 'max'=>100),
			array('start', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, link, start, state_id, error', 'safe', 'on'=>'search'),
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
			'link' => 'Ссылка',
			'start' => 'Время старта',
			'state_id' => 'Состояние',
			'error' => 'Сообщение ошибки',
		);
	}

	public function add($link = false, $time = NULL)
	{	
		if( $link ){
			$model = new Cron();
			$model->link = $link;

			if( $time !== NULL )
				$model->time = $time;

			$model->state_id = $this->states["waiting"];
			$model->save();
			return $model;
		}else{
			return Log::error("Отсутствует ссылка для добавления в планировщик задач");
		}
	}

	public function addAll($values, $offset = NULL, $interval = NULL)
	{	
		if( $values ){
			foreach ($values as $key => $value) {
				if( !is_array($value) ){
					$value = array("link"=>$value, "state_id"=>$this->states["waiting"]);
				}else if( !isset($value["state_id"]) )
					$value["state_id"] = $this->states["waiting"];

				$values[$key] = $value;
			}

			Controller::insertValues($this->tableName(), $values);
		}
	}

	public function getNext(){
		$task = Cron::model()->nextStart()->find(array("order"=>"t.start ASC"));
		if( !count($task) )
			$task = Cron::model()->next()->find(array("order"=>"t.id ASC"));

		return $task;
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
		$criteria->compare('link',$this->link,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('error',$this->error,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cron the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
