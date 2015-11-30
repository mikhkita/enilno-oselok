<?php

class KolesoOnlineController extends Controller
{
	public $layout='//layouts/kolesoOnline';

	public $params = array(
		1 => array(
			"NAME" => "Шины",
			"TITLE_CODE" => 66,
			"TITLE_2_CODE" => 30,
			"DESCRIPTION_CODE" => 72,
			"GARANTY_CODE" => 70,
			"PRICE_CODE" => 73,
			"ORDER" => 63,
			"FILTER" => array(
				23 => "Сезонность",
				27 => "Город",
				9 => "Диаметр",
				7 => "Ширина",
				8 => "Профиль",
				16 => "Модель",
				28 => "Количество"
			),
			
			"PRICE_MIN" => 0,
			"PRICE_MAX" => 0,
		),
		2 => array(
			"NAME" => "Диски",
			"TITLE_CODE" => 67,
			"TITLE_2_CODE" => 68,
			"DESCRIPTION_CODE" => 71,
			"GARANTY_CODE" => 69,
			"PRICE_CODE" => 74,
			"ORDER" => 64,
			"FILTER" => array(
				9 => "Диаметр",
				5 => "Сверловка",
				31 => "Ширина",
				32 => "Вылет",
				28 => "Количество",
				27 => "Город"
			),
			"CATEGORY" => array(
				"AMOUNT" => array(
					"ID" => 28,
					"LABEL" => "Количество в комплекте",
					"UNIT" => "шт."
				),
				"DIAMETER" => array(
					"ID" => 9,
					"LABEL" => "Диаметр",
					"UNIT" => '"'
				),
				"WIDTH" => array(
					"ID" => 31,
					"LABEL" => "Ширина диска",
					"UNIT" => '"'
				),
				"VILET" => array(
					"ID" => 32,
					"LABEL" => "Вылет",
					"UNIT" => 'мм.'
				),
				"DRILL" => array(
					"ID" => 5,
					"LABEL" => "Сверловка",
					"UNIT" => ' '
				),
				"CONDITION" => array(
					"ID" => 26,
					"LABEL" => "Состояние товара",
					"UNIT" => ' '
				),
				"CENTER" => array(
					"ID" => 33,
					"LABEL" => "Центральное отверстие",
					"UNIT" => 'мм.'
				),
				"YEAR" => array(
					"ID" => 10,
					"LABEL" => "Год выпуска",
					"UNIT" => ' '
				),
				"LOCATION" => array(
					"ID" => 27,
					"LABEL" => "Местонахождение товара",
					"UNIT" => ' '
				),
			),
			"PRICE_MIN" => 0,
			"PRICE_MAX" => 0,
		));

	public $filter = array();

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
				'actions'=>array('index', 'index2', 'detail','contacts','mail','category'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($countGood = false)
	{	
		$start = microtime(true);
		$count=0;
        $condition="";
        $check = $this->getChecked( (isset($_GET["arr"]))?$_GET["arr"]:array() );
        
       	isset($_GET['type']) ? $_GET['type'] : $_GET['type'] = 2;
		// $type = ($_GET['type']==1) ? "tires": "discs";

		// $criteria=new CDbCriteria();
		// $criteria->with = array('good' => array('select'=> false));
  //       $criteria->condition = 'attribute_id=51 AND good.good_type_id=1';
  //       $criteria->select = array('int_value');
  //       $criteria->order = 'int_value ASC';

		// $model = GoodAttributeFilter::model()->findAll($criteria);
		// $price_min_tire = ($model[0]->int_value) ? $model[0]->int_value : 0;
		// $price_max_tire = array_pop($model)->int_value;
		
		// $criteria->condition = 'attribute_id=51 AND good.good_type_id=2';
		// $model = GoodAttributeFilter::model()->findAll($criteria);
		// $price_min_disc = ($model[0]->int_value) ? $model[0]->int_value : 0;
		// $price_max_disc = array_pop($model)->int_value;
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

		// $goods = Good::model()->filter(
		// 	array(
		// 		"good_type_id"=>$_GET['type'],
		// 		"attributes"=>$_GET["arr"],
		// 		"int_attributes"=>isset( $_GET["int"] )?$_GET["int"]:array(),
		// 	)
		// )->sort( 
		// 	$_GET['sort'] 
		// )->getPage(
		// 	array(
		//     	'pageSize'=>20,
		//     )
		// );

		// $count = $goods['count'];	
		// $pages = $goods['pages'];	
		// $allCount = $goods["allCount"];
		// $goods = $goods['items'];

    	if( !$countGood ) {
   //  		$criteria=new CDbCriteria();
			// $criteria->with = array('good' => array('select'=> false));
	  //       $criteria->condition = 'attribute_id=51 AND good.good_type_id=1';
	  //       $criteria->select = array('int_value');
	  //       $criteria->order = 'int_value ASC';

			// $model = GoodAttributeFilter::model()->findAll($criteria);
			// $price_min_tire = ($model[0]->int_value) ? $model[0]->int_value : 0;
			// $price_max_tire = array_pop($model)->int_value;
			
			// $criteria->condition = 'attribute_id=51 AND good.good_type_id=2';
			// $model = GoodAttributeFilter::model()->findAll($criteria);
			// $price_min_disc = ($model[0]->int_value) ? $model[0]->int_value : 0;
			// $price_max_disc = array_pop($model)->int_value;

   //          $criteria=new CDbCriteria();
   //          $criteria->with = array(
   //              // 'good'
   //              //  => array(
   //              //     'select' => false,
   //              //     'condition' => 'good_type_id='.$_GET['type']
   //              //     ),
   //              'variant'

   //              );
   //          $criteria->addInCondition('t.attribute_id',array(9,43,27,28,7,8,23,16,6,5,31,32));
            // $criteria->condition = 't.attribute_id=9 OR t.attribute_id=43 OR t.attribute_id=27 OR t.attribute_id=28 OR ';
         //    if($_GET['type']==1) {
         //    	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16';
        	// }	
        	// if($_GET['type']==2) {
         //    	$criteria->condition .= 't.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
        	// }	

        	// $criteria->addInCondition("good.id",$goods_no_photo);
     //        $criteria->group = 't.variant_id';
     //        $criteria->order = 'variant.sort ASC';

     //        $model = GoodAttribute::model()->findAll($criteria);
     //        $this->filter = array();

   		// 	foreach ($model as $i => $item) {
   		// 		if(!isset($this->filter[$item->attribute_id])) {
   		// 			$this->filter[$item->attribute_id] = array();
   		// 			$temp = array();
   		// 		}
   		// 		$temp['variant_id'] = $item->variant_id;
	    //                 $temp['value'] = $item->value;
	    //                 if(isset($check[$item->variant_id])) {
	    //                 	$temp['checked'] = "checked";
	    //             	} else {
	    //             		$temp['checked'] = "";
	    //             	}
   		// 		array_push($this->filter[$item->attribute_id], $temp);
   		// 	}

   		// 	foreach ($this->filter as $attr_id => $attr) {
   		// 		if( $attr_id == 43 ){
					// $list = $this->getListValue(41);
					// foreach ($this->filter[$attr_id] as $i => &$variant) {
					// 	$variant["value"] = $list[$variant["variant_id"]];
					// }
   		// 		}
   		// 	}
   		// 	foreach ($this->filter as &$attr) {
   		// 		$attr = $this->splitByRows(11,$attr);
   		// 	}
    		$this->getFilter();
   			$this->params[1]["FILTER"] = $this->splitByRows(4,$this->params[1]["FILTER"]);
   			$this->params[2]["FILTER"] = $this->splitByRows(4,$this->params[2]["FILTER"]);

  //  			list($queryCount, $queryTime) = Yii::app()->db->getStats();
		// echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
		// printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);	

			$this->render('index',array(
				// 'goods'=>$goods,
				'filter' =>$this->filter,
				// 'pages' => $pages,
				'params' => $this->params,
			));
		} else {
			echo $count;
		}		
	}

	public function getFilter() {
		$check = $this->getChecked( (isset($_GET["arr"]))?$_GET["arr"]:array() );

		$criteria=new CDbCriteria();
		$criteria->with = array('good' => array('select'=> false));
        $criteria->condition = 'attribute_id=51 AND good.good_type_id=1';
        $criteria->select = array('int_value');
        $criteria->order = 'int_value ASC';

		$model = GoodAttributeFilter::model()->findAll($criteria);
		$this->params[1]["PRICE_MIN"] = ($model[0]->int_value) ? $model[0]->int_value : 0;
		$this->params[1]["PRICE_MAX"] = array_pop($model)->int_value;
		
		$criteria->condition = 'attribute_id=51 AND good.good_type_id=2';
		$model = GoodAttributeFilter::model()->findAll($criteria);
		$this->params[2]["PRICE_MIN"] = ($model[0]->int_value) ? $model[0]->int_value : 0;
		$this->params[2]["PRICE_MAX"] = array_pop($model)->int_value;

		$criteria=new CDbCriteria();
            $criteria->with = array(
                // 'good'
                //  => array(
                //     'select' => false,
                //     'condition' => 'good_type_id='.$_GET['type']
                //     ),
                'variant'

                );
            $criteria->addInCondition('t.attribute_id',array(9,43,27,28,7,8,23,16,6,5,31,32));
        $criteria->group = 't.variant_id';
            $criteria->order = 'variant.sort ASC';

            $model = GoodAttribute::model()->findAll($criteria);
            $this->filter = array();

   			foreach ($model as $i => $item) {
   				if(!isset($this->filter[$item->attribute_id])) {
   					$this->filter[$item->attribute_id] = array();
   					$temp = array();
   				}
   				$temp['variant_id'] = $item->variant_id;
	                    $temp['value'] = $item->value;
	                    if(isset($check[$item->variant_id])) {
	                    	$temp['checked'] = "checked";
	                	} else {
	                		$temp['checked'] = "";
	                	}
   				array_push($this->filter[$item->attribute_id], $temp);
   			}

   			foreach ($this->filter as $attr_id => $attr) {
   				if( $attr_id == 43 ){
					$list = $this->getListValue(41);
					foreach ($this->filter[$attr_id] as $i => &$variant) {
						$variant["value"] = $list[$variant["variant_id"]];
					}
   				}
   			}
   			foreach ($this->filter as &$attr) {
   				$attr = $this->splitByRows(11,$attr);
   			}
	}

	public function actionCategory($countGood = false) {
		$this->getFilter();
		if(isset($_GET["int"])) {
			if($_GET["int"][51]["min"] == "") {
				$_GET["int"][51]["min"] = $this->params[$_GET['type']]["PRICE_MIN"];
			}
			if($_GET["int"][51]["max"] == "") {
				$_GET["int"][51]["max"] = $this->params[$_GET['type']]["PRICE_MAX"];
			}
		}
		$goods = Good::model()->filter(
			array(
				"good_type_id"=>$_GET['type'],
				"attributes"=>$_GET["arr"],
				"int_attributes"=>isset( $_GET["int"])?$_GET["int"]:array(),
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

		$this->render('category',array(
			'goods'=>$goods,
			'filter' =>$this->filter,
			'pages' => $pages,
			'params' => $this->params,
		));

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
					'imgs'=>$imgs,
					'params' => $this->params
				));
			}else{
				$this->renderPartial('detail',array(
					'good'=>$good,
					'imgs'=>$imgs,
					'params' => $this->params
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

        $email_admin = $this->getParam("KolesoOnline","EMAILS");

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
