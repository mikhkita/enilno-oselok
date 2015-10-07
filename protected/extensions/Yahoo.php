<?

Class Yahoo {
    private $curl;
    private $cur_page;
    private $tog;
    
    function __construct() {
        $this->curl = new Curl();

        $this->tog = true;

        $this->cur_page = 1;
    }

    public function getNextPage($category_id,$max_price,$order){
        $params = array(
            "appid" => "dj0zaiZpPTFFVXE5clJCTVY3ayZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-", // ID приложения
            "category" => $category_id, // ID категории
            "sort" => "bids", // Сортировка. end - по времени окончания, bids - по кол-ву ставок, cbids - по текущей цене, bidorbuy - по блиц-цене, img - есть ли имаги.
            "order" => $order, // Порядок сортировки. a - по возрастанию, d - по убыванию
            "item_status" => 2, // Состояние товара, 0 - не важно, 1 - новые, 2 - б/у
            // "timebuf" => 10,
            "aucmaxprice" => $max_price, // Ограничение текущей цены сверху
            // "seller" => "fix1youki",// Продавец или много продавцов через запятую
            // "page" => 1, // Страница
        );

        $page = false;

        if($this->tog == true){
            $params["page"] = $this->cur_page;
            $page = $this->parseItems($params);

            $file = "";
            if( isset($page["items"]) )
            foreach ($page["items"] as $key => $item) {
                $file .= intval($item->CurrentPrice)." ".$max_price."\n";
            }
            file_put_contents('yahoo.txt', $file, FILE_APPEND);

            $this->cur_page = $this->cur_page+1;
            $this->tog = ( $this->cur_page*20 < intval($page["result"]["allItems"])+20 )?true:false;

            // $this->tog = false;
        }

        return $page;
    }

    public function getLastPage(){
        return $this->cur_page-1;
    }

    public function parseItems($params){
        $result = $this->request("http://auctions.yahooapis.jp/AuctionWebService/V2/json/categoryLeaf?".$this->urlParams($params));

        $ResultSet = (array)$result->ResultSet;

        return array("result" => array(
            "allItems" => $ResultSet["@attributes"]->totalResultsAvailable,
            "nowItems" => $ResultSet["@attributes"]->totalResultsReturned,
            "firstItem" => $ResultSet["@attributes"]->firstResultPosition
        ), "items" => $ResultSet["Result"]->Item);
    }

    public function urlParams($params){
        foreach ($params as $key => $value) {
            $params[$key] = $key."=".$value;
        }
        return implode("&", $params);
    }

    public function request($url){
        return json_decode(preg_replace('/.+?({.+}).+/','$1', $this->curl->request($url)));
    }
}

?>