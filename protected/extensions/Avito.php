<?

Class Avito {
    private $login = "";
    private $password = "";
    public $curl;
    public $captcha_curl;
    public $cat_codes = array(
    	1 => 19,
    	2 => 19,
    	101 => 18
    );
    public $dir_codes = array(
        1 => 10048,
        2 => 10046
    );
    
    function __construct($proxy = NULL) {
    	include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $this->curl = new Curl($proxy);
        $this->captcha_curl = new Curl();
    }

    public function setUser($login,$password){
    	$this->curl->changeCookies($login);
        $this->login = $login;
        $this->password = $password;
    }

    public function auth(){
    	$html = str_get_html($this->curl->request("https://www.avito.ru/"));

        if( is_object($html) && $html->find(".userinfo-details",0) && trim($html->find(".userinfo-details",0)->plaintext) == $this->login )
        	return true;

        echo "Авторизация ".$this->login;
        $this->curl->removeCookies();

        $params = array(
            'next'=>'/profile',
			'login'=>$this->login,
			'password'=>$this->password
        );
        return $this->curl->request("https://www.avito.ru/profile/login",$params);
    }

    public function addAdvert($params,$images = NULL){
    	echo "string";
        Log::debug("Шаг 1"."<br>");
        if($images !== NULL) {
        	$params = $this->addImages($params,$images);
		}
		Log::debug("Шаг 2"."<br>");
		$result = $this->curl->request("https://www.avito.ru/additem");
		sleep(rand(1,5));
		// $result = $this->curl->request("https://www.avito.ru/items/fees",array(
		// 	'locationId' => "657600",
		// 	'parentLocationId' => "657310",
		// 	"categoryId" => 10,
		// 	'params[5]' => 19,
		// 	'email' => "vladis1ove81@gmail.com"
		// 	));
		// print_r($result);
		$html = str_get_html($result);
		Log::debug("Шаг 3"."<br>");
	    $token = array();
	    $token['name'] = $html->find('input[name^=token]',0)->name;
	    $token['value'] = $html->find('input[name^=token]',0)->value;
	    $params[$token['name']] = $token['value'];
	    $params['source'] = "add";

	    // $params['fees[purchase]'] = "single";
	    // $params['fees[locationId]'] = "657600";
	    // $params['fees[parentLocationId]'] = "657310";
	    // $params['fees[microcategories][]'] = "17";
	    // $params['fees[quantity]'] = "10";

	    $result = $this->curl->request("https://www.avito.ru/additem",$params);
	    sleep(rand(1,5));
	    print_r($result);
		$html = str_get_html($result);
		Log::debug("Шаг 4"."<br>");
		$captcha = $html->find('.form-captcha-image',0)->src;

        $out = $this->curl->request('https://www.avito.ru'.$captcha);
        sleep(rand(1,5));
        Log::debug("Шаг 5"."<br>");
        $captcha_name = md5(time()."koleso");
	    $captcha_path = Yii::app()->basePath.'/extensions/captcha/'.$captcha_name.'.jpg';  
	    file_put_contents($captcha_path, $out); 

		$captcha = $this->captcha_curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		Log::debug("Шаг 6"."<br>");
		while ($captcha == 'ERROR_NO_SLOT_AVAILABLE') {
			var_dump($captcha);
			sleep(5);
			Log::debug("Вошли 0"."<br>");
		    $captcha = $this->captcha_curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		} 
		if(strpos($captcha, "|") !== false) {
			Log::debug("Вошли"."<br>");
			$captcha = substr($captcha, 3);
			$url = "http://rucaptcha.com/res.php?";
					$url_params = array(
				    	'key' => '0b07ab2862c1ad044df277cbaf7ceb99',
				    	'action' => 'get',
				    	'id' => $captcha
					);
			$url .= urldecode(http_build_query($url_params));
			Log::debug("Вошли 1"."<br>");
			$captcha = $this->captcha_curl->request($url);
			while ($captcha == 'CAPCHA_NOT_READY') {
				sleep(2);
		   		$captcha = $this->captcha_curl->request($url);
			} 
			Log::debug("Вошли 2"."<br>");
			print_r($captcha."<br>");
			if(strpos($captcha, "|") !== false) {
				Log::debug("Вошли 3"."<br>");
				$captcha = substr($captcha, 3);
				$result_array = array(
					'captcha' => $captcha,
					'subscribe-position' => '0',
					'inn' => "",
					'kpp' => "",
					'companyName' => "",
					'legalAddress' => "",
					'postalAddress' => "",
					'companyManager' => "",
					'companyPhone' => "",
					'bookkeeperAddress' => "",
					'bookkeeperName' => "",
					'bookkeeperEmail' => "",
					'bookkeeperPhone' => "",
					'legalInfoHash' => "",
					'companyNameKa' => "",
					'kppKa' => "",
					'legalAddressKa' => "",
					'done' => "",
    				'action' => "company_info"
				);
				$result = $this->curl->request("https://www.avito.ru/additem/confirm",$result_array);
				file_put_contents(Yii::app()->basePath."/logs/avito.txt", $result);
				print_r($result);
				$html = str_get_html($result);
				Log::debug("Вошли 4"."<br>");
				$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
				print_r($id."<br>");
				$id = end(explode("_", $id));

				unlink($captcha_path);
				return $id;
			} else {
				unlink($captcha_path);
				Log::debug("Вошли 5"."<br>");
				return false;
			}
		} else {
			unlink($captcha_path);
			// Log::debug("Вошли 6");
			return false;
		}
    }

    public function updateAdvert($advert_id,$params,$images = NULL,$only_images = false){
    	$result = $this->curl->request("https://www.avito.ru/".$advert_id);
    	sleep(rand(1,5));
		$html = str_get_html($result);
		if( !$html->find('meta[property="og:url"]',0) ) return NULL;
		$href = $html->find('meta[property="og:url"]',0)->getAttribute('content');
		$href = $href."/edit";

		$result = $this->curl->request($href);
		// print_r($result);
		$html = str_get_html( $result );
		$version = $html->find('input[name="version"]',0)->value;
		if($images !== NULL) {
        	$params = $this->addImages($params,$images);
		} else {
			foreach ( $html->find('input[name="images[]"]') as $i => $image) {
				$params['images['.$i.']'] = $image->value;
			}
		}

		$params['version'] = $version;
		$params['source'] = 'edit';		

		$result = $this->curl->request($href,$params);
		sleep(rand(1,5));
   		// print_r($result);
   		$result = $this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1'));
   		sleep(rand(1,5));
		$html = str_get_html($result);
		// print_r($result);
		$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
		$id = end(explode("_", $id));
		// print_r($id);
		if( $html->find(".alert-warning-big a",0) && $html->find(".alert-warning-big a",0)->plaintext == "активировать его" ){
			$result = $this->curl->request("https://www.avito.ru/profile/items/old?item_id[]=$advert_id&start");
		}
		return $id;
    }

   	public function updatePrice($advert_id,$params,$images = NULL){	
    	$result = $this->curl->request("https://www.avito.ru/".$advert_id);
    	sleep(rand(1,5));
    	// echo "1111";
    	// print_r($result);
		$html = str_get_html($result);
		if( !$html->find('meta[property="og:url"]',0) ) return NULL;
		$href = $html->find('meta[property="og:url"]',0)->getAttribute('content');
		$href = $href."/edit";
		$result = $this->curl->request($href);
		sleep(rand(1,5));
		// echo "2222";
		// print_r($result);
		$html = str_get_html( $result , true, true, DEFAULT_TARGET_CHARSET, false);
		$fields = Advert::model()->with('place.interpreters')->find("url=".$advert_id);
		foreach ($fields->place->interpreters as $key => $value) {
			if(stripos($value->code, "params") === false) {
				if( $value->code != "price" ){
					if( $value->code == "description" ){
						$params[$value->code] = str_replace("&quot;", "''", $html->find('.form-fieldset [name="'.$value->code.'"]',0)->outertext);
					}else{
						$params[$value->code] = str_replace("&quot;", "''", $html->find('.form-fieldset [name="'.$value->code.'"]',0)->value);
					}
				}
			} else {
				if($html->find('[name="'.$value->code.'"] option[selected=""]',0))
					$params[$value->code] = $html->find('[name="'.$value->code.'"] option[selected=""]',0)->value;
			}
		}
		// var_dump($params);
		// $params['price'] = $price;
		unset($params['login']);
		if($images !== NULL) {
        	$params = $this->addImages($params,$images);
		} else {
			foreach ( $html->find('input[name="images[]"]') as $i => $image) {
				$params['images['.$i.']'] = $image->value;
			}
		}
		
		// $params['seller_name'] = $html->find('span[data-read-id="companyName"]',0)->plaintext;
		$params['version'] = $html->find('input[name="version"]',0)->value;
		$params['source'] = 'edit';		

		$result = $this->curl->request($href,$params);
		sleep(rand(1,5));

		// echo "3333";
		// print_r($result);
   		$result = $this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1'));
   		// echo "4444";
   		// print_r($result);
		$html = str_get_html($result);

		$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
		$id = end(explode("_", $id));
		// echo "5555";
		// print_r($id);
		if( $html->find(".alert-warning-big a",0) && $html->find(".alert-warning-big a",0)->plaintext == "активировать его" ){
			$result = $this->curl->request("https://www.avito.ru/profile/items/old?item_id[]=$advert_id&start");
		}
		return $id;
    }

    public function addImages($params, $images = NULL) {
        if($images) {
        	$img = array();
            foreach ($images as $key => $image_path) {
            	if($key == 5) break;
            	$filename = Yii::app()->params['tempFolder']."/".md5(time().rand()).".jpg";
	            $resizeObj = new Resize(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path);
	            $resizeObj -> resizeImageAvito();
	            $quality = rand(65,95);
	            $resizeObj -> saveImage($filename, $quality);
	            Log::debug("Генерация фотки");
                array_push($img, json_decode($this->curl->request("https://www.avito.ru/additem/image",array('image' => new CurlFile($filename))))->id);
                sleep(rand(1,5));
                Log::debug("Отправка фотки");
            	unlink($filename);
            }
            shuffle($img);
            foreach ( $img as $i => $image) {
				$params['images['.$i.']'] = $image;
			}
            return $params;
        }
    }

    public function deleteAdvert($advert_id) {
        $result = $this->curl->request("https://www.avito.ru/profile",array('item_id[]' => $advert_id,'delete' => 'Снять объявление с публикации'));
        sleep(rand(1,5));
        print_r($result);
        $result = $this->curl->request("https://www.avito.ru/".$advert_id);
        sleep(rand(1,5));
        print_r("https://www.avito.ru/".$advert_id);
        print_r($result);
		$html = str_get_html($result);
		if( is_object($html) && $html->find(".catalog-filters",0) ) return true;
		if( !is_object($html) ) return false;
		$delete = trim($html->find('.has-bold',0)->plaintext);

		$result = $this->curl->request("https://www.avito.ru/profile/items/old?item_id[]=$advert_id&remove");
		return ($delete == "Срок размещения этого объявления истёк" || $delete == "Вы закрыли это объявление" || $delete == "Вы удалили это объявление навсегда.");
    }

    public function up($advert_id){

    	$i = 0;
    	$tog = false;
    	do {
    		$i++;
			$result = $this->curl->request("https://www.avito.ru/profile/items/old?item_id[]=$advert_id&start");
			sleep(rand(1,5));
        	$html = str_get_html($this->curl->request("https://www.avito.ru/$advert_id"));
        	$tog = ($html->find(".alert-red",0))?false:true;
    		if( $i > 1 ) sleep(10);
    	}while( $i < 3 && !$tog );
        
        return $tog;
    }

    public function parseMessages(){
    	$html = str_get_html($this->curl->request("https://www.avito.ru/profile/messenger/"));
    	var_dump($html->find(".messenger-channel-text"));
    	foreach ($html->find(".messenger-channel-text") as $i => $chat) {
    		echo $chat->getAttribute("href")."<br>";
    	}
    }

    public function generateFields($fields,$good_type_id){
    	$multi = array("params[798]","params[801]","params[799]","params[800]");
    	foreach ($multi as $key => $m) {
    		if( isset($fields[$m]) ){
    			$fields[$m] = explode("/", $fields[$m]);
    			$fields[$m] = $fields[$m][0];
    		}
    	}

		$fields['authState'] = 'phone-edit';
		$fields['private'] =  0;
		$fields['root_category_id' ] = 1;
		$fields['category_id'] = 10;
		$fields['metro_id'] = "";
		$fields['district_id'] = "";
		$fields['road_id'] = "";
		$fields['params[5]'] = $this->cat_codes[intval($good_type_id)];
		$fields['params[709]'] = $this->dir_codes[intval($good_type_id)];
		$fields['image'] = "";
		$fields['videoUrl'] = "";
		$fields['service_code'] = 'free';
        return $fields;
    }

    public function self(){
        return new Avito();
    }

    public function parseAll($src){
    	$html = str_get_html($this->curl->request($src));
    	sleep(rand(1,5));
    	$links = array();
    	$count = 0;
    	if( $tabs = $html->find(".tabs-item") ){
    		foreach ($tabs as $key => $tab)
    			if( $tab->find("span.tabs-item-title",0) )
    				$count = intval($tab->find(".tabs-item-num",0)->plaintext);

    		$num = ceil($count/10);
    		for ($i=1; $i <= $num; $i++) { 
    			$html = str_get_html($this->curl->request($src."/rossiya?p=".$i));
    			sleep(rand(1,5));
    			if( $images = $html->find(".profile-item-title a") ){
    				$code = "lololo";
    				foreach ($images as $j => $link) {
    					$href = $link->getAttribute("href");
    					$code = substr($href, strripos($href, "_")+1);
    					$links[$code] = str_replace("&quot;", '"', trim($link->plaintext));
    				}
    				if( AvitoAdvert::model()->count("url='$code'") ) break;
    			}

    		}
    	}
    	
    	return array("links" => $links, "count" => $count);
    }

    public function parseAllItems($link,$good_type_id = NULL){
        $html = str_get_html($this->curl->request($link));
        sleep(rand(3,5));
        $links = array();
        $first = NULL;
        $pageLinks = $html->find('.item_table');
        $page = 1;
        while(count($pageLinks)){
            foreach($pageLinks as $element){
            	if($good_type_id) {
	                $tmp = array(
	                    'title' => trim($element->find(".item-description-title-link",0)->plaintext),
	                    'date' => date('Y-m-d H:i:s'),
	                    'type' => $good_type_id,
	                    'params' => NULL,
	                    'views' => NULL,
	                    'amount' => NULL,
	                    'state' => 0,
	                    'price_type' => 0,
	                    'seller' => NULL,
	                    'platform' => 2,
	                    'folder' => 0
	                );
	                $tmp['price'] = (trim($element->find(".description .about",0))) ? intval(str_replace(" ","",$element->find(".description .about",0)->plaintext)) : NULL;   	          
	                $tmp['img'] = ($element->find(".photo-wrapper img",0)) ? $element->find(".photo-wrapper img",0)->src : "/".Yii::app()->params["imageFolder"]."/default.jpg";            
	                if($element->find(".data p",0)->plaintext != "Магазин") $links[substr($element->id, 1)] = $tmp; 
	            }
        	}
            $page++;
            if(strpos($link,"&") === false)
               $html = str_get_html($this->curl->request($link."?p=".$page));
            else $html = str_get_html($this->curl->request($link."&p=".$page));
            $pageLinks = $html->find('.item_table');
        }
        print_r(count($links));
        return $links;
    }

    public function parseCategory() {
        $good_types = array("shiny","diski","kolesa");
        foreach ($good_types as $key => $good_type) {
            $good_type_id = $key+1;
            $goods = array();
            $url = "https://www.avito.ru/tomsk/zapchasti_i_aksessuary/shiny_diski_i_kolesa/$good_type";
            $goods = $this->parseAllItems($url,$good_type_id);   
            $model = Track::model()->findAll("type=$good_type_id AND platform=2 AND state<>2");
            foreach ($model as $good) {
                if(!isset($goods[$good->id])) {
                    $good->state = 1;
                    $good->save();
                } elseif($good->state == 1) {
                    $good->state = 0;
                    $good->save();
                }
            }
            $rows = array();
            foreach($goods as $advert_id => $good) {
                array_push($rows, array($advert_id,$good['title'],$good['params'],$good['price'],$good['views'],$good['amount'],$good['img'],$good['date'],$good['type'],$good['state'],$good['price_type'],$good['seller'],$good['platform'],$good['folder']));
            }    
            Controller::updateRows(Track::tableName(),$rows,array('title','price','img'));  
        	Log::debug("отслеживание $good_type завершено");
        } 

    }
}

?>