<?php

class SettingsController extends Controller
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
				'actions'=>array("adminYahooCategoryCreate","adminYahooCategoryUpdate","adminYahooCategoryDelete"),
				'roles'=>array('root'),
			),
			array('allow',
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminList',"adminCategoryCreate","adminCategoryUpdate"),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminYahooCategoryCreate()
	{
		$model=new YahooCategory;

		if(isset($_POST['YahooCategory']))
		{
			$model->attributes=$_POST['YahooCategory'];
			if($model->save()){
				$this->actionAdminList(13,false,true);
				return true;
			}
		}

		$this->renderPartial('adminYahooCategoryCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminYahooCategoryUpdate($id)
	{
		$model=YahooCategory::model()->findByPk($id);

		if(isset($_POST['YahooCategory']))
		{
			$model->attributes=$_POST['YahooCategory'];
			if($model->save())
				$this->actionAdminList(13,false,true);
		}else{
			$this->renderPartial('adminYahooCategoryUpdate',array(
				'model'=>$model,
			));
		}
	}
	public function actionAdminYahooCategoryIndex($partial = false,$back_link = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
		$filter = new YahooCategory('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['YahooCategory']))
        {
            $filter->attributes = $_GET['YahooCategory'];
            foreach ($_GET['YahooCategory'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 'name ASC';

        $model = YahooCategory::model()->findAll($criteria);

		if( !$partial ){
			$this->render('adminYahooCategoryIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'back_link'=>$back_link,
				'labels'=>YahooCategory::attributeLabels()
			));
		}else{
			$this->renderPartial('adminYahooCategoryIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'back_link'=>$back_link,
				'labels'=>YahooCategory::attributeLabels()
			));
		}
	}

	public function actionAdminYahooCategoryDelete($id)
	{
		$model=YahooCategory::model()->findByPk($id);
		$model->delete();

		$this->actionAdminList(13,false,true);
	}

	public function actionAdminCategoryCreate()
	{
		$model=new Category;

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save()){
				$this->actionAdminIndex(true);
				return true;
			}
		}

		$this->renderPartial('adminCategoryCreate',array(
			'model'=>$model,
		));

	}

	public function actionAdminCategoryUpdate($id)
	{
		$model=Category::model()->findByPk($id);

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save())
				$this->actionAdminIndex(true);
		}else{
			$this->renderPartial('adminCategoryUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminCreate()
	{
		$model=new Settings;

		if(isset($_POST['Settings']))
		{
			$model->attributes=$_POST['Settings'];
			if($model->save()){
				$this->actionAdminList($model->parent_id,$model->category_id,true);
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

		$this->checkAccess($model);

		if(isset($_POST['Settings']))
		{
			$model->attributes=$_POST['Settings'];
			if($model->save())
				$this->actionAdminList($model->parent_id,$model->category_id,true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->checkAccess( Settings::model()->findByPk($id) );

		$model = $this->loadModel($id);
		$model->delete();

		$this->actionAdminList($model->parent_id,$model->category_id,true);
	}

	public function actionAdminIndex($partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}
  
		$model = Category::model()->findAll(array("order"=>'sort ASC'));

		$option = array(
			'data'=>$model,
			'labels'=>Category::attributeLabels()
		);
		if( !$partial ){
			$this->render('adminIndex',$option);
		}else{
			$this->renderPartial('adminIndex',$option);
		}
	}

	public function actionAdminList($parent_id = false,$id = false,$partial = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}

		if( $id ){
        	$category = Category::model()->findByPk($id);
        }else if( $parent_id ){
        	$parent = Settings::model()->find("id=".$parent_id);
        }

		$filter = new Settings('filter');
		$criteria = new CDbCriteria();

		if (isset($_GET['Settings']))
        {
            $filter->attributes = $_GET['Settings'];
            foreach ($_GET['Settings'] AS $key => $val)
            {
                if ($val != '')
                {
                    $criteria->addSearchCondition($key, $val);
                }
            }
        }

        $criteria->order = 'sort ASC';

        if( $id ){
        	$criteria->addSearchCondition('category_id', $id);
        	$back_link = $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex');
        }else if( $parent_id ){
        	$criteria->addSearchCondition('parent_id', $parent_id);

        	if( $parent->parent_id == 0 ){
        		$back_link = $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist',array('id'=>$parent->category_id));
        	}else{
        		$back_link = $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist',array('parent_id'=>$parent->parent_id));
        	}
        }
  
		$model = Settings::model()->findAll($criteria);

		foreach ($model as $key => $item)
			if(!$this->checkAccess($item,true)) unset($model[$key]);

		$option = array(
			'data'=>$model,
			'filter'=>$filter,
			'back_link'=>$back_link,
			'labels'=>Settings::attributeLabels()
		);

		if( $id ){
        	$option['category'] = $category;
        }else if( $parent_id ){
        	$option['parent'] = $parent;
        }

        if( $parent_id == 13 ){
        	$this->actionAdminYahooCategoryIndex($partial,$back_link);
        }else{
        	if( !$partial ){
				$this->render('adminList',$option);
			}else{
				$this->renderPartial('adminList',$option);
			}
        }

	}

	public function loadModel($id)
	{
		$model=Settings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
