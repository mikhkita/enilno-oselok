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

    public function addAdvert($params,$images,$edit_id = NULL){
        $photo_arr = array();
        $inc = 0;
        $result = false;
        
        array_push($photo_arr, $this->addPhoto($images[0],1));
        for ($i=1; $i < count($images); $i++) {
            if($i == 5) break; 
            $photo = $this->addPhoto($images[$i]);
            if( $photo )
                array_push($photo_arr, $photo);
            
        }  
        $url = ($edit_id) ? $this->base_url."market.edit" : $this->base_url."market.add";
        $album_id = $params["album_id"];
        $params = array(
            'owner_id' => "-".$this->group_id,
            'category_id' => 404,
            'name' => urlencode($params['name']),
            'description' => urlencode($params['description']),
            'price' => $params['price'],
            'main_photo_id' => $photo_arr[0],
            'v' => $this->version,
            'access_token' => $this->access_token
        );
        if($edit_id) $params['item_id'] = $edit_id;
        unset($photo_arr[0]);
        if(count($photo_arr))
            $params['photo_ids'] = implode(",", $photo_arr);       
        $url .='?'.urldecode(http_build_query($params));
        $json = json_decode($this->curl->request($url));
        print_r($json);

        if(!$edit_id) {
            $advert_id = $json->response->market_item_id;
            if($advert_id) {
                while ( $inc < 5 && $result === false) {
                    $url = $this->base_url."market.addToAlbum";
                    $params = array(
                        'owner_id' => "-".$this->group_id,
                        'item_id' => $advert_id,
                        'album_ids' => $album_id,
                        'v' => $this->version,
                        'access_token' => $this->access_token,
                    );
                    $url .='?'.urldecode(http_build_query($params));  
                    $json = json_decode($this->curl->request($url));
                    $result = $json->response;
                    $result = ($result == 1) ? $advert_id : false;
                    $inc++;
                }    
                if($result === false) {
                    $inc = 0;
                    while ( $inc < 5 && !$this->deleteAdvert($advert_id)) {
                        $inc++;
                    }
                }
            }
        } else {
            $result = ($json->response == 1) ? $edit_id : false;
        }
        $this->curl->removeCookies();
        return $result;
    }

    public function updateAdvert($advert_id,$params,$images) {
        return $this->addAdvert($params,$images,$advert_id);
    }

    public function deleteAdvert($advert_id) {
        $url = $this->base_url."market.delete";
        $params = array(
            'owner_id' => "-".$this->group_id,
            'item_id' => $advert_id,
            'v' => $this->version,
            'access_token' => $this->access_token
        );
        $url .='?'.urldecode(http_build_query($params));  
        $result = json_decode($this->curl->request($url));
        $result = $result->response;
        $this->curl->removeCookies();
        return ($result == 1);
    }

    public function generateFields($fields,$good_type_id){
        if( $good_type_id == 1) $fields['album_id'] = 6;
        if( $good_type_id == 2) $fields['album_id'] = 7;
        if( $good_type_id == 3) $fields['album_id'] = 5;
        return $fields;
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
        $json = json_decode($this->curl->request($url));
        $url = $json->response->upload_url;
        $url = json_decode($this->curl->request($url,array("file" => new CurlFile(Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.$image))));
        print_r($url);
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
        $json = json_decode($this->curl->request($url));
        return (isset($json->response[0]) && $json->response[0]->id)?$json->response[0]->id:false;
    }
}

?>