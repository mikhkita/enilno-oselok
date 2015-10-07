<h1><?=$this->adminMenu["cur"]->name?></h1>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminvars')?>">Переменные</a></td>
		</tr>
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admindictionary')?>">Списки</a></td>
		</tr>
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admintable')?>">Таблицы</a></td>
		</tr>
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincube')?>">Трехмерные массивы</a></td>
		</tr>
	</table>
<?php $this->endWidget(); ?>