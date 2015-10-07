<h1><?=$this->adminMenu["cur"]->name?></h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-step1',
	'action' => Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminstep2'),
	'enableAjaxValidation'=>false
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<div>
		<div class="b-choosable" style="width: 200px;">
			<h3>Тип товара</h3>
			<ul class="b-choosable-values">
			<? foreach ($model as $i => $item): ?>
				<li <? if($i == 0): ?>class="selected" <? endif; ?>data-id="<?=$item->id?>"><?=$item->name?></li>
			<? endforeach; ?>
			</ul>	
		</div>
	</div>
	<a href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>1,'extensions'=>'xls,xlsx', 'title' => 'Загрузка файла "Excel"', 'selector' => '.b-excel-input') ); ?>" class="b-get-image b-get-xls" ><img class="b-import-image" src="/images/excel.png" alt=""><span>Загрузить файл</span></a>
	<input type="hidden" name="excel_name" class="b-excel-input">
	<div>
		<a href="#" id="b-next" onclick="$('#import-step1').submit();" class="hidden b-import-butt b-butt">Импортировать</a>
	</div>
	<input type="hidden" name="GoodTypeId" id="GoodTypeId">
<?php $this->endWidget(); ?>


