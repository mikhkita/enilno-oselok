<?php

class DromUserParseController extends Controller
{

    public function filters()
    {
        return array(
                'accessControl'
            );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('adminIndex'),
                'roles'=>array('manager'),
            ),
            array('allow',
                'actions'=>array('parse'),
                'users'=>array('*'),
            ),
            array('deny',
                'users'=>array('*'),
            )
        );
    }
    public function actionAdminUsers($debug = false) {
        $this->doQueueNext($debug);
    }

    public function actionAdminIndex($alert = false) {
        if($_POST['user'] && $_POST['good_types']) {
            include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
            $curl = new Curl;
            $html = str_get_html(iconv('windows-1251', 'utf-8',$curl->request('http://baza.drom.ru/user/'.trim($_POST['user']))));
            $user_id = $html->find(".userProfile",0) ? $html->find(".userProfile",0)->getAttribute('data-view-dir-user-id') : NULL;
            $curl->removeCookies();
            if($user_id) {
                $model = Attribute::model()->with('variants.variant')->find("attribute_id=43 AND value=".$user_id);
                if(!$model) {
                    if($variant_id = Variant::add(43,$user_id)) {
                        $user_name = trim($html->find("span.userNick",0)->plaintext);
                        if($user_id != $user_name) Dictionary::add(41,$variant_id,$user_name);
                    } else return false;
                }
               	$drom = new Drom(); 	
		        foreach ($_POST['good_types'] as $good_type_id) {
                    $links = array();
		            $type = GoodType::model()->findByPk($good_type_id)->code;
		            $pages = $drom->parseAllItems('http://baza.drom.ru/user/'.$user_id.'/wheel/'.$type, $user_id, false);   
		            foreach ($pages as $page) {
		            	array_push($links, "http://".Yii::app()->params['host'].$this->createUrl('/dromUserParse/parse',array('page'=> $page,'user_id' => $user_id)));
		            }
                    Cron::addAll($links);
		        }
		        $drom->curl->removeCookies();
                Yii::app()->user->setFlash('message','Товары пользователя добавлены в очередь');
                $this->refresh();
		    }
        } else if($_POST['links']){
            $arr = explode(PHP_EOL,$_POST['links']);
            foreach ($arr as $key => &$value) {
                $item = explode(" ", $value); 
                $value = "http://".Yii::app()->params['host'].$this->createUrl('/dromUserParse/parse',array('page'=> trim($item[0]),'code' => trim($item[1])));
            }
            Cron::addAll($arr);
            Yii::app()->user->setFlash('message','Товар(ы) добавлен(ы) в очередь');
            $this->refresh();
        } else $this->render('adminIndex');
    }
    public function actionParse($page,$user_id = NULL,$code = NULL) {
    	$page = urldecode($page);	
    	$drom = new Drom();
        $good_code = ($code === NULL) ? $this->getParam( "OTHER", "PARTNERS_LAST_CODE", true) : $code;
        $params = $drom->parseAdvert($page,$good_code,$user_id);
        $drom->curl->removeCookies();
        if($params) {
            if(Good::addAttributes($params,$params[0]) === true) {
                if($code === NULL) $this->setParam( "OTHER", "PARTNERS_LAST_CODE",($good_code+1));
                echo json_encode(array("result" => "success"));
            } else echo json_encode(array("result" => "error","message" => "Ошибка при добавлении товара"));
        } else if($params === false) {
                echo json_encode(array("result" => "success")); 
            } else echo json_encode(array("result" => "error","message" => "Ошибка при парсинге товара")); 

    }

}
