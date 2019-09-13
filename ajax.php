<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
header ('Content-Type: application/json; charset=UTF-8');

	if(!CModule::IncludeModule("iblock"))
		return;
	
	function getFormatMonth($val){
		$_monthsList = array(
			"Январь"   =>"01",
			"Февраль"  =>"02",
			"Март"     =>"03",
			"Апрель"   =>"04",
			"Май"      =>"05",
			"Июнь"     =>"06",
			"Июль"     =>"07",
			"Август"   =>"08",
			"Сентябрь" =>"09",
			"Октябрь"  =>"10",
			"Ноябрь"   =>"11",
			"Декабрь"  =>"12"
		);
		$valDate = explode(" ", $val);
		return "01.".$_monthsList[$valDate[0]].".".$valDate[1];	
	}
	
	function getEndMonth($val){
		return date("t.m.Y", strtotime($val));
	}
	

	$category = $_GET["category"];
	$finance = $_GET["finance"];
	$type_work = $_GET["type_work"];
	$builder = $_GET["builder"];
	$project_finance = $_GET["project_finance"];
	$datefrom = htmlspecialcharsbx($_GET["datefrom"]);
	$dateto = htmlspecialcharsbx($_GET["dateto"]);
	
	if($datefrom != "" && $datefrom != "") {
		if(strlen($datefrom) > 4) {
			$datefrom = getFormatMonth($datefrom);
			$dateto = getEndMonth(getFormatMonth($dateto));
		}
		else {
			$datefrom = "01.01.".$datefrom;
			$dateto = "31.12.".$dateto;
		}
	}
	
	$arOrder = array("propertysort_CATEGORY" => "ASC", "timestamp_x"=>"DESC");
	$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 302);
	if ($category)
		$arFilter["PROPERTY_CATEGORY"] = $category;
	if ($finance)
		$arFilter["PROPERTY_FINANCE"] = $finance;
	if ($type_work)
		$arFilter["PROPERTY_TYPE_WORK"] = $type_work;
	if ($builder)
		$arFilter["PROPERTY_builder"] = iconv("UTF-8", "WINDOWS-1251", $builder);
	if ($project_finance)
		$arFilter["PROPERTY_project_finance"] = iconv("UTF-8", "WINDOWS-1251", $project_finance);
	if ($datefrom && $dateto){
		$arFilter[">=PROPERTY_DATE_START"] = ConvertDateTime($datefrom, "YYYY-MM-DD", "ru");
		$arFilter["<=PROPERTY_DATE_FINISH"] = ConvertDateTime($dateto, "YYYY-MM-DD", "ru");
	}
	$arGroupBy = false;
	$arNavStartParams = false;
	$arSelectFields = array("IBLOCK_ID", "ID", "NAME", "PREVIEW_TEXT", "PROPERTY_*");
	$res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

	$arResult = array(
		"points" => array("type" => "FeatureCollection", "features" => array()),
		"others" => array("type" => "FeatureCollection", "features" => array()),
	);
	$data = '<table class="table"><tr><th>Название</th><th>Описание работ</th><th>Отраслевая принадлежность</th></tr>';
	$c = 0;
	
	$_colorList = array(
		524 => 'blue',
		525 => 'red', 
		526 => 'darkOrange', 
		527 => 'night',   
		528 => 'darkBlue',     
		529 => 'pink',    
		530 => 'gray',      
		558 => 'brown',     
		559 => 'darkGreen',   
		560 => 'violet',    
		561 => 'black',
		562 => 'yellow',      
		563 => 'green',      
		564 => 'orange',      
		565 => 'lightBlue',      
		566 => 'olive'  
	);
	$_codeList = array(
		524 => '#1e98ff',
		525 => '#ed4543',
		526 => '#e6761b',
		527 => '#0e4779',
		528 => '#177bc9',
		529 => '#f371d1',
		530 => '#b3b3b3',
		558 => '#793d0e',
		559 => '#1bad03',
		560 => '#b51eff',
		561 => '#595959',
		562 => '#ffd21e',
		563 => '#56db40',
		564 => '#ff931e',
		565 => '#82cdff',
		566 => '#97a100'
	);
	$_iconList = array(
		524 => 'Education',
		525 => 'Medical',
		526 => 'Family',
		527 => 'Theater',
		528 => 'Sport',
		529 => 'Leisure',
		530 => 'Home',
		558 => 'Water',
		559 => 'Auto',
		560 => 'Factory',
		561 => 'Money',
		562 => 'Beach',
		563 => 'Vegetation',
		564 => 'Pocket',
		565 => 'Attention',
		566 => 'Delivery'
	);
	
	while($ob = $res->GetNextElement()):
		$arFields = $ob->GetFields();
		$arFields["PREVIEW_TEXT"] = trim(strip_tags(str_replace("\n", '', $arFields["PREVIEW_TEXT"]), "<a><br><b>"));
		$arProps = $ob->GetProperties();
		$arProps["lon"] = explode(';', $arProps["lon"]["VALUE"]);
		$arProps["lat"] = explode(';', $arProps["lat"]["VALUE"]);
		/*
		$colorStr = "green";
		$colorCode = '#56DB40';
		*/
		
		$colorStr = $_colorList[$arProps["category"]["VALUE_ENUM_ID"]];
		$colorCode = $_codeList[$arProps["category"]["VALUE_ENUM_ID"]];
		$iconStr = $_iconList[$arProps["category"]["VALUE_ENUM_ID"]];
		
		
		if (intval($arProps["lon"][0]) && intval($arProps["lat"][0]))
		{
			//var_dump($arProps["category"]);
			if ($arProps["type"]["VALUE_ENUM_ID"] == 531)
			{
				$type = "Point";
				$pointSet = array($arProps["lon"][0], $arProps["lat"][0]);
			}
			elseif ($arProps["type"]["VALUE_ENUM_ID"] == 532)
			{
				$type = "LineString";
				$pointSet = array();
				for ($i=0; $i<count($arProps["lon"]); $i++):
					$pointSet[] = array($arProps["lon"][$i], $arProps["lat"][$i]);
				endfor;
			}
			else
			{
				$type = "Polygon";
				$pointSet = array();
				$points = array();
				for ($i=0; $i<count($arProps["lon"]); $i++):
					$points[] = array($arProps["lon"][$i], $arProps["lat"][$i]);
				endfor;
				$pointSet = array($points);
			}
			if ($type == "Point")
			{
				/*
				Если понадобится - внедрить иконки через iconContent
				*/
				$arResult["points"]["features"][] = array(
					"type" => "Feature",
					"id" => $arFields["ID"],
					"geometry" => array(
						"type" => $type,
						"coordinates" => $pointSet
					),
					"properties" => array(
						"balloonContentHeader" => iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]),
						"balloonContentBody" => iconv("WINDOWS-1251", "UTF-8", $arFields["PREVIEW_TEXT"]),
						"balloonContentFooter" => 'Отраслевая принадлежность: '.iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"]),
						"clusterCaption" => iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"]),
						"hintContent" => iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]),
						"iconCaption" => iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["DISPLAY_VALUE"])
					),
					"options" => array(
						"preset" => "islands#".$colorStr.$iconStr."Icon",
						//"preset" => "islands#".$colorStr."CircleDotIconWithCaption",
						//"iconLayout" => 'createChipsLayout(function (zoom) {return 4 * zoom + 8;})'
					)
				);
			} else {
				$arResult["others"]["features"][] = array(
					"type" => "Feature",
					"id" => $arFields["ID"],
					"geometry" => array(
						"type" => $type,
						"coordinates" => $pointSet,
						"fillRule" => "nonZero"
					),
					"properties" => array(
						"balloonContentHeader" => iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]),
						"balloonContentBody" => iconv("WINDOWS-1251", "UTF-8", $arFields["PREVIEW_TEXT"]),
						"balloonContentFooter" => 'Отраслевая принадлежность: '.iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"]),
						"clusterCaption" => iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"]),
						"hintContent" => iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"]),
						"iconCaption" => iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["DISPLAY_VALUE"])
					),
					"options" => array(
						//"strokeWidth" => [5, 1],
						//"strokeColor" => [$colorCode, "#666666"],
						//"strokeStyle" => [0, 'dash'],
						"strokeWidth" => 7,
						"strokeColor" => $colorCode,
						"fillColor" => $colorCode,
						"fillOpacity" => 0.3
					)
				);
			}
			$c++;
			$data .= "<tr><td><a class='goForward' href='#' data-id=".$arFields["ID"]." lon='".$arProps["lon"][0]."' lat='".$arProps["lat"][0]."'>".iconv("WINDOWS-1251", "UTF-8", $arFields["NAME"])."</a></td><td>".iconv("WINDOWS-1251", "UTF-8", $arFields["PREVIEW_TEXT"])."</td><td>".iconv("WINDOWS-1251", "UTF-8", $arProps["category"]["VALUE"])."<p style='background-color: $colorCode;' class='industry-color'></p></td></tr>";
		}
	endwhile;
	$data .= "</table>";
	
	$arResult["html"] = "<div>Выбрано объектов: ".$c."</div>";
	if($c) $arResult["html"] .= $data;
	
	echo json_encode($arResult);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");