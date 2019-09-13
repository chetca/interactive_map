<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php $APPLICATION->ShowTitle(); ?></title>
	<?php
	$asset = \Bitrix\Main\Page\Asset::getInstance();
	CJSCore::Init(array("jquery2"));
	$asset->addJs("https://api-maps.yandex.ru/2.1/?apikey=312dc67c-c281-45e5-8f82-16bc946f1607&lang=ru_RU");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/jquery-3.4.1.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/slick.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/lightgallery-all.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/jquery.mousewheel.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/selectize.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/current-device.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/datepicker.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/load.js");
	$asset->addCss("https://fonts.googleapis.com/css?family=Roboto:400,500,700");
	//$asset->addCss(SITE_TEMPLATE_PATH."/css/cols.css");
	//$asset->addCss(SITE_TEMPLATE_PATH."/css/bootstrap.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/css/slick.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/css/slick-theme.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/css/lightgallery.min.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/css/selectize.css");
	$asset->addCss(SITE_TEMPLATE_PATH."/css/datepicker.min.css");
	$asset->addString('<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0">');
	$APPLICATION->ShowHead();
	?>
</head> 

<body>
	<main class="container-flex main">
		<div class="row-flex">
			<article class="main_article col-flex" id="myMapId">
			</article>
			<aside class="main_aside col-flex">
				<header>
					<h2 class="main_aside_header">Интерактивная карта объектов <br/> капитального строительства</h2>
				</header>
				<section class="main_aside_search" style="display: block;">
					<header>
						Поиск по объектам
					</header>
					<div class="main_aside_search-content">
						<div class="filter_dates">
							<div class="filter__datepicker">
								<input class="datepicker-here" 
									data-range="true" 
									data-multiple-dates-separator=" - " 
									data-clear-button="true" 
									data-auto-close="true" 
									data-min-view="years"
									data-view="years"
									data-date-format="yyyy"
									value="" 
									id="dateIntervalData" 
									placeholder="Сроки реализации по годам" 
									autocomplete="off" />
								<div class="icon fas fa-calendar-alt"></div>
							</div>
							<div class="filter__datepicker">
								<input class="datepicker-here" 
									data-range="true" 
									data-multiple-dates-separator=" - " 
									data-clear-button="true" 
									data-auto-close="true" 
									data-min-view="months"
									data-view="months"
									data-date-format="MM yyyy"
									value="" 
									id="dateIntervalMonth" 
									placeholder="Сроки реализации по месяцам" 
									autocomplete="off" >
								<div class="icon fas fa-calendar-alt"></div>
							</div>
						</div>
						<div class="filter-select">
							<select name="category" id="category" placeholder="Отраслевая принадлежность" class="selectize-control single" multiple />
								<option value=""></option>
								<?php
								if (CModule::IncludeModule("iblock"))
								{
									$rsEnums = CIBlockPropertyEnum::GetList(array("sort" => "asc"), array("PROPERTY_ID" => 1136));
									while($enums = $rsEnums->Fetch()){
								?>
								<option value="<?=$enums["ID"]?>"><?=$enums["VALUE"]?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="filter-select">
							<select name="finance" id="finance" placeholder="Источник финансирования" class="selectize-control single" multiple />
								<option value=""></option>
								<?php
								if (CModule::IncludeModule("iblock"))
								{
									$rsEnums = CIBlockPropertyEnum::GetList(array("sort" => "asc"), array("PROPERTY_ID" => 1146));
									while($enums = $rsEnums->Fetch()){
								?>
								<option value="<?=$enums["ID"]?>"><?=$enums["VALUE"]?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="filter-select">
							<select name="type_work" id="type_work" placeholder="Вид работ" class="selectize-control single" multiple />
								<option value=""></option>
								<option value=""></option>
								<?php
								if (CModule::IncludeModule("iblock"))
								{
									$rsEnums = CIBlockPropertyEnum::GetList(array("sort" => "asc"), array("PROPERTY_ID" => 1147));
									while($enums = $rsEnums->Fetch()){
								?>
								<option value="<?=$enums["ID"]?>"><?=$enums["VALUE"]?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="filter-select">						
							<div class="selectize-input items not-full has-options">
								<input type="text" name="builder" id="builder" placeholder="Подрядчик, застройщик" style="width: 100%;"/>
								<ul class="search_result"></ul>
							</div>	
						</div>
						<div class="filter-select">						
							<div class="selectize-input items not-full has-options">
								<input type="text" name="project_finance" id="project_finance" placeholder="Наименование проекта / федеральной программы" style="width: 100%;"/>
								<ul class="search_result_project_finance"></ul>
							</div>	
						</div>
					</div>
					<footer>
						<input type="button" name="findBtn" id="findBtn" value="Найти" class="btn btn-secondary" />
						<input type="button" name="resetBtn" id="resetBtn" value="Сбросить" class="btn btn-light inp-reset-content" />
						<input type="button" name="excelBtn" id="excelBtn" value="Выгрузить в Excel" class="btn btn-link">
					</footer>
				</section>
				<section class="main_aside_button">
					<button class="btn btn-secondary">Показать фильтр</button>
				</section>
				<section class="main_aside_top">
					<header></header>
					<div class="main_aside_top-content"></div>
					<footer>Техническое сопровождение: Управление информатизации и информационных ресурсов Администрации г. Улан-Удэ<br />© 2019</footer>
				</section>
			</aside>
		</div>
	</main>
