<?php

class IntegrateController extends Controller
{
    private $params = array(
        "TIRE" => array(
            "GOOD_TYPE_ID" => 1,
            "TITLE_CODE" => 100,
            "HEADER" => "HEADER_T",
            "FOOTER" => "FOOTER_T",
            "JOIN" => array(7,8,9),
            "ADVERT_TITLE_CODE" => 13,
            "NAME_ROD" => "Шины",
            "NAME_ROD_MN" => "Шин",
        ),
        "DISC" => array(
            "GOOD_TYPE_ID" => 2,
            "TITLE_CODE" => 86,
            "TEXT_CODE" => 87,
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
        
        $photodoska->deleteAdverts(trim($this->getParam("PHOTODOSKA","MAIN_ADVERT")));

        $this->generatePdQueue($this->getGroups("TIRE"),"TIRE");

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

        // $i = 1;
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

    public function getGroups($good_type_code){
        $curParams = $this->params[$good_type_code];

        $model = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($curParams["GOOD_TYPE_ID"])->goods;
        $result = array();

        foreach ($model as $item) {
            $tog = true;

            foreach ($curParams["JOIN"] as $field)
                if( !(isset($item->fields_assoc[intval($field)]) && $item->fields_assoc[intval($field)]->value != 0) ) $tog = false;

            if( $tog && ( (isset($item->fields_assoc[27]) && intval($item->fields_assoc[27]->variant_id) == 1056) || (isset($item->fields_assoc[46]) && intval($item->fields_assoc[46]->value) == 1) ) ){
                $title = Interpreter::generate($curParams["ADVERT_TITLE_CODE"],$item);

                if( !isset($result[$title]) ) $result[$title] = array();
                array_push($result[$title], $item);
            }
        }

        $header = $this->replaceToBr($this->getParam("PHOTODOSKA",$curParams["HEADER"]));
        $footer = $this->replaceToBr($this->getParam("PHOTODOSKA",$curParams["FOOTER"]));

        foreach ($result as $key => $group) {
            $price = $this->findPrice($group);
            if( $price != false ){
                $result[$key] = array(
                    "TEXT" => $header."<br>".$this->generateList($group,$curParams["TITLE_CODE"]).$footer,
                    "TITLE" => $key,
                    "PRICE" => $price,
                    "IMAGE" => substr($this->findImage($group),1)
                );
            }else{
                unset($result[$key]);
            }
        }

        return $result;
    }

    public function getItems($good_type_code){
        $curParams = $this->params[$good_type_code];

        $model = GoodType::model()->with('goods.fields.variant','goods.fields.attribute')->findByPk($curParams["GOOD_TYPE_ID"])->goods;
        $result = array();

        if( $model )
        foreach ($model as $key => $item) {
            if( (isset($item->fields_assoc[27]) && intval($item->fields_assoc[27]->variant_id) == 1056) || (isset($item->fields_assoc[46]) && intval($item->fields_assoc[46]->value) == 1) ){
                array_push($result, array(
                        "TEXT" => $this->replaceToBr(Interpreter::generate($curParams["TEXT_CODE"],$item)),
                        "TITLE" => Interpreter::generate($curParams["TITLE_CODE"],$item),
                        "PRICE" => $item->fields_assoc[20]->value,
                        "IMAGE" => substr($this->getImages($item)[0],1)
                    )
                );
            }
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
            $price = intval($item->fields_assoc[20]->value);
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
        

        $users = $this->getParam("DROM","USERS");

        $users = explode("\n", $users);

        foreach ($users as $value) {
            $user = explode(" ", $value);
            $drom->setUser($user[0],$user[1]);
            $drom->upAdverts();
        }

        Log::debug("Кончало автоподнятия дром");
    }

// Дром ------------------------------------------------------------------ Дром

// Yahoo ----------------------------------------------------------------- Yahoo
    public function actionYahooBids(){
        return false;
        $model = YahooCategory::model()->findAll(array("order"=>"id ASC"));
        foreach ($model as $item)
            $this->parseCategory($item,true);

    }

    public function actionYahooAll(){
        return false;
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

        $page = $yahoo->getNextPage($category->code,intval($category->max_price*$this->courses["USD"]),( ($bids)?"a":"d" ));

        while( $page && $tog ){
            $sellers = array();

            foreach ($page["items"] as $key => $item)
                if( !in_array($item->Seller->Id, $sellers) ) array_push($sellers, $item->Seller->Id);

            $this->updateSellers($sellers);

            $sellers_id = $this->getSellersID($sellers);

            foreach ($page["items"] as &$item){
                $item->Seller->Id = $sellers_id[$item->Seller->Id];
            }

            $this->updateLots($page["items"],$category->id);

            Log::debug($category->name." Страница: ".$yahoo->getLastPage());

            if( $bids ){
                $tog = ( intval(array_pop($page["items"])->Bids) > 0 )?true:false;
            }else{
                $tog = ( intval(array_pop($page["items"])->Bids) == 0 )?true:false;
            }

            $page = $yahoo->getNextPage($category->code,intval($category->max_price*$this->courses["USD"]),( ($bids)?"a":"d" ));
        }
        
        Log::debug($category->name." Парсинг завершен. Количество полученных страниц: ".$yahoo->getLastPage()-1);
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

    public function actionAdminIndex(){
        
    }
}
