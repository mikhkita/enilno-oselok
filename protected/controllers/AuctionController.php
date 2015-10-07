<?php

class AuctionController extends Controller
{
	public $minutes_before = 3;
	public $tryes = 9;

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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminRefresh','adminArchive','adminArchiveBack','adminLive','adminArchiveAll'),
				'roles'=>array('root'),
			),
			array('allow',
				'actions'=>array('adminCheck','adminDelete'),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Auction;

		if(isset($_POST['Auction']))
		{
			$model->attributes=$_POST['Auction']+Injapan::getFields($_POST['Auction']['code'],$_POST['Auction']['price'])["main"];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}

		$this->renderPartial('adminCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Auction']))
		{
			if($this->update($model,$_POST['Auction']))
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new Auction('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Auction']))
        {
            $filter->attributes = $_GET['Auction'];
            foreach ($_GET['Auction'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->addCondition("archive = '".( isset($_GET["archive"])?1:0 )."'");

        $criteria->order = 'date ASC';

        $model = Auction::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Auction::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Auction::attributeLabels()
			));
		}
	}

	public function actionAdminCheck()
	{
		Log::debug("Начало проверки");
		$yahon = new Yahon();

		$model = Auction::model()->findAll(array("condition"=>"state=0 AND archive=0 AND date<'".date("Y-m-d H:i:s", time()+$this->minutes_before*60)."'"));
		// $model = Auction::model()->findAll(array("condition"=>"state=0 AND date<'".date("Y-m-d H:i:s", strtotime("2015-07-20 19:21:00")+$this->minutes_before*60)."'"));
		foreach ($model as $key => $auction) {
			$fields = NULL;
			$fields = Injapan::getFields($auction->code, $auction->price, $auction->state);

			if( intval($fields["main"]["state"]) == 0 ){ // ПОПРАВИТЬ НА 0
				if( strtotime($fields["main"]["date"]) < time()+$this->minutes_before*60 ){
				// if( strtotime($fields["main"]["date"]) < strtotime("2015-07-20 19:21:00")+$this->minutes_before*60 ){
					if( !$yahon->isAuth() ) $yahon->auth();

					$fields = $this->setBid($auction,$fields,$yahon);
				}
			}
			$auction->attributes = $fields["main"];
			$auction->save();
		}

		$model = Auction::model()->findAll(array("condition"=>"state=2 AND archive=0 AND date<'".date("Y-m-d H:i:s", time()+$this->minutes_before*60)."'"));
		// $model = Auction::model()->findAll(array("condition"=>"state=2 AND date<'".date("Y-m-d H:i:s", strtotime("2015-07-20 19:21:00")+$this->minutes_before*60)."'"));
		foreach ($model as $key => $auction) {
			$this->update($auction);
		}
		Log::debug("Конец проверки");
	}

	public function actionAdminLive()
	{
		$model = Auction::model()->findAll(array("condition"=>"archive=0 AND date<'".date("Y-m-d H:i:s", time()+15*60)."'"));
		
		$out = array();
		foreach ($model as $key => $auction) {
			$item = array();
			$item["id"] = $auction->id;
			$item["date"] = $auction->date;
			$item["current_price"] = $auction->current_price;
			$item["state"] = Auction::model()->states[$auction->state];
			array_push($out, $item);
		}
		echo json_encode($out);
	}

	public function setBid($auction,$fields,$yahon){
		$tog = false;
		$counter = 0;
		do{
			$counter++;

			// Если 5 раз пробовали уже ставить ставку, то на 6-й раз ставим максимум
			$cur_price = ($counter == $this->tryes+1)?(intval($auction->price)-intval($fields["other"]["step"])):$fields["main"]["current_price"];
			
			$result = $yahon->setBid($auction->code,$cur_price,$fields["other"]["step"],$auction->price);
			Log::debug("Первый раз вернуло state=".$result["result"]."; cur_price = ".$cur_price);
			if( $result["result"] == 2 || $result["result"] == 0 ){
				$fields = Injapan::getFields($auction->code, $auction->price, $auction->state);

				Log::debug("Ставили: ".$result["price"]."; Сейчас: ".$fields["main"]["current_price"]);
				if( intval($fields["main"]["current_price"]) <= intval($result["price"]) ){
					Log::sniper("Лот ".$auction->code.". Поставили ставку ".$result["price"].". Текущая цена ".$fields["main"]["current_price"]);
					$fields["main"]["state"] = 2;
					$tog = true;
				}else{
					Log::sniper("Лот ".$auction->code.". Нашу ставку в ".$result["price"]." перебили ценой ".$fields["main"]["current_price"]);
					$tog = ($counter == $this->tryes+1)?true:false;
				}
			}else{
				$fields["main"]["state"] = $result["result"];
				$tog = true;
			}
			Log::debug("Цикл завершился");
		}while(!$tog);

		return $fields;
	}

	public function actionAdminRefresh($id){
		$model=$this->loadModel($id);
		if($this->update($model))
			$this->actionAdminIndex(true);
	}

	public function actionAdminArchive($id){
		$model=$this->loadModel($id);

		$model->archive = 1;
		if($model->save())
			$this->actionAdminIndex(true);
	}

	public function actionAdminArchiveAll(){
		$model= Auction::model()->findAll(array("condition" => "archive=0"));

		foreach ($model as $key => $auction) {
			$auction->archive = 1;
			$auction->save();	
		}
		
		$this->actionAdminIndex();
	}

	public function actionAdminArchiveBack($id){
		$model=$this->loadModel($id);

		$model->archive = 0;
		if($this->update($model))
			$this->actionAdminIndex(true);
	}

	public function update($auction,$params = NULL){
		$fields = Injapan::getFields($auction->code, ($params==NULL)?$auction->price:$params["price"], $auction->state);

		if( $params !== NULL )
			$fields["main"] = $fields["main"]+$params;

		if( intval($auction->state) == 2 && intval($fields["main"]["current_price"]) <= intval($auction->current_price) && intval($fields["main"]["state"]) != 6 && intval($fields["main"]["state"]) != 3  )
			$fields["main"]["state"] = 2;

		$auction->attributes = $fields["main"];
		return $auction->save();
	}

	public function loadModel($id)
	{
		$model=Auction::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
