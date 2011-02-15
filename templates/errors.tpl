{if count($errors)}
	<br/><div><fieldset><legend class="error"><img src="{$BASE_URL}/images/error.png" border="0"></legend>
	<font color="red"><b>
	{foreach from=$errors item=error}
		{$error}<br/>
	{/foreach}
	</font></b>
	</fieldset>
	</div><br/>
{/if}