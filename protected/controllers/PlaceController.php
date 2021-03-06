<?php

class PlaceController extends Controller
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
				'actions'=>array('adminDelete'),
				'roles'=>array('root'),
			),
			array('allow',
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminPreview','adminDrom','adminTest'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Place;

		if(isset($_POST['Place']))
		{
			if(!Place::model()->count("category_id='".$_POST["Place"]["category_id"]."' AND good_type_id='".$_POST["Place"]["good_type_id"]."'")){
				$model->attributes=$_POST['Place'];
				if($model->save()){
					$this->actionAdminIndex(true);
					return true;
				}
			}else{
				echo "Такая площадка уже существует";
			}
		}else{
			$this->renderPartial('adminCreate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminTest(){
		print_r($this->getDromAccount("wheels70"));
	}

	public function actionAdminUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Place']))
		{
			$tmp = Place::model()->with(array("interpreters"))->find("category_id='".$_POST["Place"]["category_id"]."' AND good_type_id='".$_POST["Place"]["good_type_id"]."'");
			if( !(isset($tmp) && $tmp->id != $id) ){
				$model->attributes=$_POST['Place'];

				$this->updateInters($model);

				if($model->save())
					$this->actionAdminIndex(true);
			}else{
				echo "Такая площадка уже существует";
			}
		}else{
			$this->renderPartial('adminUpdate',array(
				'model' => $model,
				'inter' => $model->interpreters,
				'allInter' => Interpreter::model()->findAll(array("order"=>"name ASC","condition"=>"good_type_id=".$model->good_type_id." AND service=0")),
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
		$filter = new Place('filter');
		$criteria = new CDbCriteria();

		// Good::model()->find("good_type_id=2")->update();

		if (isset($_GET['Place']))
        {
            $filter->attributes = $_GET['Place'];
            foreach ($_GET['Place'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 'id ASC';

        $model = Place::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Place::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Place::attributeLabels()
			));
		}
	}

	public function actionAdminDrom(){
		$queue = Queue::model()->with("advert.good.type","advert.place","action")->findByPk(156);
		$advert = $queue->advert;

		$queue->setState("processing");

		$dynamic = $this->getDynObjects(array(
			57 => $advert->place->category_id,
			38 => $advert->city_id,
			37 => $advert->type_id
		));

		$fields = Place::getValues(Place::getInters($advert->place->category_id,$advert->good->type->id),$advert->good,$dynamic);
		$fields = Drom::self()->generateFields($fields,1);
		$images = $this->getImages($advert->good);

		$drom = new Drom();
        $drom->setUser($fields["login"][0],$fields["login"][1]);
        unset($fields["login"]);
      	$drom->auth();

		if( $queue->action->code == "delete" ){

		}else{
			if( $queue->action->code == "add" ){
				$id = $drom->addAdvert($fields,$images);

				if( $id ){
		        	$advert->setUrl($id);

		        	$queue->delete();
		        }else{
		        	$queue->setState("error");
		        }
			}
		}

		$drom->curl->removeCookies();
	}

	public function updateInters($model){
		PlaceInterpreter::model()->deleteAll("place_id=".$model->id);

		if( isset($_POST["inter"]) ){
			$values = array();
			foreach ($_POST["inter"] as $key => $value) {
				$values[] = array("place_id"=>$model->id, "interpreter_id"=>$key, "code"=>$value);
			}
			$this->insertValues(PlaceInterpreter::tableName(),$values);
		}
	}

	public function loadModel($id)
	{
		$model=Place::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
