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
        while(count($pageLinks) && ($links[0] != $pageLinks[0]->getAttribute("href")) ){
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

    public function updateAdvert($advert_id,$params,$images = NULL,$only_images = false) {
        if($images) {
            foreach ($images as &$image_path) {
                $image_path = json_decode($this->curl->request("http://baza.drom.ru/upload-image-jquery",array('up[]' => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path))))->id;
            }
            $params['images'] = array('images' => $images);
        }
        $options = $this->setOptions($params,$advert_id,$only_images);    
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

    public function setOptions($params,$advert_id = NULL,$only_images = false) {
        $options = array(
            'cityId' => $params['cityId'],
            'bulletinType'=>'bulletinRegular',
            'directoryId'=> $params['dirId'],
        );

        if( !$only_images )
            $options["fields"] = $params;
       
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
        if( isset($fields['model']) ) $fields['model'] = array($fields["model"],0,0);
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

        if( $good_type_id == 1 || $good_type_id == 3 ){
            $fields['predestination'] = "regular";
        }
        return $fields;
    }

    public function parseAdvert($page,$good_code,$user_id) {
        $link = "http://baza.drom.ru/".array_pop(explode("-", $page));
        if(!GoodAttribute::model()->find("attribute_id=106 AND varchar_value='".$link."'")) {
            include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
            $fields = array(
                'type' => 0,
                'link' => 106,
        		'code' => 3,
        		'realisation' => 43,
                'diffWidth' => 73,
                'diffVilet' => 80,
                'title' => 98,
                'state' => 98,
                'city' => 27,
        		'price' => 20,
        		'inSetQuantity' => 28,
        		'quantity' => 98,
                'diskModel' => 6,
        		'wheelDiameter' => 9,
        		'condition' => 26,
        		'wheelWeight' => 34,
        		'wheelWidth' => 31,
        		'wheelVilet' => 32,
        		'wheelPcd' => 5,
                'diskType' => 41,
        		'diskHoleDiameter' => 33,
        		'desc' => 52,
                'tireModel' => 98,
                'year' => 10,
                'wheelSeason' => 23,
                'wheelTireWear' => 29,
                'tireHeight' => 8,
                'tireWidth' => 7,
                'predestination' => 98,
                'made' => 11,
                'tireWidthMore' => 98,
                'tireHeightMore' => 98
                
        	);

            $params = array();
            $marking = 1;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($page)));
            $params[$fields['link']] = $link;
            if($user_id === NULL) {
                $user_id = $html->find("div.ownerInfoInner",0)->getAttribute("data-id");
                $model = Attribute::model()->with('variants.variant')->find("attribute_id=43 AND value=".$user_id);
                if($variant_id = Variant::add(43,$user_id)) {
                    $user_name = trim($html->find("span.userNick",0)->plaintext);
                    if($user_id != $user_name) Dictionary::add(41,$variant_id,$user_name);
                } else return false;
            }

            switch (trim($html->find("#breadcrumbs span",3)->plaintext)) {
                case "Шины":
                    $params[$fields['type']] = 1;
                    break;
                case "Диски":
                    $params[$fields['type']] = 2;
                    break;
                case "Колёса":
                    $params[$fields['type']] = 3;
                    break;
            }

            $params[$fields['title']] = "Заголовок: ".trim(str_ireplace($html->find("span[data-field=subject] nobr",0)->plaintext,"",$html->find("span[data-field=subject]",0)->plaintext))."\n\r";
            $params[$fields['state']] .= "Наличие товара: ".trim($html->find("span[data-field=goodPresentState]",0)->plaintext)."\n\r";
        	$params[$fields['code']] = $good_code;
        	$params[$fields['realisation']] = $user_id;
            $params[$fields['city']] = str_ireplace(array('в ','во '," "),"", $html->find("span[data-field=subject] nobr",0)->plaintext);
            $params[$fields['price']] = $html->find("div[itemprop=price]",0) ? $html->find("div[itemprop=price]",0)->getAttribute('content') : NULL;
            $params[$fields['inSetQuantity']] = $html->find("span[data-field=inSetQuantity]",0) ? array_shift(explode(" ш", $html->find("span[data-field=inSetQuantity]",0)->plaintext)) : NULL;   
            $params[$fields['quantity']] .= "Количество комплектов: ".trim(array_shift(explode(" ш", $html->find("span[data-field=quantity]",0)->plaintext)))."\n\r";

            if($params[$fields['type']] == 2) {
            	$params[$fields['diskModel']] = $html->find("span[data-field=model]",0)->plaintext;
            	$params[$fields['wheelDiameter']] = str_replace('"',"", $html->find("span[data-field=wheelDiameter]",0)->plaintext);
            	$params[$fields['condition']] = $html->find("span[data-field=condition]",0) ? trim($html->find("span[data-field=condition]",0)->plaintext) : NULL;
            	$params[$fields['condition']] = ($params[$fields['condition']]=="Новый") ? "Новые": $params[$fields['condition']];
            }

            if($params[$fields['type']] != 1) {
    	        $params[$fields['wheelWeight']] = $html->find("span[data-field=wheelWeight]",0) ? str_replace(',','.',str_replace('кг.',"", $html->find("span[data-field=wheelWeight]",0)->plaintext)) : NULL;
    	        $params[$fields['wheelWidth']] = $html->find("div[data-field=discParameters] .value span",0) ? explode("/",str_replace('"',"", trim($html->find("div[data-field=discParameters] .value span",0)->plaintext))) : NULL;
    	        if($params[$fields['wheelWidth']]) {
    	        	foreach ($params[$fields['wheelWidth']] as $key => &$width) {
    	        		$width = floatval($width);
    	        	}
                    if(count($params[$fields['wheelWidth']]) == 2) $params[$fields['diffWidth']] = 1;
    	        }
    	        $params[$fields['wheelVilet']] = $html->find("div[data-field=discParameters] .value span",1) ? explode("/",str_replace(' мм.',"", trim($html->find("div[data-field=discParameters] .value span",1)->plaintext))) : NULL;
    	        if(count($params[$fields['wheelVilet']]) == 2) $params[$fields['diffVilet']] = 1;
                $params[$fields['wheelPcd']] = $html->find("span[data-field=wheelPcd]",0) ? explode(", ",trim($html->find("span[data-field=wheelPcd]",0)->plaintext)) : NULL;
    	        if($params[$fields['wheelPcd']]) {				   
    	        	foreach ($params[$fields['wheelPcd']] as $key => &$item) {
    	        		$item = explode('x',$item);
    	        		$item = $item[1]."*".floatval($item[0]);
    	        	}
                    
    	        }
    	        $params[$fields['diskType']] = $html->find("span[data-field=diskType]",0) ? trim($html->find("span[data-field=diskType]",0)->plaintext) : NULL;
                if($params[$fields['diskType']] == "Литой") $params[$fields['diskType']] = 1;
                if($params[$fields['diskType']] == "Кованый") $params[$fields['diskType']] = 2;
                if($params[$fields['diskType']] == "Штампованный") $params[$fields['diskType']] = 4;
    	        $params[$fields['diskHoleDiameter']] = $html->find("span[data-field=diskHoleDiameter]",0) ? array_shift(explode(" м", $html->find("span[data-field=diskHoleDiameter]",0)->plaintext)) : NULL;
    	    	if($params[$fields['diskHoleDiameter']]) $params[$fields['diskHoleDiameter']] = floatval(str_replace(',', '.', $params[$fields['diskHoleDiameter']]));
    	    }

            if($params[$fields['type']] == 1) {
            	$params[$fields['tireModel']] .= "Модель шины: ".trim(str_ireplace($html->find("span[data-field=model] div",0)->plaintext,"",$html->find("span[data-field=model]",0)->plaintext))."\n\r";
                if(count($html->find("span[data-field=marking]")) == 5) {
                    $params[$fields['wheelDiameter']] = $html->find("span[data-field=marking] a",0)->plaintext;
                    $marking = 2;
                }
            } 
            if($params[$fields['type']] == 3) {
                $params[$fields['tireModel']] .= "Модель шины: ".trim($html->find("span[data-field=tireFirmAndModel]",1)->plaintext)."\n\r";
                $params[$fields['diskModel']] = $html->find("span[data-field=discFirmAndModel]",0)->plaintext;
                if(count($html->find("span[data-field=marking]")) == 5) {
                    $params[$fields['wheelDiameter']] = str_replace('"',"",$html->find("span[data-field=marking]",1)->plaintext);
                    $marking = 2;
                }
            }

            if($params[$fields['type']] != 2) {
    	        $params[$fields['year']] = $html->find("span[data-field=year]",0) ? $html->find("span[data-field=year]",0)->plaintext : NULL;
    	        $params[$fields['wheelSeason']] = $html->find("span[data-field=wheelSeason]",0) ? trim($html->find("span[data-field=wheelSeason]",0)->plaintext) : NULL;
                if($params[$fields['wheelSeason']] == "Зимние") {
                    if($html->find("span[data-field=wheelSpike]",0)) {
                        $params[$fields['wheelSeason']] = trim($html->find("span[data-field=wheelSpike]",0)->plaintext);
                        if($params[$fields['wheelSeason']] == "Без шипов") $params[$fields['wheelSeason']] = "Нешипованные";
                    }
                }
    	        $params[$fields['wheelTireWear']] = $html->find("span[data-field=wheelTireWear]",0) ? trim(str_replace('%',"",$html->find("span[data-field=wheelTireWear]",0)->plaintext)) : NULL;
                if($params[$fields['wheelTireWear']] == "Без износа") $params[$fields['wheelTireWear']] = 0;
    	        if($params[$fields['wheelTireWear']] || $params[$fields['wheelTireWear']] == 0) $params[$fields['condition']] = ($params[$fields['wheelTireWear']] == 0) ? "Новые" : "Б/у";
                if(strripos(trim($html->find("span[data-field=marking]",$marking)->plaintext), "мм.")) {
                    $params[$fields['tireWidth']] = explode("/",trim(str_replace(array('мм.','"'),"",$html->find("span[data-field=marking]",$marking)->plaintext)));
                    if(count($params[$fields['tireWidth']]) > 1) {
                        $params[$fields['tireWidthMore']] .= "Полная ширина шин:";
                        foreach ($params[$fields['tireWidth']] as $key => $value) {
                            $params[$fields['tireWidthMore']] .= " ".$value;
                        }
                        $params[$fields['tireWidthMore']] .= "\n\r";
                    }
                    $params[$fields['tireWidth']] = $params[$fields['tireWidth']][0];
                } else $params[$fields['tireHeight']] = str_replace(array('мм.','"'),"",$html->find("span[data-field=marking]",$marking)->plaintext);


                if(strripos(trim($html->find("span[data-field=marking]",($marking+1))->plaintext), "%")) {
                    $params[$fields['tireHeight']] = explode("/",trim(str_replace(array('"','%'),"",$html->find("span[data-field=marking]",($marking+1))->plaintext)));
                    if(count($params[$fields['tireHeight']]) > 1) {
                        $params[$fields['tireHeightMore']] .= "Полная высота профиля:";
                        foreach ($params[$fields['tireHeight']] as $key => $value) {
                            $params[$fields['tireHeightMore']] .= " ".$value;
                        }
                        $params[$fields['tireHeightMore']] .= "\n\r";
                    }
                    $params[$fields['tireHeight']] = $params[$fields['tireHeight']][0];
                } else $params[$fields['tireWidth']] = str_replace(array('"','%'),"",$html->find("span[data-field=marking]",($marking+1))->plaintext);
    	        // $params['tireСarcase'] = str_replace(array('%','"'),"",$html->find("span[data-field=marking]",3)->plaintext);
    	        $params[$fields['predestination']] .= "Тип шины: ".trim($html->find("span[data-field=predestination]",0)->plaintext)."\n\r";
        	}

        	

            $params[$fields['desc']] = str_replace('<br />',"\n", trim($html->find("p[data-field=text]",0)->innertext));
            $params[$fields['made']] = "Не указано";
            // $params['guarantee'] = $html->find("p[data-field=guarantee]",0) ? str_replace('<br />',"\n", trim($html->find("p[data-field=guarantee]",0)->innertext)) : NULL;
            // $params['delivery'] = $html->find("div[data-field=delivery] p",0) ? str_replace('<br />',"\n", trim($html->find("div[data-field=delivery] p",0)->innertext)) : NULL;

            foreach ($params as  $key => &$value) {
                if(!is_array($value)) $value = trim($value);
            }
            if(!empty($html->find(".bulletinImages img"))) {
    	        $dir = Yii::app()->params["imageFolder"]."/".GoodType::model()->findByPk($params[$fields['type']])->code."s/".$good_code; 
    	        if (!is_dir($dir)) mkdir($dir, 0777, true);
    	        foreach ($html->find(".bulletinImages img") as $i => $img) {
                    if($img->getAttribute("data-zoom-image")) {
                        copy( $img->getAttribute("data-zoom-image"), $dir."/".$good_code."_".sprintf("%'.02d", $i).".jpg");
                    } else copy( $img->src, $dir."/".$good_code."_".sprintf("%'.02d", $i).".jpg");
                }
            }
            return $params;
        } else return false;
        
	}

    public function self(){
        return new Drom();
    }
}

?>