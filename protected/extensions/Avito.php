<?

Class Avito {
    private $login = "";
    private $password = "";
    public $curl;
    public $dir_codes = array(
        1 => 10048,
        2 => 10046
    );
    
    function __construct($proxy = NULL) {
        $this->curl = new Curl($proxy);
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
        if($images !== NULL) {
        	$params = $this->addImages($params,$images);
		}
		$html = str_get_html($this->curl->request("https://www.avito.ru/additem"));
	    $token = array();
	    $token['name'] = $html->find('input[name^=token]',0)->name;
	    $token['value'] = $html->find('input[name^=token]',0)->value;
	    $params[$token['name']] = $token['value'];
	    $params['source'] = "add";

		$html = str_get_html($this->curl->request("https://www.avito.ru/additem",$params));
		$captcha = $html->find('.form-captcha-image',0)->src;

        $out = $this->curl->request('https://www.avito.ru'.$captcha);
	    $captcha_path = Yii::app()->basePath.'/extensions/captcha.jpg';  
	    file_put_contents($captcha_path, $out); 

		$captcha = $this->curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		while ($captcha == 'ERROR_NO_SLOT_AVAILABLE') {
			sleep(5);
		    $captcha = $this->curl->request("http://rucaptcha.com/in.php",array('key'=>'0b07ab2862c1ad044df277cbaf7ceb99','file'=> new CurlFile($captcha_path)));
		} 
		if(strpos($captcha, "|") !== false) {
			$captcha = substr($captcha, 3);
			$url = "http://rucaptcha.com/res.php?";
					$url_params = array(
				    	'key' => '0b07ab2862c1ad044df277cbaf7ceb99',
				    	'action' => 'get',
				    	'id' => $captcha
					);
			$url .= urldecode(http_build_query($url_params));

			$captcha = $this->curl->request($url);
			while ($captcha == 'CAPCHA_NOT_READY') {
				sleep(2);
		   		$captcha = $this->curl->request($url);
			} 
			if(strpos($captcha, "|") !== false) {
				$captcha = substr($captcha, 3);
				$html = str_get_html($this->curl->request("https://www.avito.ru/additem/confirm",array('captcha' => $captcha,'done' => "",'subscribe-position' => '0')));
				$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
				$id = end(explode("_", $id));
				return $id;
			} else {
				return "error";
			}
		} else {
			return "error";
		}
    }
    public function updateAdvert($advert_id,$params,$images = NULL){
    	include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
		$html = str_get_html($this->curl->request("https://www.avito.ru/".$advert_id));
		$href = $html->find('.item_change',0)->href;
		$href = "https://www.avito.ru".substr($href, 0, -3)."/edit";

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

		$this->curl->request($href,$params);
   
		$html = str_get_html($this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1')));
		$id = $html->find('.content-text a[rel="nofollow"]',0)->href;
		$id = end(explode("_", $id));
		return $id;
    }
   				
    public function addImages($params,$images = NULL) {
        if($images) {
        	$img = array();
            foreach ($images as $key => $image_path) {
            	if($key == 5) break;
                array_push($img, json_decode($this->curl->request("https://www.avito.ru/additem/image",array('image' => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path))))->id);
            }
            foreach ( $img as $i => $image) {
				$params['images['.$i.']'] = $image;
			}
            return $params;
        }
    }

    public function deleteAdvert($advert_id) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $this->curl->request("https://www.avito.ru/profile",array('item_id[]' => $advert_id,'delete' => 'Снять объявление с публикации'));
		$html = str_get_html($this->curl->request("https://www.avito.ru/".$advert_id));
		$delete = $html->find('.has-bold',0)->plaintext;
		return ($delete == "Вы закрыли это объявление");
    }

    public function generateFields($fields,$good_type_id){
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