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
			$good_type_id = array();
			$criteria = new CDbCriteria();
			if(isset($_GET['Place'])) {
		    	$criteria->addInCondition("place_id",$_GET['Place']);
		    	$model = Place::model()->findAll('id IN ('.implode(",", $_GET['Place']).')');
				foreach ($model as $key => $place) {
					$good_type_id[$place->goodType->id] = $place->goodType->id;
				}
			}
			if(isset($_GET['Codes']) && $_GET['Codes']) {
				$arr = explode(PHP_EOL,$_GET['Codes']);
				foreach ($arr as $key => $value) {
					$arr[$key] = trim($value);
				}
				$criteria->addInCondition("good_id",Good::getIdbyCode($arr,$good_type_id));
			}
			if(isset($_GET['Attr'][37])) {
		    	$criteria->addInCondition("type_id",$_GET['Attr'][37]);
		    }
			if(isset($_GET['Attr'][58])) {
		    	$criteria->addInCondition("city_id",$_GET['Attr'][58]);
		   	}
			$dataProvider = new CActiveDataProvider('Advert', array(
			    'criteria'=>$criteria,
			    'pagination'=> array(
			    	'pageSize' => 20
			    ),
			));
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
