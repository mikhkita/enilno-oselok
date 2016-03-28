<?

Class Vk {

    private $access_token = '5bafda61f16ad372a8e79d39b2090d0eaa03576846f16f8cb4e672ba9aea1dd55a7c08a997eb512b8fde9';
    private $group_id = '118079986';
    private $version = 5.50;
    private $base_url = 'https://api.vk.com/method/';
    public $curl;

    function __construct() {
        $this->curl = new Curl();
    }

    public function addAdvert($params,$images = NULL){
        if($images) {
            $photo_arr = array();
            array_push($photo_arr, $this->addPhoto($images[0],1));
            for ($i=1; $i < count($images); $i++) {
                if($i == 5) break; 
                array_push($photo_arr, $this->addPhoto($images[$i]));
                
            }
            $url = $this->base_url."market.add";
            $params = array(
                'owner_id' => "-".$this->group_id,
                'category_id' => 404,
                'v' => $this->version,
                'access_token' => $this->access_token,
                'name' => $params['name'],
                'description' => $params['description'],
                'price' => $params['price'],
                'main_photo_id' => $photo_arr[0]
            );
            unset($photo_arr[0]);
            if(count($photo_arr))
                $params['photo_ids'] = implode(",", $photo_arr);       
            $url .='?'.urldecode(http_build_query($params));
            $this->curl->removeCookies();
            return json_decode($this->curl->request($url))->response->market_item_id;
        }
    }

    public function addPhoto($image,$type_img = 0){
        $url = $this->base_url."photos.getMarketUploadServer";
            $params = array(
                'main_photo' => $type_img,
                'group_id' => $this->group_id,
                'v' => $this->version,
                'access_token' => $this->access_token
            );
        $url .='?'.urldecode(http_build_query($params));
        $url = json_decode($this->curl->request($url))->response->upload_url;
        $url = json_decode($this->curl->request($url,array("file" => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image))));
        $params = array(
            'group_id' => $this->group_id,
            'photo' => $url->photo,
            'server' => $url->server,
            'hash' => $url->hash,
            'v' => $this->version,
            'access_token' => $this->access_token
        );
        if($type_img) {
            $params['crop_hash'] = $url->crop_hash;
            $params['crop_data'] = $url->crop_data;
        }
        $url = $this->base_url."photos.saveMarketPhoto";
        $url .='?'.urldecode(http_build_query($params));
        return json_decode($this->curl->request($url))->response[0]->id;
    }
}

?>