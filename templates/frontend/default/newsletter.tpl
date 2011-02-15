{include file="frontend/$template/header.tpl" title=$title}
		<div id="wrapper">
			<div id="content">
					{include file="frontend/$template/search_box.tpl"}
				<div class="box">
				<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
				<div class="padding">
{parse_banner position="center"}

{include file="errors.tpl" errors=$error error_count=$error_count}
<h2>{$listing.title|stripslashes}</h2>
	    {if $smarty.post.newsletter_subscribe}
        <h3>{#newsletter_title#} {#newsletter_subscribe#}</h3>			
			{if count($error) eq "0"}
				{#newsletter_email_sent_to#} {$email}.<br/>
				{#newsletter_msg_subscribe#}
			{/if}
	    {/if}
	    	    
		{if $request.1 eq "confirm"}
        <h3>{#newsletter_title#} {#confirm#}</h3>			
			{if count($error) eq "0"}
				{#newsletter_msg_confirm_subscribe#} {$email}
			{/if}
		{/if}
		
	    {if $smarty.post.newsletter_unsubscribe}
        <h3>{#newsletter_title#} {#newsletter_unsubscribe#}</h3>			
			{if count($error) eq "0"}
				{#newsletter_email_sent_to#} {$email}.<br/>
				{#newsletter_msg_unsubscribe#}
			{/if}
	    {/if}
	    {if $request.1 eq "confirm_unsubscribe"}
        <h3>{#newsletter_title#} {#newsletter_unsubscribe#}</h3>			
			{if count($error) eq "0"}
				{#newsletter_msg_confirm_unsubscribe#} {$email}
			{/if}
	    {/if}
				</div>
				<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
				</div>
			</div>
		</div>
{include file="frontend/$template/left.tpl"}
{include file="frontend/$template/right.tpl"}
{include file="frontend/$template/footer.tpl"}
