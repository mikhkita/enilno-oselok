<?

Class Drom {
    private $login = "";
    private $password = "";
    public $curl;
    public $dir_codes = array(
        1 => 234,
        2 => 235
    );
    
    function __construct() {
        $this->curl = new Curl();
    }

    public function setUser($login,$password){
        $this->login = $login;
        $this->password = $password;
    }

    public function auth($redirect = "https://baza.drom.ru/partner/sign"){
        $this->curl->removeCookies();

        $params = array(
        	'radio' => 'sign',
            'sign' => $this->login,
            'password' => $this->password
        );

        return iconv('windows-1251', 'utf-8', $this->curl->request("https://www.farpost.ru/sign?mode=openid&return=".urlencode($redirect)."&login_by_password=1",$params));
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

        $this->auth("https://baza.drom.ru/partner/sign");
        
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request("https://baza.drom.ru/personal/non_active/bulletins")));

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

    public function parseAllItems($link){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $this->auth("https://baza.drom.ru/partner/sign");
        
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($link)));

        $links = array();
        $pageLinks = $html->find('.bulletinLink');
        $page = 1;
        while(count($pageLinks)){
            foreach($pageLinks as $element){
                array_push($links, $element->getAttribute("href"));
            }

            $page++;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($link."?page=".$page)));
            $pageLinks = $html->find('.bulletinLink');
        }
        
        return $links;
    }

    public function addAdvert($params,$images,$type = NULL){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $options = $this->setOptions($params);
        $advert_id = json_decode($this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options))->id;

        $this->updateAdvert($advert_id,$params,$images);

        if($type == 'bestOffer' || $type == 'fixedPrice') {
        	
        	$url = "https://baza.drom.ru/bulletin/service-configure?";
			$url_params = array(
		    	'ids' => $advert_id,
		    	'applier' => 'publishBulletinWithDealCapabilities',
		    	'return_to' => 'bulletin',
		    	'from' => 'adding.publish__publishBulletinWithDealCapabilities',
		    	'auctionType' => $type,
		    	'buyitnowPrice' => $params['price'][0],
		    	'currency' => 'RUB',
		    	'isAutoExtension' => 'false',
		    	'isAutoRecreation' => 'false'

			);

			$url .= http_build_query($url_params);
			$html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($url)));

			$auction = array(
				'return_to' => $html->find("input[name=return_to]",0)->value,
				'applier' => $html->find("input[name=applier]",0)->value,
				'uid' => $html->find("input[name=uid]",0)->value,
				'price' => $html->find("input[name=price]",0)->value,
				'order_id' => $html->find("input[name=order_id]",0)->value,
				'bulletin['.$advert_id.']' => 'on',
				'auctionType' => $type,
				'buyitnowPrice' => $params['price'][0],
				'currency' => 'RUB',
				'nextUp' => 0
			);
        	$result = iconv('windows-1251', 'utf-8', $this->curl->request("https://baza.drom.ru/bulletin/service-apply",$auction));
        } else $result = iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/bulletin/".$advert_id."/draft/publish?from=draft.publish",array('from'=>'adding.publish')));
        $html = str_get_html($result);
        return ( $html->find('#fieldsetView',0) && $html->find('#fieldsetView',0)->getAttribute("bulletinid") == $advert_id )?$advert_id:false;
    }
    

    public function updateAdvert($advert_id,$params,$images = NULL) {
        if($images) {
            foreach ($images as &$image_path) {
                $image_path = json_decode($this->curl->request("http://baza.drom.ru/upload-image-jquery",array('up[]' => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path))))->id;
            }
            $params['images'] = array('images' => $images);
        }
        $options = $this->setOptions($params,$advert_id);    
        $result = json_decode($this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options));
        return $result->id;
    }

    // public function deleteAdverts($arr) {
    //     include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
    //     $html = str_get_html($this->curl->request('https://baza.drom.ru/bulletin/service-configure?ids='.$arr[0].'&applier=deleteBulletin'));
        
    //     $del_arr = array(
    //         'applier' => 'deleteBulletin',
    //         'uid' => $html->find('input[name="uid"]', 0)->value,
    //         'price' => 0,
    //         'order_id' => 0,
    //         'return_to' => ''
    //         );
    //     foreach ($arr as $key => $value) {
    //         $del_arr['bulletin['.$value.']']= 'on';
    //     }
    //     $this->curl->request('https://baza.drom.ru/bulletin/service-apply',$del_arr);
    // }

    public function deleteAdvert($advert_id) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $html = str_get_html($this->curl->request('https://baza.drom.ru/bulletin/service-configure?ids='.$advert_id.'&applier=deleteBulletin'));
        
        $del_arr = array(
            'applier' => 'deleteBulletin',
            'uid' => $html->find('input[name="uid"]', 0)->value,
            'price' => 0,
            'order_id' => 0,
            'return_to' => 'http://baza.drom.ru/'.$advert_id.'.html',
            'bulletin['.$advert_id.']' => 'on'
        );

        $result = iconv('windows-1251', 'utf-8', $this->curl->request('https://baza.drom.ru/bulletin/service-apply',$del_arr));

        $html = str_get_html($result);

        return ( $html->find('.bulletin_expired_notification h2',0) && $html->find('.bulletin_expired_notification h2',0)->plaintext == "Вы удалили объявление" );
    }

    public function setOptions($params,$advert_id = NULL) {
        $options = array(
            'cityId' => $params['cityId'],
            'bulletinType'=>'bulletinRegular',
            'directoryId'=> $params['dirId'],
            'fields'=> $params
        );
       
        if($advert_id) {
            if(isset($params['images'])) {
                $options['images'] = $params['images'];
            }
            $options['id'] = $advert_id;
        }
        $options = array('changeDescription' => json_encode($options));
        $options['client_type'] = ($advert_id) ? 'v2:editing' : "v2:adding";
        return $options;
    }

    public function generateFields($fields,$good_type_id){
        $fields['dirId'] = $this->dir_codes[intval($good_type_id)];
        $fields['model'] = array($fields["model"],0,0);
        $fields['price'] = array($fields["price"],"RUB");
        $fields['quantity'] = 1;
        $fields['contacts'] =  array("email" => "","is_email_hidden" => false,"contactInfo" => $fields['contacts']);
        $fields['delivery'] = array("pickupAddress" => $fields['pickupAddress'],"localPrice" => $fields['localPrice'],"minPostalPrice" => $fields['minPostalPrice'],"comment" => $fields['comment']);
        unset($fields['pickupAddress'],$fields['localPrice'],$fields['minPostalPrice'],$fields['comment']);

        if( isset($fields["disc_width"]) ){
            $disc_width = explode("/",$fields['disc_width']);
            $disc_et = explode("/",$fields['disc_et']);
            $fields['wheelPcd'] = explode("/",$fields['wheelPcd']);

            $fields['discParameters'] = array();
            foreach ($disc_width as $i => $value)
                $fields['discParameters'][$i] = array("disc_width"=>$value);

            foreach ($disc_et as $i => $value){
                if( !isset($fields['discParameters'][$i]) ) $fields['discParameters'][$i] = array();
                $fields['discParameters'][$i]["disc_et"] = $value;
            }
  
            unset($fields['disc_width'],$fields['disc_et'],$disc_width,$disc_et);
        }

        if( $good_type_id == 1 ){
            $fields['predestination'] = "regular";
        }
        return $fields;
    }

    public function self(){
        return new Drom();
    }
}

?>