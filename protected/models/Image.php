<?php

/**
 * This is the model class for table "image".
 *
 * The followings are the available columns in table 'image':
 * @property string $id
 * @property string $good_id
 * @property integer $site
 * @property integer $sort
 * @property string $ext
 */
class Image extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_id, sort, ext', 'required'),
			array('site, sort', 'numerical', 'integerOnly'=>true),
			array('good_id', 'length', 'max'=>10),
			array('ext', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, good_id, site, sort, ext', 'safe', 'on'=>'search'),
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
			'caps' => array(self::HAS_MANY, 'ImageCap', 'image_id'),
			'good' => array(self::BELONGS_TO, 'Good', 'good_id'),
			'good_filter' => array(self::BELONGS_TO, 'GoodFilter', 'good_id'),
			'cache' => array(self::HAS_MANY, 'Cache', 'image_id'),
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
			'site' => 'Участие на сайте',
			'sort' => 'Сортировка',
			'ext' => 'Расширение',
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
		$criteria->compare('good_id',$this->good_id,true);
		$criteria->compare('site',$this->site);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('ext',$this->ext,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function add($good_id, $ext, $site = NULL, $sort = 999){
		$image = new Image();
		$image->good_id = $good_id;
		$image->site = ($site == NULL)?1:$site;
		$image->sort = $sort;
		$image->ext = $ext;

		if( $image->save() )
			return $image->id;

		return false;
	}

	public function updateImages($good)
    {   
        if( is_object($good) ){
            $code = $good->fields_assoc[3]->value;
            $good_type_id = $good->good_type_id;
        }else if( is_array($good) ){
            $code = $good["code"];
            $good_type_id = $good["good_type_id"];
        }
        $imgs = array();
        $path = Yii::app()->params["imageFolder"]."/".GoodType::getCode($good_type_id);
        if($extra) {
            $code = $code."/extra";
        }
        $good_id = $good->id;
        $dir = $path."/".$code;
        $dir2 = $dir;
        $index = 1;
        Image::model()->deleteAll("good_id='$good_id'");
        if (is_dir($dir)) {
            $imgs = array_values(array_diff(scandir($dir), array('..', '.', 'Thumbs.db', '.DS_Store')));
            $dir = Yii::app()->request->baseUrl."/".$path."/".$code;
            $out = array();
            if(count($imgs)) {
                foreach ($imgs as $key => &$value) {
                    if(!is_dir($dir2."/".$value)) {
                        $tmp = explode(".", $value);
                        $new_id = Image::add($good_id, $tmp[1], 1, $index);
                        rename($path."/".$code."/".$value, $path."/".$code."/".$new_id.".".$tmp[1]);
                        $index++;
                    } else {
                        if( $value == "extra" ){
                            $dir3 = $path."/".$code."/extra";
                            $imgs1 = array_values(array_diff(scandir($dir3), array('..', '.', 'Thumbs.db', '.DS_Store')));
                            $out = array();
                            if(count($imgs1)) {
                                foreach ($imgs1 as $key => &$value) {
                                    $tmp = explode(".", $value);
                                    $new_id = Image::add($good_id, $tmp[1], 1, $index);
                                    rename($dir3."/".$value, $dir2."/".$new_id.".".$tmp[1]);
                                    $new_cap = new ImageCap();
                                    $new_cap->image_id = $new_id;
                                    $new_cap->cap_id = 3;
                                    $new_cap->sort = $key+1;
                                    $new_cap->save();
                                    $index++;
                                }
                            }
                            @rmdir($dir3);
                        }
                    }
                }
            }
        }
    }

    public function remove($id, $path){
    	$image = Image::model()->with("caps","cache")->findByPk($id);

    	if( !$image ) return true;
    	foreach ($image->cache as $i => $item)
    		@unlink(str_replace("images", "cache", $path)."/".$id."_".$item->size.".".$image->ext);

    	@unlink($path."/".$id.".".$image->ext);

    	$image->delete();
 	}

 	public function beforeDelete(){
  		$good = GoodFilter::model()->findByPk($this->good_id);
  		$code = GoodAttributeFilter::model()->find("attribute_id=3 AND good_id=".$this->good_id);
  		if( $good && $code ){
  			@unlink(Yii::app()->params["imageFolder"]."/".Controller::getTypeCode($good->good_type_id)."/".$code->varchar_value."/".$this->id.".".$this->ext);
  			foreach ($this->cache as $key => $cache)
  				@unlink(Yii::app()->params["cacheFolder"]."/".Controller::getTypeCode($good->good_type_id)."/".$code->varchar_value."/".$this->id."_".$cache->size.".".strtolower($this->ext) );
  		}

  		foreach ($this->cache as $key => $value)
  			$value->delete();

  		foreach ($this->caps as $key => $value)
  			$value->delete();
  		
  		return parent::beforeDelete();
 	}

 	public function renameFolder($good_type_id, $from, $to){
 		$filename_from = Yii::app()->params["imageFolder"]."/".Controller::getTypeCode($good_type_id)."/".$from;
 		$filename_to = Yii::app()->params["imageFolder"]."/".Controller::getTypeCode($good_type_id)."/".$to;
 		if( file_exists($filename_to) ){
 			Controller::removeDirectory($filename_to);
 		}
 		rename($filename_from, $filename_to);

 		$filename_from = Yii::app()->params["cacheFolder"]."/".Controller::getTypeCode($good_type_id)."/".$from;
 		$filename_to = Yii::app()->params["cacheFolder"]."/".Controller::getTypeCode($good_type_id)."/".$to;
 		if( file_exists($filename_to) ){
 			Controller::removeDirectory($filename_to);
 		}
 		rename($filename_from, $filename_to);
 	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Image the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
