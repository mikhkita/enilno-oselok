<?

Class Avito {
    private $login = "";
    private $password = "";
    public $curl;
    public $captcha_curl;
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
	    print_r($result);
		$html = str_get_html($result);
		Log::debug("Шаг 4"."<br>");
		$captcha = $html->find('.form-captcha-image',0)->src;

        $out = $this->curl->request('https://www.avito.ru'.$captcha);
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
		$html = str_get_html($result);
		if( !$html->find('meta[property="og:url"]',0) ) return NULL;
		$href = $html->find('meta[property="og:url"]',0)->getAttribute('content');
		$href = $href."/edit";

		$result = $this->curl->request($href);
		// print_r($result);
		$html = str_get_html( $this->curl->request($href));
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
   		// print_r($result);
   		$result = $this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1'));
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
    	// echo "1111";
    	// print_r($result);
		$html = str_get_html($result);
		if( !$html->find('meta[property="og:url"]',0) ) return NULL;
		$href = $html->find('meta[property="og:url"]',0)->getAttribute('content');
		$href = $href."/edit";
		$result = $this->curl->request($href);
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
        print_r($result);
        $result = $this->curl->request("https://www.avito.ru/".$advert_id);
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
		$fields['params[5]'] = 19;
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
    	$links = array();
    	$count = 0;
    	if( $item = $html->find(".tabs-item_active .tabs-item__num", 0) ){
    		$count = intval($item->plaintext);
    		$num = ceil($count/10);
    		for ($i=1; $i <= $num; $i++) { 
    			$html = str_get_html($this->curl->request($src."/rossiya?p=".$i));
    			if( $images = $html->find(".photo-wrapper") ){
    				foreach ($images as $j => $link) {
    					array_push($links, $link->getAttribute("href"));
    				}
    			}
    		}
    	}
    	
    	return array("links" => $links, "count" => $count);
    }
}

?>