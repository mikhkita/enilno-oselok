<?php

class ApiController extends Controller
{
    public function actionLogin($login = NULL, $password = NULL)
    {
        if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
            $this->answer(array("result" => "error", "message" => "Ошибка сервера"));

        $model=new LoginForm;

        if( $login === NULL || $password === NULL )
            $this->answer(array("result" => "error", "message" => "Не указан логин или пароль"));

        $model->attributes = array("username" => $login, "password" => $password);

        if($model->validate() && $model->login()){
            $user = User::model()->with("role")->find("usr_login='$login'");
            $user->prevPass = $user->usr_password;
            $user->prevRole = $user->role->code;
            $user->usr_token = md5(time()."Olololo".rand());
            $user->save();

            $this->answer(array("result" => "success", "token" => $user->usr_token));
        }else{
            $this->answer(array("result" => "error", "message" => "Неправильная пара логин-пароль"));
        }
    }

    public function answer($array){
        echo json_encode($array);
        die();
    }
}
