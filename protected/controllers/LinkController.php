<?php

class LinkController extends Controller
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
				'actions'=>array('adminIndex','countImg'),
				'roles'=>array('manager'),
			),
			array('allow',
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminIndex()
	{
		$this->scripts[] = "link";
		if(isset($_POST['link'])) {
			$directory = explode("\t",$_POST['link']);
			$link = $directory[1];
			$dir_name = $directory[0];
			include_once  Yii::app()->basePath.'/extensions/simple_html_dom.php';
			$html = new simple_html_dom();
			$html = file_get_html($link); 
			$dir = Yii::app()->params["imageFolder"]."/drom/".$dir_name;
			if (!is_dir($dir)) mkdir($dir,0777, true);
			$page_img = $html->find('.bulletinImages .image img');
			if( $this->countImg($dir) != count($page_img) ) {
				foreach ($page_img as $i => $item) {
				$src = ($item->getAttribute("data-zoom-image")) ? $item->getAttribute("data-zoom-image") : $item->src;
					copy( $src, $dir."/".$dir_name."_".sprintf("%'.02d", $i).".jpg");
	  			}
	  			if( $this->countImg($dir) ) {
	  				echo "1";
	  			}	else {
	  				echo "0";
	  			}
	  		} else {
	  			echo "2";
	  		}
		} else {
			$this->render('adminIndex');
		}
	}

	public function countImg($path) {
		$dir = opendir ("$path");
		$i = 0;
		while (false !== ($file = readdir($dir))) {
		    if (strpos($file, '.jpg', 1)) $i++;
		}
		closedir($dir);
		return $i;
	}

	public function actionAdminLinkParse()
	{
		$this->scripts[] = "link";
		if(isset($_POST['link'])) {
			include_once  Yii::app()->basePath.'/simple_html_dom.php';
			$html = new simple_html_dom();
			$html = file_get_html($_POST['link']); 
			$url = array_pop(explode("/",$_POST['link']));
			$url = str_replace(".html","",$url);
			$dir = Yii::app()->params["imageFolder"]."/links/".$url;
			if (!is_dir($dir)) mkdir($dir,0777, true);
			$imgs = array_values(array_diff(scandir($dir), array('..', '.', 'Thumbs.db')));
			if(!count($imgs)) {
				foreach ($html->find('div[class=old_lot_images] img') as $i => $item) { 
					copy( $item->src, $dir."/".$url."_".$i.".jpg");
	  			}
	  			$imgs = array_values(array_diff(scandir($dir), array('..', '.', 'Thumbs.db')));
	  			if(count($imgs)) {
	  				echo "1";
	  			}	else {
	  				echo "0";
	  			}
	  		} else {
	  			echo "2";
	  		}
		} else {
			$this->render('adminIndex');
		}
	}

}
