<a href="<?php echo $this->createUrl('/user/adminCreate')?>" class="ajax-form ajax-create b-butt b-top-butt">Добавить</a>
<h1>Пользователи</h1>
<table class="b-table" border="1">
	<tr>
		<th style="width: 30px;">№</th>
		<th><? echo $labels['usr_login']; ?></th>
		<th style="width: 125px;"><? echo $labels['usr_name']; ?></th>
		<th style="width: 135px;"><? echo $labels['usr_email']; ?></th>
		<th style="width: 135px;"><? echo $labels['usr_rol_id']; ?></th>
		<th style="width: 150px;">Действия</th>
	</tr>
	<? foreach ($data as $i => $item): ?>
		<tr<?if(isset($_GET["id"]) && $item->usr_id == $_GET["id"]):?> class="b-refresh"<?endif;?>>
			<td><? echo $i+1; ?></td>
			<td class="align-left"><? echo $item->usr_login; ?></td>
			<td class="align-left"><? echo $item->usr_name; ?></td>
			<td class="align-left"><? echo $item->usr_email; ?></td>
			<td><? echo $item->role->name; ?></td>
			<td><a href="<?php echo Yii::app()->createUrl('/user/adminUpdate',array('id'=>$item->usr_id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать раздел"></a><a href="<?php echo Yii::app()->createUrl('/user/adminDelete',array('id'=>$item->usr_id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" title="Удалить раздел"></a></td>
		</tr>
	<? endforeach; ?>
</table>
