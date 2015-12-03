<h1><?=$this->adminMenu["cur"]->name?></h1>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<? foreach ($model as $i => $type): ?>
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist',array("Interpreter[good_type_id]"=>$type->id))?>"><?=$type->name?></a></td>
		</tr>
		<? endforeach; ?>
		<tr>
			<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist')?>">Все</a></td>
		</tr>
	</table>
<?php $this->endWidget(); ?>