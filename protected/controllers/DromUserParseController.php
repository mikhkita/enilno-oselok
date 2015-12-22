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
            array('deny',
                'users'=>array('*'),
            )
        );
    }
    public function actionAdminUsers($debug = false) {
        $this->doQueueNext($debug);
    }

    public function actionAdminIndex() {
        if($_POST['user']) {
            include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';
            $curl = new Curl;
            $html = str_get_html(iconv('windows-1251', 'utf-8',$curl->request('http://baza.drom.ru/user/'.trim($_POST['user']))));
            $user_id = $html->find(".userProfile",0) ? $html->find(".userProfile",0)->getAttribute('data-view-dir-user-id') : NULL;
            $curl->removeCookies();
            if($user_id) {
                $user_name = trim($html->find("span .userNick",0)->plaintext);
                $model = Attribute::model()->with('variants.variant')->find("attribute_id=43 AND value=".$user_id);
                if($model) {
                    $variant_id = $model->variants->variant_id;
                } else {
                    if($variant_id = Variant::add(43,$user_id)) {
                        Dictionary::add(41,intval($variant_id),$user_name); //хуйня какая-то
                    } else return false;
                }
                $drom = new Drom();
                // $count = array();
                $_POST['good_types'] = isset($_POST['good_types']) ? $_POST['good_types'] : array(1,2,3);
                foreach ($_POST['good_types'] as $good_type_id) {
                    // $count[$good_type_id] = 0;
                    $type = GoodType::model()->findByPk($good_type_id)->code;
                    $pages = $drom->parseAllItems('http://baza.drom.ru/user/'.$user_id.'/wheel/'.$type,false);   
                    foreach ($pages as $page) {
                        $last_code = $this->getParam( "OTHER", "PARTNERS_LAST_CODE", true);
                        $params = $drom->parseAdvert($page,$user_id,$good_type_id,$last_code);
                        if($params) {
                            if(Good::addAttributes($params,$good_type_id) === true) {
                                $this->setParam( "OTHER", "PARTNERS_LAST_CODE",($last_code+1));
                                // $count[$good_type_id]++;
                            }
                        }
                    }   
                }
                $drom->curl->removeCookies();
                // echo json_encode($count);
            }
        } else {
            $this->render('adminIndex');
        }
    }

   
    public function doQueueNext($debug = false){
        if( !$this->checkQueueAccess() && !$debug ) return true;

        while( $this->allowed() || $debug ){
            $this->getUsers();
            if( $debug ) return true;
        }
    }

    public function checkQueueAccess(){
        $last = $this->getParam( "OTHER", "DROM_USER_TIME", true );
        return ( time() - intval($last) > 120 );
    }

    public function allowed(){
        $queue = $this->getParam( "OTHER", "DROM_USER_TOGGLE", true );
        return ( trim($queue) == "on" );
    }

}
