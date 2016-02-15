<div class="b-popup">
	<h1>Редактирование</h1>
	<?php $this->renderPartial('_form', array('model'=>$model,'result' => $result, 'only_cities' => false,'cities' => $cities, 'fields' => $fields, 'view_fields' => $view_fields, 'good_type_id' => $good_type_id)); ?>
</div>