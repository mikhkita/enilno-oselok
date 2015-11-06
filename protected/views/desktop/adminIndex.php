<h1><?=$folder->name?></h1>
<div class="b-top-butt">
	<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admincreate',array("parent_id"=>$folder->id))?>" class="ajax-form ajax-create b-butt">Создать папку</a>
	<a href="<?php echo $this->createUrl('/'.$this->adminMenu["cur"]->code.'/admintablecreate',array("folder_id"=>$folder->id))?>" class="ajax-form ajax-create b-butt" style="margin-right: 10px;">Создать таблицу</a>
</div>
<div class="b-link-back">
	<? if(isset($folder->parent)): ?>
		<a href="<?=$this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("id"=>$folder->parent_id))?>">Назад</a>
	<? endif; ?>
	<span>Режим правки: </span>
	<a href="#" class="b-kit-switcher right<?if($editable) echo" checked";?>" data-on="setEditable" data-off="unsetEditable">
	    <div class="b-kit-rail">
	        <div class="b-kit-state1">Вкл.</div>
	        <div class="b-kit-slider"></div>
	        <div class="b-kit-state2">Выкл.</div>
	    </div>
	</a>
</div>
<div class="b-desktop<?if($editable) echo" b-editable";?>">
	<? if( count($folder->childs) || count($folder->tables) ): ?>
		<ul class="clearfix">
			<? if( isset($folder->childs) ): ?>
				<? foreach ($folder->childs as $child): ?>
					<li>
						<div class="b-folder-control">
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/adminupdate',array('id'=>$child->id,"parent_id"=>$child->parent_id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать <?=$this->adminMenu["cur"]->vin_name?>"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admindelete',array('id'=>$child->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить папку &#34;<?=$child->name?>&#34;" title="Удалить <?=$this->adminMenu["cur"]->vin_name?>"></a>
						</div>
						<a class="link" href="<?=$this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex',array("id"=>$child->id))?>">
							<div class="b-icon b-folder"></div>
							<h3><?=$child->name?></h3>
						</a>
					</li>
				<? endforeach; ?>
			<? endif; ?>
			<? if( isset($folder->tables) ): ?>
				<? foreach ($folder->tables as $table): ?>
					<li>
						<div class="b-folder-control">
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admintableupdate',array('id'=>$table->id,"folder_id"=>$table->folder_id))?>" class="ajax-form ajax-update b-tool b-tool-update" title="Редактировать таблицу"></a>
							<a href="<?php echo Yii::app()->createUrl('/'.$this->adminMenu["cur"]->code.'/admintabledelete',array('id'=>$table->id))?>" class="ajax-form ajax-delete b-tool b-tool-delete" data-warning="Вы действительно хотите удалить таблицу &#34;<?=$table->name?>&#34;" title="Удалить таблицу"></a>
						</div>
						<a class="link" href="<?=$this->createUrl('/'.$this->adminMenu["cur"]->code.'/admintableindex',array("id"=>$table->id))?>">
							<div class="b-icon b-excel"></div>
							<h3><?=$table->name?></h3>
						</a>
					</li>
				<? endforeach; ?>
			<? endif; ?>
		</ul>
	<? else: ?>
		Папка пуста
	<? endif; ?>
</div>