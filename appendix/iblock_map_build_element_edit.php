<style>
	.search_result{
		background: #FFF;
		border: 1px #ccc solid;
		max-width:200px;
		/*width: 100px;*/
		border-radius: 4px;
		/*max-height:200px;*/
		/*overflow-y:scroll;*/
		display:none;
	}
	
	.search_result li{
		max-width:200px;
		list-style: none;
		padding: 5px 10px;
		margin: 0 0 0 -40px;
		color: #000;
		border-bottom: 1px #ccc solid;
		cursor: pointer;
		transition:0.3s;
	}
	
	.search_result li:hover{
		background: #39f;
		color: #fff;
	}
	
	.search_result_project_finance{
		max-width:200px;
		background: #FFF;
		border: 1px #ccc solid;
		/*width: 100px;*/
		border-radius: 4px;
		/*max-height:200px;*/
		/*overflow-y:scroll;*/
		display:none;
	}
	
	.search_result_project_finance li{
		max-width:200px;
		list-style: none;
		padding: 5px 10px;
		margin: 0 0 0 -40px;
		color: #000;
		border-bottom: 1px #ccc solid;
		cursor: pointer;
		transition:0.3s;
	}
	
	.search_result_project_finance li:hover{
		background: #39f;
		color: #fff;
	}
	
	.link-invalid {
		font-size: medium;
		color: red;
		display: none;
	}
	
	.link-valid {
		font-size: medium;
		color: green;
		display: none;
	}
</style>
<script type="text/javascript" src="/bitrix/templates/maps/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#save").click(function(){
		var lon = $("#PROPlon").val();
		$("#PROPlonF").find("input").val(lon);
		var lat = $("#PROPlat").val();
		$("#PROPlatF").find("input").val(lat);
		var type = $("#PROPtype").val();
		$("#PROPtypeF").find("select").val(type);
    });
    $("#apply").click(function(){
		var lon = $("#PROPlon").val();
		$("#PROPlonF").find("input").val(lon);
		var lat = $("#PROPlat").val();
		$("#PROPlatF").find("input").val(lat);
		var type = $("#PROPtype").val();
		$("#PROPtypeF").find("select").val(type);
    });
});
</script>
<?
	$aTabs = array();
	$aTabs[] = array("DIV" => "edit1", "TAB" => "Описание метки", "ICON" => "iblock_element", "TITLE" => "Описание");
	$aTabs[] = array("DIV" => "edit2", "TAB" => "Координаты метки", "ICON" => "iblock_element", "TITLE" => "Координаты");

	$tabControl = new CAdminForm($bCustomForm? "tabControl": "form_element_".$IBLOCK_ID, $aTabs);

	$tabControl->BeginPrologContent();
	echo CAdminCalendar::ShowScript();

	if(COption::GetOptionString("iblock", "use_htmledit", "Y")=="Y" && $bFileman)
	{
		//TODO:This dirty hack will be replaced by special method like calendar do
		echo '<div style="display:none">';
		CFileMan::AddHTMLEditorFrame(
			"SOME_TEXT",
			"",
			"SOME_TEXT_TYPE",
			"text",
			array(
				'height' => 450,
				'width' => '100%'
			),
			"N",
			0,
			"",
			"",
			$arIBlock["LID"]
		);
		echo '</div>';
	}
	if($bFileman)
		CMedialibTabControl::ShowScript();

	if($arTranslit["TRANSLITERATION"] == "Y")
	{
		CUtil::InitJSCore(array('translit'));
		?>
		<script>
		var linked=<?php if($bLinked) echo 'true'; else echo 'false';?>;
		function set_linked()
		{
			linked=!linked;

			var name_link = document.getElementById('name_link');
			if(name_link)
			{
				if(linked)
					name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var code_link = document.getElementById('code_link');
			if(code_link)
			{
				if(linked)
					code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
				else
					code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
			}
			var linked_state = document.getElementById('linked_state');
			if(linked_state)
			{
				if(linked)
					linked_state.value='Y';
				else
					linked_state.value='N';
			}
		}
		var oldValue = '';
		function transliterate()
		{
			if(linked)
			{
				var from = document.getElementById('NAME');
				var to = document.getElementById('CODE');
				if(from && to && oldValue != from.value)
				{
					BX.translit(from.value, {
						'max_len' : <?echo intval($arTranslit['TRANS_LEN'])?>,
						'change_case' : '<?echo $arTranslit['TRANS_CASE']?>',
						'replace_space' : '<?echo $arTranslit['TRANS_SPACE']?>',
						'replace_other' : '<?echo $arTranslit['TRANS_OTHER']?>',
						'delete_repeat_replace' : <?echo $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
						'use_google' : <?echo $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
						'callback' : function(result){to.value = result; setTimeout('transliterate()', 250);}
					});
					oldValue = from.value;
				}
				else
				{
					setTimeout('transliterate()', 250);
				}
			}
			else
			{
				setTimeout('transliterate()', 250);
			}
		}
		transliterate();
		</script>
		<?
	}

	$tabControl->EndPrologContent();

	$tabControl->BeginEpilogContent();
?>

<script language="JavaScript">
<!--
function addNewRow(tableID, row_to_clone)
{
	var tbl = document.getElementById(tableID);
	var cnt = tbl.rows.length;
	if(row_to_clone == null)
		row_to_clone = -2;
	var sHTML = tbl.rows[cnt+row_to_clone].cells[0].innerHTML;
	var oRow = tbl.insertRow(cnt+row_to_clone+1);
	var oCell = oRow.insertCell(0);

	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('[n',p);
		if(s<0)break;
		var e = sHTML.indexOf(']',s);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+2,e-s));
		sHTML = sHTML.substr(0, s)+'[n'+(++n)+']'+sHTML.substr(e+1);
		p=s+1;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('__n',p);
		if(s<0)break;
		var e = sHTML.indexOf('_',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'__n'+(++n)+'_'+sHTML.substr(e+1);
		p=e+1;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('__N',p);
		if(s<0)break;
		var e = sHTML.indexOf('__',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'__N'+(++n)+'__'+sHTML.substr(e+2);
		p=e+2;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('xxn',p);
		if(s<0)break;
		var e = sHTML.indexOf('xx',s+2);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+3,e-s));
		sHTML = sHTML.substr(0, s)+'xxn'+(++n)+'xx'+sHTML.substr(e+2);
		p=e+2;
	}
	var p = 0;
	while(true)
	{
		var s = sHTML.indexOf('%5Bn',p);
		if(s<0)break;
		var e = sHTML.indexOf('%5D',s+3);
		if(e<0)break;
		var n = parseInt(sHTML.substr(s+4,e-s));
		sHTML = sHTML.substr(0, s)+'%5Bn'+(++n)+'%5D'+sHTML.substr(e+3);
		p=e+3;
	}
	oCell.innerHTML = sHTML;

	var patt = new RegExp ("<"+"script"+">[^\000]*?<"+"\/"+"script"+">", "ig");
	var code = sHTML.match(patt);
	if(code)
	{
		for(var i = 0; i < code.length; i++)
		{
			if(code[i] != '')
			{
				var s = code[i].substring(8, code[i].length-9);
				jsUtils.EvalGlobal(s);
			}
		}
	}
}
//-->
</script>


<?=bitrix_sessid_post()?>
<?echo GetFilterHiddens("find_");?>
<input type="hidden" name="linked_state" id="linked_state" value="<?if($bLinked) echo 'Y'; else echo 'N';?>">
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="from" value="<?echo htmlspecialchars($from)?>">
<input type="hidden" name="WF" value="<?echo htmlspecialchars($WF)?>">
<input type="hidden" name="return_url" value="<?echo htmlspecialchars($return_url)?>">
<?if($ID>0 && !$bCopy):?>
	<input type="hidden" name="ID" value="<?echo $ID?>">
<?endif;?>
<?if ($bCopy)
{
	?><input type="hidden" name="copyID" value="<? echo intval($ID); ?>"><?
}
?>
<input type="hidden" name="IBLOCK_SECTION_ID" value="<?echo IntVal($IBLOCK_SECTION_ID)?>">
<input type="hidden" name="TMP_ID" value="<?echo IntVal($TMP_ID)?>">
<?
$tabControl->EndEpilogContent();

$customTabber->SetErrorState($bVarsFromForm);
$tabControl->AddTabs($customTabber);
$strFormAction = "/bitrix/admin/iblock_element_edit.php?type=".urlencode($type)."&lang=".LANG."&IBLOCK_ID=".$IBLOCK_ID."&find_section_section=".intval($find_section_section);
if ((true == defined('BT_UT_AUTOCOMPLETE')) && (1 == BT_UT_AUTOCOMPLETE))
{
	$strFormAction .= "&lookup=".urlencode($strLookup);
}
$tabControl->Begin(array(
	"FORM_ACTION" => $strFormAction,
));

$tabControl->BeginNextFormTab();
?>
	<?
	if($ID > 0 && !$bCopy):
		$p = CIblockElement::GetByID($ID);
		$pr = $p->ExtractFields("prn_");
	endif;
$tabControl->AddCheckBoxField("ACTIVE", "Показывать на карте:", false, "Y", $str_ACTIVE=="Y");

if($arTranslit["TRANSLITERATION"] == "Y")
{
	$tabControl->BeginCustomField("NAME", "Адрес:", true);
	?>
		<tr id="tr_NAME">
			<td><?echo $tabControl->GetCustomLabelHTML()?></td>
			<td nowrap>
				<input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?echo $str_NAME?>"><image id="name_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
			</td>
		</tr>
	<?
	$tabControl->EndCustomField("NAME",
		'<input type="hidden" name="NAME" id="NAME" value="'.$str_NAME.'">'
	);

	$tabControl->BeginCustomField("CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["CODE"]["IS_REQUIRED"] === "Y");
	?>
		<tr id="tr_CODE">
			<td><?echo $tabControl->GetCustomLabelHTML()?></td>
			<td nowrap>

				<input type="text" size="50" name="CODE" id="CODE" maxlength="255" value="<?echo $str_CODE?>"><image id="code_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
			</td>
		</tr>
	<?
	$tabControl->EndCustomField("CODE",
		'<input type="hidden" name="CODE" id="CODE" value="'.$str_CODE.'">'
	);
}
else
{
	$tabControl->AddEditField("NAME", "Название:", true, array("size" => 50, "maxlength" => 255), $str_NAME);
}
	if (false == ((true == defined('BT_UT_AUTOCOMPLETE')) && (1 == BT_UT_AUTOCOMPLETE)))
	{
		$rsLinkedProps = CIBlockProperty::GetList(array(), array(
			"PROPERTY_TYPE" => "E",
			"LINK_IBLOCK_ID" => $IBLOCK_ID,
			"ACTIVE" => "Y",
			"FILTRABLE" => "Y",
		));
		$arLinkedProp = $rsLinkedProps->GetNext();
		if($arLinkedProp)
		{
			$tabControl->BeginCustomField("LINKED_PROP", GetMessage("IBLOCK_ELEMENT_EDIT_LINKED"));
			?>
			<tr class="heading" id="tr_LINKED_PROP">
				<td colspan="2"><?echo $tabControl->GetCustomLabelHTML();?></td>
			</tr>
			<?
			do {
				$elements_name = CIBlock::GetArrayByID($arLinkedProp["IBLOCK_ID"], "ELEMENTS_NAME");
				if(strlen($elements_name) <= 0)
					$elements_name = GetMessage("IBLOCK_ELEMENT_EDIT_ELEMENTS");
			?>
			<tr id="tr_LINKED_PROP<?echo $arLinkedProp["ID"]?>">
				<td colspan="2"><a href="<?echo htmlspecialchars(CIBlock::GetAdminElementListLink($arLinkedProp["IBLOCK_ID"], array('set_filter'=>'Y', 'find_el_property_'.$arLinkedProp["ID"]=>$ID)))?>"><?echo CIBlock::GetArrayByID($arLinkedProp["IBLOCK_ID"], "NAME").": ".$elements_name?></a></td>
			</tr>
			<?
			} while ($arLinkedProp = $rsLinkedProps->GetNext());
			$tabControl->EndCustomField("LINKED_PROP", "");
		}
	}

$tabControl->BeginCustomField("SECTIONS", GetMessage("IBLOCK_SECTION"), $arIBlock["FIELDS"]["IBLOCK_SECTION"]["IS_REQUIRED"] === "Y");
	?>
	<tr id="tr_SECTIONS">
	<?if($arIBlock["SECTION_CHOOSER"] != "D" && $arIBlock["SECTION_CHOOSER"] != "P"):?>

		<?$l = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));?>
		<td width="40%" class="adm-detail-valign-top"><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td width="60%">
		<select name="IBLOCK_SECTION[]" size="14" multiple onchange="onSectionChanged()">
			<option value="0"<?if(is_array($str_IBLOCK_ELEMENT_SECTION) && in_array(0, $str_IBLOCK_ELEMENT_SECTION))echo " selected"?>><?echo GetMessage("IBLOCK_UPPER_LEVEL")?></option>
		<?
			while($ar_l = $l->GetNext()):
				?><option value="<?echo $ar_l["ID"]?>"<?if(is_array($str_IBLOCK_ELEMENT_SECTION) && in_array($ar_l["ID"], $str_IBLOCK_ELEMENT_SECTION))echo " selected"?>><?echo str_repeat(" . ", $ar_l["DEPTH_LEVEL"])?><?echo $ar_l["NAME"]?></option><?
			endwhile;
		?>
		</select>
		</td>

	<?elseif($arIBlock["SECTION_CHOOSER"] == "D"):?>
		<td width="40%" class="adm-detail-valign-top"><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td width="60%">
			<table class="internal" id="sections">
			<?
			if(is_array($str_IBLOCK_ELEMENT_SECTION))
			{
				$i = 0;
				foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
				{
					$rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
					$strPath = "";
					while($arChain = $rsChain->GetNext())
						$strPath .= $arChain["NAME"]."&nbsp;/&nbsp;";
					if(strlen($strPath) > 0)
					{
						?><tr>
							<td nowrap><?echo $strPath?></td>
							<td>
							<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
							<input type="hidden" name="IBLOCK_SECTION[]" value="<?echo intval($section_id)?>">
							</td>
						</tr><?
					}
					$i++;
				}
			}
			?>
			<tr>
				<td>
				<script type="text/javascript">
				function deleteRow(button)
				{
					var my_row = button.parentNode.parentNode;
					var table = document.getElementById('sections');
					if(table)
					{
						for(var i=0; i<table.rows.length; i++)
						{
							if(table.rows[i] == my_row)
							{
								table.deleteRow(i);
								onSectionChanged();
							}
						}
					}
				}
				function addPathRow()
				{
					var table = document.getElementById('sections');
					if(table)
					{
						var section_id = 0;
						var html = '';
						var lev = 0;
						var oSelect;
						while(oSelect = document.getElementById('select_IBLOCK_SECTION_'+lev))
						{
							if(oSelect.value < 1)
								break;
							html += oSelect.options[oSelect.selectedIndex].text+'&nbsp;/&nbsp;';
							section_id = oSelect.value;
							lev++;
						}
						if(section_id > 0)
						{
							var cnt = table.rows.length;
							var oRow = table.insertRow(cnt-1);

							var i=0;
							var oCell = oRow.insertCell(i++);
							oCell.innerHTML = html;

							var oCell = oRow.insertCell(i++);
							oCell.innerHTML =
								'<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
								'<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
							onSectionChanged();
						}
					}
				}
				function find_path(item, value)
				{
					if(item.id==value)
					{
						var a = Array(1);
						a[0] = item.id;
						return a;
					}
					else
					{
						for(var s in item.children)
						{
							if(ar = find_path(item.children[s], value))
							{
								var a = Array(1);
								a[0] = item.id;
								return a.concat(ar);
							}
						}
						return null;
					}
				}
				function find_children(level, value, item)
				{
					if(level==-1 && item.id==value)
						return item;
					else
					{
						for(var s in item.children)
						{
							if(ch = find_children(level-1,value,item.children[s]))
								return ch;
						}
						return null;
					}
				}
				function change_selection(name_prefix, prop_id,value,level,id)
				{
					var lev = level+1;
					var oSelect;

					while(oSelect = document.getElementById(name_prefix+lev))
					{
						jsSelectUtils.deleteAllOptions(oSelect);
						jsSelectUtils.addNewOption(oSelect, '0', '(<?echo GetMessage("MAIN_NO")?>)');
						lev++;
					}

					oSelect = document.getElementById(name_prefix+(level+1))
					if(oSelect && (value!=0||level==-1))
					{
						var item = find_children(level,value,window['sectionListsFor'+prop_id]);
						for(var s in item.children)
						{
							var obj = item.children[s];
							jsSelectUtils.addNewOption(oSelect, obj.id, obj.name, true);
						}
					}
					if(document.getElementById(id))
						document.getElementById(id).value = value;
				}
				function init_selection(name_prefix, prop_id, value, id)
				{
					var a = find_path(window['sectionListsFor'+prop_id], value);
					change_selection(name_prefix, prop_id, 0, -1, id);
					for(var i=1;i<a.length;i++)
					{
						if(oSelect = document.getElementById(name_prefix+(i-1)))
						{
							for(var j=0;j<oSelect.length;j++)
							{
								if(oSelect[j].value==a[i])
								{
									oSelect[j].selected=true;
									break;
								}
							}
						}
						change_selection(name_prefix, prop_id, a[i], i-1, id);
					}
				}
				var sectionListsFor0 = {id:0,name:'',children:Array()};

				<?
				$rsItems = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$IBLOCK_ID), array("ID", "NAME", "DEPTH_LEVEL"));
				$depth = 0;
				$max_depth = 0;
				$arChain = array();
				while($arItem = $rsItems->GetNext())
				{
					if($max_depth < $arItem["DEPTH_LEVEL"])
					{
						$max_depth = $arItem["DEPTH_LEVEL"];
					}
					if($depth < $arItem["DEPTH_LEVEL"])
					{
						$arChain[]=$arItem["ID"];

					}
					while($depth > $arItem["DEPTH_LEVEL"])
					{
						array_pop($arChain);
						$depth--;
					}
					$arChain[count($arChain)-1] = $arItem["ID"];
					echo "sectionListsFor0";
					foreach($arChain as $i)
						echo ".children['".intval($i)."']";

					echo " = { id : ".$arItem["ID"].", name : '".CUtil::JSEscape($arItem["NAME"])."', children : Array() };\n";
					$depth = $arItem["DEPTH_LEVEL"];
				}
				?>
				</script>
				<?
				for($i = 0; $i < $max_depth; $i++)
					echo '<select id="select_IBLOCK_SECTION_'.$i.'" onchange="change_selection(\'select_IBLOCK_SECTION_\',  0, this.value, '.$i.', \'IBLOCK_SECTION[n'.$key.']\')"><option value="0">('.GetMessage("MAIN_NO").')</option></select>&nbsp;';
				?>
				<script type="text/javascript">
					init_selection('select_IBLOCK_SECTION_', 0, '', 0);
				</script>
				</td>
				<td><input type="button" value="<?echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>" onClick="addPathRow()"></td>
			</tr>
			</table>
		</td>

	<?else:?>
		<td width="40%" class="adm-detail-valign-top"><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td width="60%">
			<table id="sections" class="internal">
			<?
			if(is_array($str_IBLOCK_ELEMENT_SECTION))
			{
				$i = 0;
				foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
				{
					$rsChain = CIBlockSection::GetNavChain($IBLOCK_ID, $section_id);
					$strPath = "";
					while($arChain = $rsChain->GetNext())
						$strPath .= $arChain["NAME"]."&nbsp;/&nbsp;";
					if(strlen($strPath) > 0)
					{
						?><tr>
							<td><?echo $strPath?></td>
							<td>
							<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">
							<input type="hidden" name="IBLOCK_SECTION[]" value="<?echo intval($section_id)?>">
							</td>
						</tr><?
					}
					$i++;
				}
			}
			?>
			</table>
				<script type="text/javascript">
				function deleteRow(button)
				{
					var my_row = button.parentNode.parentNode;
					var table = document.getElementById('sections');
					if(table)
					{
						for(var i=0; i<table.rows.length; i++)
						{
							if(table.rows[i] == my_row)
							{
								table.deleteRow(i);
								onSectionChanged();
							}
						}
					}
				}
				function InS<?echo md5("input_IBLOCK_SECTION")?>(section_id, html)
				{
					var table = document.getElementById('sections');
					if(table)
					{
						if(section_id > 0 && html)
						{
							var cnt = table.rows.length;
							var oRow = table.insertRow(cnt-1);

							var i=0;
							var oCell = oRow.insertCell(i++);
							oCell.innerHTML = html;

							var oCell = oRow.insertCell(i++);
							oCell.innerHTML =
								'<input type="button" value="<?echo GetMessage("MAIN_DELETE")?>" OnClick="deleteRow(this)">'+
								'<input type="hidden" name="IBLOCK_SECTION[]" value="'+section_id+'">';
							onSectionChanged();
						}
					}
				}
				</script>
				<input name="input_IBLOCK_SECTION" id="input_IBLOCK_SECTION" type="hidden">
				<input type="button" value="<?echo GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD")?>..." onClick="jsUtils.OpenWindow('<?=$selfFolderUrl?>iblock_section_search.php?lang=<?echo LANGUAGE_ID?>&amp;IBLOCK_ID=<?echo $IBLOCK_ID?>&amp;n=input_IBLOCK_SECTION&amp;m=y&amp;iblockfix=y&amp;tableId=iblocksection-<?=$IBLOCK_ID; ?>', 900, 700);">
		</td>
	<?endif;?>
	</tr>
	<input type="hidden" name="IBLOCK_SECTION[]" value="">
	<script type="text/javascript">
	function onSectionChanged()
	{
		<?
		$additionalParams = '';
		if ($bCatalog)
		{
			$catalogParams = array('TMP_ID' => $TMP_ID);
			CCatalogAdminTools::addTabParams($catalogParams);
			if (!empty($catalogParams))
			{
				foreach ($catalogParams as $name => $value)
				{
					if ($additionalParams != '')
						$additionalParams .= '&';
					$additionalParams .= urlencode($name) . "=" . urlencode($value);
				}
				unset($name, $value);
			}
			unset($catalogParams);
		}
		?>
		var form = BX('<?echo CUtil::JSEscape($tabControl->GetFormName())?>'),
			url = '<?echo CUtil::JSEscape($APPLICATION->GetCurPageParam($additionalParams))?>',
			selectedTab = BX(s='<?echo CUtil::JSEscape("form_element_".$IBLOCK_ID."_active_tab")?>'),
			groupField;

		if (selectedTab && selectedTab.value)
		{
			url += '&<?echo CUtil::JSEscape("form_element_".$IBLOCK_ID."_active_tab")?>=' + selectedTab.value;
		}
		<?if($arIBlock["SECTION_PROPERTY"] === "Y" || defined("CATALOG_PRODUCT")):?>
		groupField = new JCIBlockGroupField(form, 'tr_IBLOCK_ELEMENT_PROPERTY', url);
		groupField.reload();
		<?endif;
		if($arIBlock["FIELDS"]["IBLOCK_SECTION"]["DEFAULT_VALUE"]["KEEP_IBLOCK_SECTION_ID"] === "Y"):?>
		InheritedPropertiesTemplates.updateInheritedPropertiesValues(false, true);
		<?endif?>
	}
	</script>
	<?
	$hidden = "";
	if(is_array($str_IBLOCK_ELEMENT_SECTION))
		foreach($str_IBLOCK_ELEMENT_SECTION as $section_id)
			$hidden .= '<input type="hidden" name="IBLOCK_SECTION[]" value="'.intval($section_id).'">';
	$tabControl->EndCustomField("SECTIONS", $hidden);
	
/*
$prop_fields = $PROP["PARENT_ID"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);
*/


$tabControl->BeginCustomField("PREVIEW_TEXT", "Описание запланированных работ:", $arIBlock["FIELDS"]["PREVIEW_TEXT"]["IS_REQUIRED"] === "Y");
?>
	<tr id="tr_PREVIEW_TEXT">
    	<td><?echo $tabControl->GetCustomLabelHTML()?></td>
		<td align="left">
			<input type="hidden" name="PREVIEW_TEXT_TYPE" id="PREVIEW_TEXT_TYPE_text" value="text"<?if($str_DETAIL_TEXT_TYPE!="html")echo " checked"?>>
            <textarea cols="60" rows="5" name="PREVIEW_TEXT" style="width:100%"><?echo $str_PREVIEW_TEXT?></textarea>
		</td>
	</tr>
<?
$tabControl->EndCustomField("PREVIEW_TEXT",
	'<input type="hidden" name="PREVIEW_TEXT" value="'.$str_DETAIL_TEXT.'">'.
	'<input type="hidden" name="PREVIEW_TEXT_TYPE" value="'.$str_DETAIL_TEXT_TYPE.'">'
);


$prop_fields = $PROP["photos_before"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$prop_fields = $PROP["photos"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);



$tabControl->AddSection("IBLOCK_ELEMENT_PROP_VALUE", "Сроки и финансирование");

$prop_fields = $PROP["date_start"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:<br />
		<?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["date_finish"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:<br />
		<?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

/*
$prop_fields = $PROP["summ"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);
*/

$prop_fields = $PROP["finance"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["federal"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["republican"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["municipal"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["offbudget"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);




$prop_fields = $PROP["type_work"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["builder"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td>
			<?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?>
			<ul class="search_result"></ul>
		</td>
	</tr>
	<script type="text/javascript">
	
		//Фильтр по застройщикам
		$('[name^="PROP[1148]"]').bind("click", function() {
			$.ajax({
				dataType: "json",
				url: "/bitrix/templates/maps-build-plan/ajax_builder.php",
				data: {'builder':this.value}
			}).done(function(data) {
				$('.search_result').html(data).fadeIn();
			});
		})
		
		$('[name^="PROP[1148]"]').bind("change", function() {
			$(".search_result").fadeOut();
		})
		
		//При выборе результата поиска, прячем список и заносим выбранный результат в input
		$(".search_result").on("click", "li", function(){
			s_user = $(this).text();
			$('[name^="PROP[1148]"]').val(s_user);
			$(".search_result").fadeOut();
		})
		
	</script>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$prop_fields = $PROP["project_finance"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td>
			<?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?>
			<ul class="search_result_project_finance"></ul>
		</td>
	</tr>
	
	<script type="text/javascript">
	
		//Фильтр по проектам
		$('[name^="PROP[1154]"]').bind("click", function() {
			$.ajax({
				dataType: "json",
				url: "/bitrix/templates/maps-build-plan/ajax_project_finance.php",
				data: {'project_finance':this.value}
		}).done(function(data) {
				$('.search_result_project_finance').html(data).fadeIn();
			});
		})
		
		$('[name^="PROP[1154]"]').bind("change", function() {
			$(".search_result_project_finance").fadeOut();
		})
			
		//При выборе результата поиска, прячем список и заносим выбранный результат в input
		$(".search_result_project_finance").on("click", "li", function(){
			s_user = $(this).text();
			$('[name^="PROP[1154]"]').val(s_user);
			$(".search_result_project_finance").fadeOut();
		})
		
	</script>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$prop_fields = $PROP["link"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td>
			<?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?>
			<p class="link-invalid">Внимание: ссылка не корректна!</p>
			<p class="link-valid">Ссылка корректна</p>
		</td>
	</tr>
	
	<script type="text/javascript">
	
		//Регулярка для проверки ссылки
		$('[name^="PROP[1157]"]').bind("change input", function() {
			url_validate = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
			if(!url_validate.test(this.value)){
			   $('.link-invalid').show();
			   $('.link-valid').hide();
			}
			else{
			   $('.link-invalid').hide();
			   $('.link-valid').show();
			}
		})	
	</script>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);

$tabControl->BeginNextFormTab();

$prop_fields = $PROP["category"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?_ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0) && (!$bPropertyAjax)), $bVarsFromForm||$bPropertyAjax, 50000, $tabControl->GetFormName(), $bCopy);?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}


$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$prop_fields = $PROP["type"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
$type_values = $prop_fields["VALUE"];
$type_name = 'PROPtype';
$res = "";
if(!is_array($type_values)) $type_values = array();
foreach($type_values as $key => $value){
	if (is_array($value) && array_key_exists("VALUE", $value))
		$type_values[$key] = $value["VALUE"];
}
$bNoValue = true;
$prop_enums = CIBlockProperty::GetPropertyEnum($prop_fields["ID"]);
while($ar_enum = $prop_enums->Fetch()){
	$sel = in_array($ar_enum["ID"], $type_values);
	if($sel)
		$bNoValue = false;
			$res .= '<option value="'.htmlspecialchars($ar_enum["ID"]).'"'.($sel?" selected":"").'>'.htmlspecialcharsex($ar_enum["VALUE"]).'</option>';
}
$res = '<select id="'.$type_name.'" name="'.$type_name.'" size="4">'.'<option value=""'.($bNoValue?' selected':'').'>'.htmlspecialcharsex(($def_text ? $def_text : GetMessage("IBLOCK_AT_PROP_NA") )).'</option>'.$res.'</select>';
?>
<div style="display:none" id="PROPtypeF"><?php _ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0)), $bVarsFromForm, 50000, $tabControl->GetFormName());?></div>
<tr name="tr_PROPERTY_type"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
	<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><?=$res?></td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$prop_fields = $PROP["lon"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr><td colspan="2" align="center"><p style="text-align:center; font-size:14px; color:red;">Внимание! Метка на карте ставится правой кнопкой мыши!</p><iframe style="border:none" src="/bitrix/php_interface/include/maps/iblock_yandex_map.php" width="648" height="520">Ваш браузер не поддерживает фреймы!</iframe></td></tr>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><div style="display:none" id="PROPlonF"><?php _ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0)), $bVarsFromForm, 50000, $tabControl->GetFormName());?></div>
					<?php if (!is_array($prop_fields["VALUE"])): ?>
						<input type="text" id="PROPlon" name="PROPlon" value="<?=$prop_fields["VALUE"]?>" disabled="disabled" size="60" />
					<?php elseif (count($prop_fields["VALUE"]) < 1): ?>
                    	<input type="text" id="PROPlon" name="PROPlon" value="" disabled="disabled" size="60" />
					<?php else:
						foreach ($prop_fields["VALUE"] as $val):
							if (is_array($val)): ?>
							<input type="text" id="PROPlon" name="PROPlon" value="<?=$val["VALUE"]?>" disabled="disabled" size="60" />
                            <?php else: ?>
                            <input type="text" id="PROPlon" name="PROPlon" value="<?=$val?>" disabled="disabled" size="60" />
                            <?php endif; ?>
						<?php endforeach;
					endif;
					?>
		</td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$prop_fields = $PROP["lat"];
$prop_values = $prop_fields["VALUE"];
$tabControl->BeginCustomField("PROPERTY_".$prop_fields["ID"], $prop_fields["NAME"], $prop_fields["IS_REQUIRED"]==="Y");
?>
	<tr id="tr_PROPERTY_<?echo $prop_fields["ID"];?>"<?if ($prop_fields["PROPERTY_TYPE"]=="F"):?> class="adm-detail-file-row"<?endif?>>
		<td class="adm-detail-valign-top"><?if($prop_fields["HINT"]!=""):
			?><span id="hint_<?echo $prop_fields["ID"];?>"></span><script type="text/javascript">BX.hint_replace(BX('hint_<?echo $prop_fields["ID"];?>'), '<?echo CUtil::JSEscape(htmlspecialcharsbx($prop_fields["HINT"]))?>');</script>&nbsp;<?
		endif;?><?echo $tabControl->GetCustomLabelHTML();?>:</td>
		<td><div style="display:none" id="PROPlatF"><?php _ShowPropertyField('PROP['.$prop_fields["ID"].']', $prop_fields, $prop_fields["VALUE"], (($historyId <= 0) && (!$bVarsFromForm) && ($ID<=0)), $bVarsFromForm, 50000, $tabControl->GetFormName());?></div>
					<?php if (!is_array($prop_fields["VALUE"])): ?>
						<input type="text" id="PROPlat" name="PROPlat" value="<?=$prop_fields["VALUE"]?>" disabled="disabled" size="60" />
					<?php elseif (count($prop_fields["VALUE"]) < 1): ?>
                    	<input type="text" id="PROPlat" name="PROPlat" value="" disabled="disabled" size="60" />
					<?php else:
						foreach ($prop_fields["VALUE"] as $val):
							if (is_array($val)): ?>
							<input type="text" id="PROPlat" name="PROPlat" value="<?=$val["VALUE"]?>" disabled="disabled" size="60" />
                            <?php else: ?>
                            <input type="text" id="PROPlat" name="PROPlat" value="<?=$val?>" disabled="disabled" size="60" />
                            <?php endif; ?>
						<?php endforeach;
					endif;
					?>
		</td>
	</tr>
<?
$hidden = "";
if(!is_array($prop_fields["~VALUE"]))
	$values = Array();
else
	$values = $prop_fields["~VALUE"];
$start = 1;
foreach($values as $key=>$val)
{
	if($bCopy)
	{
		$key = "n".$start;
		$start++;
	}
	if(is_array($val) && array_key_exists("VALUE",$val))
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val["VALUE"]);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', $val["DESCRIPTION"]);
	}
	else
	{
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][VALUE]', $val);
		$hidden .= _ShowHiddenValue('PROP['.$prop_fields["ID"].']['.$key.'][DESCRIPTION]', "");
	}
}
$tabControl->EndCustomField("PROPERTY_".$prop_fields["ID"], $hidden);


$bDisabled = $view=="Y" || ($bWorkflow && $prn_LOCK_STATUS=="red");

if (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1):
	ob_start();
	?>
	<input <?if ($bDisabled) echo "disabled";?> type="submit" class="button" name="save" id="save" value="<?echo GetMessage("IBLOCK_EL_SAVE")?>">
	<input <?if ($bDisabled) echo "disabled";?> type="submit" class="button" name="apply" id="apply" value="<?echo GetMessage('IBLOCK_APPLY')?>">
	<input <?if ($bDisabled) echo "disabled";?> type="submit" class="button" name="dontsave" id="dontsave" value="<?echo GetMessage("IBLOCK_EL_CANC")?>">
	<?
	$buttons_add_html = ob_get_contents();
	ob_end_clean();
	$tabControl->Buttons(false, $buttons_add_html);
else:
	$tabControl->ButtonsPublic(array(
		'.btnSave',
		($ID > 0 && $bWorkflow)
			? "{
	title: '".CUtil::JSEscape(GetMessage("IBLOCK_EL_CANC"))."',
	name: 'dontsave',
	id: 'dontsave',
	action: function () {
		var FORM = this.parentWindow.GetForm();
		FORM.appendChild(BX.create('INPUT', {
			props: {
				type: 'hidden',
				name: this.name,
				value: 'Y'
			}
		}));

		this.disableUntilError();
		this.parentWindow.Submit();
	}
}"
			: '.btnCancel'
	));
endif;

$tabControl->Show();
if (
	(!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1)
	&& $BlockPerm >= "X"
	&& !(defined('BT_UT_AUTOCOMPLETE') && (1 == BT_UT_AUTOCOMPLETE))
)
{

	echo
		BeginNote(),
		GetMessage("IBEL_E_IBLOCK_MANAGE_HINT"),
		' <a href="/bitrix/admin/iblock_edit.php?type='.htmlspecialchars($type).'&amp;lang='.LANG.'&amp;ID='.$IBLOCK_ID.'&amp;admin=Y&amp;return_url='.urlencode("/bitrix/admin/iblock_element_edit.php?ID=".$ID.($WF=="Y"?"&WF=Y":"")."&lang=".LANG. "&type=".htmlspecialchars($type)."&IBLOCK_ID=".$IBLOCK_ID."&find_section_section=".intval($find_section_section).(strlen($return_url)>0?"&return_url=".UrlEncode($return_url):"")).'">',
		GetMessage("IBEL_E_IBLOCK_MANAGE_HINT_HREF"),
		'</a>',
		EndNote()
	;
}
?>