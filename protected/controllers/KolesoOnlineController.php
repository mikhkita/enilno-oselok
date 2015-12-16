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
			"ORDER" => 165,
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
			"ORDER" => 164,
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
		),
		3 => array(
			"NAME" => "Колеса",
			"TITLE_CATEGORY" => 170,
			"TITLE_CODE" => 146,
			"TITLE_2_CODE" => 171,
			"DESCRIPTION_CODE" => 167,
			"GARANTY_CODE" => 168,
			"PRICE_CODE" => 74,
			"ORDER" => 166,
			"SEASON" => 23,
			'SHIPPING' => 174,
			'AVAILABLE' => 172,
			"FILTER" => array(
				9 => "Диаметр",
				7 => "Ширина шины",
				8 => "Профиль",
				16 => "Модель шины",
				5 => "Сверловка",
				31 => "Ширина диска",
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
					"ID" => 176,
					"LABEL" => "Сверловка",
					"UNIT" => ' ',
					"TYPE" => "INTER"
				),
				"DISC_WIDTH" => array(
					"ID" => 175,
					"LABEL" => "Ширина диска",
					"UNIT" => '"',
					"TYPE" => "INTER"
				),
				"VILET" => array(
					"ID" => 177,
					"LABEL" => "Вылет",
					"UNIT" => ' мм.',
					"TYPE" => "INTER"
				),
				"CENTER" => array(
					"ID" => 33,
					"LABEL" => "Центральное отверстие",
					"UNIT" => ' мм.'
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
				"TIRE_WIDTH" => array(
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
				"COUNTRY" => array(
					"ID" => 179,
					"LABEL" => "Страна изготовитель",
					"UNIT" => ' ',
					"TYPE" => "INTER"
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
				'actions'=>array('index', 'index2', 'detail','page','mail','category','getCities','setCity'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionSetCity() {

		if(!isset($_SESSION)) session_start();
		if( !( isset($_SESSION['city']) && AttributeVariant::model()->count("variant_id=".$_SESSION['city']['variant_id'] ) ) || isset($_POST['city']) ) {
			$default_city = "Томск";
			$default_variant_id = 1081;
			$_SESSION['city']['name'] = $default_city;
	     	$_SESSION['city']['variant_id'] = $default_variant_id;

			if(isset($_POST['city']) && $_POST['city']) {
				$city = $_POST['city'];
			} else {
				$city = json_decode(file_get_contents('http://api.sypexgeo.net/json/'.$_SERVER["REMOTE_ADDR"]));
	       		$city = (isset($city->city->name_ru)) ? $city->city->name_ru : $default_city;
	       	}
	       	$model = Attribute::model()->with(array("variants.variant"))->find("value='".$city."' AND folder=1");
	       	if($model) {
	       		$_SESSION['city']['name'] = $model->variants[0]->value;
	     		$_SESSION['city']['variant_id'] = $model->variants[0]->variant_id;
	       	}
	       	if(isset($_POST['city'])) header("Location: ".$_POST['url']);
     	}
	    
    }

    public function actionGetCities() {
     	$city_groups = array();
     	$model = Attribute::model()->with(array("variants.variant"))->findAll(array("order"=>"t.id ASC, variant.sort ASC","condition"=>"folder=1"));
     	foreach ($model as $key => $group) {
     		$city_groups[$group->name] = array();
     		foreach($group->variants as $i => $city) {
     			$city_groups[$group->name][$i]['name'] = $city->value;
     			$city_groups[$group->name][$i]['variant_id'] = $city->variant_id;
     		}
     		$city_groups[$group->name] = $this->splitByRows(10,$city_groups[$group->name]);
     	}

	    $this->renderPartial('_cities',array(
			'cities' => $city_groups
		));	
    }


	public function actionIndex($countGood = false)
	{	
		$start = microtime(true);

		$this->keywords = $this->getParam("SHOP","MAIN_KEYWORDS");
		$this->description = $this->getParam("SHOP","MAIN_DESCRIPTION");

		$tire_filter = $this->getFilter(1);
		$disc_filter =  $this->getFilter(2);
		$wheel_filter =  $this->getFilter(3);
       	$tires = $this->getGoods(8,1,array(
			"good_type_id"=>1,
		),array("field"=>46,"type"=>"DESC")); 
		$tires = $tires['items'];

		$discs = $this->getGoods(8,2,array(
			"good_type_id"=>2,
			"attributes"=>array(78=>2486,77=>array(2478,2479,2480,2481,2482))
		),array("field"=>46,"type"=>"DESC"));
		$discs = $discs['items'];

		$wheels = $this->getGoods(8,3);
		$wheels = $wheels['items'];

		$this->params[1]["FILTER"] = $this->splitByRows(4,$this->params[1]["FILTER"]);
		$this->params[2]["FILTER"] = $this->splitByRows(4,$this->params[2]["FILTER"]);
		$this->params[3]["FILTER"] = $this->splitByRows(4,$this->params[3]["FILTER"]);

		$this->actionSetCity();	
		$dynamic = $this->getDynObjects(array(
            38 => $_SESSION['city']['variant_id']
    	));

		$this->render('index',array(
			'tires'=> $tires,
			'discs' => $discs,
			'wheels' => $wheels,
			'tire_filter' => $tire_filter,
			'disc_filter' => $disc_filter,
			'wheel_filter' => $wheel_filter,
			'params' => $this->params,
			'dynamic' => $dynamic
		));		
	}

	public function actionCategory($partial = false, $countGood = false) {

		if(!isset($_SESSION)) session_start();

		$good_type = GoodType::model()->findByPk($_GET["type"]);

		$this->keywords = $this->getParam("SHOP", $good_type->code."_KEYWORDS");
		$this->description = $this->getParam("SHOP", $good_type->code."_DESCRIPTION");

		$_GET['type'] = isset($_GET['type']) ? $_GET['type'] : 2;
	  	
		$this->title = "Колесо Онлайн - Б/у ".GoodType::model()->find(array("limit"=>1,"condition"=>"id=".$_GET['type']))->name;

		$filter = $this->getFilter($_GET['type']);

		$last = isset($_GET['last']) ? $_GET['last'] : 1;
		
		if($partial) {
			$last++;
			$_GET['GoodFilter_page'] = $last;
		}

		$_SESSION["FILTER"][$_GET['type']]['sort'] = (isset($_SESSION["FILTER"][$_GET['type']]['sort']))?$_SESSION["FILTER"][$_GET['type']]['sort']:array("field"=>9,"type"=>"DESC");

		$goods = $this->getGoods(40,$_GET['type']); 
		$count = $goods['count'];	
		$pages = $goods['pages'];	
		$allCount = $goods["allCount"];
		$goods = $goods['items'];

		if( $_GET['GoodFilter_page'] >= $pages->pageCount || $pages->pageCount == 1 ) {
			$last = 0;
		}

		$this->actionSetCity();

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
				'dynamic' => $dynamic
			));
		}
	}

	public function getFilter($type = NULL) {

		$criteria=new CDbCriteria();
		$criteria->with = array('good' => array('select'=> false));
        $criteria->condition = 'attribute_id=20 AND good.good_type_id='.$type;
        $criteria->select = array('int_value');
        $criteria->order = 'int_value ASC';

		$model = GoodAttributeFilter::model()->findAll($criteria);
		$this->params[$type]["PRICE_MIN"] = ($model[0]->int_value) ? $model[0]->int_value : 0;
		$this->params[$type]["PRICE_MAX"] = array_pop($model)->int_value;

		if(isset($_GET['int'])) $_SESSION["FILTER"][$type] = $_GET;

		if( !isset($_SESSION["FILTER"][$type]["int"]) || $_SESSION["FILTER"][$type]["int"][20]["min"] == "") {
			$_SESSION["FILTER"][$type]["int"][20]["min"] = $this->params[$type]["PRICE_MIN"];
		}
		if( !isset($_SESSION["FILTER"][$type]["int"]) || $_SESSION["FILTER"][$type]["int"][20]["max"] == "") {
			$_SESSION["FILTER"][$type]["int"][20]["max"] = $this->params[$type]["PRICE_MAX"];
		}

		$check = $this->getChecked(isset($_SESSION["FILTER"][$type]['arr']) ? $_SESSION["FILTER"][$type]['arr'] : array());

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
    	if($type==3) {
        	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16 OR t.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
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

			$this->actionSetCity();
			
			$dynamic = $this->getDynObjects(array(
	            38 => $_SESSION['city']['variant_id']
	    	));

			$this->render('detail',array(
				'good'=>$good,
				'imgs'=>$imgs,
				'params' => $this->params,
				'dynamic' => $dynamic
			));
		}
	}

	public function getGoods($page_size = 8,$type = 2,$filter = NULL,$sort = NULL) {
		if( $sort === NULL )
			$sort = isset($_SESSION["FILTER"][$type]['sort']) ? $_SESSION["FILTER"][$type]['sort'] : NULL;

		if( $filter === NULL )
			$filter = array(
				"good_type_id"=>$type,
				"attributes"=>isset($_SESSION["FILTER"][$type]['arr']) ? $_SESSION["FILTER"][$type]['arr'] : array(),
				"int_attributes"=>isset($_SESSION["FILTER"][$type]['int']) ? $_SESSION["FILTER"][$type]['int'] : array(),
			);

		$goods = Good::model()->filter(
			$filter
		)->sort( 
			$sort
		)->getPage(
			array(
		    	'pageSize'=>$page_size,
		    )
		);
		return $goods;
	}

	public function actionPage($page = NULL)
	{
		if( $page ){
			$page = $this->getPage($page);

			if ($page) {
				$this->keywords = $page->keywords;
				$this->description = $page->description;
				$this->title = "Колесо Онлайн - ".$page->title;
				
				$this->render('page',array(
					"page" => $page
				));	
			}
		}
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
