<?php

/**
 * This is the model class for table "yahoo_lot".
 *
 * The followings are the available columns in table 'yahoo_lot':
 * @property integer $sort
 * @property string $id
 * @property string $title
 * @property string $update_time
 * @property string $image
 * @property integer $cur_price
 * @property integer $bid_price
 * @property integer $bids
 * @property string $end_time
 * @property integer $category_id
 * @property integer $seller_id
 * @property integer $state
 */
class YahooLot extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'yahoo_lot';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, title, update_time, cur_price, end_time, category_id, seller_id', 'required'),
			array('cur_price, bid_price, bids, category_id, seller_id, state', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>11),
			array('title, image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sort, id, title, update_time, image, cur_price, bid_price, bids, end_time, category_id, seller_id, state', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'YahooCategory', 'category_id'),
			'seller' => array(self::BELONGS_TO, 'YahooSeller', 'seller_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sort' => 'Сортировка',
			'id' => 'Номер лота',
			'title' => 'Заголовок',
			'update_time' => 'Время последнего обновления',
			'image' => 'Изображение',
			'cur_price' => 'Текущая цена',
			'bid_price' => 'Блиц-цена',
			'bids' => 'Количество ставок',
			'end_time' => 'Осталось часов',
			'category_id' => 'Категория',
			'seller_id' => 'Продавец',
			'state' => 'Состояние',
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

		$criteria->compare('sort',$this->sort);
		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('cur_price',$this->cur_price);
		$criteria->compare('bid_price',$this->bid_price);
		$criteria->compare('bids',$this->bids);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('seller_id',$this->seller_id);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function filter(){
		return array(
			"category_id" => array("TYPE" => "CHECKBOX", "VIEW" => "CHECKBOX", "FROM" => YahooCategory::tableName(), "FIELDS" => array("id","name") ),
			"cur_price" => array("TYPE"=>"FROMTO", "VIEW" => "FROMTO"),
			"bids" => array("TYPE"=>"FROMTO", "VIEW" => "FROMTO"),
			"end_time" => array("TYPE"=>"CUSTOM_FROMTO", "VIEW" => "FROMTO"),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YahooLot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
