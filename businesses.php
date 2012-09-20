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

cabecera($globals['app_name'], $_SERVER['PHP_SELF']);

echo '<div id="cuerpo">'. "\n";

echo "   <div id='business-container'> \n";
echo "      <div id='search-bar'> \n";
echo "      </div> \n"; // search bar
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
echo "               <input type='checkbox' id='brewery-check' value='brewery' /> \n";
echo "               <span>". $idioma['breweries'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // brewery-filter
echo "             <li id='pub-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='pub-check' value='pub' /> \n";
echo "               <span>". $idioma['pubs'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // pub-filter
echo "             <li id='store-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='store-check' value='store' /> \n";
echo "               <span>". $idioma['stores'] ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // store-filter
echo "           </ul> \n"; // type-list
echo "         </ul> \n"; // filter-list
echo "         </div> \n"; // filters
echo "         <div id='results'> \n";
$tabla = 'business_view';
include('table_'. $tabla .'.php');
echo "         </div> \n"; // results
echo "      </div> \n"; // search container
echo "   </div> \n"; // business container
//echo " \n";

//echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();


?>

