<?php
/*
    Birrols
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
include(libpath.'log.php');

check_login();
if( !$current_user->authenticated ) {
  show_error($idioma['err_login']);
} else { // current_user authenticated

  if( !empty( $_REQUEST['id'] ) ) {
    $id = $_REQUEST['id'];
  } else {
    $id = 0;
  }
  if(isset($_POST["process"])) {
    bsns_insert();
  } else {
    bsns_form();
  }
} // if authenticated

function bsns_form(){
  global $mysql_link, $idioma, $globals, $current_user, $id;
  
  cabecera('', $_SERVER['PHP_SELF']);
  echo '<div id="container_cuerpo">'."\n";
  echo '<div id="cuerpo">'. "\n";
  
  if( $id > 0 ) {
    $query = "SELECT * FROM business_view WHERE auto_id = $id";
//     echo "<p> query: $query </p> \n";
    $res = $mysql_link->query( $query );
    if( $row = $res->fetch_array() ) {
      if( !$current_user->admin && $current_user->id != $row['register_id'] && $current_user->id != $row['user_admin_id'] ) { // no allowed to modify
	show_error( $idioma['err_perms_business'] );
	return;
      }
      $name = $row['name'];
      $brewery = $row['brewery'];
      $pub = $row['pub'];
      $store = $row['store'];
      $homebrew_store = $row['homebrew_store'];
      $food = $row['food'];
      $wifi = $row['wifi'];
      $taps = $row['taps'];
      $country = $row['country'];
      $country_id = $row['country_id'];
      $state = $row['state'];
      $city = $row['city'];
      $address_1 = $row['address_1'];
      $address_2 = $row['address_2'];
      $zip_code = $row['zip_code'];
      $url = $row['url'];
      $email = $row['email'];
      $phone = $row['phone'];
      $lat = $row['lat'];
      $lon = $row['lon'];
      $description = $row['description'];
      $avatar = $row['avatar'];
      $score = $row['score'];
    } else {
      show_error( $idioma['err_no_business'] );
      return;
    }
  } else {
    $name = '';
    $brewery = '';
    $pub = '';
    $store = '';
    $homebrew_store = '';
    $food = '';
    $wifi = '';
    $taps = '';
    $country = '';
    $country_id = '';
    $state = '';
    $city = '';
    $address_1 = '';
    $address_2 = '';
    $zip_code = '';
    $url = '';
    $email = '';
    $phone = '';
    $lat = '';
    $lon = '';
    $description = '';
    $avatar = 0;
    $score = 0;
  }

  echo "<script src='".$globals['js_url']. "jquery-ui.min.js'></script>\n";
  echo "<script src='".$globals['js_url']. "jquery.select-to-autocomplete.min.js'></script>\n";
  echo "<script src='".$globals['js_url']. "business_edit.js'></script>\n";
  
  echo '<form enctype="multipart/form-data" action="'. $_SERVER['PHP_SELF'].'" method="post" id="thisform">' . "\n";
  echo '<fieldset>' . "\n";
  if( $id > 0 )
    echo '<legend><span class="sign">' . $name . '</span></legend>' . "\n";
  else
    echo '<legend><span class="sign">' . $idioma['bsns_new'] . '</span></legend>' . "\n";
  echo '<dl id="business_list">' . "\n";
  
  echo "<input type='hidden' id='id' name='id' value='$id' />\n";

  if( $id > 0 ) show_avatar( 'beers', $id, $avatar, '', 80 );

  echo "<dt><label for='name'>" . $idioma['beer_name'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='name' id='name' value='$name' autofocus='autofocus'/></dd>\n";

  // type
  input_checkbox('brewery',$idioma['brewery'],1, $brewery);
  input_checkbox('pub',$idioma['pub'],1, $pub);
  input_checkbox('store',$idioma['store'],1, $store);
  input_checkbox( 'homebrew_store', $idioma['bsns_homebrew_store'], 1, $homebrew_store );
  input_checkbox( 'food', $idioma['bsns_food'], 1, $food );
  input_checkbox( 'wifi', $idioma['bsns_wifi'], 1, $wifi );

  input_number( 'taps', $idioma['bsns_taps'], 0, 999, $taps );

  echo "<dt><label for='country_id'>" . $idioma['bsns_country'] . ":</label></dt>\n";
  echo "<dd><select name='country_id' id='country_id' class='turn-to-ac' >\n";
  echo "<option value='' ". ( $country_id=='' ? "selected='selected'" : "" ) .">". $idioma['bsns_sel_country'] ."</option> \n";
  // TODO: language_id
  $query = "SELECT auto_id, name, alternative_spellings, relevancy FROM countries WHERE language_id = 3";
  $res = mysqli_query( $mysql_link, $query );
  while( $country = mysqli_fetch_object( $res ) )
    echo "<option value='". $country->auto_id ."' ". ( $country_id == $country->auto_id ? "selected='selected'" : "" ) ." data-alternative-spellings='". $country->alternative_spellings ."' data-relevancy-booster='". $country->relevancy . "'>". $country->name ."</option> \n";
  echo "</select>\n";
  echo "</dd>\n";
  input_textfield( 'state', $idioma['bsns_state'], $state );
  input_textfield( 'city', $idioma['bsns_city'], $city );
  
  input_textfield( 'address_1', $idioma['bsns_address'] . '1', $address_1);
  input_textfield( 'address_2', $idioma['bsns_address'] . '2', $address_2);
  input_textfield( 'zip_code', $idioma['bsns_zip'], $zip_code );
  
  echo "<dt><label for='url'>" . $idioma['bsns_url'] . ":</label></dt>\n";
//   echo "<dd><input type='url' name='url' id='url' ". (empty($url) ? "value='http://'" : "value='$url'" ) ." /></dd>\n";
  echo "<dd><input type='url' name='url' id='url' value='$url' /></dd>\n";
  echo "<dt><label for='email'>" . $idioma['id_email'] . ":</label></dt>\n";
  echo "<dd><input type='email' name='email' id='email' value='$email' /></dd>\n";
  echo "<dt><label for='phone'>" . $idioma['bsns_phone'] . ":</label></dt>\n";
  echo "<dd><input type='phone' name='phone' id='phone' value='$phone' /></dd>\n";
  
  input_number( 'lat', $idioma['bsns_lat'], -90, 90, 0.01, $lat );
  input_number( 'lon', $idioma['bsns_lat'], -180, 180, 0.01, $lon );
  
  echo "<dt><label for='description'>" . $idioma['beer_desc'] . ":</label></dt>\n";
  echo "<dd><textarea name='description' id='description'>$description</textarea></dd>\n";
  
//   echo "<dt><label for=''>" . $idioma[''] . ":</label></dt>\n";
//   echo "<dd></dd>\n";
  // logo
  input_avatar('business');
  
  echo '<dt></dt><dd><input type="submit" class="button" name="submit" value="'.$idioma['id_enviar'].'" /></dd>' . "\n";
  
  echo '</dl>' . "\n";
  echo '<input type="hidden" name="process" id="process" value="1"/>' . "\n";
  echo '</fieldset>' . "\n";
  echo '</form>' . "\n";

  echo '	  </div> <!-- cuerpo -->'. "\n";
//echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
  echo '	  </div> <!-- container_cuerpo -->'. "\n";
  pie();
  
} // bsns_form

function bsns_insert(){
  global $mysql_link, $idioma, $current_user, $messages, $globals;
  
  $messages = '';
  
//   print_r($_POST);
  
  $id = ( empty($_POST['id']) ? 0 : $_POST['id'] );
  $name = mysqli_real_escape_string( $mysql_link, $_POST['name'] );
  $brewery = ( empty($_POST['brewery']) ? 0 : $_POST['brewery'] );
  $pub = ( empty($_POST['pub']) ? 0 : $_POST['pub'] );
  $store = ( empty($_POST['store']) ? 0 : $_POST['store'] );
  $homebrew_store = ( empty($_POST['homebrew_store']) ? 0 : $_POST['homebrew_store'] );
  $food = ( empty($_POST['food']) ? 0 : $_POST['food'] );
  $wifi = ( empty($_POST['wifi']) ? 0 : $_POST['wifi'] );
  $taps = ( empty($_POST['taps']) ? 0 : $_POST['taps'] );
  $country_id = ( empty($_POST['country_id']) ? 0 : $_POST['country_id'] );
  $state = mysqli_real_escape_string( $mysql_link, $_POST['state'] );
  $city = mysqli_real_escape_string( $mysql_link, $_POST['city'] );
  $address_1 = mysqli_real_escape_string( $mysql_link, $_POST['address_1'] );
  $address_2 = mysqli_real_escape_string( $mysql_link, $_POST['address_2'] );
  $zip_code = mysqli_real_escape_string( $mysql_link, $_POST['zip_code'] );
  $url = mysqli_real_escape_string( $mysql_link, $_POST['url'] );
  if( $url == 'http://' ) $url='';
  $email = mysqli_real_escape_string( $mysql_link, $_POST['email'] );
  $phone = mysqli_real_escape_string( $mysql_link, $_POST['phone'] );
  $lat = ( empty($_POST['lat']) ? 'NULL' : $_POST['lat'] );
  $lon = ( empty($_POST['lon']) ? 'NULL' : $_POST['lon'] );
  $description = mysqli_real_escape_string( $mysql_link, $_POST['description'] );
//   $ = $_POST[''];

  if( empty( $name ) ) {
    show_error( $idioma['err_name_miss'] );
    return FALSE;
  }
  if ( !empty($url) && !preg_match('/^http/', $url) ) $url = 'http://'.$url;


  if( $id > 0 ) { // modify
    $query = "UPDATE business SET name='$name', brewery=$brewery, pub=$pub, store=$store, homebrew_store=$homebrew_store, food=$food, wifi=$wifi, taps=$taps, country_id=$country_id, state='$state', city='$city', address_1='$address_1', address_2='$address_2', zip_code='$zip_code', url='$url', email='$email', phone='$phone', lat=$lat, lon =$lon, description='$description', register_id=$current_user->id WHERE auto_id=$id";
    $log_type = 'business_update';
  } else { // new 
    $query = "INSERT INTO business SET name='$name', brewery=$brewery, pub=$pub, store=$store, homebrew_store=$homebrew_store, food=$food, wifi=$wifi, taps=$taps, country_id=$country_id, state='$state', city='$city', address_1='$address_1', address_2='$address_2', zip_code='$zip_code', url='$url', email='$email', phone='$phone', lat=$lat, lon =$lon, description='$description', register_id=$current_user->id";
    $log_type = 'business_new';
  } // fi id>0
//   echo "<p> query: $query </p>\n";
  if( $res = mysqli_query( $mysql_link, $query ) ) {
    if( empty($id) ) $id = $mysql_link->insert_id;
    log_insert($log_type, $id, $current_user->id);
//     print_r($_FILES);
//     print_r($_REQUEST);
    if( manage_avatars_upload( 'business', $id ) )
      header("Location:". $globals['base_url']. "business?id=". $id);
    else {
      cabecera('', $_SERVER['PHP_SELF']);
      show_error($messages);
      pie();
      return FALSE;
    }
  } else { // error in query
    cabecera('', $_SERVER['PHP_SELF']);
//     echo "<p> error: ". mysqli_error( $mysql_link ) ."</p>";
    show_error($idioma['err_db']);
    pie();
    return FALSE;
  } // fi query

} // bsns_new_insert

?>
