{include file="admin/header.tpl"}
{include file="admin/html_editor.tpl"}
<div class="left">
			<h3>{#edit_category#}: {$category.title|stripslashes}</h3>
			<div class="left_box">
{include file="errors.tpl" errors=$error error_count=$error_count}
{include file="admin/msg.tpl"}
<script language="JavaScript">
	// Enter name of mandatory fields
	var fieldRequired = Array("title", "uri");
	// Enter field description to appear in the dialog box
	var fieldDescription = Array("{#title#}", "{#uri#}");
</script>
<ul id="countrytabs" class="shadetabs">
{foreach from=$languages_array item=lang}
<li><a href="categories.php?edit={$category.cat_id}&edit_lang={$lang.lang_name}" {if $edit_lang == $lang.lang_name}class="selected"{/if}><img src="{$BASE_URL}/uploads/flags/{$lang.lang_name}.gif" border="0" />&nbsp;{$lang.lang_title}</a></li>
{/foreach}
</ul>
<div id="countrydivcontainer" style="border:1px solid gray; width:850px; margin-bottom: 1em; padding: 10px">
<form method="post" action="{$smarty.server.PHP_SELF|xss}" ENCTYPE="multipart/form-data" onsubmit="return formCheck(this);">
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" />
<table border="0" class="tbl">
<tr><td>{#title#}:</td><td><input type="text" name="title" id="title" class="required" value="{$category.title|stripslashes}" onMouseOver="showhint('{#hint_category_title#}', this, event, '150px')" />
{if $edit_lang != $language}
<a onClick="submitChange('title');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
</td></tr>
</table>
{#description#}:<a href="#" class="hintanchor" onMouseover="showhint('{#hint_category_description#}', this, event, '150px')">[?]</a><textarea name="description" id="description" cols="70" rows="17">{$category.description|stripslashes}</textarea><br/>
{if $edit_lang != $language}
<a onClick="toggleEditor('description');submitChange('description');" title="{#auto_translate#}"><img src="{$BASE_URL}/images/translate.gif" border="0" alt="{#auto_translate#}" style="float:right;cursor:pointer;" /></a>
{/if}
<a href="javascript:toggleEditor('description');">{#show_hide_editor#}</a><br/>
<table border="0" class="tbl">
<tr><td>{#parent_category#}:</td><td>
<select name="category" onMouseOver="showhint('{#hint_parent_category#}', this, event, '150px')">
<option value="0">{#main_category#}</option>
<option value="0">------------------</option>
{foreach from=$main_categories item=cat}
<option value="{$cat.cat_id}" {if $cat.cat_id == $category.parent}selected{/if}>{$cat.title}</option>
{/foreach}
</select>
</td></tr>
<tr><td>{#uri#}:</td><td><input type="text" name="uri"  class="required" value="{$category.uri}" onMouseOver="showhint('{#hint_category_uri#}', this, event, '150px')" /></td></tr>
</table>
<input type="hidden" name="edit_lang" value="{$edit_lang}" />
<input type="hidden" name="edit_category" value="{$category.cat_id}" />
<input type="submit" value="{#save_changes#}" /> &nbsp;&nbsp;&nbsp;<input class="submit" name="Button" type="button" onClick="window.location='categories.php'" value="{#back_categories#}" /></form>
<br/>
Editing language: <b>{$edit_lang}</b>
</div>
</div>
  <script type="text/javascript">
    google.load("language", "1");
	var form_element;
{literal}
   function submitChange(where) {
{/literal}  
      var value = document.getElementById(where).value;
      form_element = where;
      var src = '{$language}';
      var dest = '{$edit_lang}';
      google.language.translate(value, src, dest, translateResult);
      return false;
{literal}
    }

    function translateResult(result) {
      if (result.translation) {
        document.getElementById(window.form_element).value = result.translation;
      } 
    }
{/literal}	
  </script>
{include file="admin/footer.tpl"}