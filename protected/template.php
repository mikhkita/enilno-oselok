<div class="need_help">
	<div class="container">
<?
$block_name = explode(" | ", $arResult['NAME']);
//print_r($block_name);
$block_name = $block_name[0]
?>
		<table class="block-center" cellspacing="0" cellpadding="0" border="0">
		
			<tr>
			
				<td class="strip-left-tail"></td>
			
				 <td class="strip-body">
					  НЕОБХОДИМА ПОМОЩЬ?			  
				 </td>
				 
				<td class="strip-right-tail"></td>
				 
			</tr>
			
		</table>
		<br/>
		<br/>
		<div id="accordion" class="panel-group">
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="panel">
				<div class="panel-heading">
 					<a data-parent="#accordion" data-toggle="collapse" href="#<?=$arItem["CODE"]?>" class="accordion-toggle"><?echo $arItem["NAME"]?> <span class="accordion_arrow"></span></a>
				</div>
				<div aria-expanded="true" id="<?=$arItem["CODE"]?>" class="collapse panel-collapse">
 					<section class="panel-body">
						<p>
							 <?echo $arItem["PREVIEW_TEXT"];?>
						</p>
 					</section>
				</div>
			</div>
<?endforeach;?>
		</div>
	</div>
</div>
