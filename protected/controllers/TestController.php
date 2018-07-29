<?php

class TestController extends Controller
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
                'roles'=>array('root'),
            ),
        );
    }

    public function actionGetModels(){
        $model = Good::model()->filter(
            array(
                "good_type_id"=>1,
                "attributes"=>array(
                    
                )
            )
        )->getPage(
            array(
                'pageSize'=>10000,
            )
        );
        $goods = $model["items"];

        $marks = array();
        $models = array();
        foreach ($goods as $i => $good) {
            $arr = explode(" ", $good->fields_assoc[16]->value);
            if( count($arr) && !in_array($arr[0], $marks) )
                array_push($marks, $arr[0]);

            if( count($arr) > 1 ){
                array_shift($arr);
                $model = implode(" ", $arr);
                if( !in_array($model, $models) )
                    array_push($models, $model);
            }
        }

        foreach ($models as $key => $value) {
            echo $value."<br>";
        }
    }

    public function actionAdminSitePhoto(){
        $model = Image::model()->findAll("site=1");
        $values = array();
        foreach ($model as $i => $image){
            array_push($values, array("image_id" => $image->id, "cap_id" => 1, "sort" => $image->sort));
            array_push($values, array("image_id" => $image->id, "cap_id" => 2, "sort" => $image->sort));
        }

        Controller::insertValues(ImageCap::tableName(), $values);
    }

    public function actionAdminAvitoPhoto(){
        $model = ImageCap::model()->findAll("cap_id=3");
        $values = array();
        foreach ($model as $i => $image){
            array_push($values, array("image_id" => $image->image_id, "cap_id" => 4, "sort" => $image->sort));
        }

        Controller::insertValues(ImageCap::tableName(), $values);
    }

    public function actionModels(){
        // $model = Good::model()->filter(
        //     array(
        //         "good_type_id"=>1,
        //         "attributes"=>array(
                    
        //         )
        //     )
        // )->getPage(
        //     array(
        //         'pageSize'=>10000,
        //     )
        // );
        // $goods = $model["items"];

        // $values = array();
        // foreach ($goods as $i => $good) {
        //     $arr = explode(" ", $good->fields_assoc[16]->value);
        //     if( count($arr) > 1 ){
        //         if( $model = Variant::model()->with("attributes")->find("attribute_id=17 AND varchar_value='".$model."'") ){
        //             if( isset($good->fields_assoc[17]) ){
        //                 array_push($values, array($good->fields_assoc[17]->id, $good->id, 17, NULL, $model ) );
        //             }else{
        //                 array_push($values, array(NULL, $good->id, 17, NULL, $model ) );
        //             }
        //         }else{
        //             echo $good->fields_assoc[3]->value."<br>";
        //             continue;
        //         }
        //         array_push($values, array($good->fields_assoc[16]->id, $good->id, ) );
        //     }
        // }
    }

    public function actionAdminAutoPost(){
        $drom = new Drom();
        $drom->setUser("beatbox787@gmail.com", "3vsyp25a");
        $res = $drom->auth();

        $text = "Здравствуйте, обмен интересен?";
        $advert_id = "21910583";
        $drom->postComment($advert_id, $text);
    }

    public function actionAdminAvitoLogin(){
        $avito = new Avito("tomsk:Sd8as9fhsd@91.226.83.143:9320");
        $avito->setUser("tomskdiski@yandex.ru", "aksldjfng1");
        $res = $avito->auth();

        print_r($res);
    }
}
