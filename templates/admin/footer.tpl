{if $load_google_api}
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
			</div>
		</div>
		<div class="footer">
			<p>
			&copy; Copyright 2009 <a href="http://raysolutions.net" target="_blank">Raysolutions</a></p>
		</div>
	</div>
</body>
</html>