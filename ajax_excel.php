<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
//header ( 'charset=UTF-8');

	function floatvalue($val){
		$val = str_replace(" ","",$val);
           $val = str_replace(",",".",$val);
           $val = preg_replace('/\.(?=.*\.)/', '', $val);
           return floatval($val);
	}
	
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

	require_once 'app/PHPExcel.php'; 

	if(!CModule::IncludeModule("iblock"))
		return;

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
	
	$arOrder = array("propertysort_CATEGORY" => "ASC", "timestamp_x"=>"ASC");
	$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => 302);
	if ($category) {
		$category = explode(",", $category);
		$arFilter["PROPERTY_CATEGORY"] = $category;
	}
	if ($finance) {
		$finance = explode(",", $finance);
		$arFilter["PROPERTY_FINANCE"] = $finance;
	}
	if ($type_work) {
		$type_work = explode(",", $type_work);
		$arFilter["PROPERTY_TYPE_WORK"] = $type_work;
	}
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

	$arTable = array();
	
	$c = 0;
	while($ob = $res->GetNextElement()):
		$arProps = $ob->GetProperties();
		$arFields = $ob->GetFields();
		$arFields["PREVIEW_TEXT"] = trim(strip_tags(str_replace("\n", '', $arFields["PREVIEW_TEXT"]), "<a><br><b>"));		
		
		$totalAmount =  floatvalue($arProps["federal"]["VALUE"]) + 
						floatvalue($arProps["republican"]["VALUE"]) + 
						floatvalue($arProps["municipal"]["VALUE"]) + 
						floatvalue($arProps["offbudget"]["VALUE"]);
		
		
		$arTable[$c]["date_start"] = $arProps["date_start"]["VALUE"];
		$arTable[$c]["date_finish"] = $arProps["date_finish"]["VALUE"];
		$arTable[$c]["finance"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["finance"]["VALUE"]));
		$arTable[$c]["type_work"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["type_work"]["VALUE"]));
		$arTable[$c]["builder"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["builder"]["VALUE"]));
		$arTable[$c]["project_finance"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["project_finance"]["VALUE"]));
		$arTable[$c]["category"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["category"]["VALUE"]));
		$arTable[$c]["federal"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["federal"]["VALUE"]));
		$arTable[$c]["republican"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["republican"]["VALUE"]));
		$arTable[$c]["municipal"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["municipal"]["VALUE"]));
		$arTable[$c]["offbudget"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["offbudget"]["VALUE"]));
		$arTable[$c]["summ"] = $totalAmount;
		$arTable[$c]["preview"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arFields["PREVIEW_TEXT"]));
		$arTable[$c]["link"] = iconv("WINDOWS-1251", "UTF-8", htmlspecialchars_decode($arProps["link"]["VALUE"]));
		
		$c++;
	endwhile;

	$arResult["excel"][] = $arTable;
	//print_r($arTable);
	
	$fileType = 'Excel5';
	$fileName = 'maps-build.xls';
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	
	$num_rows = 2;
	
	foreach(array('A','B','C','D','E','F','G','H','I','J','K','L','M','N') as $column) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);		
	}
	
	//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Порядковый номер'); 
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Дата начала работ');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Дата окончания работ');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Вид работ');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Подрядчик, застройщик');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Наименование проекта / федеральной программы');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Отраслевая принадлежность');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Федеральный бюджет');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Республиканский бюджет');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Местный бюджет');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Внебюджетные источники');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Итоговая сумма');
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Описание работ');
	$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Ссылка');
	
	foreach($arTable as $arElement) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$num_rows, $num_rows-1); 
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$num_rows, $arElement["date_start"]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$num_rows, $arElement["date_finish"]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$num_rows, $arElement["type_work"]);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$num_rows, $arElement["builder"]);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$num_rows, $arElement["project_finance"]);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$num_rows, $arElement["category"]);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$num_rows, $arElement["federal"]);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$num_rows, $arElement["republican"]);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$num_rows, $arElement["municipal"]);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$num_rows, $arElement["offbudget"]);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$num_rows, $arElement["summ"]);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$num_rows, $arElement["preview"]);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$num_rows, $arElement["link"]);
		$num_rows++;
	}
	
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="file.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);	
	$objWriter->save('php://output');
	
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");