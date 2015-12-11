<?

Class Drom {
    private $login = "";
    private $password = "";
    public $curl;
    public $dir_codes = array(
        1 => 234,
        2 => 235,
        3 => 236
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

    public function parseAllItems($link,$auth = true){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        if($auth) $this->auth("https://baza.drom.ru/partner/sign");
        
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

    public function addAdvert($params,$images){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $options = $this->setOptions($params);
        $advert_id = json_decode($this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options))->id;

        $this->updateAdvert($advert_id,$params,$images);

        if($params["advert_type"] == 'bestOffer' || $params["advert_type"] == 'fixedPrice') {
        	
        	$url = "https://baza.drom.ru/bulletin/service-configure?";
			$url_params = array(
		    	'ids' => $advert_id,
		    	'applier' => 'publishBulletinWithDealCapabilities',
		    	'return_to' => 'bulletin',
		    	'from' => 'adding.publish__publishBulletinWithDealCapabilities',
		    	'auctionType' => $params["advert_type"],
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
				'auctionType' => $params["advert_type"],
				'buyitnowPrice' => $params['price'][0],
				'currency' => 'RUB',
				'nextUp' => 0
			);
        	$result = iconv('windows-1251', 'utf-8', $this->curl->request("https://baza.drom.ru/bulletin/service-apply",$auction));
        } else $result = iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/bulletin/".$advert_id."/draft/publish?from=draft.publish",array('from'=>'adding.publish')));
        $html = str_get_html($result);
        return ( $html->find('#fieldsetView',0) && $html->find('#fieldsetView',0)->getAttribute("bulletinid") == $advert_id )?$advert_id:false;
    }
    
    public function upPaidAdverts($advert_id){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        
        $begin = iconv('windows-1251', 'utf-8', $this->curl->request("https://baza.drom.ru/".$advert_id));
        $html = str_get_html($begin);

        if($html->find("a.serviceUp",0)->href) {
            $url = "https://baza.drom.ru/bulletin/service-configure?";
            $url_params = array(
                'ids' => $advert_id,
                'applier' => 'upBulletin',
                'from' => 'viewbull.menu__upBulletin'
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
                'nextUp' => 0
            );
            $result = iconv('windows-1251', 'utf-8', $this->curl->request("https://baza.drom.ru/bulletin/service-apply",$auction));
            $html = str_get_html($result);
            if($html->find("div.appliedDisabled",0)) {
                return $advert_id;
            }
        }
        return false;
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

    public function parseDetailPage($pages = array()) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        // $pages = $this->parseAllItems('http://baza.drom.ru/user/Mikhail60/disc',false);
        // foreach ($pages as $key => $page) {
        $params = array();
        $page = "http://baza.drom.ru/vladivostok/wheel/disc/8895-diski-81-motorsport-r18-5-100-5-114.3-7.5-j-42-b-p-rf-40509769.html";
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($page)));
        $params['goodPresentState'] = trim($html->find("span[data-field=goodPresentState]",0)->plaintext);
        if($params['goodPresentState'] == "В наличии") {

        }

        $title = $html->find("span[data-field=subject]",0)->plaintext;
        $city_title = $html->find("span[data-field=subject] nobr",0)->plaintext;
        $params['title'] = str_ireplace($city_title,"", $title);
        
        $params['price'] = $html->find("div[itemprop=price]",0)->getAttribute ('content');
        $params['mark'] = $html->find("span[data-field=model]",0)->plaintext;
        $params['inSetQuantity'] = array_shift(explode(" ш", $html->find("span[data-field=inSetQuantity]",0)->plaintext));
        $params['quantity'] = array_shift(explode(" ш", $html->find("span[data-field=quantity]",0)->plaintext));
        $params['wheelDiameter'] = array_shift(explode(' "', $html->find("span[data-field=wheelDiameter]",0)->plaintext));
        $params['width'] = str_replace('"',"", trim($html->find("div[data-field=discParameters] .value span",0)->plaintext));
        $params['width'] = explode("/", $params['width']);
        $params['vilet'] = array_shift(explode(" м", $html->find("div[data-field=discParameters] .value span",-1)->plaintext));
        $params['wheelPcd'] = explode(", ",trim($html->find("span[data-field=wheelPcd]",0)->plaintext));
        $params['diskHoleDiameter'] = array_shift(explode(" м", $html->find("span[data-field=diskHoleDiameter]",0)->plaintext));
        $params['condition'] = $html->find("span[data-field=condition]",0)->plaintext;

        $params['desc'] = str_replace('<br />',"\n", trim($html->find("p[data-field=text]",0)->innertext));
        $params['guarantee'] = str_replace('<br />',"\n", trim($html->find("p[data-field=guarantee]",0)->innertext));
        $params['delivery'] = str_replace('<br />',"\n", trim($html->find("div[data-field=delivery] p",0)->innertext));
        foreach ($params as &$value) {
            if(!is_array($value)) $value = trim($value);
        }
        foreach ($html->find(".bulletinImages img") as $key => $img) {
            $params['images'][$key] = $img->src;
            $file = file_get_contents('http://xmltv.s-tv.ru/loadimage.php?id=127233');
            file_put_contents('1.jpg',$file);
        }
        print_r($params['images']);
        // }
    }

    public function self(){
        return new Drom();
    }
}

?>