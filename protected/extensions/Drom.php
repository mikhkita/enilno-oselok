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
    
    function __construct($ip = NULL) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $this->curl = new Curl($ip);
    }

    public function setUser($login,$password){
        $this->curl->changeCookies($login);
        $this->login = $login;
        $this->password = $password;
    }

    public function auth($redirect = "https://baza.drom.ru/partner/sign"){
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/personal")));

        if( is_object($html) && $html->find("body",0) && trim($html->find("body",0)->getAttribute("data-user-login")) == $this->login )
            return true;

        echo "Авторизация ".$this->login;
        $this->curl->removeCookies();

        $params = array(
        	'radio' => 'sign',
            'sign' => $this->login,
            'password' => $this->password
        );

        return iconv('windows-1251', 'utf-8', $this->curl->request("https://www.farpost.ru/sign?mode=openid&return=".urlencode($redirect)."&login_by_password=1",$params));
    }

    public function upFreeAdverts($links){
        $upLinks = array();
        Log::debug("Пользователь ".$this->login." ".count($links)." неактивных бесплатных объявлений");

        foreach ($links as $key => $value) {
            $index = floor($key/50);
            $upLinks[$index] = $upLinks[$index]."&bulletin%5B".$value."%5D=on";
        }

        foreach ($upLinks as $key => $value) {
            $url = "http://baza.drom.ru/bulletin/service-configure?return_to=%2Fpersonal%2Fnon_active%2Fbulletins%3Fpage%3D2&from=personal.non_active&applier%5BprolongBulletin%5D=prolongBulletin".$value."=on&note=";
            $this->curl->request($url);
        }
    }

    public function upAdverts(){
        $links = $this->parseExpired();
        $model = Advert::model()->with("good_filter")->findAll("url IN (".implode(",", $links).")");
        $free = array();
        $pay = array();
        foreach ($model as $key => $item) {
            if($item->good_filter->archive == 1) {
                $this->deleteAdvert($item->url);
            } else {
                if($item->type_id == 869) {
                    array_push($free, $item->url);
                } else array_push($pay, $item->url);
            }
        }
        if($free) {
            $this->upFreeAdverts($free);
        }
        if($pay) {
            $links = $pay;
            $upLinks = array();
            Log::debug("Пользователь ".$this->login." ".count($links)." неактивных платных объявлений");

            foreach ($links as $key => $value) {
                $index = floor($key/50);
                $upLinks[$index] = $upLinks[$index]."&bulletin%5B".$value."%5D=on";
            }

            foreach ($upLinks as $key => $value) {
                $url = "http://baza.drom.ru/bulletin/service-configure?return_to=%2Fpersonal%2Fnon_active%2Fbulletins%3Fpage%3D2&from=personal.non_active&applier%5BupBulletin%5D=upBulletin".$value."=on&note=";
                $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($url)));
                $url = "https://baza.drom.ru/bulletin/service-apply";
                $params = array();
                foreach ($html->find('.paid-service form input[type="hidden"]') as $key => $item) {
                    $params[$item->getAttribute("name")] = $item->getAttribute("value");
                    if($item->getAttribute("name") == "price") $price = $item->getAttribute("value");
                }
                if($price > 50) {
                    Log::debug("Поднятие объвлений больше 1р/шт.");
                    continue;
                }
                foreach ($html->find('.paid-service form .viewdirBulletinTable input') as $key => $item) {
                    $params[$item->getAttribute("name")] = "on";
                }
                $this->curl->request($url,$params);
            }
        }
    }

    public function parseExpired(){
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

    public function parseAllItems($link, $user_id, $auth = true, $get_id = false, $get_object = false){
        if($auth) $this->auth("https://baza.drom.ru/partner/sign");
        
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($link)));

        $links = array();
        $first = NULL;
        if( !$html->find('.userProfile',0) || trim($html->find('.userProfile',0)->getAttribute("data-view-dir-user-id")) != trim($user_id) ) return $links;
        $pageLinks = $html->find('.descriptionCell');
        $page = 1;

        if($get_id) {
            $attr = "name";
        } else $attr = "href";

        while(count($pageLinks) && ($first != $pageLinks[0]->find(".bulletinLink",0)->getAttribute($attr)) ){
            foreach($pageLinks as $element){
                if( !count($links) ) $first = $element->find(".bulletinLink",0)->getAttribute($attr);

                if( $get_object ){
                    $tmp = array(
                        "link" => $element->find(".bulletinLink",0)->getAttribute($attr),
                        "type" => (($element->find(".bestOffer"))?1:2),
                        "title" => $element->find(".bulletinLink",0)->plaintext
                    );
                    array_push($links, (object)$tmp);
                }else{
                    array_push($links, $element->find(".bulletinLink",0)->getAttribute($attr) );
                }
            }
            $page++;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($link."?page=".$page)));
            $pageLinks = $html->find('.descriptionCell');
        }

        if($get_id && $html->find('#itemsCount_placeholder strong',0)) {
            if(count($links) != array_shift(explode(" пр", $html->find('#itemsCount_placeholder strong',0)->plaintext)))
                return false;
        }
        return $links;
    }

    public function addAdvert($params,$images){
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
            $result = iconv('windows-1251', 'utf-8', $this->curl->request($url));

			$html = str_get_html($result);

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

        print_r($result);

        $html = str_get_html($result);
        return ( $html->find('#fieldsetView',0) && $html->find('#fieldsetView',0)->getAttribute("bulletinid") == $advert_id )?$advert_id:false;
    }
    
    public function upPaidAdverts($advert_id){        
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
                $image_path = json_decode($this->curl->request("http://baza.drom.ru/upload-image-jquery",array('debug' => true,'up[]' => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path))))->id;
            }
            $params['images'] = array('images' => $images);
        }
         
        if($params["advert_type"] == 'bestOffer') {
            $params['offersRestricted'] = 0;
        }
        if($params["advert_type"] == 'fixedPrice') {
            $params['offersRestricted'] = 1;
        }
        $options = $this->setOptions($params,$advert_id,$only_images);    

        $result = json_decode($this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options));
        // print_r($result);

        return $result->id;
    }

    public function deleteAdvert($advert_id) {
        echo 'https://baza.drom.ru/bulletin/service-configure?ids='.$advert_id.'&applier=deleteBulletin';
        $result = $this->curl->request('https://baza.drom.ru/bulletin/service-configure?ids='.$advert_id.'&applier=deleteBulletin');
        print_r($result);
        echo "<br><br>";
        $html = str_get_html($result);
        echo $html->find('input[name="uid"]', 0)->value;
        echo "<br><br>";

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

        // echo "<br><br><br>";
        print_r($result);

        return ( ($html->find('.bulletin_expired_notification h2',0) && $html->find('.bulletin_expired_notification h2',0)->plaintext == "Вы удалили объявление") || ($html->find('.annotation .alert_r p',0) && $html->find('.annotation .alert_r p',0)->plaintext == "Действие уже выполнено") );
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
        $options = array(
            'changeDescription' => json_encode($options),
            'APIOptions' => json_encode(array(
                "fieldSetVariation" => "full_set",
                "canUsePersistentSettings" => true
            ))
        );

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

    public function parseUser() {
        $wheel_array = array("tire","disc","wheel");
        $Users = AttributeVariant::model()->with('variant')->findAll("attribute_id=43 AND variant_id > 2900");
        $add_count = 0;
        $delete_count = 0;
        $restore_count = 0;

        foreach ($Users as $user) {
        	if(DictionaryVariant::model()->find("dictionary_id=139 AND attribute_1='".$user->variant_id."' AND value=1")) {
	            $good_type = 1;
	            foreach ($wheel_array as $type) {
	                $model = Good::model()->filter(
	                    array(
	                        "good_type_id"=>$good_type,
	                        "archive" => 'all',
	                        "attributes"=>array(
	                            43 => array($user->variant_id)
	                        )
	                    )
	                )->getPage(
	                    array(
	                        'pageSize'=>10000,
	                    )
	                );
	                   
	                if($model = $model['items']) {
	                    $goods = array();
	                    $links = array();
	                    $drom_ids = $this->parseAllItems('http://baza.drom.ru/user/'.$user->variant->value.'/wheel/'.$type, $user->variant->value, false,true);
	                    $drom_ids = ($drom_ids) ? $drom_ids : array(); 

	                    foreach ($model as $key => $item) {
	                        $code = str_replace(".html","", array_pop(explode("/", $item->fields_assoc[106]->value)));
	                        array_push($goods, $code);
	                        if(array_search($code, $drom_ids) === false) {
	                            if($archive = Good::model()->with(array("type","fields.variant","fields.attribute"))->find("t.id=".$key." AND t.archive=0")) {
                                    // if( !$archive ){
                                    //     echo $key." ".$code." ";
                                    //     die();
                                    // }
                                    if( $archive->toTempArchive() ){
                                        Log::debug("Убирание товара во временный архив ".$archive->fields_assoc[3]->value);
                                        $delete_count++;
                                    }
	                            }
	                        } 
                            else {
	                            if($item->archive == 2) {
	                                $item->archive = 0;
                                    $item->date = NULL;
	                                if($item->save()) {
	                                    Log::debug("Восстановление товара из временного архива ".$item->fields_assoc[3]->value);
	                                    $restore_count++;
	                                }  
	                            }
	                        }
	                    }

	                    foreach ($drom_ids as $key => $code) {
	                        if(array_search($code, $goods) === false) {
	                        	$link = "http://".Yii::app()->params['ip'].Controller::createUrl('/dromUserParse/parse',array('page'=> 'http://baza.drom.ru/'.$code,'user_id' => $user->variant->value));
	                        	if(Cron::model()->count("link='".addslashes($link)."'")) {
									Log::debug("Объявление ".$code." уже добавлено в очередь на парсинг");	
								} else {
	                                Log::debug("Товар добавлен в очередь на парсинг ".$link);
	                                array_push($links, $link);     
	                            }
	                        }
	                    }
	                    Cron::addAll($links);    
	                    $add_count += count($links);
	                }
	                $good_type++;
	            } 
	        }    
        }
        Good::soldAllTemp();

        $this->curl->removeCookies();
        Log::debug("Добавлено товаров: ".$add_count);
        Log::debug("Удалено товаров: ".$delete_count);
        Log::debug("Восстановлено товаров: ".$restore_count);
    }

    public function parseAdvert($page,$good_code,$user_id) {
        $link = "http://baza.drom.ru/".array_pop(explode("-",array_pop(explode("/", $page))));
        if(!GoodAttribute::model()->find("attribute_id=106 AND varchar_value='".$link."'")) {
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
                'tireModel' => 16,
                'year' => 10,
                'wheelSeason' => 23,
                'wheelTireWear' => 29,
                'tireHeight' => 8,
                'tireWidth' => 7,
                'predestination' => 98,
                'made' => 11,
                'tireWidthMore' => 98,
                'tireHeightMore' => 98,
                'archive' => 998,
                'images' => 999
                
        	);

            $params = array();
            $marking = 1;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($page)));
            $params[$fields['link']] = $link;
            if($user_id === NULL) {
                $user_id = $html->find("div.ownerInfoInner",0)->getAttribute("data-id");
                $model = Attribute::model()->with('variants.variant')->find("attribute_id=43 AND value=".$user_id);
                if(!$model) {
                    if($variant_id = Variant::add(43,$user_id)) {
                        if(!Dictionary::add(139,$variant_id,0)) return false;
                        $user_name = trim($html->find("span.userNick",0)->plaintext);
                        if($user_id != $user_name) Dictionary::add(41,$variant_id,$user_name);
                    } else return false;
                }
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
            $params[$fields['archive']] = 0;

            $params[$fields['title']] = "Заголовок: ".trim(str_ireplace($html->find("span[data-field=subject] nobr",0)->plaintext,"",$html->find("span[data-field=subject]",0)->plaintext))."\n\r";
            $params[$fields['state']] .= "Наличие товара: ".trim($html->find("span[data-field=goodPresentState]",0)->plaintext)."\n\r";
        	$params[$fields['code']] = $good_code;
        	$params[$fields['realisation']] = $user_id; intval(str_replace(" ","", $str));
            $params[$fields['city']] = str_ireplace(array('в ','во '," "),"", $html->find("span[data-field=subject] nobr",0)->plaintext);
            $params[$fields['price']] = $html->find('span[data-field="price"]',0) ? intval(str_replace(" ","",$html->find('span[data-field="price"]',0)->plaintext)) : NULL;
            $params[$fields['inSetQuantity']] = $html->find("span[data-field=inSetQuantity]",0) ? array_shift(explode(" ш", $html->find("span[data-field=inSetQuantity]",0)->plaintext)) : NULL;   
            $params[$fields['quantity']] .= "Количество комплектов: ".trim(array_shift(explode(" ш", $html->find("span[data-field=quantity]",0)->plaintext)))."\n\r";

            if($params[$fields['type']] == 2) {
            	$params[$fields['diskModel']] = $html->find("span[data-field=model]",0)->plaintext;
            	$params[$fields['wheelDiameter']] = str_replace('"',"", $html->find("span[data-field=wheelDiameter]",0)->plaintext);
            	$params[$fields['condition']] = $html->find("span[data-field=condition]",0) ? trim($html->find("span[data-field=condition]",0)->plaintext) : 'Б/п РФ';
                if( $params[$fields['condition']] == "Новый" ) $params[$fields['archive']] = 1;
                $params[$fields['condition']] = ($params[$fields['condition']]=="Новый") ? "Новые": $params[$fields['condition']];

                if($params[$fields['condition']] != "Новые" || $params[$fields['condition']] != "Б/у") $params[$fields['condition']] = 'Б/п РФ';
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
            	$params[$fields['tireModel']] = trim(str_ireplace($html->find("span[data-field=model] div",0)->plaintext,"",$html->find("span[data-field=model]",0)->plaintext));
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

            if($html->find(".bulletinImages img")) {
                $params[$fields['images']] = array();
    	        foreach ($html->find(".bulletinImages img") as $i => $img) {
                    if($img->getAttribute("data-zoom-image")) {
                        array_push($params[$fields['images']], $img->getAttribute("data-zoom-image"));
                    } else array_push($params[$fields['images']], $img->src);
                }
            }
            return $params;
        } else return false;
        
	}

    public function parseTitle($link){
        $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request($link)));
        if( $html && $html->find("h1.subject nobr",0) ){
            $html->find("h1.subject nobr",0)->innertext = "";
            return preg_replace('| +|', ' ', str_replace(array("- ","= ","-","="), " ", trim($html->find("h1",0)->plaintext)));
        }else{
            return false;
        }
    }

    public function self(){
        return new Drom();
    }

    public function registration(){
        print_r( iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/personal/email_confirmation_request")) );
    }
}

?>