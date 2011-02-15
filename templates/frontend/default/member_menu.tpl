<div id="member_menu">
<ul>
		<li><a href="{$BASE_URL}/members/index.php">{#member_panel#}</a></li>
					{if count_member_orders($member.member_id) > "0"}
					<li><a href="orders.php" title="View Orders">{#menu_orders#} ({count_member_orders member_id=$member.member_id})</a></li>
					{/if}
		<li><a href="{$BASE_URL}/members/login.php?logout">{#logout#}</a></li>		
				</ul>
				</div>

