<?php

class ApiController extends Controller
{
    public function actionLogin($login = NULL, $password = NULL)
    {
        if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
            $this->answer(array("result" => "error", "message" => "Ошибка сервера"));

        $model=new LoginForm;

        header("Access-Control-Allow-Origin: *");

        if( $login === NULL || $password === NULL || $login == "" || $password == "" )
            $this->answer(array("result" => "error", "message" => "Не указан логин или пароль"));

        $model->attributes = array("username" => $login, "password" => $password);

        if($model->validate() && $model->login()){
            $user = User::model()->with("role")->find("usr_login='$login'");
            $user->prevPass = $user->usr_password;
            $user->prevRole = $user->role->code;
            $user->usr_token = md5(time()."Olololo".rand());
            $user->save();

            $this->answer(array("result" => "success", "user" => array("id" => $user->usr_id, "login" => $user->usr_login, "name" => $user->usr_name, "email" => $user->usr_email, "role" => $user->role->code)));
        }else{
            $this->answer(array("result" => "error", "message" => "Неправильная пара логин-пароль"));
        }
    }

    public function answer($array){
        echo json_encode($array);
        die();
    }
}
