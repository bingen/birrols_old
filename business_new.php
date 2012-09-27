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
    bsns_new_insert();
  } else {
    bsns_new_form();
  }
} // if authenticated

echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();

function bsns_new_form(){
  global $mysql_link, $idioma, $globals;
  
  echo "<script src='".$globals['base_url']."js/jquery-ui.min.js'></script>\n";
  echo "<script src='".$globals['base_url']."js/jquery.select-to-autocomplete.min.js'></script>\n";
  
  echo '<form action="'. $_SERVER['PHP_SELF'].'" method="post" id="thisform">' . "\n";
  echo '<fieldset>' . "\n";
  echo '<legend><span class="sign">' . $idioma['bsns_new'] . '</span></legend>' . "\n";
  echo '<dl>' . "\n";
  
  echo "<dt><label for='name'>" . $idioma['beer_name'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='name' id='name' value='' /></dd>\n";

  // type
  echo "<dt><label for='brewery'>" . $idioma['brewery'] . ":</label></dt>\n";
  echo "<dd><input type='checkbox' name='brewery' id='brewery' value='1'  />\n";
  echo "</dd>\n";
  echo "<dt><label for='pub'>" . $idioma['pub'] . ":</label></dt>\n";
  echo "<dd><input type='checkbox' name='pub' id='pub' value='1' />\n";
  echo "</dd>\n";
  echo "<dt><label for='store'>" . $idioma['store'] . ":</label></dt>\n";
  echo "<dd><input type='checkbox' name='store' id='store' value='1' />\n";
  echo "</dd>\n";

  print("
    <script type='text/javascript'>
    jQuery(function(){
      jQuery('select.turn-to-ac').selectToAutocomplete();
    });
    </script>
  ");
  echo "<dt><label for='country_id'>" . $idioma['bsns_country'] . ":</label></dt>\n";
  echo "<dd><select name='country_id' id='country_id' class='turn-to-ac' >\n";
  echo "<option value='' selected='selected'>". $idioma['bsns_sel_country'] ."</option> \n";
  // TODO: language_id
  $query = "SELECT auto_id, name, alternative_spellings, relevancy FROM countries WHERE language_id = 3";
  $res = mysqli_query( $mysql_link, $query );
  while( $country = mysqli_fetch_object( $res ) )
    echo '<option value="'. $country->auto_id .'" data-alternative-spellings="'. $country->alternative_spellings .'" data-relevancy-booster="'. $country->relevancy . '">'. $country->name .'</option>' ."\n";
  echo "</select>\n";
  echo "</dd>\n";
  echo "<dt><label for='state'>" . $idioma['bsns_state'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='state' id='state' />\n";
  echo "</dd>\n";
  echo "<dt><label for='city'>" . $idioma['bsns_city'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='city' id='city' />\n";
  echo "</dd>\n";
  
  echo "<dt><label for='address_1'>" . $idioma['bsns_address'] . " 1:</label></dt>\n";
  echo "<dd><input type='text' name='address_1' id='address_1' value='' /></dd>\n";
  echo "<dt><label for='address_2'>" . $idioma['bsns_address'] . " 2:</label></dt>\n";
  echo "<dd><input type='text' name='address_2' id='address_2' value='' /></dd>\n";
  echo "<dt><label for='zip_code'>" . $idioma['bsns_zip'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='zip_code' id='zip_code' value='' /></dd>\n";
  
  echo "<dt><label for='url'>" . $idioma['bsns_url'] . ":</label></dt>\n";
  echo "<dd><input type='url' name='url' id='url' value='' /></dd>\n";
  echo "<dt><label for='email'>" . $idioma['id_email'] . ":</label></dt>\n";
  echo "<dd><input type='email' name='email' id='email' value='' /></dd>\n";
  echo "<dt><label for='phone'>" . $idioma['bsns_phone'] . ":</label></dt>\n";
  echo "<dd><input type='phone' name='phone' id='phone' value='' /></dd>\n";
  
  echo "<dt><label for='lat'>" . $idioma['bsns_lat'] . ":</label></dt>\n";
  echo "<dd><input type='number' name='lat' id='lat' value='' min=-90, max=90/></dd>\n";
  echo "<dt><label for='lon'>" . $idioma['bsns_lat'] . ":</label></dt>\n";
  echo "<dd><input type='number' name='lon' id='lon' value='' min=-180, max=180/></dd>\n";
  
  echo "<dt><label for='description'>" . $idioma['beer_desc'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='description' id='description' value='' /></dd>\n";
  
//   echo "<dt><label for=''>" . $idioma[''] . ":</label></dt>\n";
//   echo "<dd></dd>\n";
  
  echo '<dt></dt><dd><input type="submit" class="button" name="submit" value="'.$idioma['id_enviar'].'" /></dd>' . "\n";
  
  echo '</dl>' . "\n";
  echo '<input type="hidden" name="process" id="process" value="1"/>' . "\n";
  echo '</fieldset>' . "\n";
  echo '</form>' . "\n";

} // bsns_new_form

function bsns_new_insert(){
  global $mysql_link, $idioma, $current_user;
  
//   print_r($_POST);
  
  $name = mysqli_real_escape_string( $mysql_link, $_POST['name'] );
  $brewery = ( empty($_POST['brewery']) ? 0 : $_POST['brewery'] );
  $pub = ( empty($_POST['pub']) ? 0 : $_POST['pub'] );
  $store = ( empty($_POST['store']) ? 0 : $_POST['store'] );
  $country_id = $_POST['country_id'];
  $state = mysqli_real_escape_string( $mysql_link, $_POST['state'] );
  $city = mysqli_real_escape_string( $mysql_link, $_POST['city'] );
  $address_1 = mysqli_real_escape_string( $mysql_link, $_POST['address_1'] );
  $address_2 = mysqli_real_escape_string( $mysql_link, $_POST['address_2'] );
  $zip_code = mysqli_real_escape_string( $mysql_link, $_POST['zip_code'] );
  $url = mysqli_real_escape_string( $mysql_link, $_POST['url'] );
  $email = mysqli_real_escape_string( $mysql_link, $_POST['email'] );
  $phone = mysqli_real_escape_string( $mysql_link, $_POST['phone'] );
  $lat = $_POST['lat'];
  $lon = $_POST['lon'];
  $description = mysqli_real_escape_string( $mysql_link, $_POST['description'] );
//   $ = $_POST[''];

  $query = "INSERT INTO business SET name='$name', brewery=$brewery, pub=$pub, store=$store, country_id=$country_id, state='$state', city='$city', address_1='$address_1', address_2='$address_2', zip_code='$zip_code', url='$url', email='$email', phone='$phone', lat=$lat, lon =$lon, description='$description', register_id=$current_user->id";
//   echo "<p> query: $query </p>\n";
  if( $res = mysqli_query( $mysql_link, $query ) ) {
    log_insert('beer_new', mysqli_insert_id($mysql_link), $current_user->id);
  } else {
//     echo "<p> error: ". mysqli_error( $mysql_link ) ."</p>";
    register_error($idioma['err_insert_beer']);
  }
} // bsns_new_insert

?>
