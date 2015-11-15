<?

Class Avito {
    private $login = "";
    private $password = "";
    public $curl;
    public $dir_codes = array(
        1 => 10048,
        2 => 10046
    );
    
    function __construct() {
        $this->curl = new Curl();
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

    public function upAdverts(){
        $links = $this->parseExpired();
        $upLinks = array();
        Log::debug("Пользователь ".$this->login." ".count($links)." неактивных объявлений");

        foreach ($links as $key => $value) {
            $index = floor($key/50);
            $upLinks[$index] = $upLinks[$index]."&bulletin%5B".$value."%5D=on";
        }

        foreach ($upLinks as $key => $value) {
            $url = "http://baza.drom.ru/bulletin/service-configure?return_to=%2Fpersonal%2Fnon_active%2Fbulletins%3Fpage%3D2&from=personal.non_active&applier%5BprolongBulletin%5D=prolongBulletin".$value."=on&note=";
            $this->curl->request($url);
        }
    }

    public function parseExpired(){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $html = str_get_html($this->auth("http://baza.drom.ru/personal/non_active/bulletins"));

        $links = array();
        $pageLinks = $html->find('.bullNotPublished');
        $page = 1;
        while(count($pageLinks)){
            foreach($pageLinks as $element){
                $exp = $element->find(".expired");
                if( count($exp) )
                    array_push($links, $element->find(".bulletinLink",0)->getAttribute("name"));
            }

            $page++;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/personal/non_active/bulletins?page=".$page)));
            $pageLinks = $html->find('.bullNotPublished');
        }

        return $links;
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
    	
		$html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request("https://www.avito.ru/".$advert_id)));
		$href = $html->find('.item_change',0)->href;
		$href = "https://www.avito.ru".substr($href, 0, -3)."/edit";

		$html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($href)));
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

		iconv('windows-1251', 'utf-8', $this->curl->request($href,$params));
   
		print_r(iconv('windows-1251', 'utf-8', $this->curl->request($href."/confirm",array('done' => "",'subscribe-position' => '1'))));
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

    public function deleteAdverts($arr) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $html = str_get_html($this->curl->request('https://baza.drom.ru/bulletin/service-configure?ids='.$arr[0].'&applier=deleteBulletin'));
        
        $del_arr = array(
            'applier' => 'deleteBulletin',
            'uid' => $html->find('input[name="uid"]', 0)->value,
            'price' => 0,
            'order_id' => 0,
            'return_to' => ''
            );
        foreach ($arr as $key => $value) {
            $del_arr['bulletin['.$value.']']= 'on';
        }
        $this->curl->request('https://baza.drom.ru/bulletin/service-apply',$del_arr);
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