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

echo '<div id="cuerpo">'. "\n";

if( $current_user->authenticated )
  echo "<a class='button' href='beer_edit.php' title='". $idioma['beer_new'] ."'>". $idioma['beer_new'] ." </a> \n";

$tabla = 'beers_view';
echo '    <div id="'. $tabla .'-env" class="tabla-env">' . "\n";
include('table_'. $tabla .'.php');
echo '    </div>' . "\n"; // beers-env

echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();

?>
