<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/advert/adminIndex')?>">Назад</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav">Охват</h1>
<table class="b-table" border="1">
	<? foreach ($model as $i => $type): ?>
	<tr>
		<td class="tleft"><a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminseelist',array("good_type_id"=>$type->id))?>"><?=$type->name?></a></td>
	</tr>
	<? endforeach; ?>
</table>
   
