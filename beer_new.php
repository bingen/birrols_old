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
include(includepath.'log.php');

if( !$current_user->authenticated && empty($_POST['usuario']) && $_REQUEST['error'] != 'login' && !isset($_GET['error_acceso']) ) {
	$url = $_SERVER['PHP_SELF'];
	$_REQUEST['error'] = 'login';
	$query_string = http_build_query( $_REQUEST );
	$url = $_SERVER['PHP_SELF'] . (empty($query_string) ? '' : '?'. http_build_query( $_REQUEST ));
	header("Location:". $url);
	exit();
}
cabecera('', $_SERVER['PHP_SELF']);
echo '<div id="cuerpo">'. "\n";
if( !$current_user->authenticated ) {
	  echo '<p class="error">'.$idioma['err_login'].'</p>'."\n";
} else { // current_user authenticated

  if(isset($_POST["process"])) {
    beer_new_insert();
  } else {
    beer_new_form();
  }
} // if authenticated

echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();

function beer_new_form(){
  global $mysql_link, $idioma;
  
  echo '<form action="'. $_SERVER['PHP_SELF'].'" method="post" id="thisform">' . "\n";
  echo '<fieldset>' . "\n";
  echo '<legend><span class="sign">' . $idioma['beer_new'] . '</span></legend>' . "\n";
  echo '<dl>' . "\n";
  
  echo "<dt><label for='name'>" . $idioma['beer_name'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='name' id='name' value='' tabindex='1'/></dd>\n";
  
  echo "<dt><label for='brewery_id'>" . $idioma['brewery'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='brewery_id' id='brewery_id' value='' tabindex='2' list='breweries' />\n";
  echo "<datalist id='breweries'>\n";
  $res=mysqli_query($mysql_link, "SELECT auto_id, name FROM business WHERE brewery") or die ('ERROR:'.mysqli_error($mysql_link));
  while( $brewery=mysqli_fetch_row($res) )
    echo "<option value='". $brewery[0]. "' >". $brewery[1] ."</option>\n";
  print('
   ');
  echo "</datalist>\n";
  echo "</dd>\n";
  
  echo "<dt><label for='category_id'>" . $idioma['beer_category'] . ":</label></dt>\n";
  echo "<dd><select name='category_id' id='category_id' tabindex='4'>\n";
  $res=mysqli_query($mysql_link, "SELECT auto_id, category FROM beer_categories") or die ('ERROR:'.mysqli_error($mysql_link));
  while( $category=mysqli_fetch_row($res) ) {
    echo "	<option value='". $category[0]."'>". $category[1] ."</option>\n";
  }
  echo "</select></dd>\n";
  
  echo "<dt><label for='type'>" . $idioma['beer_type'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='type_id' id='type_id' value='' tabindex='4'/></dd>\n";
  
  echo "<dt><label for='abv'>" . $idioma['beer_abv'] . ":</label></dt>\n";
  echo "<dd><input type='number' name='abv' id='abv' value='' tabindex='5' min=0, max=100/></dd>\n";
  
  echo "<dt><label for='ibu'>" . $idioma['beer_ibu'] . ":</label></dt>\n";
  echo "<dd><input type='number' name='ibu' id='ibu' value='' tabindex='6' min=0, max=9999/></dd>\n";
  
  echo "<dt><label for='description'>" . $idioma['beer_desc'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='description' id='description' value='' tabindex='7'/></dd>\n";
  
//   echo "<dt><label for=''>" . $idioma[''] . ":</label></dt>\n";
//   echo "<dd></dd>\n";
  
  echo '<dt></dt><dd><input type="submit" class="button" name="submit" value="'.$idioma['id_enviar'].'" /></dd>' . "\n";
  
  echo '</dl>' . "\n";
  echo '<input type="hidden" name="process" id="process" value="1"/>' . "\n";
  echo '</fieldset>' . "\n";
  echo '</form>' . "\n";

}

function beer_new_insert(){
  global $mysql_link, $idioma, $current_user;
  
  $name = mysqli_real_escape_string( $mysql_link, $_POST['name'] );
  $brewery_id = $_POST['brewery_id'];
  $category_id = $_POST['category_id'];
  $type_id = $_POST['type_id'];
  $abv = $_POST['abv'];
  $ibu = $_POST['ibu'];
  $description = mysqli_real_escape_string( $mysql_link, $_POST['description'] );
//   $ = $_POST[''];

  $query = "INSERT INTO beers (name, brewery_id, category_id, type_id, abv, ibu, description, register_id) VALUES ('$name', $brewery_id, $category_id, $type_id, $abv, $ibu, '$description', $current_user->id)";
//   echo "<p> query: $query </p>\n";
  if( $res = mysqli_query( $mysql_link, $query ) ) {
    log_insert('beer_new', mysqli_insert_id($mysql_link), $current_user->id);
  } else {
//     echo "<p> error: ". mysqli_error( $mysql_link ) ."</p>";
    register_error($idioma['err_insert_beer']);
  }
}
?>
