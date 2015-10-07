<?

Class Yahon {

    private $cookies = NULL;
    
    function __construct() {

    }

    public function auth() {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/auth");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'set_login'=>'svc1',
            'set_pass'=>'kb5e1law',
        // 'set_login'=>'p_e_a_c_e@mail.ru',
         // 'set_pass'=>'261192',
            'set_from'=>'',
            'to_remain_here'=>'Y'
        ));
        $content = curl_exec($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $content, $result);
        $this->cookies = implode(';', $result[1]);
        Log::debug("Yahon.php 30 строка. После авторизации cookies = ".$this->cookies); file_put_contents(Yii::app()->basePath."/logs/sniper/auth.txt", $content);
        curl_close($ch);
    }
    public function isAuth() {
        return ($this->cookies !== NULL);
    }

    public function setBid($lot_number,$cur_price,$step,$max_price) {
        $cur_price = intval($cur_price);
        $step = intval($step);
        $max_price = intval($max_price);

        if($this->cookies === NULL) return "Авторизация не пройдена";
        $bid = (($cur_price+$step*2)<=$max_price) ? ($cur_price+$step) : ( (($cur_price+$step)<=$max_price) ? $max_price : 0);
        Log::debug("Yahon.php 44 строка. bid = ".$bid);
        if($bid===0) {
            return array('result' => 5);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/yahoo/bid_preview");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
          'user_rate'=>$bid,
          'quantity'=>'1',
          'lot_no'=>$lot_number
        ));
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);
    
        $content = curl_exec($ch);
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
    
        curl_setopt($ch, CURLOPT_URL, "https://www.yahon.ru/yahoo/bid_place");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
    
        $content = curl_exec($ch);
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
    
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookies.";lotViewMode=1");
        curl_setopt($ch, CURLOPT_URL,"https://www.yahon.ru/modules/yahoo_auction/data_request/rate.php?".implode("&", $params));
        curl_setopt($ch, CURLOPT_POST, 0);
    
        $content = curl_exec($ch);
        file_put_contents(Yii::app()->basePath."/logs/sniper/rate.txt", $content);
        curl_close($ch);
        if(mb_stripos($content,"Ставка принята",0,"UTF-8")) {
            return array('price' => $bid, 'result' => 2);
        } else {
            return array('price' => $bid, 'result' => 0);
        }
    }

}

?>