<?

Class Curl {

    public $cookie = NULL;
    public $proxy_login = NULL;
    public $proxy_ip = NULL;

    function __construct($proxy = NULL) {
        $this->cookie = md5(rand().time());
        $this->removeCookies();
        if($proxy !== NULL) {
            $this->proxySet($proxy);
        }
    }

    public function request($url,$post = NULL){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_AUTOREFERER,true);
        if($this->proxy_login && $this->proxy_ip) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_login); 
        }
        if (!is_dir(dirname(__FILE__).'/cookies')) mkdir(dirname(__FILE__).'/cookies',0777, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
        if( $post != NULL ){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($ch);
        curl_close( $ch );
        return $result;
    }

    public function proxySet($proxy) {
        $proxy = explode("@", $proxy);
        $ch = curl_init();
        $url = "https://2ip.ru/";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_PROXY, $proxy[1]);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy[0]); 
        $html = str_get_html(curl_exec( $ch ));
        $ip = $html->find('#d_clip_button',0)->plaintext;
        $temp_ip = explode(":", $proxy[1]);
        if( $ip == $temp_ip[0]) {
            $this->$proxy_login = $proxy[0];
            $this->$proxy_ip = $proxy[1];
        }
    }
    public function proxyUnset() {
        $this->$proxy_login = NULL;
        $this->$proxy_ip = NULL;
    }
    public function removeCookies(){
        if( file_exists(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt') )
            unlink(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
    }
}

?>