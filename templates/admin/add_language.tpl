{include file="admin/header.tpl"}
<div class="left">
			<h3>{#add_new_language#}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("lang_name", "lang_title");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#language_id#}", "{#language_title#}");
</script>
<form method="post" action="{$smarty.server.PHP_SELF|xss}" onsubmit="return formCheck(this);">
<div id="frm">
<label for="name">{#language_id#}:</label>
<select name="lang_name" onChange="document.flag.src = this.options[this.selectedIndex].value;">
{foreach from=$flags item=flag}
<option value="{$BASE_URL}/uploads/flags/{$flag}.gif" {if $flag eq $language}selected{/if}>{$flag|strtoupper}</option>
{/foreach}
</select>&nbsp;&nbsp;<img src="{$BASE_URL}/uploads/flags/{$language}.gif" name="flag" border="0" /><br/>
<label>{#language_title#}:</label><input type="text" name="lang_title" class="required" /><br/>
<label>{#language_encoding#}:</label>	<select name="lang_encoding" style="font-family: verdana">
										<option value="ISO-8859-1">English - ISO-8859-1</option>
										<option value="utf-8" selected="selected"> Universal - utf-8 </option>
										<option value="ISO-8859-1"> Afrikaans - ISO-8859-1 </option>
										<option value="windows-1252"> Afrikaans - windows-1252 </option>
										<option value="ISO-8859-1"> Albanian - ISO-8859-1 </option>
										<option value="windows-1252"> Albanian - windows-1252 </option>
										<option value="ISO-8859-6"> Arabic - ISO-8859-6 </option>
										<option value="ISO-8859-4"> Baltic - ISO-8859-4 </option>
										<option value="ISO-8859-1"> Basque - ISO-8859-1 </option>
										<option value="windows-1252"> Basque - windows-1252 </option>
										<option value="windows-1251"> Bulgarian cyrillic - windows-1251 </option>
										<option value="ISO-8859-5"> Byelorussian - ISO-8859-5 </option>
										<option value="ISO-8859-1"> Catalan - ISO-8859-1 </option>
										<option value="windows-1252"> Catalan - windows-1252 </option>
										<option value="gb2312"> Chinese Simplified - gb2312 </option>
										<option value="hz-gb-2312"> Chinese Simplified - hz-gb-2312 </option>
										<option value="big5"> Chinese Traditional - big5 </option>
										<option value="ISO-8859-2"> Croatian - ISO-8859-2 </option>
										<option value="windows-1250"> Croatian - windows-1250 </option>
										<option value="ISO-8859-2"> Czech - ISO-8859-2 </option>
										<option value="ISO-8859-1"> Danish - ISO-8859-1 </option>
										<option value="windows-1252"> Danish - windows-1252 </option>
										<option value="ISO-8859-1"> Dutch - ISO-8859-1 </option>
										<option value="windows-1252"> Dutch - windows-1252 </option>
										<option value="windows-1252"> English - windows-1252 </option>
										<option value="ISO-8859-3"> Esperanto - ISO-8859-3 </option>
										<option value="ISO-8859-15"> Estonian - ISO-8859-15 </option>
										<option value="ISO-8859-1"> Faroese - ISO-8859-1 </option>
										<option value="windows-1252"> Faroese - windows-1252 </option>
										<option value="ISO-8859-1"> Finnish - ISO-8859-1 </option>
										<option value="windows-1252"> Finnish - windows-1252 </option>
										<option value="ISO-8859-1"> French - ISO-8859-1 </option>
										<option value="windows-1252"> French - windows-1252 </option>
										<option value="ISO-8859-1"> Galician - ISO-8859-1 </option>
										<option value="windows-1252"> Galician - windows-1252 </option>
										<option value="ISO-8859-1"> German - ISO-8859-1 </option>
										<option value="windows-1252"> German - windows-1252 </option>
										<option value="ISO-8859-7"> Greek - ISO-8859-7 </option>
										<option value="ISO-8859-8"> Hebrew - ISO-8859-8 </option>
										<option value="ISO-8859-8-i"> Hebrew - ISO-8859-8-i </option>
										<option value="ISO-8859-2"> Hungarian - ISO-8859-2 </option>
										<option value="ISO-8859-1"> Icelandic - ISO-8859-1 </option>
										<option value="windows-1252"> Icelandic - windows-1252 </option>
										<option value="ISO-8859-10"> Inuit (Eskimo) - ISO-8859-10 </option>
										<option value="ISO-8859-1"> Irish - ISO-8859-1 </option>
										<option value="windows-1252"> Irish - windows-1252 </option>
										<option value="ISO-8859-1"> Italian - ISO-8859-1 </option>
										<option value="windows-1252"> Italian - windows-1252 </option>
										<option value="shift_jis"> Japanese - shift_jis </option>
										<option value="ISO-2022-jp"> Japanese - ISO-2022-jp </option>
										<option value="euc-jp"> Japanese - euc-jp </option>
										<option value="ISO-2022-kr"> Korean - ISO-2022-kr </option>
										<option value="ISO-8859-10"> Lapp - ISO-8859-10 </option>
										<option value="ISO-8859-13"> Latvian - ISO-8859-13 </option>
										<option value="windows-1257"> Latvian - windows-1257 </option>
										<option value="ISO-8859-13"> Lithuanian - ISO-8859-13 </option>
										<option value="windows-1257"> Lithuanian - windows-1257 </option>
										<option value="ISO-8859-5"> Macedonian - ISO-8859-5 </option>
										<option value="windows-1251"> Macedonian - windows-1251 </option>
										<option value="ISO-8859-3"> Maltese - ISO-8859-3 </option>
										<option value="ISO-8859-1"> Norwegian - ISO-8859-1 </option>
										<option value="windows-1252"> Norwegian - windows-1252 </option>
										<option value="ISO-8859-2"> Polish - ISO-8859-2 </option>
										<option value="ISO-8859-1"> Portuguese - ISO-8859-1 </option>
										<option value="windows-1252"> Portuguese - windows-1252 </option>
										<option value="ISO-8859-2"> Romanian - ISO-8859-2 </option>
										<option value="koi8-r"> Russian - koi8-r </option>
										<option value="ISO-8859-5"> Russian - ISO-8859-5 </option>
										<option value="ISO-8859-1"> Scottish - ISO-8859-1 </option>
										<option value="windows-1252"> Scottish - windows-1252 </option>
										<option value="windows-1251"> Serbian cyrillic - windows-1251 </option>
										<option value="ISO-8859-5"> Serbian cyrillic - ISO-8859-5 </option>
										<option value="ISO-8859-2"> Serbian latin - ISO-8859-2 </option>
										<option value="windows-1250"> Serbian latin - windows-1250 </option>
										<option value="ISO-8859-2"> Slovak - ISO-8859-2 </option>
										<option value="ISO-8859-2"> Slovenian - ISO-8859-2 </option>
										<option value="windows-1250"> Slovenian - windows-1250 </option>
										<option value="ISO-8859-1"> Spanish - ISO-8859-1 </option>
										<option value="windows-1252"> Spanish - windows-1252 </option>
										<option value="ISO-8859-1"> Swedish - ISO-8859-1 </option>
										<option value="windows-1252"> Swedish - windows-1252 </option>
										<option value="windows-874"> Thai - windows-874 </option>
										<option value="ISO-8859-9"> Turkish - ISO-8859-9 </option>
										<option value="windows-1254"> Turkish - windows-1254 </option>
										<option value="ISO-8859-5"> Ukrainian - ISO-8859-5 </option>
										<option value="windows-1258"> Vietnamese - windows-1258 </option>
									</select><br/>
<br/>
<input type="submit" name="add_lang" value="{#add_this_language#}" />&nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='languages.php'" value="{#back_languages#}" />
</form>
</div>
</div>
{include file="admin/footer.tpl"}