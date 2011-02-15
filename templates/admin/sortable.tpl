	<br/><div id="controls" {if $smarty.foreach.count_items.iteration <= $conf.items_per_page}style="display:none;"{/if}>
		<div id="perpage">
			<select onchange="setCookie('per_page',this.value);sorter.size(this.value)">
			<option value="5" {if $smarty.cookies.per_page eq "5"} selected="selected"{elseif $conf.items_per_page eq "5"}selected="selected"{/if}>5</option>
				<option value="10" {if $smarty.cookies.per_page eq "10"} selected="selected"{elseif $conf.items_per_page eq "10"}selected="selected"{/if}>10</option>
				<option value="20" {if $smarty.cookies.per_page eq "20"} selected="selected"{elseif $conf.items_per_page eq "20"}selected="selected"{/if}>20</option>
				<option value="50" {if $smarty.cookies.per_page eq "50"} selected="selected"{elseif $conf.items_per_page eq "50"}selected="selected"{/if}>50</option>
				<option value="100" {if $smarty.cookies.per_page eq "100"} selected="selected"{elseif $conf.items_per_page eq "100"}selected="selected"{/if}>100</option>
			</select>
			<span>{#entries_per_page#}</span>
		</div>

		<div id="tablenavigation">
			<img src="{$BASE_URL}/templates/admin/images/first.gif" width="16" height="16" title="{#first_page#}" onclick="sorter.move(-1,true)" />
			<img src="{$BASE_URL}/templates/admin/images/previous.gif" width="16" height="16" title="{#previous_page#}" onclick="sorter.move(-1)" />
			<img src="{$BASE_URL}/templates/admin/images/next.gif" width="16" height="16" title="{#next_page#}" onclick="sorter.move(1)" />
			<img src="{$BASE_URL}/templates/admin/images/last.gif" width="16" height="16" title="{#last_page#}" onclick="sorter.move(1,true)" />
		</div>

		<div id="text">{#displaying_page#} <span id="currentpage"></span> {#of#} <span id="pagelimit"></span></div>
	</div>

	<script type="text/javascript" src="{$BASE_URL}/js/tablesort.js"></script>
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.pagesize = "{if $smarty.cookies.per_page}{$smarty.cookies.per_page}{else}{$conf.items_per_page}{/if}"; 
	sorter.sortmethod = "desc";	
	sorter.init("table",0);
  </script>
