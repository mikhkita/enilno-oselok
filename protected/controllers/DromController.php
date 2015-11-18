<?php

class DromController extends Controller
{
   private $drom_params = array(
        1 => array(
            "subject" => array("type" => 'inter',"id" => 8),
            "cityId" => array("type" => 'inter',"id" => 31),
            "goodPresentState" => array("type" => 'inter',"id" => 102),
            "model" => array("type" => 'inter',"id" => 12),
            "inSetQuantity" => array("type" => 'attr',"id" => 28),
            "wheelSeason" => array("type" => 'inter',"id" => 101),
            "wheelTireWear" => array("type" => 'attr',"id" => 29),
            "year" => array("type" => 'attr',"id" => 10),
            "wheelSpike" => array("type" => 'inter',"id" => 104),
            "price" => array("type" => 'inter',"id" => 45),
            "marking" => array("type" => 'inter',"id" => 13),
            "text" => array("type" => 'inter',"id" => 10),
            "pickupAddress" => array("type" => 'inter',"id" => 40),
            "localPrice" => array("type" => 'inter',"id" => 39),
            "minPostalPrice" => array("type" => 'inter',"id" => 38),
            "comment" => array("type" => 'inter',"id" => 72),
            "guarantee" => array("type" => 'inter',"id" => 73),   
        ),
        2 => array(
            "price" => array("type" => 'inter',"id" => 22),
            "subject" => array("type" => 'inter',"id" => 17),
            "cityId" => array("type" => 'inter',"id" => 30),
            "goodPresentState" => array("type" => 'inter',"id" => 103),
            "model" => array("type" => 'inter',"id" => 92),
            "condition" => array("type" => 'inter',"id" => 136),
            "wheelDiameter" => array("type" => 'attr',"id" => 9),
            "inSetQuantity" => array("type" => 'attr',"id" => 28),
            "wheelPcd" => array("type" => 'attr',"id" => 5),
            "price" => array("type" => 'inter',"id" => 22),
            "wheelWeight" => array("type" => 'attr',"id" => 34),
            "diskHoleDiameter" => array("type" => 'attr',"id" => 33),
            "disc_width" => array("type" => 'inter',"id" => 93),
            "disc_et" => array("type" => 'attr',"id" => 32),
            "text" => array("type" => 'inter',"id" => 21),
            "pickupAddress" => array("type" => 'inter',"id" => 41),
            "localPrice" => array("type" => 'inter',"id" => 37),
            "minPostalPrice" => array("type" => 'inter',"id" => 36),
            "comment" => array("type" => 'inter',"id" => 32),
            "guarantee" => array("type" => 'inter',"id" => 29),           
        )
        
    );

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
                'users'=>array('*'),
            ),
        );
    }

    public function actionAdminUpload() {
        $arr = array();
        $i = 0;
        $handle = @fopen(Yii::app()->basePath.'/disc.txt', "r");
        if ($handle) {
            while (($buffer = trim(fgets($handle))) != "") {
                $temp = explode(" ", $buffer);
                $url = end(explode("-", $temp[3]));
                $arr[$i]['url'] = substr($url,0, -5);
                $arr[$i]['place_id'] = 10;
                $arr[$i]['good_id'] = $temp[0];
                $arr[$i]['city_id'] = $temp[1];
                $arr[$i]['type_id'] = $temp[2];
                $i++;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        $handle = @fopen(Yii::app()->basePath.'/tire.txt', "r");
        if ($handle) {
            while (($buffer = trim(fgets($handle))) != "") {
                $temp = explode(" ", $buffer);
                $url = end(explode("-", $temp[3]));
                $arr[$i]['url'] = substr($url,0, -5);
                $arr[$i]['place_id'] = 9;
                $arr[$i]['good_id'] = $temp[0];
                $arr[$i]['city_id'] = $temp[1];
                $arr[$i]['type_id'] = $temp[2];
                $i++;
            }  
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
            $this->insertValues(Advert::tableName(),$arr);
        }
    }
    public function actionAdminIndex(){
        $queue = Queue::model()->with("advert.good.type","advert.place","action")->findByPk(198);
        $advert = $queue->advert;

        // $queue->setState("processing");

        $dynamic = $this->getDynObjects(array(
            57 => $advert->place->category_id,
            38 => $advert->city_id,
            37 => $advert->type_id
        ));

        

        // $fields = array(
        //     'email'=>'vladis1ove81@gmail.com',
        //     'seller_name' => "Владислав",
        //     'phone' => '8 952 896-09-88',
        //     'location_id' => 657600,
        //     'params[733]' => 10359,
        //     'params[734]' => 10376,
        //     'params[731]' => 10312,
        //     'params[732]' => 10340,
        //     'title' => 'Шины для машны',
        //     'description' => 'Шины для машны 123213',
        //     'price' => 1000
        // );

        


        // $avito = new Avito();
        // $avito->setUser("vladis1ove81@gmail.com","Friday13");
        // // unset($fields["login"]);
        // $avito->auth();

        // if( $queue->action->code == "delete" ){
        //     $delete = $avito->deleteAdvert($advert->url);
        //     if($delete){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "add" ){
        //     $id = $avito->addAdvert($fields,$images);

        //     if( $id && $id != "error"){
        //         $advert->setUrl($id);

        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "update" ){
        //     $id = $avito->updateAdvert($advert->url,$fields);
        //     if( $id ){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } else if( $queue->action->code == "updateImages" ){
        //     $id = $avito->updateAdvert($advert->url,$fields,$images);
        //     if( $id ){
        //         // $queue->delete();
        //     }else{
        //         $queue->setState("error");
        //     }
        // } 

        // $avito->curl->removeCookies();

        $fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert->good,$dynamic);
        $fields["contacts"] = '+79994995000';
        $fields = Drom::self()->generateFields($fields,1);
        $images = $this->getImages($advert->good);
        $drom = new Drom();
        $drom->setUser("kitaev123","vtebar8u");
        unset($fields["login"]);
        $drom->auth();

        if( $queue->action->code == "delete" ){

        } else if( $queue->action->code == "add" ){
            $id = $drom->addAdvert($fields,$images,'fixedPrice');
            print_r($id);
            if( $id ){
                $advert->setUrl($id);

                // $queue->delete();
            }else{
                $queue->setState("error");
            }
        } else if( $queue->action->code == "update" ){
            $id = $drom->updateAdvert($advert->url,$fields);
            
            if( $id ){
                $queue->delete();
            }else{
                $queue->setState("error");
            }
        } else if( $queue->action->code == "updateImages" ){
            $id = $drom->updateAdvert($advert->url,$fields,$images);
            
            if( $id ){
                $queue->delete();
            }else{
                $queue->setState("error");
            }
        } 

        $drom->curl->removeCookies();
    }



// Дром ------------------------------------------------------------------ Дром
    public function actionCreate(){
        $good = Good::model()->find("id=1633");
        $images = $this->getImages($good);
        $dynamic = array( 38 => 1081, 37 => 869);

        print_r($this->getParams($good,$dynamic));
        die();
        
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->addAdvert($this->getParams($good,$dynamic),$images);
        $drom->curl->removeCookies();
    }   

    public function actionUpdate($images = NULL){
        $url = "http://tomsk.baza.drom.ru/n1011-letnjaja-para-yokohama-dna-ecos-es300-215-40-17-japan-b-u-38913568.html";
        $advert_id = substr($url, strrpos($url, "-")+1,-5);
        $good = Good::model()->find("id=1181");
        if($images) $images = $this->getImages($good);
        $dynamic = array( 38 => 1081, 37 => 869);
        
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->updateAdvert($advert_id,$this->getParams($good,$dynamic),$images);
        $drom->curl->removeCookies();
    }   

    public function actionDelete(){
        $drom = new Drom();
        $drom->setUser("79528960988","aeesnb33");
        $drom->auth("http://baza.drom.ru/personal/");
        $drom->deleteAdverts(array("38916734","38916795"));
        $drom->curl->removeCookies();
    }   

// Дром ------------------------------------------------------------------ Дром

    public function getParams($good,$dynamic) {
        foreach ($this->drom_params[$good->good_type_id] as $key => $value) {
            if($value['type']=="attr") {
                if(isset($good->fields_assoc[$value['id']])) {
                    if(is_array($good->fields_assoc[$value['id']])) {
                        foreach ($good->fields_assoc[$value['id']] as $i => $item) {
                            if($key=='wheelPcd') {
                                $item = explode("*", $item->value);
                                $item[1] = number_format ($item[1],2);
                                $params[$key][$i] = $item[1]."x".$item[0];
                            }else $params[$key][$i] = $item->value;
                        }
                    } else if($key=='wheelPcd') {
                        $pcd = explode("*", $good->fields_assoc[$value['id']]->value);
                        $pcd[1] = number_format ($pcd[1],2);
                        $params[$key] = $pcd[1]."x".$pcd[0];
                    } else $params[$key] = $good->fields_assoc[$value['id']]->value;
                } else $params[$key] = null;
            } else {
                $params[$key] = Interpreter::generate($value['id'],$good,$this->getDynObjects($dynamic,$good->good_type_id));
            }  
        }
        $params['model'] = array($params["model"],0,0);
        $params['price'] = array($params["price"],"RUB");
        $params['quantity'] = 1;
        $params['contacts'] =  array("email" => "","is_email_hidden" => false,"contactInfo" => "+79528960999");
        $params['delivery'] = array("pickupAddress" => $params['pickupAddress'],"localPrice" => $params['localPrice'],"minPostalPrice" => $params['minPostalPrice'],"comment" => $params['comment']);
        unset($params['pickupAddress'],$params['localPrice'],$params['minPostalPrice'],$params['comment']);

        if($good->good_type_id== "1") {
            $params['dirId'] = 234; 
            $params['predestination'] = "regular";
        }
        if($good->good_type_id== "2") {
            $params['dirId'] = 235; 
            $disc_width = explode("/",$params['disc_width']);
            $params['discParameters'] = array();
            foreach ($disc_width as  $i => $value) {
                $params['discParameters'][$i]["disc_width"] = $value;
                if(is_array($params['disc_et'])) {
                    $params['discParameters'][$i]["disc_et"] = (isset($params['disc_et'][$i])) ? $params['disc_et'][$i] : null;
                } else $params['discParameters'][0]["disc_et"] = $params['disc_et'];
            }  
            if(is_array($params['disc_et']))
            foreach ($params['disc_et'] as  $j => $value) {
                $params['discParameters'][$j]["disc_width"] = (isset($disc_width[$j])) ? $disc_width[$j] : null;
                $params['discParameters'][$j]["disc_et"] = $value;
            }     
            unset($params['disc_width'],$params['disc_et'],$disc_width);
        }
        return $params;
    }

}
