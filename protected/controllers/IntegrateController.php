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

    public function actionWater(){
        $good = Good::model()->findByPk(138);
        $images = $this->getImages(NULL, NULL, NULL, $good, false, true );

        // $account = $this->getDromAccount("6213676");
        // if($account->watermark){
            $images = $this->setWatermark($images, "vlv");
        // }
        // print_r($account);
        
        // $this->removeWatermark($images);
        print_r($images);
    }

// Выкладка -------------------------------------------------------------- Выкладка
    public function actionQueueNextVk($debug = false){
        $this->doQueueNext($debug, 3875);
    }

    public function actionQueueNextAvito($debug = false){
        if( $this->getParam( "AVITO", "AUTH", true ) == "on" ){
            Queue::checkReady();

            $this->doQueueNext($debug, 2048);
        }
    }

    public function actionQueueNextDrom($debug = false){
        Queue::checkReady(true);

        $this->doQueueNext($debug, 2047);
    }

    public function doQueueNext($debug = false,$category_id,$nth = ""){
        if( (!$this->checkQueueAccess($category_id, $nth) && !$debug) || !$this->checkTime($category_id) ) return true;

        while( ($this->allowed($category_id) && $this->checkTime($category_id)) || $debug ){
            $this->writeTime($category_id, $nth);
            if( !$this->getNext($category_id, $nth) ){
                return true;
            }else{
                if( !$debug ) sleep(rand(2,7));
            }
              
            if( $debug ) return true;
        }
    }

    public function checkTime($category_id = NULL){
        if( $category_id == 2047 ){
            // $date = (object) getdate();
            // if( $date->hours >= 0 && $date->hours < 12 ) return false;
        }
        return true;
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

    public function getNext($category_id, $avito = NULL){
        $this->actionClearArchived();

        if( $avito ){
            $queue = Queue::getAvitoAddNext($category_id);
        }else{
            $queue = Queue::getNext($category_id);
        }
        // var_dump($queue);
        // die();

        if( !$queue ) return false;
        $advert = $queue->advert;

        if( !isset($advert->good) ){
            Log::debug("Удаление объявления ".$advert->id, false, true);
            $advert->delete();
            return false;
        }

        $place_name = $this->getPlaceName($advert->place->category_id);

        // if( !$avito )
            $queue->setState("processing");

        $dynamic = $this->getDynObjects(array(
            57 => $advert->place->category_id,
            38 => $advert->city_id,
            37 => $advert->type_id
        ));

        $fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert,$dynamic);

        // var_dump($unique);
        // var_dump($fields);
        // die();

        if( $place_name == "AVITO" && $queue->action->code != "delete" && $queue->action->code != "updateImages" ){
            if( $fields["title"] == "not unique" ) $queue->setState("titleNotUnique");
            if( $fields["description"] == "not unique" ) $queue->setState("textNotUnique");

            if( $fields["title"] == "not unique" || $fields["description"] == "not unique" ){
                if( $avito ){
                    return $this->getNext(2048, true);
                }else{
                    return true;
                }
            }
        }

        if( $place_name == "DROM" ){
            $account = $this->getDromAccount($fields["login"]);

            if( $advert->type_id == 869 && isset($account->count) && $account->count >= 50 ){
                $queue->setState("limit");
                return false;
            }

            $place = new Drom( (isset($account->proxy) && $account->proxy != "")?$account->proxy:NULL );
            $fields["contacts"] = $account->phone;
        }else if( $place_name == "AVITO" ){
            // Log::debug("1");
            $account = $this->getAvitoAccount($fields["login"]);
            // Log::debug("2");
            if( $account->proxy == NULL ){
                // Log::debug("3");
                $queue->setState("notProxy");
                return false;
            }
            if( $queue->action->code != "add" ){
                $place = new Avito( $account->proxy );
            }else{
                $place = new Avito();
            }
            $fields["phone"] = $account->phone;
            $fields["email"] = $account->login;
            $fields["seller_name"] = $account->name;

            if( isset($account->package) ) 
                $fields["fees[packageId]"] = $account->package;
        }else if( $place_name == "VK" ){
            $place = new Vk();
            $account = true;
        }

        // print_r($place->curl->request("http://api.sypexgeo.net/"));
        // die();

        if( !$account ){
            Log::error("Не найден пользователь с логином \"".$fields["login"]."\"");
            $queue->setState("error");
            return true;
        }

        $images = $this->getImages(NULL, NULL, (isset($account->photo))?$account->photo:NULL, $advert->good, false, (isset($account->adding) && intval($account->adding) == 1) );

        if( !count($images) && !in_array($queue->action->code, array("delete","payUp","up")) ){
            $queue->setState("noImages");

            if( $avito ){
                return $this->getNext(2048, true);
            }else{
                return true;
            }
        }

        if($account->watermark){
            $images = $this->setWatermark($images, $account->watermark);
        }

        $fields = $place->generateFields($fields,$advert->good->good_type_id);

        // print_r($fields);
        // echo "<br>";

        if($avito){
            $fields = $place->convertFields($fields);

            $fields["images"] = $place->resizeImages($images);
            $fields["password"] = $account->password;
            $fields["proxy"] = array_pop(explode("@", $account->proxy));
            $fields["code"] = $advert->good->fields_assoc[3]->value;
            $fields["id"] = $advert->id;

            return $fields;
        }

        // print_r($images);
        // echo "<br>";
        // print_r($fields);
        // die();
        
        if( $place_name != "VK" ){
            if( $place_name == "DROM" ){
                $place->setUser($account->login, $account->password);
                $res = $place->auth();  
            }

            if( $place_name == "AVITO" ){
                $place->setUser($account->login, $account->password, $account->name);

                if( !$place->isAuth() ){
                    // $this->setParam( "AVITO", "AUTH", "off" );
                    echo "Нет авторизации";
                    $queue->setState("waiting");
                    return false;
                }
                $this->parseAvito($place);
            }
        }

        
        switch ($queue->action->code) {
            case 'delete':

                // if( $advert->type_id == 869 ){
                    Log::debug("Удаление ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->deleteAdvert( $advert->url );
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

                // !!!!!! ВАЖНО !!!!!!!! Убрать условие при создании объекта AVITO
                if( !$avito ){
                    Log::debug("Выкладка ".$advert->good->fields_assoc[3]->value." в аккаунт ".$account->login);
                    $result = $place->addAdvert($fields,$images);
                    if( $result )
                        $advert->setUrl($result);
                }

                break;
            case 'update':

                if( $place_name == "VK" ){
                    $result = $place->updateAdvert($advert->url, $fields, $images);
                }else{
                    Log::debug("Редактирование ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->updateAdvert( $advert->url ,$fields);
                }

                break;
            case 'updateImages':

                if( $place_name == "VK" ){
                    $result = $place->updateAdvert($advert->url, $fields, $images);
                }else{
                    Log::debug("Обновление фотографий ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->updateAdvert( $advert->url ,$fields,$images,true);
                }

                break;
            case 'updateWithImages':

                
                Log::debug("Обновление с фотографиями ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                $result = $place->updateAdvert( $advert->url ,$fields,$images);

                break;
            case 'payUp':

                Log::debug("Платное поднятие ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                $result = $place->upPaidAdverts( $advert->url );

                break;
            case 'up':

                if( $place_name == "AVITO" ){
                    Log::debug("Поднятие ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                    $result = $place->up( $advert->url, $fields );
                }

                break;
            case 'updatePrice':

                Log::debug("Обновление цены ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login);
                if( $place_name == "AVITO" ){
                    $result = $place->updatePrice($advert->url, $fields);
                }else if( $place_name == "DROM" ){
                    $result = $place->updateAdvert($advert->url,$fields);
                }

                break;
        }
        printf('<br>4Прошло %.4F сек.<br>', microtime(true) - $start); 

        if($account->watermark){
            $this->removeWatermark($images);
        }

        if( $result ){
            // if( $place_name == "AVITO" && ($queue->action->code == "add" || $queue->action->code == "update" || $queue->action->code == "updateWithImages") ){
                // $unique_arr = array();
                // foreach ($unique as $i => $u)
                //     $unique_arr[$u] = $fields[$i];

                // $advert->replaceUnique($unique_arr);
            // }else 
            if( $place_name == "DROM" && $advert->type_id == 869 && $queue->action->code == "add" ){
                $this->dromIncCount($account);
            }
            Log::debug("Действие над ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login." прошло успешно");
            $queue->delete();
        }else{
            Log::debug("Действие над ".$advert->good->fields_assoc[3]->value." в аккаунте ".$account->login." прошло с ОШИБКОЙ");
            $queue->setState("error");
        }

        return true;
    }
    public function actionClearArchived(){
        $ids =  Controller::getIds( Queue::model()->with("advert.good_filter")->findAll("good_filter.archive <> 0 AND t.action_id <> 3") );
        if( count($ids) )
            Queue::model()->deleteAll("id IN (".implode(",", $ids).")");
    }

    public function actionGetNextAvito(){
        $fields = $this->getNext(2048, true);
        print_r(json_encode($fields));
    }
    public function actionSetAvitoResult($id, $result, $url = NULL){
        $queue = Queue::model()->with("advert")->find("advert_id='$id' AND action_id=1");
        if( $queue ){
            if( $result == "success" ){
                $queue->advert->setUrl($url);
                $queue->delete();
            }else if( $result == "error" ){
                $queue->setState("error");
            }
            echo "1";
        }else{
            echo "0";
        }
    }

    public function getPlaceName($place_id){
        return Place::model()->categories[$place_id];
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

    public function actionResetLimit(){
        $rows = DesktopTableRow::model()->findAll("table_id=12");
        $ids = array();
        foreach ($rows as $i => $row)
            array_push($ids, $row->id);

        if( count($ids) )
            DesktopTableCell::model()->updateAll(array("int_value" => 0), "row_id IN (".implode(",", $ids).") AND col_id=121");

        Queue::model()->updateAll(array("state_id" => 1),"state_id=9");
    }

    public function actionAuto(){
        switch (Yii::app()->params["site"]) {
            case 'koleso':
                // Все выкладывать на дром платный, дром бесплатный
                Queue::model()->addByFilter(
                    array( 27 => array(1056) ),
                    array( 27 => array(1056) ),
                    array( 58 => array(1081), 61 => array(1081) )
                );      

                // На авито выкладывать по атрибуту "Выкладывать авито" == 2
                Queue::model()->addByFilter(
                    array( 27 => array(1056) ),
                    array( 27 => array(1056) ),
                    array( 60 => array(1081) ),
                    array( 111 => array("min" => 2, "max" => 2) ),
                    array( 111 => array("min" => 2, "max" => 2) )
                );
                break;
            
            case 'shikon':
                Queue::model()->addByFilter(
                    array(  ),
                    array(  ),
                    array( 61 => array(1059) )
                );  
                break;
        }
        echo json_encode(array(
            "result" => "success",
            "action" => "updateCronCount",
            "count" => Cron::model()->count()
        ));
    }

    public function actionSetCookie($login, $drom = false){
        $text = "# Netscape HTTP Cookie File".PHP_EOL.
        "# http://curl.haxx.se/docs/http-cookies.html".PHP_EOL.
        "# This file was generated by libcurl! Edit at your own risk.".PHP_EOL.PHP_EOL;

        if( $drom ){
            $text .= ("www.farpost.ru\tFALSE\t/\tFALSE\t0\tPHPSESSID\t".$_POST["PHPSESSID"].PHP_EOL.
            "#HttpOnly_.farpost.ru\tTRUE\t/\tFALSE\t1606672001\tboobs\t".$_POST["boobs"].PHP_EOL.
            "#HttpOnly_.farpost.ru\tTRUE\t/\tFALSE\t1606672001\tpony\t".$_POST["pony"].PHP_EOL.
            ".farpost.ru\tTRUE\t/\tFALSE\t1606672001\tlogin\t".$_POST["login"].PHP_EOL.
            "www.farpost.ru\tFALSE\t/\tFALSE\t1606672001\tring\t".$_POST["ring"].PHP_EOL.
            "#HttpOnly_.drom.ru\tTRUE\t/\tFALSE\t1606672001\tboobs\t".$_POST["boobs"].PHP_EOL.
            "#HttpOnly_.drom.ru\tTRUE\t/\tFALSE\t1606672001\tpony\t".$_POST["pony"].PHP_EOL.
            ".drom.ru\tTRUE\t/\tFALSE\t1606672001\tlogin\t".$_POST["login"].PHP_EOL.
            "baza.drom.ru\tFALSE\t/\tFALSE\t1606672001\tring\t".$_POST["ring"].PHP_EOL);
        }else{
            foreach ($_POST as $name => $value) {
                $text .= ("#HttpOnly_.avito.ru\tTRUE\t/\tTRUE\t1606672001\t$name\t$value".PHP_EOL);
            }
        }

        file_put_contents('protected/extensions/cookies/'.md5($login).'.txt', $text);

        if( !$drom )
            $this->setParam( "AVITO", "AUTH", "on" );

        echo "Авторизация сервера прошла успешно.";
    }

    public function actionCheckAuth(){
        // $account = $this->getAvitoAccount("tomskdiski@yandex.ru");
        // $avito = new Avito( $account->proxy  );

        // if( $account->proxy != NULL ){
        //     $avito->setUser($account->login, $account->password);
            
        //     if( $avito->isAuth() ){
        //         $this->setParam( "AVITO", "AUTH", "on" );
        //     }else{
        //         $this->setParam( "AVITO", "AUTH", "off" );
        //     }
        // }else{
        //     $this->setParam( "AVITO", "AUTH", "off" );
        // }
    }
// Выкладка -------------------------------------------------------------- Выкладка

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
                ),
                "not_contain" => 117
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
                    "IMAGE" => substr($this->getImages(1,NULL,NULL,$item)[0],1)
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
    
        $users = $this->getDromAccount();

        foreach ($users as $user) {
            $drom = new Drom((isset($user->proxy)) ? $user->proxy : NULL);
            $drom->setUser($user->login,$user->password);
            $drom->upAdverts();
        }

        Log::debug("Кончало автоподнятия дром");
    }

    public function actionDromParseAll(){
        ini_set('memory_limit', '-1');

        $users = $this->getDromAccount();
        $codes = array();
        $codes_to_sql = array();
        $adverts_to_delete = array();
        $isset_adverts = array();

        foreach ($users as $user) {
            if( $user->login == "Shikon" ) continue;
            if($user->id !== NULL){
                $drom = new Drom();
                $links = $drom->parseAllItems("http://baza.drom.ru/user/".$user->login."/", $user->id, false);

                foreach ($links as &$link)
                    $link = Drom::getCodeFromURL($link);

                $codes = array_merge($codes, $links);
            }
        }

        echo "Получено объявлений: ".count($codes)."<br>";

        foreach ($codes as $code)
            array_push($codes_to_sql, "'".$code."'");

        if( count($codes_to_sql) ){
            $isset_adverts = Controller::getIds(Advert::model()->actual()->with("good_filter")->findAll("url IN (".implode(",", $codes_to_sql).") AND good_filter.archive = 0"), "url");
            $adverts_to_delete = array_diff($codes, $isset_adverts);
        }

        $places = Controller::getIds(Place::model()->findAll("category_id = '2047'"), "id");
        if( !count($places) ) Log::debug("Не найдена площадка");

        $all_adverts = Controller::getAssoc(Advert::model()->actual()->findAll("place_id IN (".implode(",", $places).")"), "url");

        $update = array(
            0 => array(),
            1 => array(),
        );
        foreach ($all_adverts as $i => $advert)
            array_push($update[in_array($advert->url, $codes) ? 1 : 0], $advert->id);

        foreach ($update as $active => $array)
            if( count($array) )
                Advert::model()->updateAll(array( "active" => $active ), "id IN (".implode(",", $array).")");

        echo "Активно объявлений: ".count($update[1])."<br>";
        echo "Неактивно объявлений: ".count($update[0])."<br>";

        if( count($update[0]) ){
            $task = Task::model()->find("action_id = '7'");
            if( !$task )
                $task = new Task();

            $task->data = count($update[0]);
            $task->action_id = 7;
            $task->user_id = 9;
            $task->save();
        }else{
            Task::model()->deleteAll("action_id = '7'");
        }

        // Удаление заданий "Лишнее объявление", которые появились в платформе или которые удалились из базы
        if( count($adverts_to_delete) ){
            $urls = array();
            foreach ($adverts_to_delete as &$url)
                array_push($urls, "'".$url."'");
            
            Task::model()->deleteAll("action_id = '6' AND data NOT IN (".implode(",", $urls).")");
        }else{
            Task::model()->deleteAll("action_id = '6'");
        }

        // Если есть объявления, которые присутствуют в базе какого-либо аккаунта, но нет на платформе
        if( count($adverts_to_delete) ){
            echo "Лишних объявлений: ".count($adverts_to_delete)."<br>";
            $adverts_to_delete = array_diff($adverts_to_delete, Controller::getIds(Task::model()->findAll("action_id = '6'"), "data"));
            $values = array();
            foreach ($adverts_to_delete as $key => $advert) {
                array_push($values, array("data" => $advert, "action_id" => 6, "user_id" => 9));
            }
            Controller::insertValues(Task::tableName(), $values);
        }

        Log::debug("Парсинг дрома. Всего: ".count($codes).". Активно: ".count($update[1]).". Неактивно: ".count($update[0]));
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
        return date("Y-m-d H:i:s", date_timestamp_get(date_create(substr(str_replace("T", " ", $time), 0, strpos($time, "+"))))-2*60*60);
    }

    public function actionParseCategoryNew(){

        // $bj = new BestJapan();

        // $bj->auth();
        // $bj->setBid("q224032890", 6, 1000, 100, 2000);
        // $bj->getState("p621235467");

        $yahoo = new YahooQuery();

        $yahoo->getNextPage("query=45r17+2本+5.5ミリ&f=0x4&aucmaxprice=230");
        // parse_str("query=45r17 2本 5.5ミリ&f=0x4&bids=1", $query);
        // print_r($query);
    }
// Yahoo ----------------------------------------------------------------- Yahoo

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
                $robots = "User-agent: *\nDisallow: /\nSitemap: http://tomsk.koleso.online/sitemap.xml\nUser-agent: Yandex\nDisallow: \nUser-agent: Googlebot\nDisallow: \nUser-agent: googlebot-image\nDisallow: ";
                file_put_contents("robots/".$city->value.".txt", $robots);
                $sitemap = "";
            }
            // unlink($filename);
        }
    }
// Магазин --------------------------------------------------------------- Магазин

// Планировщик ----------------------------------------------------------- Планировщик
    public function actionCronCount(){
        echo Cron::model()->count();
    }
    public function actionDoNextTask($debug = false){
        if( !$this->checkTaskAccess() && !$debug ) return true;

        while( $this->allowedTask() || $debug ){
            
            $this->setParam( "SERVICE", "TASK_TIME", time() );

            if( !$this->doTask() ){
                // sleep(10);
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

// Отсмотрщик ------------------------------------------------------------ Отсмотрщик
    public function actionParseDrom(){
        $avito = new Drom();
        $avito->setUser("beatbox787@gmail.com", "481516");
        $res = $avito->auth();
        $avito->parseMessages();
    }
// Отсмотрщик ------------------------------------------------------------ Отсмотрщик

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

        // sleep(2);

        echo json_encode(array(
            "result" => "success"
        ));
    }

    public function actionValidateTitles(){
        $model = Advert::model()->findAll("title IS NOT NULL");

        $values = array();
        foreach ($model as $key => $value){
            $title = Advert::validateTitle($value->title);
            // echo "$title<br>";
            // if( !$title ) echo "asdasd";
            array_push($values, array($value->id, 1, 1, 1, 1, 1, addslashes($title), 0));
        }

        Controller::updateRows(Advert::tableName(), $values, array("title"));
    }

    public function parseAvito($place){
        $result = $place->parseAll("https://www.avito.ru/profile/items/active");
        $links = $result["links"];
        $count_links = $result["count"];

        $delete = array();
        $count = 0;
        $new = array();
        if( count($links) ){
            foreach ($links as $code => $title) {
                if( !AvitoAdvert::model()->count("url='$code'") ){
                    $av_ad = new AvitoAdvert();
                    $av_ad->url = $code;
                    if( $model = Advert::model()->find("title='$title'") ){
                        $model->url = $code;
                        $av_ad->advert_id = $model->id;
                        $model->save();

                        Queue::model()->deleteAll("advert_id='".$model->id."' AND action_id=1");
                    }
                    $av_ad->save();

                    array_push($new, $code);
                }else
                    break;
            }
            if( count($new) )
                Log::debug("Получены новые объявления на авито ".implode(", ", $new));
        }
    }

    public function actionRibka(){
        $model = array();

        $goods = Good::model()->filter(
            array(
                "good_type_id"=>1,
                "attributes"=>array(
                    43 => array(2915)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        // $goods = $goods["items"];
        if( is_array($goods["items"]) )
            $model = array_merge($model, $goods["items"]);

        $goods = Good::model()->filter(
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
        // $goods = $goods["items"];
        if( is_array($goods["items"]) )
            $model = array_merge($model, $goods["items"]);

        $images = array();
        foreach ($model as $i => $good) {
            foreach ($this->getImages(NULL,NULL,NULL,$good) as $key => $link) {
                $link = substr($link, 1);
                $size = getimagesize($link);
                if( $size[0] == 640 && $size[1] == 115 ){
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

        // print_r($goods);

        $values = array();
        foreach ($links as $key => $item) {
            if( $advert = Advert::model()->find("good_id='".$goods[$item->title]."' AND place_id='".$places[$good_type_id]."' AND type_id='".$types[$item->type]."' AND city_id='1081'") ){
                $advert->url = $item->link;
                $advert->save();

                Queue::model()->deleteAll("advert_id='".$advert->id."' AND action_id='1'");
            }else{
                $tmp = array(
                    "good_id" => $goods[$item->title],
                    "place_id" => $places[$good_type_id],
                    "url" => $item->link,
                    "type_id" => $types[$item->type],
                    "city_id" => 1081
                );
                array_push($values, $tmp);
            }
        }
        if( count($values) ){
            print_r($values);
            $this->insertValues(Advert::tableName(), $values);
        }

        $values = array();
        foreach ($links as $key => $item) {
            if( !GoodAttribute::model()->count("good_id='".$goods[$item->title]."' AND attribute_id='".$attrs[$item->type]."' AND variant_id='1081'") ){
                $tmp = array(
                    "good_id" => $goods[$item->title],
                    "attribute_id" => $attrs[$item->type],
                    "variant_id" => 1081,
                );
                array_push($values, $tmp);
            }
        }
        if( count($values) ){
            print_r($values);
            $this->insertValues(GoodAttribute::tableName(), $values);        
        }
    }

    public function actionDelete70(){
        $places = array( 1 => 9, 2 => 10 );
        $names = array( 1 => "tire", 2 => "disc" );
        $types = array( 1 => "2129", 2 => "868" );
        $attrs = array( 1 => 61, 2 => 59);
        $good_type_id = 2;
        $drom = new Drom();

        $links = $drom->parseAllItems("http://baza.drom.ru/user/wheels70/wheel/".$names[$good_type_id]."/", "848235", false, true, true);

        $codes = array();
        foreach ($links as $key => $item) {
            $item->title = array_shift(explode(" ",substr($item->title, 1)));
            array_push($codes, $item->title);
        }

        $goods = Good::getIdbyCode($codes, array($good_type_id));

        // print_r($goods);

        $values = array();
        foreach ($links as $key => $item) {
            if( $advert = Advert::model()->find("url='".$item->link."'") ){
                // $advert->url = $item->link;
                // $advert->save();

                // Queue::model()->deleteAll("advert_id='".$advert->id."' AND action_id='1'");
            }else{
                echo "delete: ".$item->link."<br>";
                // $tmp = array(
                //     "good_id" => $goods[$item->title],
                //     "place_id" => $places[$good_type_id],
                //     "url" => $item->link,
                //     "type_id" => $types[$item->type],
                //     "city_id" => 1081
                // );
                // array_push($values, $tmp);
            }
        }
        // if( count($values) ){
        //     print_r($values);
        //     $this->insertValues(Advert::tableName(), $values);
        // }

        // $account = $this->getDromAccount("wheels70");
        // $place = new Drom( NULL );
        // $place->setUser($account->login, $account->password);
        // $place->auth();
        // $result = $place->deleteAdvert( (($place_name == "AVITO")?$advert->link:$advert->url) );

        // $values = array();
        // foreach ($links as $key => $item) {
        //     if( !GoodAttribute::model()->count("good_id='".$goods[$item->title]."' AND attribute_id='".$attrs[$item->type]."' AND variant_id='1081'") ){
        //         $tmp = array(
        //             "good_id" => $goods[$item->title],
        //             "attribute_id" => $attrs[$item->type],
        //             "variant_id" => 1081,
        //         );
        //         array_push($values, $tmp);
        //     }
        // }
        // if( count($values) ){
        //     print_r($values);
        //     $this->insertValues(GoodAttribute::tableName(), $values);        
        // }
    }
// Остальное ------------------------------------------------------------- Остальное

    public function actionGoodTest(){
        $model = Good::model()->filter(
            array(
                "good_type_id"=>1,
                "attributes"=>array(
                    27 => array(1056)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $model["items"];

        foreach ($goods as $i => $good) {
            Task::model()->testGood($good);
        }

        $model = Good::model()->filter(
            array(
                "good_type_id"=>2,
                "attributes"=>array(
                    27 => array(1056)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $model["items"];

        foreach ($goods as $i => $good) {
            Task::model()->testGood($good);
        }
    }

    public function actionGoodTestPartner(){
        $model = Good::model()->filter(
            array(
                "good_type_id"=>2,
                "attributes"=>array(
                    43 => array(2915,3003,3001,2914)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $model["items"];

        foreach ($goods as $i => $good) {
            Task::model()->testGood($good);
        }
    }

    public function actionDromReg(){
        $place = new Drom( "admin:4815162342@82.146.35.208:1212" );
        $place->setUser("5069820", "y23u2e62");
        $res = $place->auth();
        $place->registration();
    }

    public function actionDromCheck(){
        $place = new Drom( "82.146.35.208" );
        $place->setUser("5069820", "y23u2e62");
        $res = $place->auth();
        print_r( iconv('windows-1251', 'utf-8', $place->curl->request("http://baza.drom.ru/tomsk/wheel/tire/b-u-letnjaja-para-yokohama-dna-ecos-es300-215-40-17-japonija-43479729.html")) );
    }

    public function actionAnalyse(){
        $ids = Controller::getIds(AdvertWord::model()->findAll(array("group"=>"advert_id")), "advert_id");

        $ids1 = Controller::getIds(Advert::model()->findAll(array("group"=>"id")), "id");

        $diff = array_diff($ids, $ids1);
        print_r($diff);
        if( $diff )
        AdvertWord::model()->deleteAll("advert_id IN (".implode(",", $diff).")");
    }

    public function actionUpdatePhoto(){
        $goods = Good::model()->filter(
            array(
                "good_type_id"=>3
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $goods["items"];

        foreach ($goods as $key => $good) {
            Image::updateImages($good);
        }
    }

    public function actionDeletePhoto(){
        // $model = Good::model()->with("fields")->findAll("archive=1 AND attribute_id=3 AND LENGTH(varchar_value) = 5");
        // foreach ($model as $key => $good) {
        //     $code = $good->fields_assoc[3]->value;
        //     $goodType = GoodType::getCode($good->good_type_id);
        //     $cache = Yii::app()->params["cacheFolder"]."/".$goodType."/".$code;
        //     $imgs = Yii::app()->params["imageFolder"]."/".$goodType."/".$code;
        //     $this->removeDirectory($cache);
        //     $this->removeDirectory($imgs);
        //     $images = Image::model()->with("caps","cache")->findAll("good_id=".$good->id);
        //     foreach ($images as $key => $image) {
        //         $image->delete();
        //     }
            
        // } 

        $good_ids = Controller::getIds(GoodFilter::model()->findAll("good_type_id=1"), "id");
        $ids = Controller::getIds(Image::model()->findAll("good_id NOT IN (".implode(",", $good_ids).")"), "id");
        print_r($ids); 
    }

    public function actionDeleteImages(){
        $good_type_code = "tires";
        $good_type_id = 1;

        $dir = Controller::getFolders(Yii::app()->params["imageFolder"]."/".$good_type_code);
        $dir_sql = array();
        foreach ($dir as $i => $item) {
            array_push($dir_sql, "'".$item."'");
        }
        $model = Controller::getIds( GoodAttributeFilter::model()->with("good_filter")->findAll("good_filter.id > 0 AND good_filter.good_type_id=".$good_type_id." AND t.attribute_id=3 AND t.varchar_value IN (".implode(",", $dir_sql).")"), "varchar_value");
        print_r( count($dir) );
        echo "<br><br>";
        print_r( count($model) );
        echo "<br><br>";
        $diff = array_diff($dir, $model);
        print_r( count($diff) );
        echo "<br><br>";
        print_r($diff);
        // $this->removeDirectories($diff, Yii::app()->params["imageFolder"]."/".$good_type_code);

        $images = Controller::getFolders(Yii::app()->params["imageFolder"]."/".$good_type_code);
        $cache = Controller::getFolders(Yii::app()->params["cacheFolder"]."/".$good_type_code);
        $diff_cache = array_diff($cache, $images);
        echo "<br><br>";
        print_r($diff_cache);

        // $this->removeDirectories($diff_cache, Yii::app()->params["cacheFolder"]."/".$good_type_code);
    }

    public function actionOlimp(){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $curl = new Curl();

        $prev = file_get_contents("olimp.txt");

        $html = str_get_html($curl->request("http://konkurs.1c.ru/"));

        if( is_object($html) && $html->find(".d-lst-articles",0) ){
            $lastUrl = $html->find(".d-lst-articles li dl dd p.h3 a",0)->getAttribute("href");
            $lastText = $html->find(".d-lst-articles li dl dd p.h3 a",0)->plaintext;
            if( $prev != $lastUrl ){
                require_once("phpmail.php");

                $subject = "Новая новость";
                $message = "<a target='_blank' href='http://konkurs.1c.ru".$lastUrl."'>".$lastText."</a>";
                $email_admin = "mike@kitaev.pro,drive-online@yandex.ru";
                    
                if(send_mime_mail("Конкурс 1С","konkurs@1c.ru",$name,$email_admin,'UTF-8','UTF-8',$subject,$message,true)){    
                    file_put_contents("olimp.txt", $lastUrl);
                }
            }else{
                Log::debug("Нет новых новостей");
            }
        }
    }

    public function actionParseTitles(){
        $goods = Good::model()->filter(
            array(
                "good_type_id"=>2,
                "attributes"=>array(
                    43 => array(2912)
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $goods["items"];

        $drom = new Drom();
        foreach ($goods as $key => $good) {
            if( $advert = Advert::model()->find("good_id=".$good->id." AND city_id=1069 AND place_id=12") ){
                if( !$advert->title ){
                    $title = $drom->parseTitle($good->fields_assoc[106]->value);
                    if( $title ){
                        Interpreter::isNotIsset($title, $advert);
                    }else{
                        echo "Не удалось спарсить товар с id ".$good->id." по ссылке ".$good->fields_assoc[106]->value;
                    }
                }
                // if( $advert->title ){
                    
                //     // Interpreter::isNotIsset($advert->title, $advert);


                //     if( !$advert->ready ){
                //         echo $advert->title."<br>";
                //         foreach ($advert->findSimilar() as $i => $title)
                //             echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$title."<br>";
                //     }
                //         // echo $advert->title."<br>";
                // }
            }else{
                // echo "Не найдено объявление у товара с id ".$good->id;
            }
        }
    }
}
