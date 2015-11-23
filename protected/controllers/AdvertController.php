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
				'actions'=>array('adminIndex'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex($partial = false)
	{
		
		$model = Place::model()->findAll();
		$data = array();
		$pages;
		foreach ($model as $key => $item) {
			$data['Place'][$item->id] = $item->category->value." ".$item->goodType->name;
		}
		$model = Attribute::model()->findAllByPk(array(37,58));
		foreach ($model as $key => $item) {
			$data['AttrName'][$item->id] = $item->name;
			foreach ($item->variants as $variant) {
				$data['Attr'][$variant->attribute_id][$variant->variant_id] = $variant->value;		
			}
		}
		$data['Attr'][58] = $this->splitByRows(5,$data['Attr'][58]);
		
		if($_GET) {
			$dataProvider = Advert::getAdverts($_GET);
			$pages = $dataProvider->getPagination();
			foreach ($dataProvider->getData() as $advert) {
				if( !isset($adverts[$advert->place->category->value]) ) $adverts[$advert->place->category->value] = array();
				array_push($adverts[$advert->place->category->value], $advert);
			}
			

		}
		if( !$partial ){
			$this->render('adminIndex',array(
				'adverts' => $adverts,
				'data'=>$data,
				"pages" => $pages,
				'labels'=> Advert::attributeLabels()
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'adverts' => $adverts,
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
