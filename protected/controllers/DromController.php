<?php

class DromController extends Controller
{
   private $drom_params = array(
        1 => array(
            "subject" => array("type" => 'inter',"id" => 8),
            "cityId" => array("type" => 'inter',"id" => 31),
            "goodPresentState" => array("type" => 'inter',"id" => 102),
            "model" => array("type" => 'inter',"id" => 12),
            "inSetQuantity" => array("type" => 'attr',"id" => 28),
            "wheelSeason" => array("type" => 'inter',"id" => 101),
            "wheelTireWear" => array("type" => 'attr',"id" => 29),
            "year" => array("type" => 'attr',"id" => 10),
            "wheelSpike" => array("type" => 'inter',"id" => 104),
            "price" => array("type" => 'inter',"id" => 45),
            "marking" => array("type" => 'inter',"id" => 13),
            "text" => array("type" => 'inter',"id" => 10),
            "pickupAddress" => array("type" => 'inter',"id" => 40),
            "localPrice" => array("type" => 'inter',"id" => 39),
            "minPostalPrice" => array("type" => 'inter',"id" => 38),
            "comment" => array("type" => 'inter',"id" => 72),
            "guarantee" => array("type" => 'inter',"id" => 73),   
        ),
        2 => array(
            "price" => array("type" => 'inter',"id" => 22),
            "subject" => array("type" => 'inter',"id" => 17),
            "cityId" => array("type" => 'inter',"id" => 30),
            "goodPresentState" => array("type" => 'inter',"id" => 103),
            "model" => array("type" => 'inter',"id" => 92),
            "condition" => array("type" => 'inter',"id" => 136),
            "wheelDiameter" => array("type" => 'attr',"id" => 9),
            "inSetQuantity" => array("type" => 'attr',"id" => 28),
            "wheelPcd" => array("type" => 'attr',"id" => 5),
            "price" => array("type" => 'inter',"id" => 22),
            "wheelWeight" => array("type" => 'attr',"id" => 34),
            "diskHoleDiameter" => array("type" => 'attr',"id" => 33),
            "disc_width" => array("type" => 'inter',"id" => 93),
            "disc_et" => array("type" => 'attr',"id" => 32),
            "text" => array("type" => 'inter',"id" => 21),
            "pickupAddress" => array("type" => 'inter',"id" => 41),
            "localPrice" => array("type" => 'inter',"id" => 37),
            "minPostalPrice" => array("type" => 'inter',"id" => 36),
            "comment" => array("type" => 'inter',"id" => 32),
            "guarantee" => array("type" => 'inter',"id" => 29),           
        )
        
    );

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
                'users'=>array('*'),
            ),
        );
    }

    public function actionAdminParse($user = "kitaev123",$good_types = array(1,2,3)) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $curl = new Curl;
        $html = str_get_html(iconv('windows-1251', 'utf-8',$curl->request('http://baza.drom.ru/user/'.trim($user))));
        $user_id = $html->find(".userProfile",0) ? $html->find(".userProfile",0)->getAttribute('data-view-dir-user-id') : NULL;
        $curl->removeCookies();
        if($user_id) {
            $user_name = trim($html->find("span .userNick",0)->plaintext);
            $model = Attribute::model()->with('variants.variant')->find("attribute_id=43 AND value=".$user_id);
            if($model) {
                $variant_id = $model->variants->variant_id;
            } else {
                if($variant_id = Variant::add(43,$user_id)) {
                    Dictionary::add(41,intval($variant_id),$user_name); //хуйня какая-то
                } else return false;
            }
            $drom = new Drom();
            foreach ($good_types as $good_type_id) {
                $type = GoodType::model()->findByPk($good_type_id)->code;
                $pages = $drom->parseAllItems('http://baza.drom.ru/user/'.$user_id.'/wheel/'.$type,false);   
                foreach ($pages as $page) {
                    $last_code = $this->getParam( "OTHER", "PARTNERS_LAST_CODE", true);
                    $params = $drom->parseAdvert($page,$user_id,$good_type_id,$last_code);
                    if($params) {
                        if(Good::addAttributes($params,$good_type_id) === true) $this->setParam( "OTHER", "PARTNERS_LAST_CODE",($last_code+1));
                    }
                }   
            }
            $drom->curl->removeCookies();
        }
    }

    public function getUsers($num) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $html = new simple_html_dom();
        $ch = curl_init($ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $count = $this->getParam( "OTHER", "DROM_USER_ID_$num", true );
        $maxs = array(
            1 => 1550001,
            2 => 3100001,
            3 => 4650001
        );
        while ( $count && ($count < $maxs[intval($num)]) ) {
            $url = "http://baza.drom.ru/user/".$count."/wheel/"; 
            curl_setopt($ch, CURLOPT_URL, $url);
            $html = str_get_html(curl_exec($ch));
            $advert = $html->find('strong',0);
            if($advert) {
                $advert = explode(" пр", $advert->plaintext);
                $advert = intval(str_replace(" ", "", $advert[0]));
                if($advert >= 10) { 
                    $model = new DromUser;
                    $model->id = $count;
                    $model->name = trim($html->find('.userNick',0)->plaintext);
                    $model->count = $advert;
                    $city = trim($html->find('.userProfile .middle .item',0)->plaintext);
                    if(stripos($city, "рейтинг") == false) {
                        $model->city = $city;
                    }
                    $model->rating = $html->find('.userRating a',0)->plaintext;
                    $model->save();
                }
            }
            $count++;
            $this->setParam( "OTHER", "DROM_USER_ID_$num", $count );
            if($count%20 == 0) {
                $this->setParam( "OTHER", "DROM_USER_TIME_$num", time() );
                return true;
            }                  
        }
        curl_close($ch);
        return true;
    }

    public function actionQueueNext($num = NULL,$debug = false){
        if( $num === NULL ) return true;
        if( !$this->checkQueueAccess($num) && !$debug ) return true;

        while( $this->allowed($num) || $debug ){
            $this->getUsers($num);
            if( $debug ) return true;
        }
    }

    public function checkQueueAccess($num){
        $last = $this->getParam( "OTHER", "DROM_USER_TIME_$num", true );
        return ( time() - intval($last) > 120 );
    }

    public function allowed($num){
        $queue = $this->getParam( "OTHER", "DROM_USER_TOGGLE_$num", true );
        return ( trim($queue) == "on" );
    }


    public function actionAdminLogin() {

        $criteria = new CDbCriteria();
        $criteria->condition = "login IS NULL";
        $criteria->limit = 100;
        $adverts = Advert::model()->findAll($criteria);
        foreach ($adverts as $advert) {

            $dynamic = $this->getDynObjects(array(
                57 => $advert->place->category_id,
                38 => $advert->city_id,
                37 => $advert->type_id
            ));
            $fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert->good,$dynamic);
            $advert->login = $fields["login"];
            $advert->save();
        }
        
    }
     
    public function actionAdminAddCities() {
        $goods = Advert::model()->findAll("type_id=2129");
        $arr = array();
        foreach ($goods as $key => $good) {
            $arr[$key]['attribute_id'] = 61;
            $arr[$key]['good_id'] = $good->good_id;
            $arr[$key]['variant_id'] = $good->city_id;
        }
        $this->insertValues(GoodAttribute::tableName(),$arr);
    }

    public function actionAdminUpload() {
        $insertAdverts = array();
        $insertCities = array();
        $handle = @fopen(Yii::app()->basePath.'/txt_adv_tire.txt', "r");
        if ($handle) {
            while (($buffer = trim(fgets($handle))) != "") {
                $temp = explode(" ", $buffer);

                array_push($insertAdverts, array(
                    'url' => end(explode("_", $temp[3])),
                    'place_id' => 11,
                    'good_id' => $temp[0],
                    'city_id' => $temp[1],
                    'type_id' => $temp[2],
                ));

                array_push($insertCities, array(
                    "attribute_id" => 60,
                    "good_id" => $temp[0],
                    "variant_id" => $temp[1],
                ));
            }

            var_dump($insertCities);
            var_dump($insertAdverts);

            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);

            $this->insertValues(Advert::tableName(),$insertAdverts);
            $this->insertValues(GoodAttribute::tableName(),$insertCities);
        }
    }
    public function actionAdminIndex(){

        // $queue = Queue::model()->with("advert.good.type","advert.place","action")->findByPk(198);
        // $advert = Advert::model()->findByPk(1);

        // // $queue->setState("processing");

        // $dynamic = $this->getDynObjects(array(
        //     57 => $advert->place->category_id,
        //     38 => $advert->city_id,
        //     37 => $advert->type_id
        // ));

        // $fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert->good,$dynamic);
       

        // print_r($advert->good);
        $fields = array(
            'email'=>'vladis1ove81@gmail.com',
            'seller_name' => "Владислав",
            'phone' => '8 952 896-09-88',
            'location_id' => 657600,
            'params[796]' => 11060,
            'params[797]' => 11065,
            'params[798]' => 11089,
            'title' => 'Шины для машнasdasdы',
            'description' => 'Шины для машны 123213',
            'price' => 1000
        );
        // $images = $this->getImages($advert->good);
        // print_r($fields);
        // die();


        $avito = new Avito();
        $avito->setUser("kolesotomskru@mail.ru","vdjieFRA34");
        // unset($fields["login"]);
        $avito->auth();
        // 25508
        $avito->updatePrice(723876982,$fields,39001);

        // $fields = $avito->generateFields($fields,2);
        // $avito->updateAdvert(724576563,$fields);
        // $id = $avito->addAdvert($fields,$images);
        // if( $queue->action->code == "delete" ){
        //     $delete = $avito->deleteAdvert($advert->url);
        //     if($delete){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "add" ){
        //     $id = $avito->addAdvert($fields,$images);

        //     if( $id && $id != "error"){
        //         $advert->setUrl($id);

        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "update" ){
        //     $id = $avito->updateAdvert($advert->url,$fields);
        //     if( $id ){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "updateImages" ){
        //     $id = $avito->updateAdvert($advert->url,$fields,$images);
        //     if( $id ){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } 

        $avito->curl->removeCookies();

        
        // $fields["contacts"] = '+79994995000';
        // $fields = Drom::self()->generateFields($fields,1);
        // $images = $this->getImages($advert->good);
        // $drom = new Drom();
        // $drom->setUser("kitaev123","vtebar8u");
        // unset($fields["login"]);
        // $drom->auth();

        // if( $queue->action->code == "delete" ){

        // } else if( $queue->action->code == "add" ){
        //     $id = $drom->addAdvert($fields,$images,'fixedPrice');
        //     print_r($id);
        //     if( $id ){
        //         $advert->setUrl($id);

        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "update" ){
        //     $id = $drom->updateAdvert($advert->url,$fields);
            
        //     if( $id ){
        //         $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "updateImages" ){
        //     $id = $drom->updateAdvert($advert->url,$fields,$images);
            
        //     if( $id ){
        //         $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } 

        // $drom->curl->removeCookies();

        // $queue = Queue::model()->with("advert")->findAll('state_id = 4 AND action_id = 1 AND advert.type_id!=869');
        // foreach ($queue as $key => $item) {
        //     $item->state_id = 1;
        //     $item->save();
        // }
        // $drom = new Drom();
        // $drom->setUser("aotomskru","kitaev2");
        // $drom->auth();
        // var_dump($drom->upPaidAdverts(31794221));

        // $drom->curl->removeCookies();
    }



// Дром ------------------------------------------------------------------ Дром
    public function actionCreate(){
        $good = Good::model()->find("id=1633");
        $images = $this->getImages($good);
        $dynamic = array( 38 => 1081, 37 => 869);

        print_r($this->getParams($good,$dynamic));
        die();
        
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->addAdvert($this->getParams($good,$dynamic),$images);
        $drom->curl->removeCookies();
    }   

    public function actionUpdate($images = NULL){
        $url = "http://tomsk.baza.drom.ru/n1011-letnjaja-para-yokohama-dna-ecos-es300-215-40-17-japan-b-u-38913568.html";
        $advert_id = substr($url, strrpos($url, "-")+1,-5);
        $good = Good::model()->find("id=1181");
        if($images) $images = $this->getImages($good);
        $dynamic = array( 38 => 1081, 37 => 869);
        
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->updateAdvert($advert_id,$this->getParams($good,$dynamic),$images);
        $drom->curl->removeCookies();
    }   

    public function actionDelete(){
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->deleteAdverts(array("38916734","38916795"));
        $drom->curl->removeCookies();
    }   

// Дром ------------------------------------------------------------------ Дром

    public function getParams($good,$dynamic) {
        foreach ($this->drom_params[$good->good_type_id] as $key => $value) {
            if($value['type']=="attr") {
                if(isset($good->fields_assoc[$value['id']])) {
                    if(is_array($good->fields_assoc[$value['id']])) {
                        foreach ($good->fields_assoc[$value['id']] as $i => $item) {
                            if($key=='wheelPcd') {
                                $item = explode("*", $item->value);
                                $item[1] = number_format ($item[1],2);
                                $params[$key][$i] = $item[1]."x".$item[0];
                            }else $params[$key][$i] = $item->value;
                        }
                    } else if($key=='wheelPcd') {
                        $pcd = explode("*", $good->fields_assoc[$value['id']]->value);
                        $pcd[1] = number_format ($pcd[1],2);
                        $params[$key] = $pcd[1]."x".$pcd[0];
                    } else $params[$key] = $good->fields_assoc[$value['id']]->value;
                } else $params[$key] = null;
            } else {
                $params[$key] = Interpreter::generate($value['id'],$good,$this->getDynObjects($dynamic,$good->good_type_id));
            }  
        }
        $params['model'] = array($params["model"],0,0);
        $params['price'] = array($params["price"],"RUB");
        $params['quantity'] = 1;
        $params['contacts'] =  array("email" => "","is_email_hidden" => false,"contactInfo" => "+79528960999");
        $params['delivery'] = array("pickupAddress" => $params['pickupAddress'],"localPrice" => $params['localPrice'],"minPostalPrice" => $params['minPostalPrice'],"comment" => $params['comment']);
        unset($params['pickupAddress'],$params['localPrice'],$params['minPostalPrice'],$params['comment']);

        if($good->good_type_id== "1") {
            $params['dirId'] = 234; 
            $params['predestination'] = "regular";
        }
        if($good->good_type_id== "2") {
            $params['dirId'] = 235; 
            $disc_width = explode("/",$params['disc_width']);
            $params['discParameters'] = array();
            foreach ($disc_width as  $i => $value) {
                $params['discParameters'][$i]["disc_width"] = $value;
                if(is_array($params['disc_et'])) {
                    $params['discParameters'][$i]["disc_et"] = (isset($params['disc_et'][$i])) ? $params['disc_et'][$i] : null;
                } else $params['discParameters'][0]["disc_et"] = $params['disc_et'];
            }  
            if(is_array($params['disc_et']))
            foreach ($params['disc_et'] as  $j => $value) {
                $params['discParameters'][$j]["disc_width"] = (isset($disc_width[$j])) ? $disc_width[$j] : null;
                $params['discParameters'][$j]["disc_et"] = $value;
            }     
            unset($params['disc_width'],$params['disc_et'],$disc_width);
        }
        return $params;
    }

}
