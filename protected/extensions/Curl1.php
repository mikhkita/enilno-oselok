<?

Class Curl1 {

    public $cookie = NULL;
    public $proxy_login = NULL;
    public $proxy_ip = NULL;
    public $ip = NULL;
    public $cookieChanged = false;

    function __construct($type = NULL) {
        if($type !== NULL) {
            if(strpos($type, "@")) {
                $this->proxySet($type);
                $this->checkProxy();
            } else $this->ip = $type;
        }
        $this->cookie = md5(rand().time());
        $this->removeCookies();
        
    }

    function __destruct() {
        if(!$this->cookieChanged)
            $this->removeCookies();
    }

    public function request($url = NULL,$post = NULL){  
        $header =  array(
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Content-Length' => '356',
            'Content-Type' => 'multipart/form-data; boundary=----WebKitFormBoundaryokjlX76shZLnk7xy',
            'Host' => 'www.avito.ru',
            'Origin' => 'https://www.avito.ru',
            'Pragma' => 'no-cache',
            'Referer' => 'https://www.avito.ru/profile/login?next=%2Fprofile',
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        );       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if( $post !== NULL ){
            // if($this->ip)
                // $post = array("json" => json_encode($post));

            $delimiter = "\r\n----WebKitFormBoundaryokjlX76shZLnk7xy";

            foreach($post as $name=>$val){
                $ret .= "--" . $delimiter. "\r\n";
                $ret .= "Content-Disposition: form-data; name=\"" . $name . "\"";
                $ret .= "\r\n\r\n".$val."\r\n";
            }
            $ret .= "--" . $delimiter . "--";

            $header['Content-Type'] = 'multipart/form-data; boundary=' . $delimiter;
            // $header['Content-Length'] = strlen($ret);

            var_dump(strlen($ret));

            var_dump($ret);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $ret);
        }

        if(strpos($url, "avito") !== false) {
            // $header['Referer'] = 'https://www.avito.ru/profile/login?next=%2Fprofile';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           
        } elseif(strpos($url, "drom") !== false) {
            $header['Accept-Encoding'] = 'gzip, deflate, sdch';
            $header['Host'] = 'baza.drom.ru';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);   
        }
        if($this->proxy_login && $this->proxy_ip) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_login); 
        }

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
        $result = curl_exec($ch);
        curl_close( $ch );
        return $result;
    }

    public function proxySet($proxy) {
        $proxy = explode("@", $proxy);
        $this->proxy_login = $proxy[0];
        $this->proxy_ip = $proxy[1];
    }

    public function checkProxy(){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $i = 0;
        do {
            $i++;
            $result = $this->request("http://www.seogadget.ru/location");
            $html = str_get_html($result);
        } while ( !is_object($html) && $i < 5 );

        if( !is_object($html) ){
            Log::debug("Прокси ".$temp_ip[0]." упал");
            return false;
        }
        $ip = $html->find('.url',0)->value;

        $temp_ip = explode(":", $this->proxy_ip);
        if( $ip == $temp_ip[0]) {
            Log::debug("Прокси ".$ip." успешно установлен");
            return true;
        }else{
            Log::debug("Прокси ".$temp_ip[0]." не был установлен. Выдало ".$ip);
            return false;
        }
    }

    public function proxyUnset() {
        $this->$proxy_login = NULL;
        $this->$proxy_ip = NULL;
    }

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