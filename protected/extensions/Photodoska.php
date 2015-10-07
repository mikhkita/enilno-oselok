<?

Class Photodoska {

    public $login = "wheels70";
    public $password = "411447";
    public $isAuth = false;
    private $curl;
    
    function __construct() {
        $this->curl = new Curl();
    }

    public function auth(){
        $this->curl->removeCookies();

        $params = array(
            'data53' => $this->login,
            'data84' => $this->password
        );

        $this->curl->request("http://photodoska.ru/?a=auth",$params);

        $this->isAuth = true;
    }

    public function addAdvert($file,$title,$text,$tel,$price) {
        $params = array(
            'upload' => curl_file_create($file), 
            'image/jpeg', 
            'image.jpg'
        );

        $photo = substr($this->curl->request("http://photodoska.ru/?a=upload_photo",$params),2);

        $data = array(
            'data[0][name]' => 'city_id',
            'data[0][value]' => 1,
            'data[1][name]' => 'parent_rubric_id',
            'data[1][value]' => 1,
            'data[2][name]' => 'child_rubric_id',
            'data[2][value]' => 42,
            'data[3][name]' => 'title',
            'data[3][value]' => $title,
            'data[4][name]' => 'text',
            'data[4][value]' => $text,
            'data[6][name]' => 'photo_1',
            'data[6][value]' => $photo,
            'data[11][name]' => 'price',
            'data[11][value]' => $price,
            'data[12][name]' => 'phone',
            'data[12][value]' => $tel,
            'data[13][name]' => 'comment_permission',
            'data[13][value]' => 0
        );
        $this->curl->request("http://photodoska.ru/?a=add_ad",$data);
    }

    public function deleteAdverts($save_id = NULL) {
        $arr = $this->parseAdverts();
        foreach($arr as $element) {
            if($save_id != $element['id']) {
                $this->curl->request("http://photodoska.ru/?a=delete_ad",array("id" => $element['id']));
            }
        }
    }
    public function parseAdverts() {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $this->curl->request('http://photodoska.ru/u/'.$this->login);

        $html = str_get_html($this->curl->request('http://photodoska.ru/u/'.$this->login));
        $arr = array();
        foreach ($html->find('.title a') as $item) {
            $temp = array();
            $temp['url'] = $item->href;
            $temp['title'] = $item->title;
            $temp['id'] = array_pop(explode('-', $item->href));
            array_push($arr, $temp);
        }
        return $arr;
    }

    public function isAuth(){
        return $this->isAuth;
    }

}

?>