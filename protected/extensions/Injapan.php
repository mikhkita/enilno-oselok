<?

Class Injapan {
    
    function __construct() {

    }

    public function getFields($code, $max_price, $state = NULL){
        include_once  Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $result = array("main"=>array(),"other"=>array());

        $html = file_get_html("https://injapan.ru/auction/".$code.".html");

        // Получение заголовка лота
        $query = $html->find('.auction tr td[class=l]');
        $result["main"]["name"] = $query[0]->innertext;

        // Получение даты окончания аукциона
        $query = $html->find('#rowInfoEnddate td[class=l]');
        $arr = explode(" ",strip_tags($query[0]->innertext));
        $d = explode("/",$arr[0]);
        $result["main"]["date"] = date("Y-m-d H:i:s", strtotime($d[2]."-".$d[1]."-".$d[0]." ".$arr[1].":00") + 3*60*60);

        // Получение первой фотографии
        $query = $html->find('.left_previews td img');
        $result["main"]["image"] = $query[0]->src;

        // Получение шага ставки
        $query = $html->find("#spanInfoStep");
        $arr = explode("<span", $query[0]->innertext);
        $result["other"]["step"] = intval(preg_replace("/[^0-9]/", '', $arr[0] ));

        // Получение текущей цены лота
        $query = $html->find("#spanInfoPrice strong");
        $result["main"]["current_price"] = intval(str_replace("&nbsp;", "", strip_tags($query[0]->innertext)));
        $result["main"]["state"] = ( intval($result["main"]["current_price"]) + intval($result["other"]["step"]) > intval($max_price) )?5:0;

        return $result;
    }

    public function getDetail($code){
        include_once  Yii::app()->basePath.'/extensions/simple_html_dom.php';
        $html = file_get_html("https://injapan.ru/auction/".$code.".html");
        
        return (object) array(
            "title"=>$html->find('span.small')[0]->innertext,
            "table"=>$html->find('.auction')[0]->innertext,
            "text"=>$html->find('#russian')[0]->innertext,
            "images"=>$html->find('.old_lot_images')[0]->innertext
        );
    }
}

?>