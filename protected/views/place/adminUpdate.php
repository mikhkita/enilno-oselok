<div class="b-popup">
	<h1>Редактирование <?=$this->adminMenu["cur"]->rod_name?> "<?=$model->category->name?>:<?=$model->goodType->name?>"</h1>

	<?php $this->renderPartial('_form', array('model'=>$model,'inter'=>$inter,'allInter'=>$allInter,'new'=>false)); ?>
</div>