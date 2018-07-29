<?

Class BestJapan {

    private $cookies = NULL;
    private $curl = NULL;
    
    function __construct() {
        $this->curl = new Curl();
    }

    public function auth() {
        $params = array(
            "set_login" => "uncklezilla@mail.ru",
            "set_pass" => "Some2Ququ8",
            "set_from" => "",
            "x" => rand(1, 20),
            "y" => rand(1, 20),
            "to_remain_here" => "Y"
        );

        $content = $this->curl->request("https://www.bestjapan.ru/auth", $params);

        // preg_match_all("/^Set-Cookie:\s*([^;]*)/mi", $content, $result);
        // $this->cookies = implode(";", $result[1]);
        Log::debug("BestJapan.php 30 строка. После авторизации cookies = ".$this->cookies); file_put_contents(Yii::app()->basePath."/logs/sniper/auth.txt", $content);
    }
    public function isAuth() {
        return ($this->cookies !== NULL);
    }

    public function setBid($lot_number, $delivery_type, $cur_price, $step, $max_price) {
        $cur_price = intval($cur_price);
        $step = intval($step);
        $max_price = intval($max_price);

        // if($this->cookies === NULL) return "Авторизация не пройдена";

        // $bid = (($cur_price+$step*2)<=$max_price) ? ($cur_price+$step) : ( (($cur_price+$step)<=$max_price) ? $max_price : 0);
        $bid = $max_price;
        
        Log::debug("BestJapan.php 44 строка. bid = " . $bid);
        if( $bid === 0 ) {
            return array("result" => 5);
        }
    
        $content = $this->curl->request("https://www.bestjapan.ru/auction/bid_preview", array(
            "user_rate" => $bid,
            "quantity" => "1",
            "lot_no" => $lot_number
        ));

        // print_r($content);
        // die();

        file_put_contents(Yii::app()->basePath."/logs/sniper/bid_preview.txt", $content);
        preg_match_all("/.(input [^>]*)/m", $content, $result);
        $fields = array(
            "comments" => "",
            "deliveryType" => $delivery_type,
            "offer" => "Y",
            "rest" => "Y",
            "nocash" => "0.42262561947756416",
            "make" => "Сделать ставку"
        );
    
        foreach ($result[1] as $key => $value) {
            if($key!=0) {
                preg_match_all('/.*name="([^"]*)".*/',$value,$name);
                preg_match_all('/.*value="([^"]*)".*/',$value,$val);
                $fields[$name[1][0]] = $val[1][0];
            }
        }
        // unset($fields["code"]);
        Log::debug("BestJapan.php 77 строка. signature = ".$fields["signature"]." token = ".$fields["token"]);
    
        if(!isset($fields["signature"])) {
            return array("result" => 4);
        }

        // print_r($fields);
        // die();
        $content = $this->curl->request("https://www.bestjapan.ru/auction/bid_place", $fields);
    
        file_put_contents(Yii::app()->basePath."/logs/sniper/bid_place.txt", $content);
    
        preg_match_all('/.*signature=([^"]*)".*/',$content,$sign);
        preg_match_all('/.*token=([^&]*)&.*/',$content,$token);
    
        $params = array();
        $fields["signature"] = $sign[1][0];
        $fields["token"] = $token[1][0];
        foreach ($fields as $key => $value) {
            $params[] = $key."=".$value;
        }
        Log::debug("BestJapan.php 99 строка. params = ".implode("&", $params));

        // print_r($params);
        $content = $this->curl->request("https://www.bestjapan.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
    
        // curl_setopt($ch, CURLOPT_COOKIE, $this->cookies.";lotViewMode=1");
        // curl_setopt($ch, CURLOPT_URL,"https://www.yahon.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
        // curl_setopt($ch, CURLOPT_POST, 0);
    
        file_put_contents(Yii::app()->basePath."/logs/sniper/rate.txt", $content);
        if(mb_stripos($content,"Ставка принята",0,"UTF-8")) {
            Log::debug("Ставка принята", false, true);
            return array("price" => $bid, "result" => 2);
        } else {
            Log::debug("Ставка не принята", false, true);
            return array("price" => $bid, "result" => 0);
        }
    }

    public function getState($lot_number){
        include_once Yii::app()->basePath."/extensions/simple_html_dom.php";

        $result = $this->curl->request("http://www.bestjapan.ru/auction/item_".$lot_number.".html");
        $html = str_get_html( $result );

        $str = $html->find(".itemHead",0)->plaintext;
        $is_end = ( mb_stripos( trim($html->find(".alert-danger span", 0)->plaintext), "Торги завершены") !== false );

        if( mb_substr($str, mb_stripos($str, "Лидер:", NULL, "UTF-8")+6, 4,"UTF-8") == "Ваша" ){
            return ($is_end)?6:2;
        }else{
            return ($is_end)?3:0;
        }
    }


}

?>