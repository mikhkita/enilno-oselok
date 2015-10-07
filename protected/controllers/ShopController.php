<?php

class ShopController extends Controller
{
	public $layout='//layouts/shop';

	public $params = array(
		1 => array(
			"NAME" => "Шины",
			"TITLE_CODE" => 50,
			"TITLE_2_CODE" => 13,
			"DESCRIPTION_CODE" => 74,
			"GARANTY_CODE" => 77,
			"PRICE_CODE" => 95,
			"ORDER" => 120
		),
		2 => array(
			"NAME" => "Диски",
			"TITLE_CODE" => 53,
			"TITLE_2_CODE" => 54,
			"DESCRIPTION_CODE" => 75,
			"GARANTY_CODE" => 78,
			"PRICE_CODE" => 94,
			"ORDER" => 121
		));

	public function filters()
	{
		return array(
				'accessControl'
			);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('filter'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array('index', 'index2', 'detail','contacts','mail'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($countGood = false)
	{	
		$count=0;
        $condition="";
        $check = $this->getChecked( (isset($_GET["arr"]))?$_GET["arr"]:array() );
        
       	isset($_GET['type']) ? $_GET['type'] : $_GET['type'] = 1;
		$type = ($_GET['type']==1) ? "tires": "discs";

		$criteria=new CDbCriteria();
		$criteria->with = array('good' => array('select'=> false));
        $criteria->condition = 'attribute_id=20 AND good.good_type_id='.$_GET['type'];
        $criteria->select = array('int_value');
        $criteria->order = 'int_value ASC';

		$model = GoodAttributeFilter::model()->findAll($criteria);
		$price_min = $model[0]->int_value;
		$price_max = array_pop($model)->int_value;
	
		// Поиск айдишников товаров у которых есть фото		
		// $imgs = array_values(array_diff(scandir(Yii::app()->params["imageFolder"]."/".$type), array('..', '.', 'Thumbs.db','default-big.png','default.jpg')));
		// $temp = "0";
		// if(count($imgs)) {
		// 	$temp = "";
		// 	foreach ($imgs as $value) {
		// 		$temp.="'".$value."',";
		// 	}
		// 	$temp = substr($temp, 0, -1);	
		// }

		// $criteria=new CDbCriteria();
		// $criteria->select = 'id,good_type_id';
	 //   	$criteria->with = array('fields' => array('select'=> array('attribute_id','varchar_value')));
		// $criteria->condition = 'good_type_id='.$_GET['type'].' AND (fields.attribute_id=3 AND fields.varchar_value IN('.$temp.'))';

		// $model=GoodFilter::model()->findAll($criteria);

		// $goods_no_photo = array();
		// foreach ($model as $good) {
		// 	array_push($goods_no_photo, $good->id); 
		// }
		// Поиск айдишников товаров у которых есть фото

		$goods = Good::model()->filter(
			array(
				"good_type_id"=>$_GET['type'],
				"attributes"=>$_GET["arr"],
			)
		)->sort( 
			$_GET['sort'] 
		)->getPage(
			array(
		    	'pageSize'=>20,
		    )
		);

		$count = $goods['count'];	
		$pages = $goods['pages'];	
		$allCount = $goods["allCount"];
		$goods = $goods['items'];

    	if( !$countGood ) {
            $criteria=new CDbCriteria();
            $criteria->with = array(
                'good'
                 => array(
                    'select' => false,
                    'condition' => 'good_type_id='.$_GET['type']
                    ),
                'variant' => array(
                		'select' => false
                	)

                );
            $criteria->condition = 't.attribute_id=9 OR t.attribute_id=27 OR t.attribute_id=28 OR ';
            if($_GET['type']==1) {
            	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16';
        	}	
        	if($_GET['type']==2) {
            	$criteria->condition .= 't.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
        	}	

        	// $criteria->addInCondition("good.id",$goods_no_photo);
            $criteria->group = 't.variant_id';
            $criteria->order = 'variant.sort ASC';

            $model = GoodAttribute::model()->findAll($criteria);
            $filter = array();

   			foreach ($model as $i => $item) {
   				if(!isset($filter[$item->attribute_id])) {
   					$filter[$item->attribute_id] = array();
   					$temp = array();
   				}
   				$temp['variant_id'] = $item->variant_id;
	                    $temp['value'] = $item->value;
	                    if(isset($check[$item->variant_id])) {
	                    $temp['checked'] = "checked";
	                	} else {
	                		$temp['checked'] = "";
	                	}
   				array_push($filter[$item->attribute_id], $temp);
   			}

			$this->render('index',array(
				'goods'=>$goods,
				'filter' =>$filter,
				'price_min' => $price_min,
				'price_max' => $price_max,
				'pages' => $pages
			));
		} else {
			echo $count;
		}		
	}

	public function getChecked($attributes){
		$out = array();

		foreach ($attributes as $attr) {
			foreach ($attr as $variant) {
				$out[$variant] = true;
			}
		}

		return $out;
	}

	public function actionDetail($partial = false,$id = NULL)
	{
		if($id) {
			$good = Good::model()->with("fields")->find("good_type_id=".$_GET['type']." AND fields.attribute_id=3 AND fields.varchar_value='".$id."'");
			$good = Good::model()->findByPk($good->id);

			$this->title = Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good);

			$this->description = $this->keywords = Interpreter::generate($this->params[$_GET['type']]["DESCRIPTION_CODE"], $good);

			$imgs = $this->getImages($good);
			if( !$partial ){
				$this->render('detail',array(
					'good'=>$good,
					'imgs'=>$imgs
				));
			}else{
				$this->renderPartial('detail',array(
					'good'=>$good,
					'imgs'=>$imgs
				));
			}
		}
	}

	public function actionContacts()
	{
		$this->render('contacts');
	}

	public function actionCount()
	{
		$goods=Good::model()->findAllbyPk($goods_id,$criteria);
	}

	public function loadModel($id)
	{
		$model=Good::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionMail(){
        require_once("phpmail.php");

        $email_admin = $this->getParam("SHOP","EMAILS");

        $from = "Koleso Tomsk Ru";
        $email_from = "koleso@tomsk.ru";

        $deafult = array("name"=>"Имя","phone"=>"Телефон", "email"=>"E-mail");

        $fields = array();

        if( count($_POST) ){

            foreach ($deafult  as $key => $value){
                if( isset($_POST[$key]) ){
                    $fields[$value] = $_POST[$key];
                }
            }

            $i = 1;
            while( isset($_POST[''.$i]) ){
                $fields[$_POST[$i."-name"]] = $_POST[''.$i];
                $i++;
            }

            $subject = $_POST["subject"];

            foreach ($fields  as $key => $value){
                $message .= "<div><p><b>".$key.": </b>".$value."</p></div>";
            }

            $message .= "<div><p><b>Товар: </b><a target='_blank' href='".$_POST["good-url"]."'>".$_POST["good"]."</a></p></div>";
                
            $message .= "</div>";
            
            if(send_mime_mail("Сайт ".$from,$email_from,$name,$email_admin,'UTF-8','UTF-8',$subject,$message,true)){    
                echo "1";
            }else{
                echo "0";
            }
        }
    }
}
