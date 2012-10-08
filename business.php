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

$id = $_REQUEST['id'];
if( empty( $id ) ) {
  // TODO
  exit;
}

$query = "SELECT * FROM business_view WHERE auto_id = $id";
$res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
$row = mysqli_fetch_object( $res );

cabecera($globals['app_name'], $_SERVER['PHP_SELF']);

laterales();

echo '<div id="cuerpo">'. "\n";

echo '<fieldset id="business"><legend>'. $row->name;
if($row->register_id == $current_user->id || $current_user->admin ) {
  echo ' [<a href="'. $globals['base_url'] .'business_edit.php?id='.$id.'">'. $idioma['usr_modificar'] .'</a>]'."\n";
}
echo '</legend>'."\n";

  echo '<dl id="business_list">' . "\n";
  
  echo '<img class="thumbnail" src="'.get_avatar_url('business', $row->auto_id, $row->avatar, 80).'" width="80" height="80" alt="'.$row->name.'" title="logo" />'."\n";
  echo '<img src="'. get_stars($row->score). '" alt="'. $row->score . '"/>'."\n";

//   show_textfield( 'name', $idioma['beer_name'], $row->name );

  // type
  show_checkbox( 'brewery', $idioma['brewery'], $row->brewery );
  show_checkbox( 'pub', $idioma['pub'], $row->pub );
  show_checkbox( 'store', $idioma['store'], $row->store );
  show_checkbox( 'homebrew_store', $idioma['bsns_homebrew_store'], $row->homebrew_store );
  show_checkbox( 'food', $idioma['bsns_food'], $row->food );
  show_checkbox( 'wifi', $idioma['bsns_wifi'], $row->wifi );

  show_textfield( 'taps', $idioma['bsns_taps'], $row->taps );

  show_textfield( 'country', $idioma['bsns_country'], $row->country );
  show_textfield( 'state', $idioma['bsns_state'], $row->state );
  show_textfield( 'city', $idioma['bsns_city'], $row->city );
  show_textfield( 'address_1', $idioma['bsns_address']." 1", $row->address_1 );
  show_textfield( 'address_2', $idioma['bsns_address']." 2", $row->address_2 );
  show_textfield( 'zip_code', $idioma['bsns_zip'], $row->zip_code );
  show_textfield( 'url', $idioma['bsns_url'], $row->url );
  show_textfield( 'email', $idioma['id_email'], $row->email );
  show_textfield( 'phone', $idioma['bsns_phone'], $row->phone );
  show_textfield( 'lat', $idioma['bsns_lat'], $row->lat );
  show_textfield( 'lon', $idioma['bsns_lon'], $row->lon );
  show_textfield( 'description', $idioma['beer_desc'], $row->description );
//   show_textfield( '', $idioma[''], $row-> );
  
  echo '</dl>' . "\n";

echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();


?>

