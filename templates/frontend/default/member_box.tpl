{if $smarty.session.res.listing_id > "0"}
{include file="frontend/$template/round_corner_top.tpl"}
<div align="center">
<img src="{$BASE_URL}/images/alert_small.gif" border="0">&nbsp;&nbsp;&nbsp;<a href="{$baseurl}/booking"><br/><b>{#review_confirm#} {#booking#}</b></a>
</div>
{include file="frontend/$template/round_corner_bottom.tpl"}
{/if}
        {if $conf.member_allow_register}
{include file="frontend/$template/round_corner_top.tpl"}

	{if $member}
		<div id="navigation">
	    <ul>
		<li><a href="{$BASE_URL}/profile">{#menu_profile#}</a></li>
		<li><a href="{$BASE_URL}/reservations">{#reservations#}</a></li>		
		<li><a href="{$BASE_URL}/logout/redirect_to/{$request_uri}">{#logout#}</a></li>		
		</ul>
		</div>
		{else}
		<div align="center">
		<a href="#" onClick="javascript:toggle_lightbox('{$baseurl}/login_box', 'progress_bar_lightbox'); return false;"><b>{#login#}</b></a>	
		</div>
		<div id="page_body"></div>		
		{/if}
{include file="frontend/$template/round_corner_bottom.tpl"}
{/if}