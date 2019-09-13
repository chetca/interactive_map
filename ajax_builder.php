<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
header ('Content-Type: application/json; charset=UTF-8');

	if(!CModule::IncludeModule("iblock"))
		return;	

	$builder = $_GET["builder"];
	
	$arOrder = array("SORT"=>"ASC");
	$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 302);
	$arFilter['?PROPERTY_builder'] = iconv("UTF-8", "WINDOWS-1251", $builder);
	$arGroupBy = false;
	$arNavStartParams = array();
	$arSelectFields = array("IBLOCK_ID", "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_*");
	$res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
	$arResult = array();
	while($ob = $res->GetNextElement()):
		$arProps = $ob->GetProperties();
		if(!in_array('<li>'.iconv("WINDOWS-1251", "UTF-8", $arProps["builder"]["VALUE"]).'</li>', $arResult)) {
			$arResult[] = '<li>'.iconv("WINDOWS-1251", "UTF-8", $arProps["builder"]["VALUE"]).'</li>';
		}
		
	endwhile;
	echo json_encode($arResult);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");