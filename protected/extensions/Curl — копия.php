<?

Class Curl {

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
            'Accept-Encoding' => 'gzip, deflate, sdch, br',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Connection' => 'keep-alive',
            'Host' => 'www.avito.ru',
            'Upgrade-Insecure-Requests' => 1,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36'
        );       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $postLength = (is_array($post)) ? strlen(http_build_query($post)) : NULL;

        if(strpos($url, "avito") !== false) {
            if( $post == "image" ){
                $header = array(
                    "Accept" => "image/webp,image/*,*/*;q=0.8",
                    "Accept-Encoding" => "gzip, deflate, sdch, br",
                    "Accept-Language" => "ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4",
                    "Connection" => "keep-alive",
                    "Host" => "www.avito.ru",
                    "Referer" => "https://www.avito.ru/additem/confirm",
                    "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
                );
            }else{
                $header = array(
                    "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                    "Accept-Encoding" => "gzip, deflate, br",
                    "Accept-Language" => "ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4",
                    "Cache-Control" => "no-cache",
                    "Connection" => "keep-alive",
                    "Content-Length" => "60",
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Host" => "www.avito.ru",
                    "Origin" => "https://www.avito.ru",
                    "Pragma" => "no-cache",
                    "Referer" => "https://www.avito.ru/profile/login?next=/Fprofile",
                    "Upgrade-Insecure-Requests" => "1",
                    "User-Agent" => "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36",
                );
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           
        } elseif(strpos($url, "drom") !== false) {
            if( is_array($post) ){
                // print_r($postLength);
                $header = array(
                    ":authority:my.drom.ru",
                    ":method:POST",
                    ":path:/sign?return=https%3A%2F%2Fbaza.drom.ru%2Ftomsk%2F&login_by_password=1",
                    ":scheme:https",
                    "accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                    "accept-encoding:gzip, deflate, br",
                    "accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,sv;q=0.6",
                    "cache-control:no-cache",
                    "content-length:".$postLength,
                    "content-type:application/x-www-form-urlencoded",
                    "origin:https://my.drom.ru",
                    "pragma:no-cache",
                    "referer:https://my.drom.ru/sign?return=https%3A%2F%2Fbaza.drom.ru%2Ftomsk%2F",
                    "upgrade-insecure-requests:1",
                    "user-agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
                );
            }else{
                if( substr($url, 0, 1) == "h" ){
                    // $header = array(
                    //     ":authority:baza.drom.ru",
                    //     ":method:GET",
                    //     ":path:/tomsk/",
                    //     ":scheme:https",
                    //     "accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                    //     "accept-encoding:gzip, deflate, br",
                    //     "accept-language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,sv;q=0.6",
                    //     "cache-control:no-cache",
                    //     "pragma:no-cache",
                    //     "upgrade-insecure-requests:1",
                    //     "user-agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
                    // );
                }else{
                    $header = array(
                        "Accept-Encoding:gzip, deflate, br",
                        "Accept-Language:ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,sv;q=0.6",
                        "Cache-Control:no-cache",
                        "Connection:Upgrade",
                        "Host:baza.drom.ru",
                        "Origin:https://baza.drom.ru",
                        "Pragma:no-cache",
                        "Sec-WebSocket-Extensions:permessage-deflate; client_max_window_bits",
                        "Sec-WebSocket-Key:4ZzKeUFzP95PDwA/sDYp5w==",
                        "Sec-WebSocket-Version:13",
                        "Upgrade:websocket",
                        "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
                    );
                }
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        }
        if($this->proxy_login && $this->proxy_ip) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_login); 
        }

        // if($this->ip) {
        //     curl_setopt($ch, CURLOPT_URL, "http://".$this->ip."/redirect.php");
        //     if($post) {
        //         $post['cookie'] = $this->cookie;
        //     } else $post = array("cookie" => $this->cookie);
        //     if($url) $post['url'] = $url;
        // } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            if (!is_dir(dirname(__FILE__).'/cookies')) mkdir(dirname(__FILE__).'/cookies',0777, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
        // }
        if( is_array($post) ){
            // if($this->ip)
                // $post = array("json" => json_encode($post));
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
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
        print_r($this->cookie);
        echo " ";
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