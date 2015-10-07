<?php

/**
 * This is the model class for table "good".
 *
 * The followings are the available columns in table 'good':
 * @property string $id
 * @property string $code
 * @property string $good_type_id
 */
class Good extends CActiveRecord
{
	private $sortOptions = NULL;
	private $criteria = NULL;
	private $sorted = NULL;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'good';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_type_id', 'required'),
			array('code', 'length', 'max'=>255),
			array('good_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, good_type_id', 'safe', 'on'=>'search'),
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
			'fields' => array(self::HAS_MANY, 'GoodAttribute', 'good_id'),
			'type' => array(self::BELONGS_TO, 'GoodType', 'good_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Код',
			'good_type_id' => 'Тип товара',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('good_type_id',$this->good_type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function filter($options = array(),$ids = NULL){

		$this->criteria = $this->filterItems($options,$ids);

    	return $this;
	}

	public function sort($options = array()){
		if( isset($options["field"]) ){
			if( !isset($options["type"]) ) $options["type"] = "ASC";

			$model = GoodFilter::model()->findAll($this->criteria);
	    	$ids = $this->getIds($model);

	    	$this->criteria = $this->sortItems($options,$ids);
	    	$this->sortOptions = $options;
	    	$this->sorted = true;
	    }

	    return $this;
	}

	public function getPage($options){
		if( !$this->sorted ){
			$model = GoodFilter::model()->findAll($this->criteria);
	    	$ids = $this->getIds($model);

	    	$this->criteria = new CDbCriteria();
	    	$this->criteria->addInCondition("t.id",$ids);
		}

		$dataProvider = new CActiveDataProvider('GoodFilter', array(
		    'criteria'=>$this->criteria,
		    'pagination'=>$options,
		));

		$goods = Good::model()->with("fields.attribute","fields.variant")->findAllByPk( $this->getIds( $dataProvider->getData() ) );

		if( $this->sortOptions != NULL ){
			$sortOptions = $this->sortOptions;
			usort($goods, function($a, $b) use($sortOptions) {
				$a = (is_array($a->fields_assoc[$sortOptions['field']])) ? $a->fields_assoc[$sortOptions['field']][0]->value : $a->fields_assoc[$sortOptions['field']]->value;
				$b = (is_array($b->fields_assoc[$sortOptions['field']])) ? $b->fields_assoc[$sortOptions['field']][0]->value : $b->fields_assoc[$sortOptions['field']]->value;
			    if ($a == $b) {
			        return 0;
			    }
			    if($sortOptions['type'] == "DESC") return ($a > $b) ? -1 : 1; else return ($a < $b) ? -1 : 1;
			});
		}
		return array( "items" => $goods, "count" => $dataProvider->getTotalItemCount(), "pages" => $dataProvider->getPagination(), "dataProvider" => $dataProvider, "allCount" => $dataProvider->getTotalItemCount() );
	}

	public function getCount(){
		return GoodFilter::model()->count($this->criteria);
	}

	public function filterItems($options,$ids = NULL){
		$criteria=new CDbCriteria();

		$criteria->select = 'id';
		$criteria->group = 'fields.good_id';
        $criteria->with = array('fields' => array('select'=> array('variant_id','attribute_id')));
        $criteria->condition = '(good_type_id='.$options["good_type_id"].' AND fields.attribute_id=20 AND fields.int_value>=0 AND fields.int_value<=100000)';
        if( $ids ) $criteria->addInCondition("t.id", $ids);

        $count = 0;
        if(isset($options["attributes"]) && count($options["attributes"]))
			foreach ($options["attributes"] as $id => $attribute_vals) {		
				foreach ($attribute_vals as $variant_id) {
					$criteria->addCondition('fields.variant_id='.$variant_id,'OR');
				}
				$count++;
			}

    	$criteria->having = 'COUNT(DISTINCT fields.attribute_id)='.($count+1);

    	return $criteria;
	}

	public function sortItems($options,$goods_id){
		$criteria=new CDbCriteria();	
		$criteria->together = true;

		$attribute = Attribute::model()->with("type")->findByPk($options["field"]);

		if( $attribute->list ){
			$with = array('fields' => array('select'=> array('attribute_id')),"fields.variant"=> array('select' => array('sort')));
			// $criteria->order = 'fields.attribute_id DESC';
			$criteria->order = 'variant.sort '.$options["type"];
		}else{
			$with = array('fields' => array('select'=> array('variant_id','attribute_id','varchar_value')));
			if( $attribute->type->code != "varchar" )
				array_push($with["fields"]["select"],$attribute->type->code."_value");
			$criteria->order = 'fields.'.$attribute->type->code.'_value '.$options["type"];
		}

		$criteria->select = "id";
		$criteria->with = $with;
		$criteria->together = true;
		$criteria->group = 't.id';
    	// $criteria->condition = 'fields.attribute_id='.$attribute->id.' OR fields.attribute_id=3';
    	$criteria->condition = 'fields.attribute_id='.$attribute->id;
    	// $criteria->order = 'variant.sort '.$options["type"];
        // $criteria->order = 'fields.varchar_value '.$options["type"];
	    $criteria->addInCondition("t.id",$goods_id);

		return $criteria;
	}

	public function getIds($model){
		$ids = array();
    	foreach ($model as $key => $value)
    		array_push($ids, $value->id);
    	return $ids;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Good the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function afterFind()
	{
		parent::afterFind();

		if( count($this->fields) ){
			$fields = array();

			foreach ($this->fields as $field) {
				$attr_id = ( $field->attribute->dynamic )?($field->attribute_id."-d"):$field->attribute_id;
				if( isset($fields[$attr_id]) ){
					if( !is_array($fields[$attr_id]) ){
						$fields[$attr_id] = array(0 => $fields[$attr_id]);
					}
					$fields[$attr_id][] = $field;
				}else{
					$fields[$attr_id] = $field;
				}
			}
			$this->setAttribute("fields_assoc",$fields,true);
		}
	}

	public function beforeDelete(){
  		foreach ($this->fields as $key => $value) {
  			$value->delete();
  		}
  		return parent::beforeDelete();
 	}
}
