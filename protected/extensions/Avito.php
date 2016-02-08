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
        $this->curl = new Curl($proxy);
        $this->captcha_curl = new Curl();
    }

    public function setUser($login,$password){
        $this->login = $login;
        $this->password = $password;
    }

    public function auth(){
        $this->curl->removeCookies();

        $params = array(
            'next'=>'/profile',
			'login'=>$this->login,
			'password'=>$this->password
        );
        return $this->curl->request("https://www.avito.ru/profile/login",$params);
    }

    public function addAdvert($params,$images = NULL){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        Log::debug("Шаг 1");
        if($images !== NULL) {
        	$params = $this->addImages($params,$images);
		}
		Log::debug("Шаг 2");
		$html = str_get_html($this->curl->request("https://www.avito.ru/additem"));
		Log::debug("Шаг 3");
	    $token = array();
	    $token['name'] = $html->find('input[name^=token]',0)->name;
	    $token['value'] = $html->find('input[name^=token]',0)->value;
	    $params[$token['name']] = $token['value'];
	    $params['source'] = "add";

		$html = str_get_html($this->curl->request("https://www.avito.ru/additem",$params));
		Log::debug("Шаг 4");
		$captcha = $html->find('.form-captcha-image',0)->src;

        $out = $this->curl->request('https://www.avito.ru'.$captcha);
        Log::debug("Шаг 5");
	    $captcha_path = Yii::app()->basePath.'/extensions/captcha.jpg';  
	    file_put_contents($captcha_path, $out); 

		$captcha = $this->captcha_curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		Log::debug("Шаг 6");
		while ($captcha == 'ERROR_NO_SLOT_AVAILABLE') {
			var_dump($captcha);
			sleep(5);
			Log::debug("Вошли 0");
		    $captcha = $this->captcha_curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		} 
		if(strpos($captcha, "|") !== false) {
			Log::debug("Вошли");
			$captcha = substr($captcha, 3);
			$url = "http://rucaptcha.com/res.php?";
					$url_params = array(
				    	'key' => '0b07ab2862c1ad044df277cbaf7ceb99',
				    	'action' => 'get',
				    	'id' => $captcha
					);
			$url .= urldecode(http_build_query($url_params));
			Log::debug("Вошли 1");
			$captcha = $this->captcha_curl->request($url);
			while ($captcha == 'CAPCHA_NOT_READY') {
				sleep(2);
		   		$captcha = $this->captcha_curl->request($url);
			} 
			Log::debug("Вошли 2");
			Log::debug($captcha);
			if(strpos($captcha, "|") !== false) {
				Log::debug("Вошли 3");
				$captcha = substr($captcha, 3);
				$result_array = array(
					'captcha' => $captcha,
					'subscribe-position' => '0',
    				'action' => "company_info"
				);
				$result = $this->curl->request("https://www.avito.ru/additem/confirm",$result_array);
				$html = str_get_html($result);
				Log::debug("Вошли 4");
				$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
				Log::debug($id);
				file_put_contents(Yii::app()->basePath."/logs/avito.txt", $result);
				$id = end(explode("_", $id));
				return $id;
			} else {
				Log::debug("Вошли 5");
				return false;
			}
		} else {
			Log::debug("Вошли 6");
			return false;
		}
    }

    public function updateAdvert($advert_id,$params,$images = NULL,$only_images = false){
    	include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
    	$result = $this->curl->request("https://www.avito.ru/".$advert_id);
		$html = str_get_html($result);
		if( !$html->find('meta[property="og:url"]',0) ) return NULL;
		$href = $html->find('meta[property="og:url"]',0)->getAttribute('content');
		$href = $href."/edit";

		$result = $this->curl->request($href);
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
		// $params['private'] = '1';	

		$result = $this->curl->request($href,$params);
		print_r($result);
   
   		$result = $this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1'));
   		print_r($result);
		$html = str_get_html($result);

		$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
		$id = end(explode("_", $id));
		
		if( $html->find(".alert-warning-big a",0) && $html->find(".alert-warning-big a",0)->plaintext == "активировать его" ){
			$result = $this->curl->request("https://www.avito.ru/profile/items/old?item_id[]=$advert_id&start");
			// print_r($result);
		}

		return $id;
    }
   				
    public function addImages($params,$images = NULL) {
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
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $result = $this->curl->request("https://www.avito.ru/profile",array('item_id[]' => $advert_id,'delete' => 'Снять объявление с публикации'));
        $result = $this->curl->request("https://www.avito.ru/".$advert_id);
		$html = str_get_html($result);
		if( $html->find(".catalog-filters",0) ) return true;
		$delete = trim($html->find('.has-bold',0)->plaintext);
		return ($delete == "Срок размещения этого объявления истёк" || $delete == "Вы закрыли это объявление" || $delete == "Вы удалили это объявление навсегда.");
    }

    public function up($advert_id){
    	include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

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
    	include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
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
}

?>