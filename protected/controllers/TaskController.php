<?php

class TaskController extends Controller
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
				'actions'=>array('adminIndex','adminCreate','adminUpdate','adminDelete'),
				'roles'=>array('manager'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionAdminCreate()
	{
		$model=new Task;

		if(isset($_POST['Task']))
		{
			$this->setAttr($model);
		}else{
			$attr = array();

			$allAttr = $this->getFields();

			$this->renderPartial('adminCreate',array(
				'model'=>$model,
				'attr'=> $attr,
				'allAttr'=>$allAttr
			));
		}
	}

	public function actionAdminUpdate($id)
	{
		$model=Task::model()->with("fields.attribute","interpreters.interpreter")->findByPk($id);

		if(isset($_POST['Task']))
		{
			$this->setAttr($model);
		}else{

			$attr = $this->getModelFields($model);
			$allAttr = array_diff_key($this->getFields($model->good_type_id), $attr);

			$this->renderPartial('adminUpdate',array(
				'model'=>$model,
				'allAttr'=>$allAttr,
				'attr'=>$attr
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminIndex(true);
	}

	public function actionAdminIndex($partial = false, $get_next = false, $user_id = NULL, $task_field = NULL, $task_type = NULL)
	{
		if( !$partial ){
			$this->layout = 'admin';
			$this->scripts[] = 'Task';
		}

		if( $task_field !== NULL ){
			$_SESSION["TASK_FIELD"] = $task_field;
		}

		if( $task_type !== NULL ){
			$_SESSION["TASK_TYPE"] = $task_type;
		}

		$order_fields = array(
			"ID" => array(
				"NAME" => "ID"
			),
			"CODE" => array(
				"NAME" => "Коду товара"
			),
			"ACTION" => array(
				"NAME" => "Типу действия"
			),
		);
		$order_types = array(
			"ASC" => array(
				"NAME" => "Возрастанию"
			),
			"DESC" => array(
				"NAME" => "Убыванию"
			),
		);
		if( !isset($_SESSION["TASK_FIELD"]) ){
			$_SESSION["TASK_FIELD"] = "ID";
		}
		if( !isset($_SESSION["TASK_TYPE"]) ){
			$_SESSION["TASK_TYPE"] = "ASC";
		}

		$order_fields[$_SESSION["TASK_FIELD"]]["ACTIVE"] = true;
		$order_types[$_SESSION["TASK_TYPE"]]["ACTIVE"] = true;

		if( $user_id == "my" ){
			unset($_SESSION["task_user_id"]);
			$user_id = NULL;
		}else if( $user_id )
			$_SESSION["task_user_id"] = $user_id;

		$user_id = ( $user_id === NULL )?( (isset($_SESSION["task_user_id"]))?$_SESSION["task_user_id"]:NULL ):$user_id;

		$user_id = ($user_id !== NULL)?$user_id:(($this->user->usr_id == 1)?NULL:$this->user->usr_id);
        $model = Task::model()->filter($user_id, $_SESSION["TASK_TYPE"]);

        $to_delete = array();
        foreach ($model as $task)
        	if( $task->good->archive != 0 || Task::toDelete($task) )
        		array_push($to_delete, $task->id);

        // print_r($to_delete);

        if( count($to_delete) ){
        	Task::model()->deleteAll("id IN (".implode(",", $to_delete).")");

        	$model = Task::model()->filter($user_id);
        }

        if( $_SESSION["TASK_FIELD"] != "ID" ){
        	function task_action($a, $b){
				$a = $a->action_id;
				$b = $b->action_id;

			    if ($a == $b) {
			        return 0;
			    }
			    if( $_SESSION["TASK_TYPE"] == "ASC" ){
			    	return ($a < $b) ? -1 : 1;
			    }else{
			    	return ($a > $b) ? -1 : 1;
			    }
			}

			function task_code($a, $b){
				$a = $a->good->code+"";
				$b = $b->good->code+"";

			    if ($a == $b) {
			        return 0;
			    }
			    if( $_SESSION["TASK_TYPE"] == "ASC" ){
			    	return ($a < $b) ? -1 : 1;
			    }else{
			    	return ($a > $b) ? -1 : 1;
			    }
			}

			switch ($_SESSION["TASK_FIELD"]) {
				case 'ACTION':
					usort($model, "task_action");
					break;

				case 'CODE':
					usort($model, "task_code");
					break;
				
				default:
					# code...
					break;
			}
        }

		if( !$partial ){
			$this->render('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Task::attributeLabels(),
				'get_next'=>$get_next,
				'user_id'=>$user_id,
				'order_fields'=>$order_fields,
				'order_types'=>$order_types,
			));
		}else{
			$this->renderPartial('adminIndex',array(
				'data'=>$model,
				'filter'=>$filter,
				'labels'=>Task::attributeLabels(),
				'get_next'=>$get_next,
				'user_id'=>$user_id,
				'order_fields'=>$order_fields,
				'order_types'=>$order_types,
			));
		}
	}
	
	public function loadModel($id)
	{
		$model=Task::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
