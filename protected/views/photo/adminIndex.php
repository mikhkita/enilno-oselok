<h1><?=$this->adminMenu["cur"]->name?></h1>
<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false
)); ?>
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
	<a style="display:block; margin-top:20px;" href="#" data-path="<? echo Yii::app()->createUrl('/uploader/getForm',array('maxFiles'=>10,'extensions'=>'jpg,png', 'title' => 'Загрузка фотографий', 'selector' => '.photo', 'tmpPath' => Yii::app()->params['tempFolder']) ); ?>" class="b-get-image" ><span>Загрузить фотографии</span></a>
	<input type="hidden" name="photo_name" class="photo">
	<ul class='photo-preview'></ul>
	<input type="hidden" id="GoodTypeId" name="GoodTypeId">
	<input type="submit" class="hidden b-butt" value="Сохранить">
	
<?php $this->endWidget(); ?>
	<script>
		$(".photo").change(function(){
			var arr = $('.photo').val().split(','),error="";
			// $(".photo-preview").empty();
			$.each( arr, function( index, item ) {
				var item_name = item.split('/');
				item_name = item_name.pop();
				if(item_name.indexOf('_') + 1) {
					var src = "<?=Yii::app()->request->baseUrl ?>"+"/"+item;
					$(".photo-preview").append("<li style='background-image: url("+src+");'></li><input type='hidden' name='photo[]' value='"+item+"'>");
				} else {
					error += " "+item_name+",";
				}
			});
			if(error!="") alert("Некорректное имя у"+error+" указанные файлы не будут загружены");
			if($(".photo-preview").html()!="") {
				$("#GoodTypeId").val($(".b-choosable-values li.selected").attr("data-id"));
				$("input[type=submit]").removeClass('hidden');
				$(".b-choosable").remove();
			}
		});
		$(".b-choosable-values li").click(function(){
        	$(".b-choosable-values li").removeClass("selected");
        	$(this).addClass("selected");
   		});
	</script>

