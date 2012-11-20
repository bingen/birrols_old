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

// if( !$globals['open'] && !$current_user->authenticated ) {
//   header("Location:". 'index.php?error_login=');
//   exit();
// }

cabecera('', $_SERVER['PHP_SELF']);

if( !$globals['open'] ) {
  check_login();
} 

$tabla = 'beers_view';
$url = $globals['base_url'].'table_'. $tabla .'.php';
$div = 'results';
echo "<script type='text/javascript'> var table_url='$url';</script>\n";
echo "<script src='".$globals['js_url']. "jquery-ui.min.js'></script>\n";
echo "<script src='".$globals['js_url']. "jquery.select-to-autocomplete.min.js'></script>\n";
echo "<script src='".$globals['js_url']. "beers.js'></script>\n";

echo '<div id="container_cuerpo">'."\n";
echo '<div id="cuerpo">'. "\n";

if( $current_user->authenticated )
  echo "<a class='button' href='beer_edit.php' title='". $idioma['beer_new'] ."'>". $idioma['beer_new'] ." </a> \n";


echo "   <div id='beers-container'> \n";
$query = "SELECT category FROM beer_categories LIMIT 3";
$beer_categories = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
for( $i = 0; $i < mysqli_num_rows($beer_categories); $i++)
  $row[] = mysqli_fetch_object($beer_categories);

echo "      <div id='search-container'> \n";
echo "         <div id='filters'> \n";
// echo "         <ul id='filter-list'> \n";
echo "           <ul id='type-list' class='filter-ul'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['bsns_type'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
echo "             <li id='ale-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='ale-check' value='". $row[0]->category ."' onChange='reload_div(get_reload_url(), \"$div\")'/> \n";
echo "               <span>". $row[0]->category ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // ale-filter
echo "             <li id='lager-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='lager-check' value='". $row[1]->category ."' onChange='reload_div(get_reload_url(), \"$div\")' /> \n";
echo "               <span>". $row[1]->category ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // lager-filter
echo "             <li id='lambic-filter' class='filter-li'> \n";
echo "               <label class='filter-label'> \n";
echo "               <input type='checkbox' id='lambic-check' value='". $row[2]->category ."' onChange='reload_div(get_reload_url(), \"$div\")' /> \n";
echo "               <span>". $row[2]->category ."</span> \n";
echo "               </label> \n";
echo "             </li> \n"; // lambic-filter
echo "           </ul> \n"; // type-list
// type ////////////////
echo "           <div id='type-div' class='filter-div'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['beer_type'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
  echo "<select name='type_id' id='type_id' class='turn-to-ac' >\n";
  echo "<option value='' ". ( $country_id=='' ? "selected='selected'" : "" ) .">". $idioma['beer_sel_type'] ."</option> \n";
  // TODO: relevancy-booster
  $query = "SELECT auto_id, type FROM beer_types";
  $res = mysqli_query( $mysql_link, $query );
  while( $row = mysqli_fetch_object( $res ) )
    echo "<option value='". $row->auto_id ."' ". ( $type_id == $row->auto_id ? "selected='selected'" : "" ) .">". $row->type ."</option> \n";
  echo "</select>\n";
echo "           </div> \n"; // type
// brewery ////////////////
echo "           <div id='brewery-div' class='filter-div'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['brewery'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
echo "		   <input type='search' name='brewery' id='brewery' results=5 placeholder='". $idioma['beer_search_brewery'] ."' />\n";
echo "		   <input type='hidden' name='brewery_id' id='brewery_id'/>\n";
echo "           </div> \n"; // brewery
// country ////////////////
echo "           <div id='country-div' class='filter-div'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['bsns_country'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
input_country();
echo "           </div> \n"; // country
slider_filter( "abv", $idioma['beer_abv'] );
echo "           <div id='ibu-div'> \n";
echo "             <h4 class='filter-header'>\n";
echo "             ". $idioma['beer_ibu'] ."\n";
echo "             <span class='filter-toggle'></span>\n";
echo "             </h4>\n";
echo "               <input type='text' id='ibu' class='slider-text'/> \n";
echo "               <input type='hidden' id='ibu-min' /> \n";
echo "               <input type='hidden' id='ibu-max' /> \n";
echo "           <div id='slider-ibu'></div> \n";
echo "           </div> \n"; // ibu-div
// echo "         </ul> \n"; // filter-list
echo "         </div> \n"; // filters

echo '        <div id="results" class="results">' . "\n";
include('table_'. $tabla .'.php');
echo '        </div>' . "\n"; // results
echo "      </div> \n"; // search container
echo "   </div> \n"; // business container

echo '	  </div> <!-- cuerpo -->'. "\n";
//echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();

?>
