<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
header ('Content-Type: application/json; charset=UTF-8');

	function floatvalue($val){
		$val = str_replace(" ","",$val);
           $val = str_replace(",",".",$val);
           $val = preg_replace('/\.(?=.*\.)/', '', $val);
           return floatval($val);
	}
		
	if(!CModule::IncludeModule("iblock"))
		return;

	$id = intval($_GET["id"]);
	$arOrder = array("SORT"=>"ASC");
	$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 302, "ID" => $id);
	$arGroupBy = false;
	$arNavStartParams = false;
	$arSelectFields = array("IBLOCK_ID", "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_*");
	$res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
	$arResult = array();
	while($ob = $res->GetNextElement()):
		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();		
		
		$arPhotos = $arProps["photos"]["VALUE"];
		$photos = '';
		foreach ($arPhotos as $photo)
		{
			$rsFile = CFile::GetByID($photo);
			$arFile = $rsFile->Fetch();
			$arSmaillFile = CFile::ResizeImageGet(
				$arFile["ID"],
				array("width" => 220, "height" => 220),
				BX_RESIZE_IMAGE_EXACT,
				false
			);
			$photos .= '<li data-src="/upload/'.$arFile["SUBDIR"].'/'.$arFile["FILE_NAME"].'" alt="'.iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]).'"><div class="media-sm__img" style="background-image:url('.$arSmaillFile["src"].')"></div><img src="'.$arSmaillFile["src"].'" style="display: none" /></li>';
		}
		if ($photos)
			$photos = '<div class="photo-section"><h3>Фото после</h3><ul class="gallery slick-slider">'.$photos.'</ul></div>';
		

		$arPhotos = $arProps["photos_before"]["VALUE"];
		$photosBefore = '';
		foreach ($arPhotos as $photo)
		{
			$rsFile = CFile::GetByID($photo);
			$arFile = $rsFile->Fetch();
			$arSmaillFile = CFile::ResizeImageGet(
				$arFile["ID"],
				array("width" => 220, "height" => 220),
				BX_RESIZE_IMAGE_EXACT,
				false
			);
			$photosBefore .= '<li data-src="/upload/'.$arFile["SUBDIR"].'/'.$arFile["FILE_NAME"].'" alt="'.iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]).'"><div class="media-sm__img" style="background-image:url('.$arSmaillFile["src"].')"></div><img src="'.$arSmaillFile["src"].'" style="display: none" /></li>';
		}
		if ($photosBefore)
			$photosBefore = '<div class="photo-section"><h3>Фото до</h3><ul class="gallery slick-slider">'.$photosBefore.'</ul></div>';
		
		// использование этой функции оправдано тем, что заполнять данные
		// структурники будут копипастой из Экселя кое-как
		
		$totalAmount =  floatvalue($arProps["federal"]["VALUE"]) + 
						floatvalue($arProps["republican"]["VALUE"]) + 
						floatvalue($arProps["municipal"]["VALUE"]) + 
						floatvalue($arProps["offbudget"]["VALUE"]);
						
		if($arProps["link"]["VALUE"]) {
			$link = '<tr><td><strong>Информация о конкурсных процедурах:</strong></td><td><a target="_blank" href="'.iconv("WINDOWS-1251", "UTF-8", $arProps["link"]["VALUE"]).'">Ссылка на сайте zakupki.gov.ru</a></td></tr>';
		}
		
		$arResult = 
				'<div><strong>'.iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]).'</strong></div>'.
				'<div><strong>Отраслевая принадлежность:</strong> '.iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"]).'</div>'.
				'<div><strong>Описание:</strong> '.iconv("WINDOWS-1251", "UTF-8", $arFields["PREVIEW_TEXT"]).'</div>'.
				'<table class="table"><tr><th>Описание</th><th>Значение</th></tr>'.
					'<tr><td>Дата начала работ:</td><td>'.$arProps["date_start"]["VALUE"].'</td></tr>'.
					'<tr><td>Дата окончания работ:</td><td>'.$arProps["date_finish"]["VALUE"].'</td></tr>'.
					'<tr><td>Вид работ:</td><td>'.iconv("WINDOWS-1251", "UTF-8", $arProps["type_work"]["VALUE"]).'</td></tr>'.
					'<tr><td>Подрядчик, застройщик:</td><td>'.iconv("WINDOWS-1251", "UTF-8", $arProps["builder"]["VALUE"]).'</td></tr>'.
					'<tr><td>Наименование проекта / федеральной программы:</td><td>'.iconv("WINDOWS-1251", "UTF-8", $arProps["project_finance"]["VALUE"]).'</td></tr>'.
					'<tr><td><strong>Источник финансирования:</strong></td><td></td></tr>'.
					'<tr><td><em>Федеральный бюджет: </em></td><td>'.$arProps["federal"]["VALUE"].' руб.</td></tr>'.
					'<tr><td><em>Республиканский бюджет:</em></td><td>'.$arProps["republican"]["VALUE"].' руб.</td></tr>'.
					'<tr><td><em>Местный бюджет:</em></td><td>'.$arProps["municipal"]["VALUE"].' руб.</td></tr>'.
					'<tr><td><em>Внебюджетные источники:</em></td><td>'.$arProps["offbudget"]["VALUE"].' руб.</td></tr>'.
					'<tr><td>Итоговая сумма, руб:</td><td>'.number_format($totalAmount, 2, ',', ' ').' руб.</td></tr>'.
					$link.
				'</table>'.
				'<div class="container-flex">'.
					$photosBefore.
					$photos.
				'</div>'.
				'<div><a href="#" class="goBack">Назад к списку</a></div>';
	endwhile;
	
	echo json_encode($arResult);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");