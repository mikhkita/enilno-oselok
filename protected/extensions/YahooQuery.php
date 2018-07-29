<?

Class YahooQuery {
    private $curl;
    private $cur_page;
    private $tog;
    
    function __construct() {
        $this->curl = new Curl();

        $this->tog = true;

        $this->cur_page = 1;
    }

    public function getNextPage($data, $order){
        $params = array(
            "appid" => (($_SERVER["HTTP_HOST"] == "koleso.com")?"dj00aiZpPVdVcTJaemJnQ2JHYSZzPWNvbnN1bWVyc2VjcmV0Jng9ZWE-":"dj0zaiZpPTFFVXE5clJCTVY3ayZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-"), // ID приложения
            // "category" => $category_id, // ID категории
            "sort" => "bids", // Сортировка. end - по времени окончания, bids - по кол-ву ставок, cbids - по текущей цене, bidorbuy - по блиц-цене, img - есть ли имаги.
            "order" => $order, // Порядок сортировки. a - по возрастанию, d - по убыванию
            "item_status" => 2, // Состояние товара, 0 - не важно, 1 - новые, 2 - б/у
            // "timebuf" => 10,
            // "aucmaxprice" => 230, // Ограничение текущей цены сверху
            // "seller" => "fix1youki",// Продавец или много продавцов через запятую
            // "query" => "", // Страница
            // "f" => "0x4"

        );

        $page = false;

        if($this->tog == true){
            $params["page"] = $this->cur_page;
            $page = $this->parseItems($params, $data);

            $this->cur_page = $this->cur_page+1;
            $this->tog = ( $this->cur_page*20 < intval($page["result"]["allItems"])+20 )?true:false;

            // $this->tog = false;
        }

        return $page;
    }

    public function getLastPage(){
        return $this->cur_page-1;
    }

    public function parseItems($params, $data){
        // https://auctions.yahooapis.jp/AuctionWebService/V2/json/search?appid=dj0zaiZpPTFFVXE5clJCTVY3ayZzPWNvbnN1bWVyc2VjcmV0Jng9YzQ-&f=0x4&query=45r17+2%E6%9C%AC+5.5%E3%83%9F%E3%83%AA
        $result = $this->request("https://auctions.yahooapis.jp/AuctionWebService/V2/json/search?".$this->urlParams($params)."&".$data);
        var_dump("http://auctions.yahooapis.jp/AuctionWebService/V2/json/search?".$this->urlParams($params)."&".$data);
        var_dump($result);
        die();

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