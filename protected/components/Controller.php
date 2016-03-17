<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='admin';
    public $scripts = array();

    public $courses = array("USD" => 120);
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();

    public $title="Godzilla Wheels";
    public $description="Godzilla Wheels";
    public $keywords="Godzilla Wheels";
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $interpreters = array();

    public $user;

    public $cache = array();

    public $start;
    public $render;
    public $debugText = "";

    public $place_states = array();

    public $type_codes = array(
        1 => "tires",
        2 => "discs",
        3 => "wheels",
    );

    public $settings = array();
    public $user_settings = NULL;
    public $city_settings = NULL;
    public $is_mobile = false;

    public $adminMenu = array();

    public $timer = NULL;

    public function init() {
        parent::init();

        $this->is_mobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));

        date_default_timezone_set("Asia/Novosibirsk");

        if( $_SERVER["HTTP_HOST"] == "koleso.tomsk.ru" || $_SERVER["HTTP_HOST"] == "xn--e1ajdlcr.xn--80asehdb" ){
            header("Location: http://koleso.online".$_SERVER["REQUEST_URI"]);
            die();
        }
        
        $this->user = User::model()->with("role")->findByPk(Yii::app()->user->id);

        $model = ModelNames::model()->findAll(array("order" => "sort ASC"));
        $this->adminMenu["items"] = $this->removeExcess($model,true);

        foreach ($this->adminMenu["items"] as $key => $value) {
            if( $value->admin_menu == 0 ){
                unset($this->adminMenu["items"][$key]);
            }else{
                $this->adminMenu["items"][$key] = $this->toLowerCaseModelNames($value);
            }
        }

        $this->place_states[2048] = $this->place_states["AVITO"] = $this->getParam("AVITO","TOGGLE");
        $this->place_states[2047] = $this->place_states["DROM"] = $this->getParam("DROM","TOGGLE");

        $this->adminMenu["cur"] = $this->toLowerCaseModelNames(ModelNames::model()->find(array("condition" => "code = '".Yii::app()->controller->id."'")));
        
        $this->pageTitle = $this->adminMenu["cur"]->name;

        $this->start = microtime(true);

        $this->getInterpreters();

        if( !Yii::app()->user->isGuest ) $this->checkModelAccess();
    }

    public function beforeRender($view){
        parent::beforeRender($view);

        $this->render = microtime(true);

        $this->debugText = "Controller ".round(microtime(true) - $this->start,4);
        
        return true;
    }

    public function afterRenderPartial(){
        parent::afterRenderPartial();

        $this->debugText = ($this->debugText."<br>View ".round(microtime(true) - $this->render,4));
    }

    public function getUserRole(){
        return $this->user->role->code;
    }

    public function getUserRoleRus() {
        return $this->user->role->name;
    }

    public function getUserRoleFromModel(){
        $user = User::model()->findByPk(Yii::app()->user->id);
        return $user->role->code;
    }

    public function toLowerCaseModelNames($el){
        if( !$el ) return false;
        $el->vin_name = mb_strtolower($el->vin_name, "UTF-8");
        $el->rod_name = mb_strtolower($el->rod_name, "UTF-8");

        return $el;
    }

    public function insertValues($tableName,$values){
        if( !count($values) ) return true;

        $structure = array();
        foreach ($values[0] as $key => $value) {
            $structure[] = "`".$key."`";
        }

        $structure = "(".implode(",", $structure).")";

        $sql = "INSERT INTO `$tableName` ".$structure." VALUES ";

        $vals = array();
        foreach ($values as $value) {
            $item = array();
            foreach ($value as $el) {
                if( $el === NULL ){
                    $item[] = "NULL";
                }else{
                    $item[] = "'".addslashes($el)."'";
                }
            }
            $vals[] = "(".implode(",", $item).")";
        }

        $sql .= implode(",", $vals);

        return Yii::app()->db->createCommand($sql)->execute();
    }

    public function getValues($model,$values,$with = NULL){
        $criteria = new CDbCriteria();
        foreach ($values as $key => $value) {
            $tmp = array();
            foreach ($value as $i => $field) {
                array_push($tmp, $i."='".$field."'");
            }
            $values[$key] = "(".implode(" AND ", $tmp).")";
        }
        $criteria->condition = implode(" OR ", $values);

        return $model->with( ($with!==NULL)?$with:array() )->findAll($criteria);
    }

    public function replaceToBr($str){
        return str_replace("\n", "<br>", $str);
    }

    public function getInterpreters(){
        $tmp = array();
        $model = Interpreter::model()->findAll();
        foreach ($model as $item) {
            $tmp[$item->id.""] = $item;
        }
        $this->interpreters = $tmp;
    }

    public function getListValue($list_id,$attrs = array()){
        $i = "list_".$list_id;
        if( !isset($this->cache[$i]) ){
            $fields = Yii::app()->db->createCommand()
                ->select('*')
                ->from(DictionaryVariant::tableName().' t')
                ->where("dictionary_id=$list_id")
                ->queryAll();

            $model = Dictionary::model()->findByPk($list_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            if( count($fields) ){
                foreach ($fields as $key => $value) {
                    $tmp["VALUES"][$value["attribute_1"]] = $value["value"];
                }
            }else{
                Log::error("getListValue: Список с идентификатором ".$list_id." не найден");
            }
            $this->cache[$i] = $tmp;
        }

        if( isset($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]) ){
            if( is_array($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]) ){
                foreach ($attrs[$this->cache[$i]["ATTRS"]["attr_1"]] as $key => &$value) {
                    $value = (isset($this->cache[$i]["VALUES"][$value->variant_id]))?$this->cache[$i]["VALUES"][$value->variant_id]:"";
                }
                return implode("/", $attrs[$this->cache[$i]["ATTRS"]["attr_1"]]);
            }else{
                return (isset($this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id]))?$this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id]:"";
            }
        }else{
            return $this->cache[$i]["VALUES"];
        }
        return "";
    }

    public function getTableValue($table_id,$attrs){
        $i = "table_".$table_id;
        if( !isset($this->cache[$i]) ){
            $fields = Yii::app()->db->createCommand()
                ->select('*')
                ->from(TableVariant::tableName().' t')
                ->where("table_id=$table_id")
                ->queryAll();

            $model = Table::model()->findByPk($table_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            $tmp["ATTRS"]["attr_2"] = $model->attribute_id_2;
            foreach ($fields as $key => $value) {
                if( !isset($tmp["VALUES"][$value["attribute_1"]]) ) $tmp["VALUES"][$value["attribute_1"]] = array();
                $tmp["VALUES"][$value["attribute_1"]][$value["attribute_2"]] = $value["value"];
            }
            $this->cache[$i] = $tmp;
        }

        return ( isset($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id) && isset($this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id]) )?$this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id]:"";
    }

    public function getCubeValue($cube_id,$attrs){
        $i = "cube_".$cube_id;
        if( !isset($this->cache[$i]) ){
            $fields = Yii::app()->db->createCommand()
                ->select('*')
                ->from(CubeVariant::tableName().' t')
                ->where("cube_id=$cube_id")
                ->queryAll();

            $model = Cube::model()->findByPk($cube_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            $tmp["ATTRS"]["attr_2"] = $model->attribute_id_2;
            $tmp["ATTRS"]["attr_3"] = $model->attribute_id_3;
            foreach ($fields as $key => $value) {
                if( !isset($tmp["VALUES"][$value["attribute_1"]]) ) $tmp["VALUES"][$value["attribute_1"]] = array();
                if( !isset($tmp["VALUES"][$value["attribute_1"]][$value["attribute_2"]]) ) $tmp["VALUES"][$value["attribute_1"]][$value["attribute_2"]] = array();
                $tmp["VALUES"][$value["attribute_1"]][$value["attribute_2"]][$value["attribute_3"]] = $value["value"];
            }
            $this->cache[$i] = $tmp;
        }
        
        return (isset($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id) &&  isset($this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id]))?$this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id]:"";
    }

    public function getVarValue($var_id){
        $i = "var_".$var_id;
        if( !isset($this->cache[$i]) ){
            $model = Vars::model()->findByPk($var_id);
            $this->cache[$i] = (isset($model->value))?$model->value:"";
        }
        
        return $this->cache[$i];
    }

    public function checkAccess($model, $return = false){
        $rule_codes = explode(",", $model->rule_code);
        if( $return ){
            foreach ($rule_codes as $rule_code)
                if( Yii::app()->user->checkAccess(trim($rule_code)) ) return true;
            return false;
        }else{
            $access = false;
            foreach ($rule_codes as $rule_code)
                if( Yii::app()->user->checkAccess(trim($rule_code)) ) $access = true;
            if( !$access ) throw new CHttpException(403,'Доступ запрещен');
        }
    }

    public function checkModelAccess($return = false,$model_code = false){
        $model_code = ($model_code)?$model_code:( (isset($this->adminMenu["cur"]->code))?$this->adminMenu["cur"]->code:false );
        if( !$model_code ) return true;
        if( $this->user->usr_models == "" || $model_code == "" ) return true;
        $models = explode(",", $this->user->usr_models);
        if( $return ){
            foreach ($models as $model)
                if( trim($model) == trim($model_code) ) return true;
            return false;
        }else{
            $access = false;
            foreach ($models as $model)
                if( trim($model) == trim($model_code) ) $access = true;
            if( !$access ) throw new CHttpException(403,'Доступ запрещен');
        }
    }

    public function getDynObjects($dynamic,$good_type_id = NULL){
        $dynObjects = array();

        if( $good_type_id != NULL ){
            $criteria = new CDbCriteria();
            $criteria->with = array("goodTypes","variants");
            $criteria->condition = "goodTypes.good_type_id=".$good_type_id." AND dynamic=1";
            $modelDyn = Attribute::model()->findAll($criteria);

            foreach ($modelDyn as $key => $value) {
                $curObj = Variant::model()->findByPk($dynamic[$value->id]);
                $dynObjects[$value->id] = (object) array("value"=>$curObj->value,"variant_id"=>$curObj->id);
            }
        }else{
            $criteria = new CDbCriteria();
            $criteria->addInCondition("id",array_values($dynamic));
            $modelDyn = Variant::model()->findAll($criteria);

            foreach ($modelDyn as $key => $variant)
                $dynObjects[array_search($variant->id, $dynamic)] = (object) array("value"=>$variant->value,"variant_id"=>$variant->id);
        }

        return $dynObjects;
    }

    public function removeExcess($model,$menu = false){
        foreach ($model as $key => $item) {
            if( !$this->checkAccess( $item, true ) || ( $menu && !$this->checkModelAccess( true, $item->code ) ) ) unset($model[$key]);
        }
        return array_values($model);
    }

    public function cutText($str, $max_char = 255){
        if( mb_strlen($str,"UTF-8") >= $max_char-3 ){
            $str = mb_substr($str, 0, $max_char-3,"UTF-8")."...";
        }
        return $str;
    }

    public function declOfNum($number, $titles){
        $cases = array (2, 0, 1, 1, 1, 2);
        return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }

    public function getTextTime($sec){
        $min = intval($sec/60);
        $hours = floor($min/60)%(24);
        $days = floor($min/24/60);
        $minutes = $min%60;
        $sec = $sec%60;

        if( $days ){
            $out = $days."д. ";
        }else{
            $out = "";
        }

        if( $hours ){
            $out .= $hours."ч. ";
        }

        if( $minutes && !$days ){
            $out .= $minutes."м. ";
        }

        if( $sec && !$days && !$hours ){
            $out .= $sec."с.";
        }

        return trim($out);
    }

    public function DownloadFile($source,$filename) {
        if (file_exists($source)) {
        
            if (ob_get_level()) {
              ob_end_clean();
            }

            $arr = explode(".", $source);
            
            header("HTTP/1.0 200 OK");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename.".".array_pop($arr) );
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($source));
            
            readfile($source);
            exit;
        }
    }

    public function implodeValues($arr){
        $out = array();
        foreach ($arr as $key => $value) {
            $out[] = $value->value;
        }
        return implode("/",$out);
    }

    public function getParam($category,$code,$reload = false){
        if( $this->settings == NULL || $reload ) $this->getSettings();

        $category_code = mb_strtoupper($category,"UTF-8");
        $param_code = mb_strtoupper($code,"UTF-8");

        return ( isset($this->settings[$category_code][$param_code]) )?$this->settings[$category_code][$param_code]:"";
    }

    public function getUserParam($code, $reload = false, $int_assoc = false){
        if( $this->user_settings == NULL || $reload ) $this->getUserSettings();

        $param_code = mb_strtoupper($code,"UTF-8");

        if( isset($this->user_settings[$param_code]) ){
            if( $int_assoc ){
                $out = array();
                foreach ($this->user_settings[$param_code] as $key => $value)
                    $out[intval($key)] = $value;
            }else{
                $out = $this->user_settings[$param_code];
            }

            return $out;
        }else{
            return NULL;
        }
    }

    public function getUserSettings(){
        $out = array();
        if( $this->user->settings )
            foreach ($this->user->settings as $i => $param)
                $out[$param->code] = json_decode($param->value);

        $this->user_settings = $out;
    }

    public function setUserParam($code, $value){
        $param_code = mb_strtoupper($code,"UTF-8");

        if( UserSettings::model()->count("user_id=".$this->user->usr_id." AND code='".$param_code."'") ){
            $model = UserSettings::model()->find(array("limit"=>1,"condition"=>"user_id=".$this->user->usr_id." AND code='".$param_code."'"));
            $model->value = json_encode($value);
            $model->save();
        }else{
            $this->insertValues(UserSettings::tableName(),array(array("user_id"=>$this->user->usr_id,"code"=>$param_code,"value"=>json_encode($value))));
        }

        if( is_array($this->user_settings) )
            $this->user_settings[$param_code] = $value;
    }

    public function getCityParam($id, $reload = false){
        if( $this->city_settings == NULL || $reload ) $this->city_settings = $this->getCitySettings();

        return ( isset($this->city_settings[$id]) )?$this->city_settings[$id]:NULL;
    }

    public function getCitySettings(){
        $cols = array(
            55 => "name",
            56 => "id",
            57 => "avito_delay"
        );
        $out = array();
        $rows = DesktopTableRow::model()->with(array("cells"))->findAll("table_id=14");
        if( $rows ){
            foreach ($rows as $i => $row) {
                $one_cell = array();
                foreach ($row->cells as $key => $cell)
                    $one_cell[$cols[$cell->col_id]] = $cell->value;
                $out[$one_cell["id"]] = (object) $one_cell;
            }   
        }
        return $out;
    }

    public function getPage($code){
        $cols = array(
            69 => "title",
            70 => "description",
            71 => "keywords",
            72 => "content",
            73 => "code",
        );
        $cells = DesktopTableCell::model()->with(array("row.cells"))->findAll("row.table_id=16 AND t.col_id=73 AND t.varchar_value='$code'");
        if( $cells ){
            $out = NULL;
            foreach ($cells as $i => $cell) {
                $one_cell = array();
                foreach ($cell->row->cells as $key => $cell)
                    $one_cell[$cols[$cell->col_id]] = $cell->value;
                $out = (object) $one_cell;
            }

            return $out;
        }else{
            return NULL;
        }
    }

    public function getDromAccount($login = NULL){
        $cols = array(
            46 => "login",
            47 => "password",
            48 => "phone",
            114 => "proxy"
        );
        $cells = DesktopTableCell::model()->with(array("row.cells"))->findAll("row.table_id=12".(($login)?" AND t.varchar_value='$login'":""));
        if( $cells ){
            $out = array();
            foreach ($cells as $i => $cell) {
                $one_cell = array();
                foreach ($cell->row->cells as $key => $cell)
                    $one_cell[$cols[$cell->col_id]] = $cell->value;
                $out[$one_cell["login"]] = (object) $one_cell;
            }

            return ($login)?array_pop($out):$out;
        }else{
            return NULL;
        }
    }

    public function getAvitoAccount($login){
        $cols = array(
            49 => "login",
            50 => "password",
            51 => "phone",
            52 => "name",
            53 => "proxy"
        );
        $cell = DesktopTableCell::model()->with(array("row"))->find("row.table_id=13 AND varchar_value='$login'");
        if( $cell ){
            $row = DesktopTableRow::model()->with(array("cells"))->findByPk($cell->row->id);
            $out = array();
            foreach ($row->cells as $key => $cell)
                $out[$cols[$cell->col_id]] = $cell->value;

            return (object) $out;
        }else{
            return NULL;
        }
    }

    public function setParam($category,$code,$value){
        $model = Settings::model()->with("category")->find("category.code='".$category."' AND t.code='".$code."'");
        $model->value = $value;
        $this->settings[$category][$code] = $value;
        return $model->save();
    }

    public function getSettings(){
        $model = Category::model()->with(array("settings"=>array("select"=>array("code","value"))))->findAll();

        foreach ($model as $category) {
            foreach ($category->settings as $param) {
                $category_code = mb_strtoupper($category->code,"UTF-8");
                $param_code = mb_strtoupper($param->code,"UTF-8");
                if( !isset($this->settings[$category_code]) ) $this->settings[$category_code] = array();
                $this->settings[$category_code][$param_code] = $param->value;
            }
        }
    }

    public function getImages($good, $number = NULL, $get_default = true,$extra = false)
    {   
        if( is_object($good) ){
            $code = $good->fields_assoc[3]->value;
            $good_type_id = $good->good_type_id;
        }else if( is_array($good) ){
            $code = $good["code"];
            $good_type_id = $good["good_type_id"];
        }
        $imgs = array();
        $path = Yii::app()->params["imageFolder"]."/".GoodType::getCode($good_type_id);
        if($extra) {
            $code = $code."/extra";
        }
        $dir = $path."/".$code;
        if (is_dir($dir)) {
            $imgs = array_values(array_diff(scandir($dir), array('..', '.', 'Thumbs.db', '.DS_Store')));
            $dir = Yii::app()->request->baseUrl."/".$path."/".$code;
            $out = array();
            if(count($imgs)) {
                if($number) {
                    for ($i=0; $i < $number; $i++) { 
                        if(!is_dir($imgs[$i])) $out[$i] = $dir."/".$imgs[$i];
                    }
                    $imgs = $out;
                } else {
                    foreach ($imgs as $key => &$value) {
                        if(!is_dir($value)) $value = $dir."/".$value;
                    }
                }           
            } else {
                
                if( $get_default )
                    array_push($imgs, Yii::app()->request->baseUrl."/".$path."/default.jpg");
            }
        }
        else {
            if( $get_default )
                array_push($imgs, Yii::app()->request->baseUrl."/".$path."/default.jpg");    
        }
        return $imgs;
    }

    public function updateRows($table_name,$values = array(),$update){
        $result = true;

        if( count($values) ){
            $query = Yii::app()->db->createCommand("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = '".$table_name."' AND `table_schema` = 'koleso'")->query();

            $structure = array();
            $primary_keys = array();
            $columns = array();
            $vals = array();
            while($next = $query->read()){
                if( !in_array("`".$next["COLUMN_NAME"]."`", $columns) ){
                    array_push($columns, "`".$next["COLUMN_NAME"]."`");
                    if( $next["COLUMN_KEY"] == "PRI" ) 
                            array_push($primary_keys, "`".$next["COLUMN_NAME"]."`");
                    $structure[$next["COLUMN_NAME"]] = $next["COLUMN_TYPE"]." ".(($next["IS_NULLABLE"] == "NO" && $next["EXTRA"] != "auto_increment")?"NOT ":"")."NULL";
                }
            }

            $structure[0] = "PRIMARY KEY (".implode(",", $primary_keys).")";

            $tmpName = "tmp_".md5(rand().time());

            Yii::app()->db->createCommand()->createTable($tmpName, $structure, 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci');

            $sql = "INSERT INTO `$tmpName` (".implode(",", $columns).") VALUES ";

            foreach ($values as $arr) {
                $strArr = array();
                foreach ($arr as $item) {
                    array_push($strArr, ( $item === NULL )?"NULL":( ($item == "LAST_INSERT_ID()")?$item:("'".$item."'")));
                }
                array_push($vals, "(".implode(",", $strArr).")");
            }

            $sql .= implode(",", $vals);

            foreach ($update as &$item) {
                $item = " ".$table_name.".".$item." = ".$tmpName.".".$item;
            }

            // print_r($sql);
            // die();

            if( Yii::app()->db->createCommand($sql)->execute() ){
                $sql = "INSERT INTO `$table_name` SELECT * FROM `$tmpName` ON DUPLICATE KEY UPDATE".implode(",", $update);
                $result = Yii::app()->db->createCommand($sql)->execute();
                
                Yii::app()->db->createCommand()->dropTable($tmpName);
            }else $result = false;
        }

        return $result;
    }

    public function splitByRows($row_count,$items){
        $out = array();
        $i = 0;
        $j = 0;
        foreach ($items as $key => $item) {
            if( $i!=0 && $i%$row_count == 0 ){
                $j++;
                $out[$j] = array();
            }
            $out[$j][$key] = $item;
            $i++;
        }
        return $out;
    }

    public function splitByCols($col_count,$items){
        return $this->splitByRows(ceil(count($items)/$col_count),$items);
    }

    public function getAssoc($items,$attr){
        $out = array();
        foreach ($items as $item)
            $out[$item->getAttribute($attr)] = $item;
        return $out;
    }

    public function getAssocByAssoc($items,$attr){
        $out = array();
        foreach ($items as $item)
            $out[$item[$attr]] = $item;
        return $out;
    }

    public function downloadImages($images,$good_code,$good_type_id){
        foreach ($images as $img) {
            $dir = $this->type_codes[$good->good_type_id];
            $img_name = array_pop(explode("/",$img));
            $dir = Yii::app()->params["imageFolder"]."/".$this->type_codes[$good_type_id]."/".$good_code;
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            copy( $img, $dir."/".$img_name);
        }
    }

    public function getArray($items){
        $out = array();
        if( $items ){
            if( is_array($items) ){
                $out = $items;
            }else{
                array_push($out, $items);
            }
        }
        return $out;
    }

    public function getIds($model, $field = NULL){
        $ids = array();
        foreach ($model as $key => $value)
            array_push($ids, (($field !== NULL)?$value[$field]:$value->id) );
        return $ids;
    }

    public function checkAdverts($model){
        $model = Good::model()->with(array("fields.variant","fields.attribute"))->findByPk($model->id);

        $check_param_id = $this->getParam("SERVICE",mb_strtoupper($model->type->code,"UTF-8")."_PARAM_ID");
        $check_city_id = $this->getParam("SERVICE",mb_strtoupper($model->type->code,"UTF-8")."_CITY_ID");

        if( intval(Interpreter::generate($check_param_id,$model)) == 1 ){
            $delete_ids = array();
            $cities = Place::model()->cities;
            foreach ($model->fields_assoc as $code => $field) {
                $code = explode("-d", $code);
                if( count($code) == 2 && count($field) )
                    foreach ($model->getArray($field) as $i => $city) {
                        $dynamic = $this->getDynObjects(array(
                            57 => $cities[intval($code[0])]["PLACE"],
                            38 => $city->variant_id,
                            37 => $cities[intval($code[0])]["TYPE"]
                        ));
                        
                        if( intval(Interpreter::generate($check_city_id,$model,$dynamic)) != 1 )
                            array_push($delete_ids, $city->id);
                    }
            }
            // print_r($delete_ids);
            if( count($delete_ids) )
                GoodAttribute::model()->deleteAll("id IN (".implode(",", $delete_ids).")");
        }else{
            $city_field_ids = array();
            foreach (Place::model()->cities as $i => $value)
                array_push($city_field_ids, $i);

            if( count($city_field_ids) )
                GoodAttribute::model()->deleteAll("attribute_id IN (".implode(",", $city_field_ids).") AND good_id=".$model->id);
        }
    }

    public function cleanDir($dir) {
        $files = glob($dir."/*");
        $c = count($files);
        if (count($files) > 0) {
            foreach ($files as $file) {      
                if (file_exists($file)) {
                unlink($file);
                }   
            }
        }
    }

    public function isRoot(){
        return $this->user->role->code == "root";
    }

    public function startTimer(){
        $this->timer = microtime(true);
    }

    public function printTimer($str = ""){
        // list($queryCount, $queryTime) = Yii::app()->db->getStats();
        echo $str.": Прошло ".sprintf('%0.5f',microtime(true) - $this->timer)." сек. Запросов: $queryCount, Время работы с БД: ".sprintf('%0.5f',$queryTime)."s<br>";
    }

    public function checkCity(){
        if( isset(Yii::app()->params["city"]) ) return true;
        $show = 0;
        if( !(isset($_GET['city']) && $_GET['city'] != "") ) {
            if (!isset($_COOKIE['geo'])) {
                include_once Yii::app()->basePath.'/geo.php';
                $geo = new Geo();
                $city = $geo->get_value('city', false);
                $city = Variant::model()->with(array("attribute"))->find("value='".$city."' AND attribute.attribute_id=38");
                if( !$city ) {
                    $city = Variant::model()->with(array("attribute"))->find("value='Москва' AND attribute.attribute_id=38");
                    $show = 1;
                }
                $city_id = $city->id;
                if(!$show) setcookie('geo', $city_id, time() + 3600 * 24 * 30, '/','koleso.online');
            } else {
                $city_id = $_COOKIE['geo'];
                $city = Variant::model()->with(array("attribute"))->find("id=$city_id AND attribute.attribute_id=38");
            }
            $dictionary = DictionaryVariant::model()->find("attribute_1='$city_id' AND dictionary_id=125");
            header("Location: http://".$dictionary->value.".".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);     
        }else{
            $dictionary = DictionaryVariant::model()->find("value='".$_GET["city"]."' AND dictionary_id=125");
            $city_id = $dictionary->attribute_1;
            $city = Variant::model()->with(array("attribute"))->find("id=$city_id AND attribute.attribute_id=38");
            setcookie('geo', $city_id, time() + 3600 * 24 * 30, '/','koleso.online');
        }
        $region = intval(DictionaryVariant::model()->find("dictionary_id=138 AND attribute_1='$city_id'")->value);
        $row = DesktopTableCell::model()->find("col_id=120 AND int_value=$region");
        if( $row ) {
            $phone = DesktopTableCell::model()->find("col_id=119 AND row_id=".$row->row_id)->varchar_value;
        } else $phone = '79138275756';
        
        $in = DictionaryVariant::model()->find("attribute_1='$city_id' AND dictionary_id=126");
        Yii::app()->params["city"] = (object) array(
            "id" => $city_id,
            "code" => $dictionary->value,
            "name" => $city->value,
            "in" => ($in)?$in->value:"",
            "phone" => $phone,
            "popup" => $show
        );
        unset($_GET['city']);
    }

    public function visualInter($template){
        $start = microtime(true);
        preg_match_all("~\[\+([^\+\]]+)\+\]~", $template, $matches);
        if( !count($matches[1]) ){
            echo $template;
            return true;
        }

        $params = array(
            "ATTR"  => array("link" => false),
            "INTER" => array("link" => true, "main_url" => "/admin/interpreter/list", "url" => "/interpreter/adminupdate"),
            "LIST"  => array("link" => true, "main_url" => "/admin/data/dictionaryedit"),
            "TABLE" => array("link" => true, "main_url" => "/admin/data/tableedit"),
            "CUBE"  => array("link" => true, "main_url" => "/admin/data/cubeedit"),
            "VAR"   => array("link" => true, "main_url" => "/admin/data/vars", "url" => "/data/adminvarsupdate"),
        );

        $types = array();
        foreach ($matches[1] as $i => $match) {
            $tmp = explode(";", $match);
            $item = explode("=", $tmp[0]);

            if( !in_array(trim($item[0]), $types) )
                array_push($types, strtoupper(trim($item[0])));

            $matches[1][$i] = array(
                "type" => strtoupper(trim($item[0])),
                "id" => trim($item[1])
            );
        }
        $types = $this->getTypes($types);

        foreach ($matches[1] as $i => $match) {
            $param = $params[$match["type"]];
            if( $param["link"] ){
                if( isset($param["url"]) ){
                    $matches[1][$i] = "<a href='".$param["main_url"]."#click|".$this->createUrl($param["url"],array("id" => $match["id"]))."' class='".strtolower($matches[1][$i]["type"])."' target='_blank'>".$types[$matches[1][$i]["type"]][$matches[1][$i]["id"]]["name"]."</a>";
                }else{
                    $matches[1][$i] = "<a href='".$this->createUrl($param["main_url"],array("id" => $match["id"]))."' class='".strtolower($matches[1][$i]["type"])."' target='_blank'>".$types[$matches[1][$i]["type"]][$matches[1][$i]["id"]]["name"]."</a>";
                }
            }else{
                $matches[1][$i] = "<span class='".strtolower($matches[1][$i]["type"])."'>".$types[$matches[1][$i]["type"]][$matches[1][$i]["id"]]["name"]."</span>";
            }
        }

        // var_dump($matches[1]);
        echo $this->tabInter(str_replace($matches[0], $matches[1], $template));
        
        // printf('<br>Генерация %.4F сек.<br>', microtime(true) - $start);   
    }

    public function tabInter($str){
        $str = str_replace(")?", ")<br>?", $str);
        $str = str_replace("):", ")<br>:", $str);
        $str = str_replace("`", "<br>`", $str);
        $str = explode("<br>", $str);
        $tab = 0;
        $prev = 0;
        $til = false;
        foreach ($str as $i => $line) {
            $first = substr($line, 0, 1);
            if( $first == "?" ){
                if( $prev == "?" ){
                    $tab++;
                }else{
                    $tab++;
                }
            }else if( $first == ":" ){
                if( $prev == ":" ){
                    $tab--;
                }else{
                    // $tab++;
                }
            }else if( $first == "`" ){
                if( $til ){
                    $tab--;
                    $til = false;
                }else{
                    $til = true;
                }
            }

            $str[$i] = $this->getTab($tab).$line;
            $prev = $first;
        }
        return implode("<br>", $str);
    }

    public function getTab($num){
        $out = "";
        for ($i=0; $i < $num*4; $i++)
            $out .= "&nbsp;";
        return $out;
    }

    public function getTypes($types){
        if( !isset($this->cache["visual"]) ) $this->cache["visual"] = array();

        $tableNames = array(
            "ATTR" => Attribute::tableName(),
            "INTER" => Interpreter::tableName(),
            "LIST" => Dictionary::tableName(),
            "TABLE" => Table::tableName(),
            "CUBE" => Cube::tableName(),
            "VAR" => Vars::tableName(),
        );

        $out = array();
        foreach ($types as $i => $type) {
            if( isset($this->cache["visual"][$type]) ){
                $out[$type] = $this->cache["visual"][$type];
            }else{
                if( $type == "VAR" ){
                    $model = Yii::app()->db->createCommand()
                        ->select('name')
                        ->from($tableNames[$type].' t')
                        ->limit(1000)
                        ->queryAll();

                    $out[$type] = $this->getAssocByAssoc($model, "name");
                }else{
                    if( isset($tableNames[$type]) ){
                        $model = Yii::app()->db->createCommand()
                            ->select('id, name')
                            ->from($tableNames[$type].' t')
                            ->limit(1000)
                            ->queryAll();
                        $out[$type] = $this->getAssocByAssoc($model, "id");
                    }
                }
            }
        }
        return $out;
    }

    public function getRussianMonth($m){
        $month = array(0,"января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
        return $month[intval($m)];
    }

    public function cityReplace($str){
        $phone = str_split(Yii::app()->params["city"]->phone); 
        $phone = $phone[0]." (".$phone[1].$phone[2].$phone[3].") ".$phone[4].$phone[5].$phone[6]."-".$phone[7].$phone[8]."-".$phone[9].$phone[10];
        return str_replace(array("[+CITY+]","[+IN+]","[+PHONE+]"), array(Yii::app()->params["city"]->name,Yii::app()->params["city"]->in,$phone), $str);
    }
}