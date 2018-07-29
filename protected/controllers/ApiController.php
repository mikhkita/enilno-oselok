<?php

class ApiController extends Controller
{
    public function actionLogin($login = NULL, $password = NULL)
    {
        if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
            $this->answer(array("result" => "error", "message" => "Ошибка сервера"));

        $model=new LoginForm;

        if( $login === NULL || $password === NULL || $login == "" || $password == "" )
            $this->answer(array("result" => "error", "message" => "Не указан логин или пароль"));

        $model->attributes = array("username" => $login, "password" => $password);

        if($model->validate() && $model->login()){
            $user = User::model()->with("role")->find("usr_login='$login'");
            $user->prevPass = $user->usr_password;
            $user->prevRole = $user->role->code;
            $user->usr_token = md5(time()."Olololo".rand());
            $user->save();

            $this->answer(array("result" => "success", "user" => array("id" => $user->usr_id, "login" => $user->usr_login, "name" => $user->usr_name, "email" => $user->usr_email, "role" => $user->role->code, "token" => $user->usr_token)));
        }else{
            $this->answer(array("result" => "error", "message" => "Неправильная пара логин-пароль"));
        }
    }

    public function actionSale($token = NULL)
    {
        // $this->auth($token);

        $types = array(
            1 => "Шины",
            2 => "Диски",
            3 => "Колеса"
        );

        $sale = Yii::app()->db->createCommand()
            ->select('s.good_id, s.summ, s.extra, s.date, s.channel_id, s.city, s.order_number, s.tk_id, s.comment, s.photo, s.customer_id, g.good_type_id, f.varchar_value')
            ->from(Sale::tableName().' s')
            ->join(Good::tableName().' g', 's.good_id=g.id')
            ->join(GoodAttributeFilter::tableName().' f', 'g.id=f.good_id')
            ->where("f.attribute_id=3")
            ->order("s.date DESC")
            ->limit(30)
            ->queryAll();

        $customers = array();
        foreach ($sale as $i => $item) {
            $date = date_format(date_create_from_format('Y-m-d H:i:s', $sale[$i]['date']), 'd.m.Y');
            $date = explode(".", $date);
            $sale[$i]["date"] = $date[0]." ".$this->getRussianMonth($date[1])." ".$date[2];

            $images = Good::getImages(NULL, NULL, NULL, (object) array("id" => $sale[$i]["good_id"], "fields_assoc" => array(3 => (object)array("value" => $sale[$i]["varchar_value"])), "good_type_id"=>$sale[$i]["good_type_id"]));
            foreach ($images as $key => $image)
                foreach ($images[$key] as $key2 => $href)
                    $images[$key][$key2] = "http://".Yii::app()->params["host"].$href;

            $sale[$i]["images"] = $images;
            if( $sale[$i]["customer_id"] )
                array_push($customers, $sale[$i]["customer_id"]);
        }

        if( count($customers) ){
            $customers = Yii::app()->db->createCommand()
                ->select('*')
                ->from(Customer::tableName().' c')
                ->where("id in (".implode(",", $customers).")")
                ->queryAll();

            $customers = $this->getAssocByAssoc($customers,"id");
        }

        $channels = DesktopTable::getTable(23,array(
            86 => "name"
        ));

        $tks = DesktopTable::getTable(19,array(
            80 => "name"
        ));

        foreach ($sale as $i => $item) {
            if( $sale[$i]["customer_id"] )
                $sale[$i]["customer"] = $customers[$sale[$i]["customer_id"]];

            if( $sale[$i]["channel_id"] )
                $sale[$i]["channel"] = $channels[$sale[$i]["channel_id"]]["name"];

            if( $sale[$i]["tk_id"] )
                $sale[$i]["tk"] = $tks[$sale[$i]["tk_id"]]["name"];

            $sale[$i]["good_type"] = $types[intval($sale[$i]["good_type_id"])];

            unset($sale[$i]["customer_id"]);
            unset($sale[$i]["channel_id"]);
            unset($sale[$i]["tk_id"]);
        }

        $out = array(
            1 => array(),
            2 => array(),
            3 => array()
        );
        foreach ($sale as $key => $item)
            array_push($out[$item["good_type_id"]], $item);

        $this->answer(array("result" => "success", "sale" => $out));
    }

    public function answer($array){
        header("Access-Control-Allow-Origin: *");

        echo json_encode($array);
        die();
    }

    public function auth($token){
        if( $token === NULL ) $this->answer(array("result" => "error", "message" => "Не указан токен"));

        if( User::model()->count("usr_token='$token'") ){
            return true;
        }else{
            $this->answer(array("result" => "not_authorized"));
        }
    }
}
