<?php

/**
 * This is the model class for table "word".
 *
 * The followings are the available columns in table 'word':
 * @property string $id
 * @property string $value
 */
class Word extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'word';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value', 'required'),
			array('value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, value', 'safe', 'on'=>'search'),
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
			'adverts' => array(self::HAS_MANY, 'AdvertWord', 'word_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'value' => 'Value',
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
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function split($string){
        $to_remove = array(".", "#", "-", "(", ")", "*", "+", "№", "%", "!", ",", ":",";","\"","'");
        $string = str_replace($to_remove, "", $string);
        $to_space = array("/");
        $string = str_replace($to_space, " ", $string);

        $array = explode(" ", $string);
        $array = array_filter($array, function($val){
            return (mb_strlen(((string) $val),"UTF-8")>2);
        });

        return $array;
    }

    public function update($words){
    	$values = array();
    	$words = array_unique($words);
    	foreach ($words as $i => $word){
    		$words[$i] = str_replace(array("ё","Ё"), "е", mb_strtolower($word, "UTF-8"));
    		array_push($values, "'".addslashes($words[$i])."'");
    	}

    	$model = Word::model()->findAll("value IN (".implode(",", $values).")");
    	if( !$model ) $model = array();

    	foreach ($model as $key => $word)
    		if( in_array($word->value, $words) )
    			unset($words[array_search($word->value, $words)]);

    	if( count($words) ){
    		foreach ($words as $i => $word)
    		{
    			if( mb_strlen($word, "UTF-8") >= 50 ){
    				echo $word;
    				die();
    			}
    			$words[$i] = array("value" => $word);
    		}

    		Controller::insertValues(Word::tableName(), $words);
    	}

    	$model = Word::model()->findAll(array("select" => "id", "condition" => "value IN (".implode(",", $values).")")); 
    	if( $model && count($model) ) return Controller::getIds($model);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Word the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
