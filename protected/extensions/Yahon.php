<?

Class Yahon {

    private $cookies = NULL;
    private $curl = NULL;
    
    function __construct() {
        $this->curl = new Curl();
    }

    public function auth() {
        $params = array(
            'set_login'=>'svc1',
            'set_pass'=>'kb5e1law',
            'set_from'=>'',
            'to_remain_here'=>'Y'
        );

        $content = $this->curl->request("https://www.yahon.ru/auth",$params);

        // preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $content, $result);
        // $this->cookies = implode(';', $result[1]);
        Log::debug("Yahon.php 30 строка. После авторизации cookies = ".$this->cookies); file_put_contents(Yii::app()->basePath."/logs/sniper/auth.txt", $content);
    }
    public function isAuth() {
        return ($this->cookies !== NULL);
    }

    public function setBid($lot_number,$cur_price,$step,$max_price) {
        $cur_price = intval($cur_price);
        $step = intval($step);
        $max_price = intval($max_price);

        // if($this->cookies === NULL) return "Авторизация не пройдена";
        $bid = (($cur_price+$step*2)<=$max_price) ? ($cur_price+$step) : ( (($cur_price+$step)<=$max_price) ? $max_price : 0);
        Log::debug("Yahon.php 44 строка. bid = ".$bid);
        if($bid===0) {
            return array('result' => 5);
        }
    
        $content = $this->curl->request("https://www.yahon.ru/yahoo/bid_preview",array(
            'user_rate'=>$bid,
            'quantity'=>'1',
            'lot_no'=>$lot_number
        ));

        file_put_contents(Yii::app()->basePath."/logs/sniper/bid_preview.txt", $content);
        preg_match_all('/.(input [^>]*)/m', $content, $result);
        $fields = array(
          'comments' => '',
          'deliveryType' =>'2',
          'nocash' => '0.21847357973456382'
        );
    
        foreach ($result[1] as $key => $value) {
            if($key!=0) {
                preg_match_all('/.*name="([^"]*)".*/',$value,$name);
                preg_match_all('/.*value="([^"]*)".*/',$value,$val);
                $fields[$name[1][0]] = $val[1][0];
            }
        }
        Log::debug("Yahon.php 77 строка. signature = ".$fields["signature"]." token = ".$fields["token"]);
    
        if(!isset($fields["signature"])) {
            return array('result' => 4);
        }
        // $fields['user_rate'] = 500;

        $content = $this->curl->request("https://www.yahon.ru/yahoo/bid_place",$fields);
    
        file_put_contents(Yii::app()->basePath."/logs/sniper/bid_place.txt", $content);
    
        preg_match_all('/.*signature=([^"]*)".*/',$content,$sign);
        preg_match_all('/.*token=([^&]*)&.*/',$content,$token);
    
        $params = array();
        $fields["signature"] = $sign[1][0];
        $fields["token"] = $token[1][0];
        foreach ($fields as $key => $value) {
            $params[] = $key."=".$value;
        }
        Log::debug("Yahon.php 99 строка. params = ".implode("&", $params));

        $content = $this->curl->request("https://www.yahon.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
    
        // curl_setopt($ch, CURLOPT_COOKIE, $this->cookies.";lotViewMode=1");
        // curl_setopt($ch, CURLOPT_URL,"https://www.yahon.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
        // curl_setopt($ch, CURLOPT_POST, 0);
    
        file_put_contents(Yii::app()->basePath."/logs/sniper/rate.txt", $content);
        print_r($content);
        if(mb_stripos($content,"Ставка принята",0,"UTF-8")) {
            echo "Ставка принята";
            return array('price' => $bid, 'result' => 2);
        } else {
            echo "Ставка не принята";
            return array('price' => $bid, 'result' => 0);
        }
    }

    public function getState($lot_number){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $html = str_get_html( $this->curl->request("http://www.yahon.ru/yahoo/lot_".$lot_number.".html") );

        $str = $html->find('.table',0)->plaintext;
        $is_end = (trim($html->find('.alert-danger span', 0)->plaintext) == "Торги завершены.");
        if( mb_substr($str, mb_stripos($str, "Лидер:", NULL, "UTF-8")+6, 2,"UTF-8") == "Вы" ){
            return ($is_end)?6:2;
        }else{
            return ($is_end)?3:0;
        }
    }

}

?>