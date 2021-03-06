<?php

class KolesoOnlineController extends Controller
{
	public $layout='//layouts/kolesoOnline';
	public $title = "Купить колеса, шины и диски [+IN+] в магазине Колесо.Онлайн";
	public $description = "Лучший выбор автомобильных бу шин и дисков из Японии. Удобный поиск и выгодные цены, а самое главное честное описание и фото. Мы постоянно работаем над расширением географии наших представительств на территории РФ.";
	public $keywords = "Лучший выбор автомобильных бу шин и дисков из Японии. Удобный поиск и выгодные цены, а самое главное честное описание и фото. Мы постоянно работаем над расширением географии наших представительств на территории РФ.";
	public $image = "";

	public $params = array(
		1 => array(
			"NAME" => "Шины",
			"TITLE_CODE" => 66,
			"HEADER_DESCRIPTION_CODE" => 226,
			"TITLE_2_CODE" => 30,
			"DESCRIPTION_CODE" => 72,
			"GARANTY_CODE" => 70,
			"PRICE_CODE" => 213,
			"ORDER" => 165,
			"SEASON" => 23,
			'SHIPPING' => 73,
			'AVAILABLE' => 112,
			"FILTER" => array(
				9 => "Диаметр",
				7 => "Ширина",
				8 => "Профиль",
				16 => "Модель",
				10 => "Год выпуска шины",
				28 => "Количество в комплекте",
				27 => "Город нахождения товара"
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
					"ID" => 41,
					"LABEL" => "Остаток протектора",
					"UNIT" => ' мм.',
					"TYPE" => "INTER"
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
			"HEADER_DESCRIPTION_CODE" => 227,
			"TITLE_2_CODE" => 68,
			"DESCRIPTION_CODE" => 71,
			"GARANTY_CODE" => 69,
			"PRICE_CODE" => 214,
			"ORDER" => 164,
			'SHIPPING' => 74,
			'AVAILABLE' => 114,
			"FILTER" => array(
				9 => "Диаметр",
				5 => "Сверловка",
				31 => "Ширина",
				32 => "Вылет",
				28 => "Количество в комплекте",
				27 => "Город нахождения товара"
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
				"WEIGHT" => array(
					"ID" => 34,
					"LABEL" => "Масса одного диска",
					"UNIT" => ' кг.'
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
			"HEADER_DESCRIPTION_CODE" => 228,
			"TITLE_2_CODE" => 171,
			"DESCRIPTION_CODE" => 167,
			"GARANTY_CODE" => 168,
			"PRICE_CODE" => 215,
			"ORDER" => 166,
			"SEASON" => 23,
			'SHIPPING' => 174,
			'AVAILABLE' => 172,
			"FILTER" => array(
				9 => "Диаметр",
				7 => "Ширина шины",
				8 => "Профиль",
				// 16 => "Модель шины",
				5 => "Сверловка",
				31 => "Ширина диска",
				32 => "Вылет",
				28 => "Количество в комплекте",
				27 => "Город нахождения товара"
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
					"ID" => 203,
					"LABEL" => "Остаток протектора",
					"UNIT" => ' мм.',
					"TYPE" => "INTER"
				),
				"CONDITION" => array(
					"ID" => 26,
					"LABEL" => "Состояние товара",
					"UNIT" => ' '
				),
				"WEIGHT" => array(
					"ID" => 34,
					"LABEL" => "Масса одного диска",
					"UNIT" => ' кг.'
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

        if( Yii::app()->params["site"] == "shikon" && isset($this->params) ){
            $this->params[1]["HEADER_DESCRIPTION_CODE"] = 230;
        }

        $this->image = Yii::app()->getBaseUrl(true)."/html/i/logo-vk.jpg";

    }

    public function beforeaction($action){
    	if (!parent::beforeAction($action)) {
	        return false;
	    }
	    
		if( Yii::app()->controller->action->id == "detail" || Yii::app()->controller->action->id == "index" || Yii::app()->controller->action->id == "category" || Yii::app()->controller->action->id == "page" || Yii::app()->controller->action->id == "mail" || Yii::app()->controller->action->id == "basket" || Yii::app()->controller->action->id == "cart" || Yii::app()->controller->action->id == "search" || Yii::app()->controller->action->id == "pay" || Yii::app()->controller->action->id == "thanks"){
			$this->checkCity();
		}
		return true;
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
				'actions'=>array('index', 'search', 'index2', 'detail','page','mail','category','getCities','setCity','basket','cart','pay','paymentNotification'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

    public function getCityGroups() {
     	$city_groups = array();
     	$model = Attribute::model()->with(array("variants.variant"))->findAll(array("order"=>"t.id ASC, variant.sort ASC","condition"=>"folder=1"));
     	$dictionary_tmp = DictionaryVariant::model()->findAll("dictionary_id=125");
     	$dictionary = array();
     	foreach ($dictionary_tmp as $key => $dic)
     		$dictionary[$dic->attribute_1] = $dic->value;

     	foreach ($model as $key => $group) {
     		$city_groups[$group->name] = array();
     		foreach($group->variants as $i => $city) {
     			$city_groups[$group->name][$i]['name'] = $city->value;
     			$city_groups[$group->name][$i]['variant_id'] = $city->variant_id;
     			$city_groups[$group->name][$i]['code'] = $dictionary[$city->variant_id];
     		}
     		$city_groups[$group->name] = $this->splitByRows(10,$city_groups[$group->name]);
     	}

     	return $city_groups;
    }


	public function actionIndex($countGood = false,$thanks = false)
	{	
		if( Yii::app()->params["site"] == "shikon" ){
			$this->title = "Шикон - Купить колеса, шины и диски [+IN+]";
		}
		// if($this->beginCache(Yii::app()->params["region"]."_".Yii::app()->params["city"]->id."_".$this->is_mobile.( (isset($_GET["thanks"]))?"_t":"_n" ), array('duration'=>5*60))) { 
		$this->keywords = $this->getParam("SHOP","MAIN_KEYWORDS");
		$this->description = $this->getParam("SHOP","MAIN_DESCRIPTION");

		$count = (Yii::app()->params["site"] == "shikon")?10:8;
		$tire_filter = $this->getFilter(1);
		$disc_filter =  $this->getFilter(2);
		$wheel_filter =  $this->getFilter(3);
       	$tires = $this->getGoods($count,1,array(
			"good_type_id"=>1,
			"attributes" => array()
		),array("field"=>3,"type"=>"DESC")); 
		$tires = $tires['items'];

		$discs = $this->getGoods($count,2,array(
			"good_type_id"=>2,
			"attributes"=> array()
		),array("field"=>3,"type"=>"DESC"));
		$discs = $discs['items'];

		$wheels = $this->getGoods($count,3,array(
			"good_type_id"=>3,
			"attributes"=> array( 27 => array(1037, 1034) )
		));
		$wheels = $wheels['items'];

		$this->params[1]["FILTER"] = $this->splitByRows(4,$this->params[1]["FILTER"]);
		$this->params[2]["FILTER"] = $this->splitByRows(4,$this->params[2]["FILTER"]);
		$this->params[3]["FILTER"] = $this->splitByRows(4,$this->params[3]["FILTER"]);

		$dynamic = $this->getDynObjects(array(
            38 => Yii::app()->params["city"]->id
    	));

		$this->render($this->getView('index'),array(
			'tires'=> $tires,
			'discs' => $discs,
			'wheels' => $wheels,
			'tire_filter' => $tire_filter,
			'disc_filter' => $disc_filter,
			'wheel_filter' => $wheel_filter,
			'params' => $this->params,
			'dynamic' => $dynamic,
			'mobile' => $this->is_mobile,
			'thanks' => $thanks
		));		
		// $this->endCache(); }
	}

	public function actionCategory($partial = false, $countGood = false) {
		if(isset($_GET["arr"])) foreach ($_GET["arr"] as $i => $item) {
			if( count($item) == 1 && $item[0] == "" )
				unset($_GET["arr"][$i]);
		}
		$cache_name = Yii::app()->params["region"]."_".Yii::app()->params["city"]->id."_".$this->is_mobile;
		if( isset($_POST) )
			$cache_name .= ("_".md5(json_encode($_POST)));

		if( isset($_GET) )
			$cache_name .= ("_".md5(json_encode($_GET)));

		// Для избежания проблем с кэшированием
		unset($_SESSION["FILTER"][$_GET['type']]);

		// if($this->beginCache($cache_name, array('duration'=>5*60))) { 
			$start = microtime(true);	
			if(!isset($_SESSION)) session_start();

			$good_type = GoodType::model()->findByPk($_GET["type"]);

			$this->keywords = $this->getParam("SHOP", $good_type->code."_KEYWORDS");
			$this->description = $this->getParam("SHOP", $good_type->code."_DESCRIPTION");

			$_GET['type'] = isset($_GET['type']) ? $_GET['type'] : 2;
		  	
		  	if( Yii::app()->params["site"] == "shikon" ){
		  		$this->title = "Шикон - купить ".mb_strtolower(GoodType::model()->find(array("limit"=>1,"condition"=>"id=".$_GET['type']))->name, "UTF-8")." [+IN+]";
		  	}else{
		  		$this->title = "Купить ".mb_strtolower(GoodType::model()->find(array("limit"=>1,"condition"=>"id=".$_GET['type']))->name, "UTF-8")." [+IN+] в магазине Колесо.Онлайн";
		  	}

			$filter = $this->getFilter($_GET['type']);

			$last = isset($_GET['last']) ? $_GET['last'] : 1;
			if($partial) {
				$last++;
				$_GET['GoodFilter_page'] = $last;
			}

			$def_field = (intval($_GET['type']) == 1)?8:9;
			$def_field = NULL;
			$def_sort = ( intval($_GET['type']) == 1 )?"ASC":"DESC";
			$def_sort = NULL;
			$_SESSION["FILTER"][$_GET['type']]['sort'] = (isset($_SESSION["FILTER"][$_GET['type']]['sort']))?$_SESSION["FILTER"][$_GET['type']]['sort']:array("field"=>$def_field,"type"=>$def_sort);
			if( isset($_SESSION["FILTER"][$_GET['type']]["arr"][43]) ){
				unset($_SESSION["FILTER"][$_GET['type']]["arr"][43]);
			}
			// if( !$this->user ){
			// 	$_SESSION["FILTER"][$_GET['type']]["arr"][43] = array(1418,1419,1857,1860);
			// }else{
			// print_r($_SESSION["FILTER"][2]);
			// }
		
			$goods = $this->getGoods(40,$_GET['type'],NULL,NULL,true); 
			$similar = $this->similarGoods($goods['ids']);

			$count = $goods['goods']['count'];	
			$pages = $goods['goods']['pages'];	
			$allCount = $goods['goods']["allCount"];
			$goods = $goods['goods']['items'];

			if( $_GET['GoodFilter_page'] >= $pages->pageCount || $pages->pageCount == 1 ) {
				$last = 0;
			}

			$dynamic = $this->getDynObjects(array(
	            38 => Yii::app()->params["city"]->id
	    	));

			$mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
			// $mobile = true;

			$start = microtime(true);
			if($partial) {
				$options = array(
					'goods'=> $goods,
					'last' => $last,
					'params' => $this->params,
					'type' => $_GET['type'],
					'dynamic' => $dynamic,
					'pages' => $pages,
					'partial' => true
				);
				$this->renderPartial($this->getView('_list'),$options);
			} else {
				$this->render($this->getView('category'),array(
					'goods'=> $goods,
					'similar' => $similar,
					'filter' => $filter,
					'pages' => $pages,
					'last' => $last,
					'params' => $this->params,
					'dynamic' => $dynamic,
					'mobile' => $this->is_mobile,
					'pages' => $pages,
					'start' => $start,
					'partial' => false
				));
			}
			// if( !Yii::app()->user->isGuest ){
			// 	list($queryCount, $queryTime) = Yii::app()->db->getStats();
			// 	echo "Query count: $queryCount, Total query time: ".sprintf('%0.5f',$queryTime)."s";
			// 	printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);
			// }
			// $this->endCache(); 
		// }
	}


	public function similarGoods($good = NULL,$detail = false) {
		if(isset($_SESSION["FILTER"][$_GET['type']]["arr"]) || $detail) {
			if($_GET['type'] == 1) { $detail_arr = array(23,9,7,8); $attrs = array(7,8); $deltas = array(10,5); }
			if($_GET['type'] == 2) { $detail_arr = array(9,5,31,32); $attrs = array(31,32); $deltas = array(0.5,5); }
			if($_GET['type'] == 3) { $detail_arr = array(23,9,7,8,5,31,32); $attrs = array(7,8,31,32); $deltas = array(10,5,0.5,5); }
			$filter = array();
			$show = false;

			if($detail) {
				foreach ($detail_arr as $key => $value) {
					if(isset($good->fields_assoc[$value])) {
						$filter[$value] = array();
						if(is_array($good->fields_assoc[$value])) {
							foreach ($good->fields_assoc[$value] as $item) {
								array_push($filter[$value],$item->variant_id);
							}
						} else array_push($filter[$value],$good->fields_assoc[$value]->variant_id);
					}
				}
				$good = array($good->id);
			} else $filter = $_SESSION["FILTER"][$_GET['type']]["arr"];

			foreach ($attrs as $i => $attr) {
				if(isset($filter[$attr])) {
					$show = true;
					$delta = $deltas[$i];
					foreach ($filter[$attr] as $key => $value) {
						$val = AttributeVariant::model()->with("variant")->find("attribute_id=".$attr." AND variant_id=".$value)->variant->value;
						$model = AttributeVariant::model()->with("variant")->findAll("attribute_id=".$attr." AND value >=".($val-$delta)." AND value <=".($val+$delta));
						foreach ($model as $item) {
							if (array_search($item->variant_id, $filter[$attr]) === false) {
								array_push($filter[$attr], $item->variant_id);
							}
						}
					}	
				}			
			}
			if($show) {
				$similar = $this->getGoods(40,$_GET['type'],array(
						"good_type_id"=>$_GET['type'],
						"attributes"=>$filter,
						"int_attributes"=> array(),
					),NULL,$good); 
				$similar = $similar['items'];
				return $similar;
			}		
		}
		return array();
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
                'condition' => 'good_type_id='.$type." AND archive=0"
                ),
            'variant'
        );
	
		$criteria->condition = 't.attribute_id=9 OR t.attribute_id=43 OR t.attribute_id=27 OR t.attribute_id=28 OR ';
        if($type==1) {
        	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16 OR t.attribute_id=10';
    	}	
    	if($type==2) {
        	$criteria->condition .= 't.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
    	}	
    	if($type==3) {
        	$criteria->condition .= 't.attribute_id=7 OR t.attribute_id=8 OR t.attribute_id=23 OR t.attribute_id=16 OR t.attribute_id=10 OR t.attribute_id=6 OR t.attribute_id=5 OR t.attribute_id=31 OR t.attribute_id=32';
    	}	
    	$criteria->group = 't.variant_id';
        $criteria->order = 'variant.sort ASC';

        if( Yii::app()->params["region"] ){
        	$ids = Good::model()->filter(
	            array(
	                "good_type_id"=>$type,
	                "attributes"=>$this->regionFilter()
	            )
	        )->ids;
	        if( count($ids) ) 
	        	$criteria->addInCondition("good_id", $ids);
        }
        $model = GoodAttribute::model()->findAll($criteria);
        $filter = array();

		foreach ($model as $i => $item) {
			if( $item->value == "" && !$item->value ) continue;
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

		// if( Yii::app()->params["region"] ){
		// 	foreach ($filter as $key => &$attr) {
		// 		if($key == 16) {
		// 			$attr = $this->splitByCols(8,$attr);
		// 		} else $attr = $this->splitByRows(3,$attr);
		// 	}
		// }else{
			foreach ($filter as &$attr) {
				if( !$this->is_mobile ){
					if(count($attr) > 15) {
						$attr = $this->splitByRows(10,$attr);
					} else $attr = $this->splitByRows(5,$attr);
				}else{
					$attr = array($attr);
				}
			}
		// }

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
		// $this->checkCity();

		if($id) {
			$url = $id;
			$id = $this->getCodeFromUrl($id);

			$good = Good::model()->with("fields")->find("good_type_id=".$_GET['type']." AND fields.attribute_id=3 AND fields.varchar_value='".$id."'");
			if( !$good ) throw new CHttpException(404,'site');
			if( $good->code != $url && $good->code !== NULL ){
				header("HTTP/1.1 301 Moved Permanently"); 
				header("Location: http://".$_SERVER["SERVER_NAME"].Yii::app()->createUrl('/kolesoOnline/detail',array('id' => $good->code,'type' => $type))); 
				exit(); 
			}

			$dynamic = $this->getDynObjects(array(
	            38 => Yii::app()->params["city"]->id
	    	));

			$good = Good::model()->with("type","fields.variant","fields.attribute")->findByPk($good->id);

			$good_title = Interpreter::generate($this->params[$_GET['type']]["TITLE_CODE"], $good, $dynamic);
			$this->title = "Купить ".mb_strtolower(mb_substr($good_title, 0, 1, "UTF-8"),"UTF-8").mb_substr($good_title, 1, strlen($good_title), "UTF-8")." [+IN+]";
			$this->description = Interpreter::generate($this->params[$_GET['type']]["HEADER_DESCRIPTION_CODE"], $good, $dynamic);
			$this->keywords = Interpreter::generate($this->getParam("SHOP",$good->type->code."_KEYWORDS_CODE"), $good, $dynamic);

			if( $this->user || Yii::app()->params["site"] == "shikon" ){
				$imgs = $good->getImages(NULL, NULL, "all", NULL, true);
			}else{
				$imgs = $good->getImages(NULL, NULL, 1, NULL, true);
			}
			$this->image = Yii::app()->getBaseUrl(true).$imgs[0]["big"];

			$partner = NULL;

			if( $this->user && isset($good->fields_assoc[106]) && isset($good->fields_assoc[43]) && $good->fields_assoc[43]->attribute->label ){
				$nick = Dictionary::get($good->fields_assoc[43]->attribute->label, $good->fields_assoc[43]->variant_id);
				$partner = array("label" => $nick, "link" => $good->fields_assoc[106]->value);
			}

			$this->render($this->getView('detail'),array(
				'good'=>$good,
				'similar' => $this->similarGoods($good,true),
				'partner'=>$partner,
				'imgs'=>$imgs,
				'params' => $this->params,
				'dynamic' => $dynamic,
				'good_title' => $good_title
			));
		}
	}

	public function actionBasket($id = NULL,$add = false)
	{
		if(!isset($_SESSION)) session_start();
		if($id) {
			if($add) {
				if(!isset($_SESSION["BASKET"])) {
					$_SESSION["BASKET"] = array($id);
				} elseif(array_search($id, $_SESSION["BASKET"]) === false) {	
					array_push($_SESSION["BASKET"], $id);
				}
				$goods = Good::model()->with("type","fields.variant","fields.attribute")->findAllByPk($id);
				$options = array(
					'goods'=> $goods,
					'partial' => true
				);
				$this->renderPartial($this->getView('_basket'),$options);
			} else if( $_SESSION["BASKET"] != NULL ){
				$key = array_search($id, $_SESSION["BASKET"]);
				if($key !== false)
					unset($_SESSION["BASKET"][$key]);
			}		
		}	
	}

	public function actionCart($id = NULL)
	{
		if(!isset($_SESSION)) session_start();
		$goods = array();
		if($id)
			$_SESSION["BASKET"] = array($id);
		if(isset($_SESSION["BASKET"]) && count($_SESSION["BASKET"])) {
			foreach ($_SESSION["BASKET"] as $key => $value) {
		        if(Good::model()->findByPk($value,"archive <> 0")) unset($_SESSION["BASKET"][$key]);
		    }
		    
		    if(count($_SESSION["BASKET"])) {
			    $goods = Good::model()->with("type","fields.variant","fields.attribute")->findAllByPk($_SESSION["BASKET"]);
			    $array = array();
			    foreach ($_SESSION["BASKET"] as $key => $value) {
			        foreach ($goods as $good) {
			            if($good->id == $value)
			                $array[$value] = $good;
			        }
			       
			    }
			}
		}

		$dynamic = $this->getDynObjects(array(
		    38 => Yii::app()->params["city"]->id
		));
		$this->render($this->getView('cart'),array(
			'goods'=> $goods,
			'dynamic' => $dynamic
		));	
	}


	public function getCodeFromUrl($url){
		$arr = explode("-", $url);
		if(!count($arr)) return NULL;

		$last = array_pop($arr);
		if( strlen($last) == 1 ){
			if( !count($arr) ) return NULL;
			$prev = array_pop($arr);
			return $prev."-".$last;
		}
		return $last;
	}

	public function getGoods($page_size = 8,$type = 2,$filter = NULL,$sort = NULL,$ids = NULL,$need_ids = NULL) {
		if( $sort === NULL )
			$sort = isset($_SESSION["FILTER"][$type]['sort']) ? $_SESSION["FILTER"][$type]['sort'] : NULL;

		if( $filter === NULL )
			$filter = array(
				"good_type_id"=>$type,
				"attributes"=>isset($_SESSION["FILTER"][$type]['arr']) ? $_SESSION["FILTER"][$type]['arr'] : array(),
				"int_attributes"=>isset($_SESSION["FILTER"][$type]['int']) ? $_SESSION["FILTER"][$type]['int'] : array(),
			);

		if( !isset($filter["attributes"]) ){
			$filter["attributes"] = $this->regionFilter();
		}else{
			$filter["attributes"] = $filter["attributes"] + $this->regionFilter();
		}

		$goods = Good::model()->filter(
			$filter,$need_ids,$ids
		);

		if($ids === true) $ids_arr = $goods->ids;
		$goods = $goods->sort( 
			$sort
		)->getPage(
			array(
		    	'pageSize'=>$page_size,
		    )
		);
		if($ids === true) {
			return array("goods" => $goods,"ids" => $ids_arr);
		} else return $goods;
	}

	public function actionPage($page = NULL)
	{
		if( $page ){
			// $this->checkCity();

			$page = $this->getPage( $page );

			if ($page) {
				$this->keywords = $page->keywords;
				$this->description = $page->description;
				if( Yii::app()->params["site"] == "shikon" ){
					$this->title = "Шикон - ".$page->title;
				}else{
					$this->title = "Колесо Онлайн - ".$page->title;
				}
				
				$this->render($this->getView('page'),array(
					"page" => $page
				));	
			}else{
				throw new CHttpException(404,'site');
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

	public function actionMail($type = NULL){
        require_once("phpmail.php");

        // $this->checkCity();

        $email_admin = $this->getParam("SHOP","EMAILS");

        $from = "Шикон";
        $email_from = "shikon@shikon.ru";

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

            $message .= "<div><p><b>Город: </b>".Yii::app()->params["city"]->name."</p></div>";

            if( isset($_POST["good-url"]) )
            	$message .= "<div><p><b>Товар: </b><a target='_blank' href='".$_POST["good-url"]."'>".$_POST["good"]."</a></p></div>";
            
            if($type == "order") {
            	foreach ($_SESSION["BASKET"] as $key => $value) {
            		$good = Good::model()->with("type","fields.variant","fields.attribute")->findByPk($value);
            		$good_type_id = $good->good_type_id; 
            		$code = ($good->code)?$good->code:$good->fields_assoc[3]->value;
            		$href = "http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('/kolesoOnline/detail',array('id' => $code,'type' => $good_type_id));
            		if($good_type_id == 1) $title = $good->fields_assoc[16]->value." ".$good->fields_assoc[17]->value;
	                if($good_type_id == 2) $title = $good->fields_assoc[6]->value;
	                if($good_type_id == 3) $title = Interpreter::generate($this->params[$good_type_id]["TITLE_CATEGORY"], $good,$dynamic);

            		$message .= "<div><p><b>Товар: </b>".$title." ".Interpreter::generate($this->params[$good_type_id]["TITLE_2_CODE"], $good,$dynamic)." код товара: ".$good->fields_assoc[3]->value." <a target='_blank' href='".$href."'>(".$href.")</a></p></div>";
            	}
            }

            $message .= "</div>";
            if(send_mime_mail("Сайт ".$from,$email_from,$name,$email_admin,'UTF-8','UTF-8',$subject,$message,true)){    
                if($type == "order") {
                	$_SESSION["BASKET"] = array();
                	header("Location: /thanks");
                }
                echo "1";
            }else{
                echo "0";
            }
        }
    }

    public function actionSearch($search,$query = NULL,$type= NULL, $partial = false){
    	$criteria=new CDbCriteria();
    	$search_str = $search;
		$search = explode(" ", $search);
		// if( !$this->user ){
			// $criteria->condition = "1=1";
		// }
		$values = array();
		foreach ($search as $i => $val) {
			array_push($values, "value LIKE '%$val%'");
		}
		if(!$partial) {
			if(!$type) {
				$data = Yii::app()->db->createCommand()
		            ->select(array('COUNT(good_type_id)','good_type_id'))
		            ->from(Search::tableName().' s')
		            ->join(Good::tableName().' g', 'g.id=s.good_id AND g.archive=0')
		            ->where(implode(" AND ", $values))
		            ->group("good_type_id")
		            ->queryAll();
		        $max = max($data[0]['COUNT(good_type_id)'],$data[1]['COUNT(good_type_id)'],$data[2]['COUNT(good_type_id)']);
		        if($max) {
			        if($max == $data[0]['COUNT(good_type_id)']) $type = $data[0]['good_type_id'];
			        if($max == $data[1]['COUNT(good_type_id)']) $type = $data[1]['good_type_id'];
			        if($max == $data[2]['COUNT(good_type_id)']) $type = $data[2]['good_type_id'];
			    } else $type = 2;
		    }
		    $data = Yii::app()->db->createCommand()
	        ->select('*')
	        ->from(Search::tableName().' s')
	        ->join(Good::tableName().' g', 'g.id=s.good_id AND g.archive=0 AND g.good_type_id='.$type)
	        ->where(implode(" AND ", $values))
	        ->limit(100)
	        ->queryAll();
	    } else {
		    $data = Yii::app()->db->createCommand()
		        ->select('*')
		        ->from(Search::tableName().' s')
		        ->join(Good::tableName().' g', 'g.id=s.good_id AND g.archive=0')
		        ->where(implode(" AND ", $values))
		        ->limit(15)
		        ->queryAll();
		}

        $model = array();
        foreach ($data as $i => $value){
        	array_push($model, (object) $value);
    	}
		$ids = array();
		$titles = array();

		foreach ($model as $key => $value){
			array_push($ids, $value->good_id);
			$tmp = explode(" ", $value->value);
			$tmp = "<b>".array_shift($tmp)."</b> ".implode(" ", $tmp);
			$titles[$value->good_id] = $tmp;
		}

		if( count($ids) ){
			if($partial) {
				$goods = Yii::app()->db->createCommand()
		            ->select('g.id, a.varchar_value, g.good_type_id, t.code')
		            ->from(Good::tableName().' g')
		            ->join(GoodType::tableName().' t', 't.id=g.good_type_id')
		            ->join(GoodAttribute::tableName().' a', 'g.id=a.good_id')
		            ->where("a.attribute_id=3 AND g.id IN (".implode(",", $ids).")")
		            ->order("a.varchar_value ASC")
		            ->queryAll();
		    } else {
		    	$dynamic = $this->getDynObjects(array(
		            38 => Yii::app()->params["city"]->id
		    	));
		    	$goods = $this->getGoods(100,$type,NULL,NULL,NULL,$ids);
		    }
		}


		if($partial) {
	    	$this->renderPartial($this->getView('_search'),array(
				'goods' => $goods,
				'titles' => $titles,
				'query' => $query
			));
    	} else {
    		$this->render($this->getView('search'),array(
    			'search' => $search_str,
				'goods' => $goods['items'],
				'type' => $type,
				'dynamic' => $dynamic
			));
    	}
    }

    public function actionPay($order_id = NULL) {
    	// header('Status: 200 Ok');
    	if($order_id) {
    		$goods = OrderGood::model()->findAll("order_id=$order_id");
    		if($goods) {
	    		$good_ids = array();
	    		foreach ($goods as $key => $value) {
	    			array_push($good_ids, $value->good_id);
	    		}
	    		$goods = Good::model()->with("type","fields.variant","fields.attribute")->findAll("t.id IN(".implode (',',$good_ids).") AND archive = 0");
	    	}
	    	$this->renderPartial($this->getView('pay'),array(
				'goods'=> $goods,
				'dynamic' => $dynamic,
				'partial' => true,
				'order_id' => $order_id
			));
    	} else {
    		$this->render($this->getView('pay'),array(
				'goods'=> array(),
				'partial' => false
			));
    	}
	}

	public function actionPaymentNotification() {
		if( $order = Order::model()->findByPk($_POST['LMI_PAYMENT_NO']) ) {
			$hash = "";
			$names = array(
				'LMI_MERCHANT_ID', 
				'LMI_PAYMENT_NO', 
				'LMI_SYS_PAYMENT_ID', 
				'LMI_SYS_PAYMENT_DATE', 
				'LMI_PAYMENT_AMOUNT', 
				'LMI_CURRENCY', 
				'LMI_PAID_AMOUNT', 
				'LMI_PAID_CURRENCY', 
				'LMI_PAYMENT_SYSTEM', 
				'LMI_SIM_MODE'
			);
			foreach ($names as $name) {
				if(isset($_POST[$name])) $hash .= $_POST[$name];
				$hash .= ";";
			}
			$hash .= "acrobat";
			if($_POST['LMI_HASH'] == base64_encode(md5($hash, true))) {
				$order->state_id = 167;
				$order->save();
				foreach ($order->goods as $key => $item) {
					$good = Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk($item->good_id);
					if($good->archive != 1) $good->sold();
				}
				
			}
		}
	}
}
