<?

Class Curl {

    public $cookie;

    function __construct() {
        $this->cookie = md5(rand().time());

        $this->removeCookies();
    }

    public function request($url,$post = NULL){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // curl_setopt($ch, CURLOPT_MAXFILESIZE, 1024*1024*10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
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

    public function removeCookies(){
        if( file_exists(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt') )
            unlink(dirname(__FILE__).'/cookies/'.$this->cookie.'.txt');
    }
}

?>