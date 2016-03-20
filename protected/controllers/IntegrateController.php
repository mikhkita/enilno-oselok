<?php

class IntegrateController extends Controller
{
    private $params = array(
        "TIRE" => array(
            "GOOD_TYPE_ID" => 1,
            "TITLE_CODE" => 130,
            "TEXT_CODE" => 131,
            "PRICE_CODE" => 9,
            "NAME_ROD" => "Шины",
            "NAME_ROD_MN" => "Шин",
        ),
        "DISC" => array(
            "GOOD_TYPE_ID" => 2,
            "TITLE_CODE" => 76,
            "TEXT_CODE" => 77,
            "PRICE_CODE" => 10,
            "NAME_ROD" => "Диска",
            "NAME_ROD_MN" => "Дисков",
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

// Фотодоска ------------------------------------------------------------- Фотодоска
    public function actionGeneratePdQueue(){
        Log::debug("Начало генерации очереди выкладки на фотодоску");

        $photodoska = new Photodoska();
        $photodoska->auth();

        $main_adverts = explode(",", $this->getParam("PHOTODOSKA","MAIN_ADVERT"));

        foreach ($main_adverts as $key => $value)
            $main_adverts[$key] = trim($value);
        
        $photodoska->deleteAdverts($main_adverts);

        $this->generatePdQueue($this->getItems("TIRE"),"TIRE");

        $this->generatePdQueue($this->getItems("DISC"),"DISC");

        Log::debug("Конец генерации очереди выкладки на фотодоску");
    }

    public function actionPdNext(){
        // return false;
        $photodoska = new Photodoska();

        $this->publicateNext($photodoska,"TIRE");
        $this->publicateNext($photodoska,"DISC");
    }

    public function publicateNext($photodoska,$good_type_code){
        $model = PdQueue::model()->find(array("order"=>"id ASC","condition"=>"good_type_id='".$this->params[$good_type_code]["GOOD_TYPE_ID"]."'"));

        if( $model ){
            Log::debug("Выкладка ".strtolower($this->params[$good_type_code]["NAME_ROD"])." на фотодоску ".$model->title);

            $filename = Yii::app()->params['tempFolder']."-photodoska/".md5(time().rand()."asd").".jpg";
            $resizeObj = new Resize($model->image);
            $resizeObj -> resizeImage(800, 600, 'auto');
            $resizeObj -> saveImage($filename, 100);

            $model->delete();

            if( !$photodoska->isAuth() ) $photodoska->auth();

            // echo "<h3>".$model->title."</h3><img width=300 src='/".$filename."'><br>";

            $photodoska->addAdvert($filename,$model->title,$model->text,trim($this->getParam("PHOTODOSKA","PHONE")),$model->price);

            unlink($filename);

            sleep(10);

            Log::debug("Конец выкладки ".strtolower($this->params[$good_type_code]["NAME_ROD"])." на фотодоску ".$model->title);
        }else{
            Log::debug("Нет ".strtolower($this->params[$good_type_code]["NAME_ROD_MN"])." для выкладки на фотодоску");
        }
    }

    public function generatePdQueue($adverts,$good_type_code){

        PdQueue::model()->deleteAll("good_type_id='".$this->params[$good_type_code]["GOOD_TYPE_ID"]."'");

        $i = 1;
        foreach ($adverts as $advert) {
            $model = new PdQueue();

            $model->title = $advert["TITLE"];
            $model->text = $advert["TEXT"];
            $model->price = $advert["PRICE"];
            $model->image = $advert["IMAGE"];
            $model->good_type_id = $this->params[$good_type_code]["GOOD_TYPE_ID"];

            if( !$model->save() ){
                Log::debug("Ошибка добавления задания в очередь: PHOTODOSKA ".$good_type_code." ".$title);
            }

            // $photodoska->addAdvert(Yii::app()->params['tempFolder']."/photodoska.jpg",$advert["TITLE"],$advert["TEXT"],trim($this->getParam("PHOTODOSKA","PHONE")),$advert["PRICE"]);
            
            // if( $i >= 1 ) return;
            // $i++;
        }
    }

    public function getItems($good_type_code){
        $curParams = $this->params[$good_type_code];

        $model = Good::model()->filter(
            array(
                "good_type_id"=>$curParams["GOOD_TYPE_ID"],
                "int_attributes"=>array(
                    46 => array(
                        "min" => 1,
                    ),
                    43 => array(1418,1419,1857,1860)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $model = $model["items"];
        echo count($model);

        $result = array();

        $dynamic = $this->getDynObjects(array(
            57 => 2048,
            38 => 1081,
            37 => 869
        ));

        if( $model )
        foreach ($model as $key => $item) {
            array_push($result, array(
                    "TEXT" => $this->replaceToBr(Interpreter::generate($curParams["TEXT_CODE"],$item,$dynamic)),
                    "TITLE" => Interpreter::generate($curParams["TITLE_CODE"],$item,$dynamic),
                    "PRICE" => Interpreter::generate($curParams["PRICE_CODE"],$item,$dynamic),
                    "IMAGE" => substr($this->getImages($item)[0],1)
                )
            );
        }

        return $result;
    }

    public function generateList($group,$interpreter_id){
        $out = "";

        for( $j = 0 ; $j < count($group); $j++ ) {
            $min = 999999;
            $min_id = 0;

            foreach ($group as $i => $item) {
                if( intval($item->fields_assoc[20]->value) < $min ){
                    $min_id = $i;
                    $min = intval($item->fields_assoc[20]->value);
                } 
            }

            if( $min != 0 )
                $out .= Interpreter::generate($interpreter_id,$group[$min_id])."<br>";
            unset($group[$min_id]);
        }

        return $out;
    }

    public function findPrice($group){
        $min = 999999;

        foreach ($group as $i => $item) {
            $price = intval($item->fields_assoc[51]->value);
            if( $price < $min && $price != 0 ){
                $min = $price;
            } 
        }

        return ( $min == 999999 )?false:$min;
    }

    public function findImage($group){
        foreach ($group as $item) {
            $images = $this->getImages($item);
            if( count($images) != 0 ) return $images[0];
        }
        return "";
    }
// Фотодоска ------------------------------------------------------------- Фотодоска

// Дром ------------------------------------------------------------------ Дром
    public function actionDromUp(){
        Log::debug("Начало автоподнятия дром");
        $drom = new Drom();
        

        $users = $this->getDromAccount();

        foreach ($users as $user) {
            $drom->setUser($user->login,$user->password);
            $drom->upAdverts();
        }

        Log::debug("Кончало автоподнятия дром");
    }

    public function actionDromParseAll(){
        $drom = new Drom();

        $ids = array();
        $ids2 = array();
        
        $drom->setUser("wheels70","u8atas5c");
        $links = $drom->parseAllItems("http://baza.drom.ru/personal/all/bulletins");

        foreach ($links as $key => $link) {
            $tmp = explode("-", $link);
            $tmp = array_pop(explode("/", $tmp[0]));
            if( ctype_digit($tmp) ){
                $ids[$tmp] = "1";
                array_push($ids2, $tmp);
            }
        }

        foreach ($ids as $id => $seller) {
            file_put_contents(Yii::app()->basePath."/drom-links.txt", $id."\n", FILE_APPEND);
        }
    }
// Дром ------------------------------------------------------------------ Дром

// Yahoo ----------------------------------------------------------------- Yahoo
    public function actionYahooBids(){
        // return true;
        $model = YahooCategory::model()->findAll(array("order"=>"id ASC"));
        foreach ($model as $item)
            $this->parseCategory($item,true);

    }

    public function actionYahooAll(){
        // return true;
        $category = $this->getNextCategory();
        
        $this->parseCategory($category);
    }

    public function getNextCategory(){
        $cur_cat_id = intval($this->getParam("YAHOO","CURRENT_CATEGORY"));

        $model = YahooCategory::model()->findAll(array("order"=>"id ASC"));

        $first = NULL;
        $next = false;
        foreach ($model as $item) {
            if( $first === NULL ) $first = $item;
            if( $next ){
                $this->setParam("YAHOO","CURRENT_CATEGORY",$item->id);
                return $item;
            }
            if( intval($item->id) == $cur_cat_id ) $next = true;
        }

        $this->setParam("YAHOO","CURRENT_CATEGORY",$first->id);
        return $first;
    }

    public function parseCategory($category, $bids = false){
        $yahoo = new Yahoo();
        $tog = true;

        $page = $yahoo->getNextPage($category->code,( ($bids)?"a":"d" ));

        while( $page && $tog ){
            $sellers = array();
            $delete_ids = array();

            foreach ($page["items"] as $key => $item){
                if( intval($item->CurrentPrice) > intval($category->max_price*$this->courses["USD"]) ){
                    unset($page["items"][$key]);
                    array_push($delete_ids, "'".$item->AuctionID."'");
                    continue;
                }
                if( !in_array($item->Seller->Id, $sellers) ) array_push($sellers, $item->Seller->Id);
            }

            $this->updateSellers($sellers);

            $sellers_id = $this->getSellersID($sellers);

            foreach ($page["items"] as &$item){
                $item->Seller->Id = $sellers_id[$item->Seller->Id];
            }

            $this->updateLots($page["items"],$category->id);
            YahooLot::model()->deleteAll("id in (".implode(",", $delete_ids).")");

            // Log::debug($category->name." Страница: ".$yahoo->getLastPage());

            if( $bids ){
                $tog = ( intval(array_pop($page["items"])->Bids) > 0 )?true:false;
            }else{
                $tog = ( intval(array_pop($page["items"])->Bids) == 0 )?true:false;
            }

            $page = $yahoo->getNextPage($category->code,intval($category->max_price*$this->courses["USD"]),( ($bids)?"a":"d" ));
        }
        
        Log::debug($category->name." Парсинг завершен. Количество полученных страниц: ".($yahoo->getLastPage()-1));
    }

    public function getSellersID($sellers){
        $model = Yii::app()->db->createCommand()->select("id, name")->from(YahooSeller::tableName())->where(array('in', 'name', $sellers))->queryAll();

        $result = array();
        foreach ($model as $seller) {
            $result[$seller["name"]] = $seller["id"];
        }
        return $result;
    }

    public function updateSellers($sellers){
        $sql = "INSERT IGNORE INTO `".YahooSeller::tableName()."` (`id`,`name`) VALUES ";

        $exist = Yii::app()->db->createCommand()->select("id, name")->from(YahooSeller::tableName())->where(array('in', 'name', $sellers))->queryAll();

        $values = array();
        foreach ($sellers as $key => $seller) {
            if( !$this->existSeller($seller,$exist) ){
                array_push($values, "(NULL,'".addslashes(trim($seller))."')");
            }
        }

        if( count($values) ){
            $sql .= implode(",", $values);
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public function existSeller($seller,$exist){
        foreach ($exist as $sel)
            if( $sel["name"] == $seller ) return true;
        return false;
    }

    public function updateLots($items,$category_id){

        $tableName = YahooLot::tableName();

        $values = array();
        foreach ($items as $item) {
            if( $item->IsReserved == "false" ){
                $bidorbuy = (isset($item->BidOrBuy))?intval($item->BidOrBuy):0;
                array_push($values, array(NULL, $item->AuctionID, addslashes($item->Title), date("Y-m-d H:i:s", time()), $item->Image, intval($item->CurrentPrice), $bidorbuy, $item->Bids, $this->convertTime($item->EndTime), $category_id, $item->Seller->Id, 0));
            }
        }

        $update = array("update_time","cur_price","bid_price","bids","end_time");

        $this->updateRows(YahooLot::tableName(),$values,$update);
    }

    public function convertTime($time){
        return date("Y-m-d H:i:s", date_timestamp_get(date_create(substr(str_replace("T", " ", $time), 0, strpos($time, "+"))))-3*60*60);
    }
// Yahoo ----------------------------------------------------------------- Yahoo

// Выкладка -------------------------------------------------------------- Выкладка
    public function actionQueueNextAvito($debug = false){
        $this->doQueueNext($debug, 2048);
    }

    // public function actionQueueNextAvito1($debug = false){
    //     sleep(5);
    //     $this->doQueueNext($debug, 2048, "1");
    // }

    // public function actionQueueNextAvito2($debug = false){
    //     sleep(10);
    //     $this->doQueueNext($debug, 2048, "2");
    // }

    public function actionQueueNextDrom($debug = false){
        $this->doQueueNext($debug, 2047);
    }

    public function doQueueNext($debug = false,$category_id,$nth = ""){
        if( !$this->checkQueueAccess($category_id, $nth) && !$debug ) return true;

        while( $this->allowed($category_id) || $debug ){
            // if( $category_id == 2048 )
            //     $this->setParam( "AVITO", "CITY".$nth, "0" );

            $this->writeTime($category_id, $nth);
            if( !$this->getNext($category_id, $nth) ){
                sleep(5);
            }
            sleep(rand(30,50));
              
            if( $debug ) return true;
        }
    }

    public function writeTime($category_id, $nth = ""){
        $this->setParam( Place::model()->categories[$category_id], "TIME".$nth, time() );
    }

    public function checkQueueAccess($category_id, $nth = ""){
        $last = $this->getParam( Place::model()->categories[$category_id], "TIME".$nth, true );
        return ( time() - intval($last) > 180 );
    }

    public function allowed($category_id){
        $queue = $this->getParam( Place::model()->categories[$category_id], "TOGGLE", true );
        return ( trim($queue) == "on" );
    }

    public function getNext($category_id, $nth = ""){
        // if( $category_id == 2048 ){
        //     $queue = Queue::getNext($category_id, array(
        //         $this->getParam( "AVITO", "CITY", true ),
        //         $this->getParam( "AVITO", "CITY1", true ),
        //         $this->getParam( "AVITO", "CITY2", true )
        //     ));
        //     $this->setParam( "AVITO", "CITY".$nth, $queue->advert->city_id );
        // }else{
            $queue = Queue::getNext($category_id);
        // }

        if( !$queue ) return false;
        $advert = $queue->advert;

        $place_name = $this->getPlaceName($advert->place->category_id);

        $queue->setState("processing");

        $dynamic = $this->getDynObjects(array(
            57 => $advert->place->category_id,
            38 => $advert->city_id,
            37 => $advert->type_id
        ));

        $unique = Place::getInters($advert->place->category_id,$advert->good->type->id,true);
        $fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert,$dynamic);

        // var_dump($unique);
        // var_dump($fields);
        // die();

        if( $place_name == "AVITO" && $queue->action->code != "delete" && $queue->action->code != "updateImages" ){
            if( $fields["title"] == "not unique" ) $queue->setState("titleNotUnique");
            if( $fields["description"] == "not unique" ) $queue->setState("textNotUnique");

            if( $fields["title"] == "not unique" || $fields["description"] == "not unique" )return true;
        }

        printf('<br>2Прошло %.4F сек.<br>', microtime(true) - $start); 


        $images = $this->getImages($advert->good);
        if( $place_name == "DROM" ){
            $account = $this->getDromAccount($fields["login"]);
            $place = new Drom( (isset($account->proxy) && $account->proxy != "")?$account->proxy:NULL );
            $fields["contacts"] = $account->phone;
        }else if( $place_name == "AVITO" ){
            $account = $this->getAvitoAccount($fields["login"]);
            $place = new Avito( (isset($account->proxy) && $account->proxy != "")?$account->proxy:NULL );
            $fields["phone"] = $account->phone;
            $fields["email"] = $account->login;
            $fields["seller_name"] = $account->name;
        }


        if( !$account ){
            Log::error("Не найден пользователь с логином \"".$fields["login"]."\"");
            $queue->setState("error");
            return true;
        }

        $fields = $place->generateFields($fields,$advert->good->good_type_id);

        // print_r($fields);
        // die();
        
        $place->setUser($account->login, $account->password);
        $res = $place->auth();
        // die();
        
        switch ($queue->action->code) {
            case 'delete':

                // if( $advert->type_id == 869 ){
                    Log::debug("Удаление ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->deleteAdvert( (($place_name == "AVITO")?$advert->link:$advert->url) );
                    // $result = $place->deleteAdvert( $advert->url );
                    if( $result )
                        $advert->delete();
                // }else{
                //     Log::debug("Попытка удаления платного объявления ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                //     $queue->setState("partner");
                //     // $place->curl->removeCookies();
                //     return true;
                // }

                break;
            case 'add':

                Log::debug("Выкладка ".$advert->good->fields_assoc[3]->value." в аккаунт ".$account->login);
                $result = $place->addAdvert($fields,$images);
                if( $result )
                    $advert->setUrl($result);

                break;
            case 'update':

                if( $place_name == "AVITO" ){
                    $result = true;
                }else{
                    Log::debug("Редактирование ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->updateAdvert( (($place_name == "AVITO")?$advert->link:$advert->url) ,$fields);
                }

                break;
            case 'updateImages':

                if( $place_name == "AVITO" ){
                    $result = true;
                }else{
                    Log::debug("Обновление фотографий ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->updateAdvert( (($place_name == "AVITO")?$advert->link:$advert->url) ,$fields,$images,true);
                }

                break;
            case 'updateWithImages':

                if( $place_name == "AVITO" ){
                    $result = true;
                }else{
                    Log::debug("Обновление с фотографиями ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->updateAdvert( (($place_name == "AVITO")?$advert->link:$advert->url) ,$fields,$images);
                }

                break;
            case 'payUp':

                Log::debug("Платное поднятие ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                $result = $place->upPaidAdverts( (($place_name == "AVITO")?$advert->link:$advert->url) );

                break;
            case 'up':

                Log::debug("Поднятие ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                $result = $place->up( (($place_name == "AVITO")?$advert->link:$advert->url) );

                break;
            case 'updatePrice':

                Log::debug("Обновление цены ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                if( $place_name == "AVITO" ){
                    $result = $place->updatePrice($advert->link, $fields);
                }else if( $place_name == "DROM" ){
                    $result = $place->updateAdvert($advert->url,$fields);
                }

                break;
        }
        printf('<br>4Прошло %.4F сек.<br>', microtime(true) - $start); 
        // var_dump($fields);
        // die();

        // $result = 1;

        if( $result ){
            if( $place_name == "AVITO" && ($queue->action->code == "add" || $queue->action->code == "update" || $queue->action->code == "updateWithImages") ){
                $unique_arr = array();
                foreach ($unique as $i => $u)
                    $unique_arr[$u] = $fields[$i];

                $advert->replaceUnique($unique_arr);
            }
            // echo "Успешно";
            Log::debug("Действие над ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login." прошло успешно");
            $queue->delete();
        }else{
            // echo "Ошибка";
            Log::debug("Действие над ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login." прошло с ОШИБКОЙ");
            $queue->setState("error");
        }

        // $place->curl->removeCookies();
        return true;
    }

    public function getPlaceName($place_id){
        switch ($place_id) {
            case 2047:
                return "DROM";
                break;
            case 2048:
                return "AVITO";
                break;
            default:
                return "UNDEFINED";
                break;
        }
    }

    public function actionDeleteAdverts(){
        $model = Queue::model()->with("advert.place")->findAll("place.category_id=2048 AND action_id=2");

        $ids = $this->getIds($model);
        if( count($ids) )
            Queue::model()->deleteAll("id IN (".implode(",", $ids).")");
    }

    public function actionDromParse(){
        $drom = new Drom();
        $drom->parseUser();
    }
// Выкладка -------------------------------------------------------------- Выкладка

// Магазин --------------------------------------------------------------- Магазин
    public function actionGoodCodes(){
        $j = 1;
        for ($i = 1; $i <= 3; $i++) {
            $data = Good::model()->filter(
                array(
                    "good_type_id"=>$i,
                )
            )->getPage(
                array(
                    'pageSize'=>99999,
                )
            );

            $goods = $data["items"];

            $good_type = GoodType::model()->findByPk($i);

            $values = array();
            $search = array();
            foreach ($goods as $key => $good) {
                $str = Interpreter::generate($this->getParam("SHOP", $good_type->code."_CODE"),$good);
                array_push($values, array($good->id,NULL,$good->good_type_id,0,Translit::get($str)));

                $str = explode(" ", $str);
                array_pop($str);
                $str = $good->fields_assoc[3]->value." ".$good_type->name." ".implode(" ", $str);
                $public = ( isset($good->fields_assoc[43]) && in_array(intval($good->fields_assoc[43]->value), array(0, 1, 10, 13)) )?"1":"0";
                array_push($search, array($good->id, addslashes( preg_replace('/[\s]{2,}/', ' ', $str) ), $public));
            }

            $this->updateRows(Good::tableName(),$values,array("code"));
            $this->updateRows(Search::tableName(), $search, array("value", "public"));     

            header("HTTP/1.1 200 OK");
        }
    }

    public function actionCityCodes($debug=false){
        if( $debug!=1 ) return false;
        DictionaryVariant::model()->deleteAll("dictionary_id=125");
        $attribute = Attribute::model()->with(array("variants.variant"=>array("order"=>"sort ASC")))->findByPk(38);
        foreach ($attribute->variants as $key => $variant)
            Dictionary::add(125,$variant->variant_id,Translit::get($variant->variant->value));
    }

    public function actionSitemap(){
        $filename = "temp.xml";
        $city_code = "tomsk";
        $sitemap = "";
        $header = "";
        $cities = DictionaryVariant::model()->findAll("dictionary_id=125");
        if( file_exists($filename) ) {
            $lines = explode("\n", file_get_contents($filename));

            foreach ($cities as $key => $city){
                if( $city->value != "" ){
                    foreach ($lines as $line){
                        if( strpos($line, $city_code.".") === false ){
                            $sitemap .= $line."\n";
                        }else{
                            $sitemap .= (str_replace($city_code.".", $city->value.".", $line)."\n");
                        }   
                    }
                }
                file_put_contents("sitemap/".$city->value.".xml", $sitemap);
                $robots = "User-agent: *\nDisallow: \nSitemap: http://".$city->value.".koleso.online/sitemap.xml";
                file_put_contents("robots/".$city->value.".txt", $robots);
                $sitemap = "";
            }
            // unlink($filename);
        }
    }
// Магазин --------------------------------------------------------------- Магазин

// Планировщик ----------------------------------------------------------- Планировщик
    public function actionDoNextTask($debug = false){
        if( !$this->checkTaskAccess() && !$debug ) return true;

        while( $this->allowedTask() || $debug ){
            
            $this->setParam( "SERVICE", "TASK_TIME", time() );

            if( !$this->doTask() ){
                $this->setParam( "SERVICE", "TASK_TIME", 0 );
                return true;
            }
            if( $debug ) return true;
        }
    }

    public function doTask(){
        $task = Cron::getNext();

        if( !$task ) return false;

        $result = @file_get_contents(urldecode($task->link));
        if( $result !== false ){
            $json = json_decode($result);
            var_dump($result);
            if( $json->result == "success" ){
                $task->delete();
            }else{
                $task->state_id = Cron::model()->states["error"];
                if( isset($json->message) )
                    $task->error = $json->message;
                
                $task->save();
            }
        }else{
            $task->state_id = Cron::model()->states["error"];
            $task->error = "Ошибка запроса";
            $task->save();
        }
        return true;
    }

    public function checkTaskAccess(){
        $last = $this->getParam( "SERVICE", "TASK_TIME", true );
        return ( time() - intval($last) > 180 );
    }

    public function allowedTask(){
        $toggle = $this->getParam( "SERVICE", "TASK_TOGGLE", true );
        return ( trim($toggle) == "on" );
    }
// Планировщик ----------------------------------------------------------- Планировщик

// Остальное ------------------------------------------------------------- Остальное
    public function actionTest(){
        $avito = new Avito();
        $avito->setUser("beatbox787@gmail.com", "481516");
        $res = $avito->auth();
        $avito->parseMessages();
    }

    public function actionPercent(){
        $start = microtime(true);
        $unique = Yii::app()->db->createCommand()
            ->select('value')
            ->from(Unique::tableName().' t')
            ->where('interpreter_id=139')
            ->queryAll();
        
        $max = 0;
        // $txt = "";

        $text = 'Возможна отправка по России. Предоставлю доп. фото Поставляется на заказ с нашего склада, доставка 9 - 12 дней, включена в цену.диаметр 17, cверловка 5-114.3, ширина 7,вылет 38, Возможна отправка по России. Предоставлю доп. фото';

        foreach ($unique as $key => $item) {
            similar_text($item["value"], $text, $percent);
            // echo $item["value"]."<br>";
            if( $percent > $max ){
                $max = $percent;
                // $txt = $item["value"];
            }
        }
        echo round($max);
        printf('<br>Прошло %.4F сек.<br>', microtime(true) - $start);

        // print_r($unique);
    }

    public function actionAdminIndex(){
        $start = microtime(true);

        $filter_options = array(
            "good_type_id" => 2,
            "attributes" => array(
                9 => array(1318,1319,1320,1321,1322,1323,1324),
                5 => array(1384,1267,1269,1270,1473),
                31 => array(1311,1312,1313,1314,1315,1316),
            ),
            "int_attributes" => array(
                20 => array(
                    "min" => 2000,
                    "max" => 40000
                )
            )
        );
        $sort_options = array(
            "field" => 11,
            "type" => "ASC"
        );
        $page_options = array(
            'pageSize'=>1300,
        );
        $goods = Good::model()->filter($filter_options)->sort($sort_options)->getPage($page_options, array(3,5,9,11), true)["items"];

        echo " ".count($goods);

        // $criteria = new CDbCriteria();
        // // $criteria->with = array("type","fields.variant","fields.attribute");
        // $criteria->order = "field(good_id,6,3,2) DESC, good_id ASC";
        // $criteria->limit = 10000;

        // $goods = GoodAttributeFilter::model()->findAll($criteria);

        // foreach ($goods as $key => $good)
        //     echo $good->id."<br>";

        list($queryCount, $queryTime) = Yii::app()->db->getStats();
                    echo "Кол-во запросов: $queryCount, Общее время запросов: ".sprintf('%0.5f',$queryTime)."s";

            echo "<br>".round(microtime(true) - $this->start,4);
    }

    public function actionCacheAll(){
        $goods = Yii::app()->db->createCommand()
            ->select('g.id')
            ->from(Good::tableName().' g')
            ->queryAll();

        $links = array();
        foreach ($goods as $i => $good)
            array_push($links, "http://".Yii::app()->params['ip'].$this->createUrl('/integrate/cacheone',array('id'=> $good["id"])));

        Cron::addAll($links);
    }

    public function actionCacheOne($id){
        $good = Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk($id);

        $good->getImages();

        sleep(2);

        echo json_encode(array(
            "result" => "success"
        ));
    }

    public function actionParseAvito(){
        echo "<br>";
        $place = new Avito("admin:4815162342@185.63.191.103:1212");
        $place->setUser("kemerovoman@inbox.ru", "specopa45");
        $res = $place->auth();
        // $result = $place->parseAll("https://www.avito.ru/profile/items/active");
        $result = $place->parseAll("https://www.avito.ru/profile/items/old");
        $links = $result["links"];
        $count_links = $result["count"];

        // $links = array("/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_lityh_diskov_r-18_5x114.3_s_auk_723451217","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_lit._diskov_5x100_r.18_s_auktsion_723442360","/krasnodar/zapchasti_i_aksessuary/komplekt_super_diskov_r_18_5x100_s_auktsiona_723434740","/krasnodar/zapchasti_i_aksessuary/komplekt_otlichnyh_lit._diskov_5x114.3_r.19_s_auktsi_723424936","/krasnodar/zapchasti_i_aksessuary/komplekt_super_lityh_diskov_5x114.3_r.18_s_auktsion_723417257","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_avtodiskov_r18_5x114.3_s_auktsio_723197306","/krasnodar/zapchasti_i_aksessuary/komplekt_interesnyh_avtodiskov_r-18_5x114.3_s_aukts_723183564","/krasnodar/zapchasti_i_aksessuary/komplekt_otlichnyh_litya_5x114.3-5x100_r_17_s_auktsi_723171098","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_diskov_r.18_5x114.3_s_auktsiona_723163203","/krasnodar/zapchasti_i_aksessuary/komplekt_interesnyh_diskov_5x114.3_r-17_s_auktsiona_723149535","/krasnodar/zapchasti_i_aksessuary/komplekt_otlichnyh_diskov_r.18_5x114.3_s_auktsiona_723126331","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_litya_r.17_5x114.3_s_auktsiona_723112440","/krasnodar/zapchasti_i_aksessuary/komplekt_super_litya_r-18_5x114.3_s_auktsiona_723089504","/krasnodar/zapchasti_i_aksessuary/komplekt_otlichnyh_diskov_r17_5x114.3_s_auktsiona_723079475","/krasnodar/zapchasti_i_aksessuary/komplekt_super_litya_5x100_r-18_s_auktsiona_723063570","/krasnodar/zapchasti_i_aksessuary/komplekt_interesnyh_lit._diskov_5x114.3_r_18_s_auk_723052033","/krasnodar/zapchasti_i_aksessuary/komplekt_otlichnyh_diskov_5x114.3_r_18_s_auktsiona_723040316","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_diskov_5x114.3_r-18_s_auktsiona_723028781","/krasnodar/zapchasti_i_aksessuary/komplekt_super_diskov_r.17_4x114.3_s_auktsiona_722994757","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_diskov_5x114.3_r.18_s_auktsiona_722981346","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_diskov_5x114.3_r.17_s_auktsiona_722967503","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_lit._diskov_5x114.3_r17_s_auktsio_722949791","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_diskov_5x100_r17_s_auktsiona_718165070","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_avtodiskov_5x114.3_r_18_s_auktsio_718156002","/krasnodar/zapchasti_i_aksessuary/komplekt_interesnyh_lityh_diskov_r-19_5x114.3_s_au_718144655","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_lit._diskov_r.18_5x114.3_s_aukts_718133136","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_lit._diskov_5x114.3_r.19_s_auktsi_718119911","/krasnodar/zapchasti_i_aksessuary/komplekt_effektnyh_litya_5x100_r.18_s_auktsiona_718105433","/krasnodar/zapchasti_i_aksessuary/komplekt_interesnyh_avtodiskov_r-19_5x114.3_s_aukts_718096872","/krasnodar/zapchasti_i_aksessuary/komplekt_shikarnyh_lit._diskov_r19_5x114.3-4x114.3_71807689");
        $delete = array();
        $count = 0;
        if( count($links) ){
            foreach ($links as $i => $link) {
                $code = substr($link, strripos($link, "_")+1);

                $model = Advert::model()->find("url='$code'");
                if( $model ){
                    $model->link = $link;
                    if( $model->save() ){
                        $count++;
                    }
                }else{
                    array_push($delete, $link);
                }
            }

            $errors = array();
            foreach ($delete as $i => $link) {
                if( !$place->deleteAdvert($link) ){
                    array_push($errors, $link);
                }
            }

            if( !count($errors) ){
                echo "Парсинг прошел успешно, получено ссылок: $count из $count_links.<br>";
                echo "Удалено объявлений: ".count($delete);
            }else{
                echo "Возникли ошибки удаления, получено ссылок: $count из $count_links.<br>";
                print_r($errors);
            }
        }
    }

    public function actionRibka(){
        $model = Good::model()->filter(
            array(
                "good_type_id"=>2,
                "attributes"=>array(
                    43 => array(2915)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $model = $model["items"];

        $images = array();
        foreach ($model as $i => $good) {
            foreach ($this->getImages($good) as $key => $link) {
                $link = substr($link, 1);
                $size = getimagesize($link);
                if( $size[0] == 640 && $size[1] == 115 ){
                    echo "<img src='/".$link."'>";
                    echo "<br>";
                    unlink($link);
                    array_push($images, $link);
                }
                // echo imagesy($link)." ".imagesx($link)."<br>";
            }
        }
        print_r(count($images));

        // echo count($model);
    }

    public function actionParse70(){
        $places = array( 1 => 9, 2 => 10 );
        $names = array( 1 => "tire", 2 => "disc" );
        $types = array( 1 => "2129", 2 => "868" );
        $attrs = array( 1 => 61, 2 => 59);
        $good_type_id = 1;
        $drom = new Drom();

        $links = $drom->parseAllItems("http://baza.drom.ru/user/wheels70/wheel/".$names[$good_type_id]."/", "848235", false, true, true);

        $codes = array();
        foreach ($links as $key => $item) {
            $item->title = array_shift(explode(" ",substr($item->title, 1)));
            array_push($codes, $item->title);
        }

        $goods = Good::getIdbyCode($codes, array($good_type_id));

        print_r($goods);

        $values = array();
        foreach ($links as $key => $item) {
            $tmp = array(
                "good_id" => $goods[$item->title],
                "place_id" => $places[$good_type_id],
                "url" => $item->link,
                "type_id" => $types[$item->type],
                "city_id" => 1081
            );
            array_push($values, $tmp);
        }
        $this->insertValues(Advert::tableName(), $values);

        $values = array();
        foreach ($links as $key => $item) {
            $tmp = array(
                "good_id" => $goods[$item->title],
                "attribute_id" => $attrs[$item->type],
                "variant_id" => 1081,
            );
            array_push($values, $tmp);
        }
        $this->insertValues(GoodAttribute::tableName(), $values);        
    }
// Остальное ------------------------------------------------------------- Остальное

    public function actionGoodTest(){
        $good = Good::model()->with(array("type","fields.variant","fields.attribute"))->findByPk(206);

        var_dump(Task::model()->testGood($good));

        // var_dump(Task::model()->filter(1));
    }

}
