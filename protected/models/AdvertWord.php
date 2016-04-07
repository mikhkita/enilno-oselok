<?php

/**
 * This is the model class for table "advert_word".
 *
 * The followings are the available columns in table 'advert_word':
 * @property string $advert_id
 * @property string $word_id
 */
class AdvertWord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'advert_word';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('word_id', 'required'),
			array('word_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('advert_id, word_id', 'safe', 'on'=>'search'),
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
			'word' => array(self::BELONGS_TO, 'Word', 'word_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'advert_id' => 'Advert',
			'word_id' => 'Word',
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

		$criteria->compare('advert_id',$this->advert_id,true);
		$criteria->compare('word_id',$this->word_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function update($ids, $advert_id){
		$values = array();
    	foreach ($ids as $i => $id)
    		array_push($values, array("advert_id" => $advert_id, "word_id" => $id));

    	AdvertWord::model()->deleteAll("advert_id=$advert_id");
    	if( count($values) )
    		Controller::insertValues(AdvertWord::tableName(), $values);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdvertWord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
