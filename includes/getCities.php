<?php
// START "FETCH CITIES"
$country_id=$request[1];
$cities=fetch_cities($country_id);
//$cities=fetch_cities();
$i=1;
echo "document.getElementById('city').options.length = 0;\n";
foreach ($cities as $city) {
	$i++;
	 echo "obj.options[obj.options.length] = new Option('$city[city]','$city[city]');\n";
}
?>