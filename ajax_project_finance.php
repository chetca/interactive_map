<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
header ('Content-Type: application/json; charset=UTF-8');

	if(!CModule::IncludeModule("iblock"))
		return;	

	$project_finance = $_GET["project_finance"];
	
	$arOrder = array("SORT"=>"ASC");
	$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 302);
	$arFilter['?PROPERTY_project_finance'] = iconv("UTF-8", "WINDOWS-1251", $project_finance);
	$arGroupBy = false;
	$arNavStartParams = array();
	$arSelectFields = array("IBLOCK_ID", "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_*");
	$res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
	$arResult = array();
	while($ob = $res->GetNextElement()):
		$arProps = $ob->GetProperties();
		if(!in_array('<li>'.iconv("WINDOWS-1251", "UTF-8", $arProps["project_finance"]["VALUE"]).'</li>', $arResult)) {
			$arResult[] = '<li>'.iconv("WINDOWS-1251", "UTF-8", $arProps["project_finance"]["VALUE"]).'</li>';
		}
		
	endwhile;
	echo json_encode($arResult);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");