{* START GOOGLE TRANSLATE API *}
{if $load_google_api AND $member_logged_in}
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
{/if}
{* EOF GOOGLE TRANSLATE API *}
<center>
{parse_banner position="bottom"}
{* START PAGES BOTTOM MENU *}
{foreach from=$pages_down item=page}
   	[<a href="{$baseurl}/page/{$page.uri}">{$page.title|stripslashes}</a>]
{/foreach}
{* EOF PAGES BOTTOM MENU *}
	<!-- Footer -->
	<div id="footer">
	<div id="copyright"><p>&copy;&nbsp;2010 Amy Travel (India) Private Limited. All Rights Reserved.        
                 <a href="http://raysolutions.net" target="_blank">Powered by RaySolutions</a> <br/> Minimum Browser Requirement: You must have Internet Explorer 6.0 & above or Google Chrome 6.0 & above</p></div>
	</div>
	<!-- /Footer -->
</center>
</div>
</body>
</html>
