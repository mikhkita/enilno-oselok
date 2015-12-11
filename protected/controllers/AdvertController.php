<?php

class AdvertController extends Controller
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
				'actions'=>array('adminIndex','adminPayAdverts'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminPayAdverts(){
		if( isset($_GET) ){
			$adverts = Advert::getAdverts($_GET,array('type','city','place.category'),array("id"))->getData();
			$interval = isset($_GET["interval"])?intval($_GET["interval"])*60:0;
			$offset = isset($_GET["offset"])?intval($_GET["offset"])*60:0;
			Queue::addAll($adverts,"payUp",$offset,$interval);

			$this->redirect('/admin/queue/');
		}
	}

	public function actionAdminIndex($partial = false, $good_type_id = false)
	{
		if( $good_type_id !== false ){
			$_GET["Codes"] = implode("\n", Good::getCheckboxes($good_type_id));
			unset($_GET["good_type_id"]);
		}
		
		$model = Place::model()->with('category','goodType')->findAll();
		$data = array();
		$pages;
		foreach ($model as $key => $item) {
			$data['Place'][$item->id] = $item->category->value." ".$item->goodType->name;
		}
		$model = Attribute::model()->with('variants')->findAllByPk(array(37,58));
		foreach ($model as $key => $item) {
			$data['AttrName'][$item->id] = $item->name;
			foreach ($item->variants as $variant) {
				$data['Attr'][$variant->attribute_id][$variant->variant_id] = $variant->value;		
			}
		}
		$data['Attr'][58] = $this->splitByCols(5,$data['Attr'][58]);
		
		if($_GET) {
			$dataProvider = Advert::getAdverts($_GET,array('type','city','place.category'));
			$pages = $dataProvider->getPagination();
			$temp = array();
			foreach ($dataProvider->getData() as $advert) {
				array_push($temp, $advert->good_id);
			}
			$temp = GoodAttribute::getCodeById($temp);
			foreach ($dataProvider->getData() as $i => $advert) {
				if( !isset($adverts_arr[$advert->place->category->value]) ) $adverts_arr[$advert->place->category->value] = array();
				if( !isset($adverts_arr[$advert->place->category->value][$temp[$advert->good_id]]) ) $adverts_arr[$advert->place->category->value][$temp[$advert->good_id]] = array();
				array_push($adverts_arr[$advert->place->category->value][$temp[$advert->good_id]], $advert);
			}
			

		}
		if( !$partial ){
			$this->render('adminIndex',array(
				'adverts_arr' => $adverts_arr,
				'data'=>$data,
				"pages" => $pages,
				'labels'=> Advert::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'adverts_arr' => $adverts_arr,
				'data'=>$data,
				"pages" => $pages,
				'labels'=> Advert::attributeLabels()
			));
		}
	}

	public function loadModel($id)
	{
		$model=Advert::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
