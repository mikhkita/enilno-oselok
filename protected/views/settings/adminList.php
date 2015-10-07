<h1><?=$this->adminMenu["cur"]->name?>: <?if($category){echo $category->name;}else{echo $parent->name;}?></h1>
<a href="<?=$back_link?>" class="b-link-back">Назад</a>
<? if( $this->getUserRole() == "root" ):  ?>
<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate',array((($category)?'category_id':'parent_id')=>($category)?$category->id:$parent->id))?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<? endif; ?>
<?php $form=$this->beginWidget('CActiveForm'); ?>
	<table class="b-table" border="1">
		<tr>
			<th><? echo $labels['name']; ?></th>
			<? if( $this->getUserRole() == "root" ):  ?>
			<th><? echo $labels['code']; ?></th>
			<? endif; ?>
			<th><? echo $labels['value']; ?></th>
			<th style="width: 150px;">Действия</th>
		</tr>
		<? if( count($data) ): ?>
			<? foreach ($data as $i => $item): ?>
				<tr>
					<? if( $item->code ): ?>
						<td class="align-left"><?=$item->name?></td>
						<? if( $this->getUserRole() == "root" ):  ?>
						<td class="align-left"><?=$item->code?></td>
						<? endif; ?>
						<td class="align-left"><?=$this->replaceToBr($this->cutText($item->value))?></td>
					<? else: ?>
						<td class="align-left" colspan="3"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminlist',array('parent_id'=>$item->id))?>"><?=$item->name?></a></td>
					<? endif; ?>
						<td class="b-tool-cont">
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$item->id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
							<? if( $this->getUserRole() == "root" ):  ?>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$item->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
							<? endif; ?>
						</td>
				</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan=10>Пусто</td>
			</tr>
		<? endif; ?>
	</table>
<?php $this->endWidget(); ?>