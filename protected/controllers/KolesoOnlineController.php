<?php

class KolesoOnlineController extends Controller
{
	public $layout='//layouts/kolesoOnline';
	public $title = "Колесо Онлайн - самый большой выбор б/у шин и дисков в России";
	public $description = "Лучший выбор автомобильных б/у шин и дисков из Японии. Удобный поиск и выгодные цены, а самое главное честное описание и фото. Мы постоянно работаем над расширением географии наших представительств на территории РФ.";
	public $keywords = "Лучший выбор автомобильных б/у шин и дисков из Японии. Удобный поиск и выгодные цены, а самое главное честное описание и фото. Мы постоянно работаем над расширением географии наших представительств на территории РФ.";
	public $image = "";

	public $params = array(
		1 => array(
			"NAME" => "Шины",
			"TITLE_CODE" => 66,
			"TITLE_2_CODE" => 30,
			"DESCRIPTION_CODE" => 72,
			"GARANTY_CODE" => 70,
			"PRICE_CODE" => 73,
			"ORDER" => 63,
			"SEASON" => 23,
			'SHIPPING' => 73,
			'AVAILABLE' => 112,
			"FILTER" => array(
				9 => "Диаметр",
				7 => "Ширина",
				8 => "Профиль",
				16 => "Модель",
				28 => "Количество"
			),
			"CATEGORY" => array(
				"AMOUNT" => array(
					"ID" => 28,
					"LABEL" => "Количество в комплекте",
					"UNIT" => " шт."
				),
				"PROTECTOR" => array(
					"ID" => 23,
					"LABEL" => "Протектор",
					"UNIT" => ' '
				),
				"WEAR" => array(
					"ID" => 29,
					"LABEL" => "Износ",
					"UNIT" => ' %'
				),
				"DIAMETER" => array(
					"ID" => 9,
					"LABEL" => "Диаметр",
					"UNIT" => '"'
				),
				"WIDTH" => array(
					"ID" => 7,
					"LABEL" => "Ширина профиля",
					"UNIT" => ' мм.'
				),
				"HEIGHT" => array(
					"ID" => 8,
					"LABEL" => "Высота профиля",
					"UNIT" => ' %'
				),
				"REST" => array(
					"ID" => 12,
					"LABEL" => "Остаток протектора (мм.)",
					"UNIT" => ' '
				),
				"CONDITION" => array(
					"ID" => 26,
					"LABEL" => "Состояние товара",
					"UNIT" => ' '
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
		),
		2 => array(
			"NAME" => "Диски",
			"TITLE_CODE" => 67,
			"TITLE_2_CODE" => 68,
			"DESCRIPTION_CODE" => 71,
			"GARANTY_CODE" => 69,
			"PRICE_CODE" => 74,
			"ORDER" => 64,
			'SHIPPING' => 74,
			'AVAILABLE' => 114,
			"FILTER" => array(
				9 => "Диаметр",
				5 => "Сверловка",
				31 => "Ширина",
				32 => "Вылет",
				28 => "Количество"
			),
			"CATEGORY" => array(
				"ID" => array(
					"ID" => 3,
					"LABEL" => "Артикул",
					"UNIT" => ' '
				),
				"AMOUNT" => array(
					"ID" => 28,
					"LABEL" => "Количество в комплекте",
					"UNIT" => " шт."
				),
				"CONDITION" => array(
					"ID" => 26,
					"LABEL" => "Состояние товара",
					"UNIT" => ' '
				),
				"DIAMETER" => array(
					"ID" => 9,
					"LABEL" => "Диаметр",
					"UNIT" => '"'
				),
				"DRILL" => array(
					"ID" => 117,
					"LABEL" => "Сверловка",
					"UNIT" => ' ',
					"TYPE" => "INTER"
				),
				"WIDTH" => array(
					"ID" => 120,
					"LABEL" => "Ширина диска",
					"UNIT" => '"',
					"TYPE" => "INTER"
				),
				"VILET" => array(
					"ID" => 121,
					"LABEL" => "Вылет",
					"UNIT" => ' мм.',
					"TYPE" => "INTER"
				),
				"CENTER" => array(
					"ID" => 33,
					"LABEL" => "Центральное отверстие",
					"UNIT" => ' мм.'
				),
				"YEAR" => array(
					"ID" => 10,
					"LABEL" => "Год выпуска",
					"UNIT" => ' '
				),
				"COUNTRY" => array(
					"ID" => 118,
					"LABEL" => "Страна изготовитель",
					"UNIT" => ' ',
					"TYPE" => "INTER"
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

	public function init() {
        parent::init();

        $this->image = Yii::app()->getBaseUrl(true)."/html/i/logo-vk.jpg";
    }

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

	public function getCity() {
		if (!empty($_POST['city'])) {
			if(!isset($_SESSION)) session_start();
			$_SESSION["city_temp"] = $_POST['city'];
		    header("Location: ".$_SERVER["REQUEST_URI"]);
	  	}
		$city_groups = array();
		if( !(isset($_SESSION['city']) && AttributeVariant::model()->count("variant_id=".$_SESSION['city']['variant_id'])) ) {
			$city = json_decode(file_get_contents('http://api.sypexgeo.net/json/'.$_SERVER["REMOTE_ADDR"]));
			$default_city = "Томск";
			$default_variant_id = 1081;
			$_SESSION['city']['name'] = $default_city;
	     	$_SESSION['city']['variant_id'] = $default_variant_id;
	       	$city_from_ip = (isset($city->city->name_ru)) ? $city->city->name_ru : $default_city;
     	} else $city_from_ip = $_SESSION['city']['name'];

     	if (isset($_SESSION["city_temp"]) && $_SESSION["city_temp"]) {
    		$city_from_ip = $_SESSION["city_temp"];
    	}
     	$model = Attribute::model()->with(array("variants.variant"))->findAll(array("order"=>"t.id ASC, variant.sort ASC","condition"=>"folder=1"));
		
     	foreach ($model as $key => $group) {
     		$city_groups[$group->name] = array();
     		foreach($group->variants as $i => $city) {
     			$city_groups[$group->name][$i]['name'] = $city->value;
     			$city_groups[$group->name][$i]['variant_id'] = $city->variant_id;
     			if( mb_strtolower($city->value,'UTF-8') == mb_strtolower($city_from_ip,'UTF-8')) {
     				$_SESSION['city']['name'] = $city->value;
     				$_SESSION['city']['variant_id'] = $city->variant_id;
     			}
     		}

     		$city_groups[$group->name] = $this->splitByRows(10,$city_groups[$group->name]);
     	}
	    return $city_groups;
    }


	public function actionIndex($countGood = false)
	{	
		$start = microtime(true);
		if(!isset($_SESSION)) session_start();

		if(isset($_SESSION["FILTER"])) {
			$temp = $_SESSION["FILTER"];
			$_SESSION["FILTER"] = array();
		}
		$tire_filter = $this->getFilter(1);
		$disc_filter =  $this->getFilter(2);
       	$tires = $this->getGoods(8,1); 
		$tires = $tires['items'];

		$discs = $this->getGoods(8,2);
		$discs = $discs['items'];

		$_SESSION["FILTER"] = $temp;

		$this->params[1]["FILTER"] = $this->splitByRows(4,$this->params[1]["FILTER"]);
		$this->params[2]["FILTER"] = $this->splitByRows(4,$this->params[2]["FILTER"]);

        // list($queryCount, $queryTime) = Yii::app()->db->getStats();
		// echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
		// printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);
		$cities = $this->getCity();	
			$dynamic = $this->getDynObjects(array(
            38 => $_SESSION['city']['variant_id']
    	));

		$this->render('index',array(
			'tires'=> $tires,
			'discs' => $discs,
			'cities' => $cities,
			'tire_filter' => $tire_filter,
			'disc_filter' => $disc_filter,
			'params' => $this->params,
			'dynamic' => $dynamic
		));		
	}

	public function actionCategory($partial = false, $countGood = false) {

		if (!empty($_POST)) {
			if(!isset($_SESSION)) session_start();
			$_SESSION["FILTER"] = $_POST;
		    header("Location: ".$_SERVER["REQUEST_URI"]);
	  	}
	  	
		$this->title = "Колесо Онлайн - Б/у ".GoodType::model()->find(array("limit"=>1,"condition"=>"id=".$_GET['type']))->name;

		$filter = $this->getFilter($_GET['type']);

		$last = isset($_SESSION["FILTER"]['last']) ? $_SESSION["FILTER"]['last'] : 1;
		
		if($partial) {
			$last++;
			$_GET['GoodFilter_page'] = $last;
		}

		$goods = $this->getGoods(40,$_GET['type']); 
		$count = $goods['count'];	
		$pages = $goods['pages'];	
		$allCount = $goods["allCount"];
		$goods = $goods['items'];

		if( $_GET['GoodFilter_page'] >= $pages->pageCount || $pages->pageCount == 1 ) {
			$last = 0;
		}

		if(!$partial) $cities = $this->getCity();

		$dynamic = $this->getDynObjects(array(
            38 => $_SESSION['city']['variant_id']
    	));

		if($partial) {
			$this->renderPartial('_list',array(
				'goods'=> $goods,
				'last' => $last,
				'params' => $this->params,
				'type' => $_GET['type'],
				'dynamic' => $dynamic
			));
		} else {
			$this->render('category',array(
				'goods'=> $goods,
				'filter' => $filter,
				'pages' => $pages,
				'params' => $this->params,
				'last' => $last,
				'cities' => $cities,
				'dynamic' => $dynamic
			));
		}
	}
	public function getFilter($type = NULL) {

		$criteria=new CDbCriteria();
		$criteria->with = array('good' => array('select'=> false));
        $criteria->condition = 'attribute_id=51 AND good.good_type_id='.$type;
        $criteria->select = array('int_value');
        $criteria->order = 'int_value ASC';

		$model = GoodAttributeFilter::model()->findAll($criteria);
		$this->params[$type]["PRICE_MIN"] = ($model[0]->int_value) ? $model[0]->int_value : 0;
		$this->params[$type]["PRICE_MAX"] = array_pop($model)->int_value;

		if(isset($_SESSION["FILTER"]["int"])) {
			if($_SESSION["FILTER"]["int"][51]["min"] == "") {
				$_SESSION["FILTER"]["int"][51]["min"] = $this->params[$_GET['type']]["PRICE_MIN"];
			}
			if($_POST["int"][51]["max"] == "") {
				$_SESSION["FILTER"]["int"][51]["max"] = $this->params[$_GET['type']]["PRICE_MAX"];
			}
		}

		$arr = isset($_SESSION["FILTER"]['arr']) ? $_SESSION["FILTER"]['arr'] : array();
		$check = $this->getChecked($arr);

		$criteria=new CDbCriteria();
		$criteria->with = array(
            'good'
             => array(
                'select' => false,
                'condition' => 'good_type_id='.$type
                ),
            'variant'
        );
	
		$criteria->condition = 't.attribute_id=9 OR t.attribute_id=43 OR t.attribute_id=27 OR t.attribute_id=28 OR ';
        if($type==1) {
        	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16';
    	}	
    	if($type==2) {
        	$criteria->condition .= 't.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
    	}	
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

		foreach ($filter as $attr_id => $attr) {
			if( $attr_id == 43 ){
				$list = $this->getListValue(41);
				foreach ($filter[$attr_id] as $i => &$variant) {
					$variant["value"] = $list[$variant["variant_id"]];
				}
			}
		}
		foreach ($filter as &$attr) {
			if(count($attr) > 15) {
				$attr = $this->splitByRows(10,$attr);
			} else $attr = $this->splitByRows(5,$attr);
		}

		return $filter;
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

	public function actionDetail($id = NULL,$type = NULL)
	{
		if($id) {
			$good = Good::model()->with("fields")->find("good_type_id=".$_GET['type']." AND fields.attribute_id=3 AND fields.varchar_value='".$id."'");
			$good = Good::model()->findByPk($good->id);

			$this->title = Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good);

			$this->description = $this->keywords = Interpreter::generate($this->params[$_GET['type']]["DESCRIPTION_CODE"], $good);

			$imgs = $this->getImages($good);

			$this->image = Yii::app()->getBaseUrl(true).$imgs[0];

			$cities = $this->getCity();
			
			$dynamic = $this->getDynObjects(array(
	            38 => $_SESSION['city']['variant_id']
	    	));

			$this->render('detail',array(
				'good'=>$good,
				'imgs'=>$imgs,
				'params' => $this->params,
				'cities' => $cities,
				'dynamic' => $dynamic
			));
		}
	}

	public function getGoods($page_size = 8,$type = 2) {
		$goods = Good::model()->filter(
			array(
				"good_type_id"=>$type,
				"attributes"=>isset($_SESSION["FILTER"]['arr']) ? $_SESSION["FILTER"]['arr'] : array(),
				"int_attributes"=>isset($_SESSION["FILTER"]['int']) ?$_SESSION["FILTER"]['int'] : array(),
			)
		)->sort( 
			isset($_SESSION["FILTER"]['sort']) ? $_SESSION["FILTER"]['sort'] : NULL
		)->getPage(
			array(
		    	'pageSize'=>$page_size,
		    )
		);
		return $goods;
	}

	public function actionContacts()
	{
		$this->render('contacts',array(
			'cities' => $this->getCity()
		));
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

        $from = "Колесо Онлайн";
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

            $message .= "<div><p><b>Город: </b>".( (isset($_SESSION['city']))?($_SESSION['city']['name']):("") )."</p></div>";

            if( isset($_POST["good-url"]) )
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
