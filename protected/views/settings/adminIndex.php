<h1><?=$this->adminMenu["cur"]->name?></h1>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincategorycreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<? if( $this->getUserRole() == "root" ):  ?>
			<tr>
				<th><? echo $labels['name']; ?></th>
				<th><? echo $labels['code']; ?></th>
				<th style="width: 150px;">Действия</th>
			</tr>
		<? endif; ?>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<td class="align-left"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist',array('id'=>$item->id))?>"><?=$item->name?></a></td>
					<? if( $this->getUserRole() == "root" ):  ?>
					<td class="align-left"><?=$item->code?></td>
					<td class="b-tool-cont">
						<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admincategoryupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
					</td>
					<? endif; ?>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>