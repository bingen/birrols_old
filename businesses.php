<?php
/*
    Open craft beer
    Web app for craft beer lovers
    Copyright (C) 2012 ßingen Eguzkitza <bingentxu@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include('config.php');

cabecera('', $_SERVER['PHP_SELF']);

if( $current_user->authenticated )
  echo "<a class='button' href='business_edit.php' title='". $idioma['bsns_new'] ."'>". $idioma['bsns_new'] ." </a> \n";


$tabla = 'business_view';
$url = $globals['base_url'].'table_'. $tabla .'.php';
$div = 'results';

echo "<script type='text/javascript'> var table_url='$url';</script>\n";
echo "<script src='".$globals['js_url']. "businesses.js' type='text/javascript' charset='utf-8'></script>\n";

echo '<div id="container_cuerpo">'."\n";
echo '<div id="cuerpo">'. "\n";

echo "   <div id='business-container'> \n";
echo "      <div id='search-bar'> \n";
echo "         <div id='search-type-buttons'> \n";
echo "            <input type='hidden' id='search_type' value=''/> \n";
echo "            <button class='button-left' id='search-button-list' onClick='getElementById(\"search_type\").value=\"list\"; reload_div(get_reload_url(), \"$div\")'>". $idioma['bsns_list'] ."</button>\n";
echo "            <button class='button-right' id='search-button-map'>". $idioma['bsns_map'] ."</button>\n";
echo "         </div> \n"; // search-type-buttons
echo "      </div> \n"; // search bar

// jQuery call to load map on amp button click
// TODO: leaflet
    echo '<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />'."\n";
    echo ' <!--[if lte IE 8]>'."\n";
    echo '     <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.ie.css" />'."\n";
    echo ' <![endif]-->'."\n";
    echo ' <script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>'."\n";
    print("
    <script type='text/javascript'>
    $('#search-button-map').on({
	click: function () {
	    document.getElementById(\"search_type\").value=\"map\";
	    $('#$div').load(get_reload_url(), function() {
		var map = L.map('map').setView([51.505, -0.09], 13);
		L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, Imagery © <a href=\"http://cloudmade.com\">CloudMade</a>'
		}).addTo(map);


		L.marker([51.5, -0.09]).addTo(map)
			.bindPopup(\"<b>Flint!</b><br />Aquí hay bírrols!!.\").openPopup();

		var popup = L.popup();

		function onMapClick(e) {
			popup
				.setLatLng(e.latlng)
				.setContent(\"You clicked the map at \" + e.latlng.toString())
				.openOn(map);
		}

		map.on('click', onMapClick);
	    });
	}
    });
    </script>
    ");

echo "      <div id='search-container'> \n";
echo "         <div id='filters'> \n";
echo "         <ul id='filter-list'> \n";
echo "           <ul id='type-list' class='filter-ul'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['bsns_type'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
echo "             <li id='brewery-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='brewery-check' value='brewery' onChange='reload_div(get_reload_url(), \"$div\")'/> \n";
echo "               <span>". $idioma['breweries'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // brewery-filter
echo "             <li id='pub-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='pub-check' value='pub' onChange='reload_div(get_reload_url(), \"$div\")' /> \n";
echo "               <span>". $idioma['pubs'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // pub-filter
echo "             <li id='store-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='store-check' value='store' onChange='reload_div(get_reload_url(), \"$div\")' /> \n";
echo "               <span>". $idioma['stores'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // store-filter
echo "           </ul> \n"; // type-list
echo "         </ul> \n"; // filter-list
echo "         </div> \n"; // filters
echo "         <div id='results' class='results'> \n";
include('table_'. $tabla .'.php');
echo "         </div> \n"; // results
echo "      </div> \n"; // search container
echo "   </div> \n"; // business container
//echo " \n";

echo '	  </div> <!-- cuerpo -->'. "\n";
//echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();


?>

