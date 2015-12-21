<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a href="<?php echo $this->createUrl('/advert/adminupdrom')?>" class="ajax-form ajax-update">Поднять</a></li>
			<li><a>Обновить</a>
				<ul class="b-section-submenu">
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "update"))?>" class="ajax-form ajax-update">Без&nbsp;фотографий</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "updateWithImages"))?>" class="ajax-form ajax-update">С&nbsp;фотографиями</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "updateImages"))?>" class="ajax-form ajax-update">Только&nbsp;Фотографии</a></li>
				</ul>
			</li>
			<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "delete"))?>" class="ajax-form ajax-update">Удалить</a></li>
		</ul>
	</div>
</div>
<h1 class="b-with-nav"><?=$this->adminMenu["cur"]->name?></h1>
<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");  
$form=$this->beginWidget('CActiveForm',array('id'=>'adverts-form',"action" => $this->createUrl('/advert/adminindex'),"method" => "GET")); ?>
<div class="b-advert-filter clearfix">
	<div class="left">
		<div class="b-advert-block">
			<h3>Площадка</h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Place[]", $_GET['Place'], $data['Place'],$htmlopt); ?>
			</div>
		</div>
		<div class="b-advert-block">
			<h3><?=$data['AttrName'][37]?></h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[37]", $_GET['Attr'][37], $data['Attr'][37],$htmlopt);?>
			</div>
		</div>
		<h3><?=$data['AttrName'][58]?></h3>
		<div class="b-group-vars">
	        <? foreach ($data['Attr'][58] as $key => $vars): ?>
	            <div class="b-group-col">
	                <?=CHTML::checkBoxList("Attr[58]", $_GET['Attr'][58], $vars, array("separator"=>"","baseID"=>"arr".$key,"template"=>"<div>{input}{label}</div>")); ?>
	            </div>
	        <? endforeach; ?>
	    </div>
	</div>
	<div class="b-advert-textarea">
		<textarea name="Codes"><?=$_GET['Codes']?></textarea>
	</div>
</div>	
<div class="clearfix">
	<?php echo CHtml::submitButton("Поиск",array("class"=> "b-butt advert-butt")); ?>
</div>
<div id="adverts-form-hidden" style="display:none;"></div>
<p><br>Найдено объявлений: <?=$advert_count?></p>
<?php $this->endWidget(); ?>
	<table class="b-table b-advert-table" border=1>
		<tr>
			<th>Код</th>
			<th><?=$labels["place_id"]?></th>
			<th><?=$labels["type_id"]?></th>
			<th><?=$labels["city_id"]?></th>
			<th><?=$labels["url"]?></th>
		</tr>
		<?if ($adverts_arr):?>
			<? foreach ($adverts_arr as $name => $place): ?>
				<? foreach ($place as $code => $adverts): ?>
					<? foreach ($adverts as $advert): ?>
					<tr>
						<td><?=$code?></td>
						<td><?=$name?></td>
						<td><?=$advert->type->value?></td>
						<td><?=$advert->city->value?></td>
						<td>
							<? if($advert->url): ?>
							<a href="<?=$advert->getUrl();?>" target="_blank"><?=$advert->getUrl();?></a>
							<? endif; ?>
						</td>
					</tr>
					<? endforeach; ?>
				<? endforeach; ?>
			<? endforeach; ?>
		<? else:?>
			<tr>
				<td colspan="5">Пусто</td>
			</tr>
		<? endif; ?>
	</table>
	<div class="b-pagination-cont clearfix">
        <?php $this->widget('CLinkPager', array(
	        'header' => '',
	        'lastPageLabel' => 'последняя &raquo;',
	        'firstPageLabel' => '&laquo; первая', 
	        'pages' => $pages,
	        'prevPageLabel' => '< назад',
	        'nextPageLabel' => 'далее >'
	    )) ?>
	
        <div class="b-lot-count">Найдено объявлений: <?=$advert_count?></div>
    </div>
   
