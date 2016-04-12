<div class="b-section-nav clearfix">
	<div class="b-section-nav-back clearfix">
		<ul style="border-left: 0px;" class="b-section-menu clearfix left">
			<li><a>Поднять</a>
				<ul class="b-section-submenu">
					<li><a href="<?php echo $this->createUrl('/advert/adminupdrom')?>" class="ajax-form ajax-update">Дром&nbsp;платные</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminupavito')?>" class="ajax-form ajax-delete not-result not-ajax-delete" data-warning="Вы действительно хотите бесплатно поднять отфильтрованные объявления на Авито?">Авито&nbsp;бесплатные</a></li>
				</ul>
			</li>
			<li><a>Обновить</a>
				<ul class="b-section-submenu">
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "update"))?>" class="ajax-form ajax-update">Без&nbsp;фотографий</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "updateWithImages"))?>" class="ajax-form ajax-update">С&nbsp;фотографиями</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "updateImages"))?>" class="ajax-form ajax-update">Только&nbsp;фотографии</a></li>
					<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "updatePrice"))?>" class="ajax-form ajax-update">Только&nbsp;цены</a></li>
				</ul>
			</li>
			<li><a href="<?php echo $this->createUrl('/advert/adminaction', array('action'=> "delete"))?>" class="ajax-form ajax-update">Удалить</a></li>
			<li><a href="<?php echo $this->createUrl('/advert/admintitles')?>" class="ajax-form ajax-delete not-ajax-delete" data-warning="Вы действительно хотите сгенерировать заголовки для отфильтрованных объявлений?">Сгенерировать заголовки</a></li>
			<?if($this->user->usr_id == 1):?><li><a href="<?php echo $this->createUrl('/advert/adminremove', array('lol'=>'1'))?>" class="ajax-form ajax-delete" data-warning="Вы действительно хотите удалить все отфильтрованные объявления к ебеням?">Уничтожить</a></li><?endif;?>
		</ul>
	</div>
</div>
<h1 class="b-with-nav"><?=$this->adminMenu["cur"]->name?></h1>
<?php $htmlopt = array("separator"=>"","template"=>"<div class='b-advert-checkbox'>{input}{label}</div>");  
$form=$this->beginWidget('CActiveForm',array('id'=>'adverts-form',"action" => $this->createUrl('/advert/adminindex'),"method" => "GET")); ?>
<div class="b-advert-filter clearfix">
	<div class="b-advert-textarea">
		<textarea name="Codes" placeholder="Список кодов"><?=$_GET['Codes']?></textarea>
	</div>
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
		<div class="b-advert-block">
			<h3><?=$data['AttrName'][58]?></h3>
			<div class="clearfix">
			<?=CHtml::checkBoxList("Attr[58]", $_GET['Attr'][58], $data['Attr'][58]+array(0 => "Нет", 3885 => "Да"),$htmlopt);?>
			</div>
		</div>
	</div>
</div>	
<div class="clearfix">
	<?php echo CHtml::submitButton("Фильтровать",array("class"=> "b-butt advert-butt")); ?>
</div>
<div id="adverts-form-hidden" style="display:none;"></div>
<p><br>Найдено объявлений: <?=$advert_count?></p>
<?php $this->endWidget(); ?>
	<table class="b-table b-advert-table" border=1>
		<tr>
			<th>ID</th>
			<th>Код</th>
			<th>Тип товара</th>
			<th><?=$labels["place_id"]?></th>
			<th><?=$labels["type_id"]?></th>
			<th><?=$labels["city_id"]?></th>
			<th><?=$labels["title"]?></th>
			<th><?=$labels["url"]?></th>
		</tr>
		<?if ($adverts_arr):?>
			<? foreach ($adverts_arr as $name => $place): ?>
				<? foreach ($place as $code => $adverts): ?>
					<? foreach ($adverts as $advert): ?>
					<tr>
						<td><?=$advert->id?></td>
						<td><?=$code?></td>
						<td><?=$advert->place->goodType->name?></td>
						<td><?=$name?></td>
						<td><?=$advert->type->value?></td>
						<td><?=$advert->city->value?></td>
						<td class="<?=(($advert->ready)?("green"):("red"))?>" id="advert-<?=$advert->id?>"><a href="<?php echo $this->createUrl('/advert/admintitleedit', array('advert_id'=> $advert->id))?>" class="ajax-form ajax-update"><?=$advert->title?></a></td>
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
				<td colspan="10">Пусто</td>
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
   
