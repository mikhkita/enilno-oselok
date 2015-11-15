<?php

class DesktopController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete','adminTableCreate','adminTableUpdate','adminTableDelete','adminTableIndex','adminTableRowCreate','adminTableRowUpdate','adminTableRowDelete'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Desktop;

		if(isset($_POST['Desktop']))
		{
			$model->attributes=$_POST['Desktop'];
			if($model->save()){
				$this->actionAdminIndex(true,$model->parent_id);
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

		// $this->checkAccess($model);

		if(isset($_POST['Desktop']))
		{
			$model->attributes=$_POST['Desktop'];
			if($model->save())
				$this->actionAdminIndex(true,$model->parent_id,true);
		}else{
			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminDelete($id)
	{
		// $this->checkAccess( Desktop::model()->findByPk($id) );

		$model = $this->loadModel($id);
		$model->delete();

		$this->actionAdminIndex(true,$model->parent_id,true);
	}

	public function actionAdminIndex($partial = false, $id = 1, $editable = false)
	{
		if( !$partial ){
			$this->layout='admin';
		}

        $model = Desktop::model()->with(array("childs","tables"))->findByPk($id);

        // foreach ($model as $key => $value) {
        // 	if(!$this->checkAccess($value,true)) unset($model[$key]);
        // }

		if( !$partial ){
			$this->render('adminIndex',array(
				'folder'=>$model,
				'editable'=>$editable
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'folder'=>$model,
				'editable'=>$editable
			));
		}
	}

	public function actionAdminTableCreate()
	{
		$model=new DesktopTable;

		if(isset($_POST['DesktopTable']))
		{
			$model->attributes=$_POST['DesktopTable'];
			if($model->save()){
				$this->updateCols($model);
				$this->actionAdminIndex(true,$model->folder_id);
				return true;
			}
		}

		$this->renderPartial('adminTableCreate',array(
			'model'=>$model,
		));
	}

	public function actionAdminTableUpdate($id)
	{
		$model=DesktopTable::model()->findByPk($id);

		// $this->checkAccess($model);

		if(isset($_POST['DesktopTable']))
		{
			$model->attributes=$_POST['DesktopTable'];
			if($model->save()){
				$this->updateCols($model);
				$this->actionAdminIndex(true,$model->folder_id,true);
			}
		}else{
			$this->renderPartial('adminTableUpdate',array(
				'model'=>$model,
			));
		}
	}

	public function actionAdminTableDelete($id)
	{
		// $this->checkAccess( Desktop::model()->findByPk($id) );

		$model = DesktopTable::model()->findByPk($id);
		$model->delete();

		$this->actionAdminIndex(true,$model->folder_id,true);
	}

	public function actionAdminTableIndex($partial = false, $id)
	{
		if( !$partial ){
			$this->layout='admin';
		}

        $model = DesktopTable::model()->with(array("cols.type","rows.cells"))->findByPk($id);

		if( !$partial ){
			$this->render('adminTableIndex',array(
				'table'=>$model,
			));
		}else{
			$this->renderPartial('adminTableIndex',array(
				'table'=>$model,
			));
		}
	}

	public function updateCols($model){
		$cols = (isset($_POST["col"]))?$_POST["col"]:array();

		foreach ($model->cols as $col)
			if( !in_array($col->id, $cols) ) $col->delete();

		if( isset($_POST["new_col"]) ){
			foreach ($_POST["new_col"] as $name => $type) {
				$new = new DesktopTableCol();
				$new->name = $name;
				$new->type_id = $type;
				$new->table_id = $model->id;
				$new->save();
			}
		}
	}

	public function actionAdminTableRowCreate($table_id)
	{
		$model=new DesktopTableRow();

		if(isset($_POST['rows']))
		{
			$model->table_id = $table_id;

			if($model->save()){
				$this->updateRow($model);
				$this->actionAdminTableIndex(true,$model->table_id);
				return true;
			}
		}else{
			$table = DesktopTable::model()->with("cols.type")->findByPk($table_id);
			$this->renderPartial('adminTableRowCreate',array(
				'table'=>$table,
				'model'=>$model,
				'cells'=>array()
			));
		}
	}

	public function actionAdminTableRowUpdate($id,$table_id)
	{
		$model=DesktopTableRow::model()->findByPk($id);

		if(isset($_POST['rows']))
		{
			$this->updateRow($model);
			$this->actionAdminTableIndex(true,$model->table_id);
		}else{
			$table = DesktopTable::model()->with("cols.type")->findByPk($table_id);
			$this->renderPartial('adminTableRowUpdate',array(
				'table'=>$table,
				'model'=>$model,
				'cells'=>$this->getAssoc($model->cells,"col_id")
			));
		}
	}

	public function actionAdminTableRowDelete($id)
	{
		$model = DesktopTableRow::model()->findByPk($id);
		$model->delete();

		$this->actionAdminTableIndex(true,$model->table_id);
	}

	public function updateRow($row)
	{
		DesktopTableCell::model()->deleteAll("row_id=".$row->id);
		$table = DesktopTable::model()->with("cols.type")->findByPk($row->table_id);
		$cols = $this->getAssoc($table->cols,"id");

		$values = array();
		foreach ($_POST["rows"] as $col_id => $val) {
			if( $val != "" ){
				$value = array(
					"row_id" => $row->id,
					"col_id" => $col_id,
					"int_value" => NULL,
					"varchar_value" => NULL,
					"text_value" => NULL,
					"time_value" => NULL,
					"variant_id" => NULL,
				);
				$value[$cols[$col_id]->type->code."_value"] = trim($val);
				array_push($values, $value);
			}
		}
		if( count($values) )
			$this->insertValues(DesktopTableCell::tableName(),$values);
	}

	public function loadModel($id)
	{
		$model=Desktop::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
