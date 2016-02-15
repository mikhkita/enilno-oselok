<div class="b-popup">
	<h1>Добавление</h1>

	<?php $this->renderPartial('_form', array('model'=>$model,'result' => $result,'cities' => $cities, 'fields' => $fields, 'good_type_id' => $good_type_id)); ?>
</div>