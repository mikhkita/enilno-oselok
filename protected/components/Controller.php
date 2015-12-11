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

    public $adminMenu = array();

    public $timer = NULL;

    public function init() {
        parent::init();

        date_default_timezone_set("Asia/Novosibirsk");

        if( $_SERVER["HTTP_HOST"] == "koleso.tomsk.ru" ){
            header("Location: http://koleso.online".$_SERVER["REQUEST_URI"]);
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
            $model = Dictionary::model()->with("values")->findByPk($list_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            if( isset($model->values) ){
                foreach ($model->values as $key => $value) {
                    $tmp["VALUES"][$value->attribute_1] = $value->value;
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
            $model = Table::model()->with("values")->findByPk($table_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            $tmp["ATTRS"]["attr_2"] = $model->attribute_id_2;
            foreach ($model->values as $key => $value) {
                if( !isset($tmp["VALUES"][$value->attribute_1]) ) $tmp["VALUES"][$value->attribute_1] = array();
                $tmp["VALUES"][$value->attribute_1][$value->attribute_2] = $value->value;
            }
            $this->cache[$i] = $tmp;
        }
        return ( isset($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id) && isset($this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id]) )?$this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id]:"";
    }

    public function getCubeValue($cube_id,$attrs){
        $i = "cube_".$cube_id;
        if( !isset($this->cache[$i]) ){
            $model = Cube::model()->with("values")->findByPk($cube_id);
            $tmp = array( "ATTRS" => array(), "VALUES" => array() );
            $tmp["ATTRS"]["attr_1"] = $model->attribute_id_1;
            $tmp["ATTRS"]["attr_2"] = $model->attribute_id_2;
            $tmp["ATTRS"]["attr_3"] = $model->attribute_id_3;
            foreach ($model->values as $key => $value) {
                if( !isset($tmp["VALUES"][$value->attribute_1]) ) $tmp["VALUES"][$value->attribute_1] = array();
                if( !isset($tmp["VALUES"][$value->attribute_1][$value->attribute_2]) ) $tmp["VALUES"][$value->attribute_1][$value->attribute_2] = array();
                $tmp["VALUES"][$value->attribute_1][$value->attribute_2][$value->attribute_3] = $value->value;
            }
            $this->cache[$i] = $tmp;
        }
        
        return (isset($attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id) && isset($attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id) &&  isset($this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id]))?$this->cache[$i]["VALUES"][$attrs[$this->cache[$i]["ATTRS"]["attr_1"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_2"]]->variant_id][$attrs[$this->cache[$i]["ATTRS"]["attr_3"]]->variant_id]:"";
    }

    public function getVarValue($cube_id){
        $i = "var_".$cube_id;
        if( !isset($this->cache[$i]) ){
            $model = Vars::model()->findByPk($cube_id);
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
        if( $good_type_id != NULL ){
            $criteria = new CDbCriteria();
            $criteria->with = array("goodTypes","variants");
            $criteria->condition = "goodTypes.good_type_id=".$good_type_id." AND dynamic=1";
            $modelDyn = Attribute::model()->findAll($criteria);
            $dynObjects = array();

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

    public function getUserParam($code, $reload = false){
        if( $this->user_settings == NULL || $reload ) $this->getUserSettings();

        $param_code = mb_strtoupper($code,"UTF-8");

        return ( isset($this->user_settings[$param_code]) )?$this->user_settings[$param_code]:NULL;
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

    public function getDromAccount($login = NULL){
        $cols = array(
            46 => "login",
            47 => "password",
            48 => "phone"
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

    public function getImages($good, $number = NULL)
    {   
        $imgs = array();
        $code = $good->fields_assoc[3]->value;
        $path = Yii::app()->params["imageFolder"]."/".$this->type_codes[$good->good_type_id];
        $dir = $path."/".$code;
        if (is_dir($dir)) {
            $imgs = array_values(array_diff(scandir($dir), array('..', '.', 'Thumbs.db', '.DS_Store')));
            $dir = Yii::app()->request->baseUrl."/".$path."/".$code;
            if(count($imgs)) {
                if($number) {
                    for ($i=0; $i < $number; $i++) { 
                        $imgs[$i] = $dir."/".$imgs[$i];
                    }
                } else {
                    foreach ($imgs as $key => &$value) {
                        $value = $dir."/".$value;
                    }
                }           
            } else {
                array_push($imgs, Yii::app()->request->baseUrl."/".$path."/default.jpg");
            }
        }
        else {
            array_push($imgs, Yii::app()->request->baseUrl."/".$path."/default.jpg");    
        }
        return $imgs;
    }

    public function updateRows($table_name,$values = array(),$update){
        $result = true;

        if( count($values) ){
            $query = Yii::app()->db->createCommand("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$table_name."'")->query();

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

            Yii::app()->db->createCommand()->createTable($tmpName, $structure, 'ENGINE=InnoDB');

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

    public function getIds($model){
        $ids = array();
        foreach ($model as $key => $value)
            array_push($ids, $value->id);
        return $ids;
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
}