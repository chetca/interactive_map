<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<ul class="left-menu round-border">
	<p id="toolbar-gerb"><a href="<?="http://".$_SERVER['SERVER_NAME']?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/gerb.png" alt="Карты.Улан-Удэ.рф" title="Карты.Улан-Удэ.рф" /></a></p>
    <p id="toolbar-rf"><a href="<?="http://".$_SERVER['SERVER_NAME']?>"><img src="<?=SITE_TEMPLATE_PATH?>/images/uu-rf.png" alt="Улан-Удэ - Народный контроль" title="Улан-Удэ - Народный контроль" /></a></p>
    <br style="clear:both" />

<?
foreach($arResult as $i => $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<li<?php if ($i == 0) echo " class='first'"; ?>><a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a></li>
	<?else:?>
		<li<?php if ($i == 0) echo " class='first'"; ?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	
<?endforeach?>

</ul>
<?endif?>