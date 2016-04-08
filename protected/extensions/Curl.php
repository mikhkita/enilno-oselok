<?

Class Curl {

    public $cookie = NULL;
    public $ip = NULL;
    public $cookieChanged = false;

    function __construct($ip = NULL) {
        if($ip !== NULL) $this->ip = $ip;
        $this->cookie = md5(rand().time());
        $this->removeCookies();
        
    }

    function __destruct() {
        if(!$this->cookieChanged)
            $this->removeCookies();
    }

    public function request($url = NULL,$post = NULL){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        if(strpos($url, "avito") !== false) {
            curl_setopt($ch, CURLOPT_REFERER,"https://www.avito.ru/");
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER,1);
        // if($this->proxy_login && $this->proxy_ip) {
        //     curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
        //     curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_login); 
        // }

        if($this->ip) {
            curl_setopt($ch, CURLOPT_URL, "http://".$this->ip."/redirect.php");
            if($post) {
                $post['cookie'] = $this->cookie;
            } else $post = array("cookie" => $this->cookie);
            if($url) $post['url'] = $url;
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            if (!is_dir(dirname(__FILE__).'/cookies')) mkdir(dirname(__FILE__).'/cookies',0777, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
        }
        if( $post !== NULL ){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($ch);
        curl_close( $ch );
        return $result;
    }

    // public function proxySet($proxy) {
    //     $proxy = explode("@", $proxy);
    //     $this->proxy_login = $proxy[0];
    //     $this->proxy_ip = $proxy[1];
    // }

    // public function checkProxy(){
    //     include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

    //     $i = 0;
    //     do {
    //         $i++;
    //         $result = $this->request("http://www.seogadget.ru/location");

    //         $html = str_get_html($result);
    //     } while ( !is_object($html) && $i < 5 );

    //     if( !is_object($html) ){
    //         Log::debug("Прокси ".$temp_ip[0]." упал");
    //         return false;
    //     }
    //     $ip = $html->find('.url',0)->value;

    //     $temp_ip = explode(":", $this->proxy_ip);
    //     print_r($ip." ".$temp_ip[0]);
    //     if( $ip == $temp_ip[0]) {
    //         Log::debug("Прокси ".$ip." успешно установлен");
    //         return true;
    //     }else{
    //         Log::debug("Прокси ".$temp_ip[0]." не был установлен. Выдало ".$ip);
    //         return false;
    //     }
    // }

    // public function proxyUnset() {
    //     $this->$proxy_login = NULL;
    //     $this->$proxy_ip = NULL;
    // }

    public function changeCookies($login){
        $this->removeCookies();
        $this->cookie = md5($login);
        $this->cookieChanged = true;
    }

    public function removeCookies(){
        if($this->ip) {
            $this->request(NULL,array("remove" => 1));
        } else {
            if( file_exists(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt') )
                unlink(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
        }
    }
}

?>