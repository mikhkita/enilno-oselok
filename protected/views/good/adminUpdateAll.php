<div class="b-popup">
	<h1>Редактирование</h1>
	<?php $this->renderPartial('_form', array('model'=>$model, 'result' => $result, 'only_cities' => true,'cities' => $cities, 'fields' => $fields, 'json_type' => true)); ?>
</div>