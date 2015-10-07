<?

Class Drom {
    private $login = "";
    private $password = "";
    public $curl;
    
    function __construct() {
        $this->curl = new Curl();
    }

    public function setUser($login,$password){
        $this->login = $login;
        $this->password = $password;
    }

    public function auth($redirect = ""){
        $this->curl->removeCookies();

        $params = array(
            'sign' => $this->login,
            'password' => $this->password
        );

        return iconv('windows-1251', 'utf-8', $this->curl->request("https://www.farpost.ru/sign?mode=openid&return=".urlencode($redirect)."&login_by_password=1",$params));
    }

    public function upAdverts(){
        $links = $this->parseExpired();
        $upLinks = array();
        Log::debug("Пользователь ".$this->login." ".count($links)." неактивных объявлений");

        foreach ($links as $key => $value) {
            $index = floor($key/50);
            $upLinks[$index] = $upLinks[$index]."&bulletin%5B".$value."%5D=on";
        }

        foreach ($upLinks as $key => $value) {
            $url = "http://baza.drom.ru/bulletin/service-configure?return_to=%2Fpersonal%2Fnon_active%2Fbulletins%3Fpage%3D2&from=personal.non_active&applier%5BprolongBulletin%5D=prolongBulletin".$value."=on&note=";
            $this->curl->request($url);
        }
    }

    public function parseExpired(){
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $html = str_get_html($this->auth("http://baza.drom.ru/personal/non_active/bulletins"));

        $links = array();
        $pageLinks = $html->find('.bullNotPublished');
        $page = 1;
        while(count($pageLinks)){
            foreach($pageLinks as $element){
                $exp = $element->find(".expired");
                if( count($exp) )
                    array_push($links, $element->find(".bulletinLink",0)->getAttribute("name"));
            }

            $page++;
            $html = str_get_html(iconv('windows-1251', 'utf-8', $this->curl->request("http://baza.drom.ru/personal/non_active/bulletins?page=".$page)));
            $pageLinks = $html->find('.bullNotPublished');
        }

        return $links;
    }

    public function addAdvert($params,$images){
        $options = $this->setOptions($params);
        $advert_id = json_decode($this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options))->id;
        $this->updateAdvert($advert_id,$params,$images);
        
        $this->curl->request("http://baza.drom.ru/bulletin/".$advert_id."/draft/publish?from=draft.publish",array('from'=>'adding.publish'));
    }
    
    public function updateAdvert($advert_id,$params,$images = NULL) {
        if($images) {
            foreach ($images as &$image_path) {
                $image_path = json_decode($this->curl->request("http://baza.drom.ru/upload-image-jquery",array('up[]' => '@'.Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image_path)))->id;
            }
            $params['images'] = array('images' => $images);
        }
        $options = $this->setOptions($params,$advert_id);
        
        $this->curl->request("http://baza.drom.ru/api/1.0/save/bulletin",$options);
    }

    public function deleteAdverts($arr) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $html = str_get_html($this->curl->request('https://baza.drom.ru/bulletin/service-configure?ids='.$arr[0].'&applier=deleteBulletin'));
        
        $del_arr = array(
            'applier' => 'deleteBulletin',
            'uid' => $html->find('input[name="uid"]', 0)->value,
            'price' => 0,
            'order_id' => 0,
            'return_to' => ''
            );
        foreach ($arr as $key => $value) {
            $del_arr['bulletin['.$value.']']= 'on';
        }
        $this->curl->request('https://baza.drom.ru/bulletin/service-apply',$del_arr);
    }

    public function setOptions($params,$advert_id = NULL) {
        $options = array(
            'cityId' => $params['cityId'],
            'bulletinType'=>'bulletinRegular',
            'directoryId'=> $params['dirId'],
            'fields'=> $params
        );
       
        if($advert_id) {
            if(isset($params['images'])) {
                $options['images'] = $params['images'];
            }
            $options['id'] = $advert_id;
        }
        $options = array('changeDescription' => json_encode($options));
        $options['client_type'] = ($advert_id) ? 'v2:editing' : "v2:adding";
        return $options;
    }
}

?>