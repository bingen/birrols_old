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
  $id = 0;
}

cabecera($globals['app_name'], $_SERVER['PHP_SELF']);

echo '<div id="container_cuerpo">'."\n";
echo '<div id="cuerpo">'. "\n";

laterales();

$query = "SELECT * FROM business_view WHERE auto_id = $id";
$res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
if( $row = mysqli_fetch_object( $res ) ) {

  echo '<fieldset id="business"><legend>'. $row->name;
  if($row->register_id === $current_user->id || $current_user->admin ) {
    echo ' [<a href="'. $globals['base_url'] .'business_edit.php?id='.$id.'">'. $idioma['usr_modificar'] .'</a>]'."\n";
  }
  echo '</legend>'."\n";

  echo '<dl id="business_list">' . "\n";
  
  show_avatar( 'business', $row->auto_id, $row->avatar, $row->name, 80 );
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
  show_textfield( 'url', $idioma['bsns_url'], $row->url, $row->url );
  show_textfield( 'email', $idioma['id_email'], $row->email );
  show_textfield( 'phone', $idioma['bsns_phone'], $row->phone );
  show_textfield( 'lat', $idioma['bsns_lat'], $row->lat );
  show_textfield( 'lon', $idioma['bsns_lon'], $row->lon );
  show_textfield( 'description', $idioma['beer_desc'], $row->description );
//   show_textfield( '', $idioma[''], $row-> );
  
  echo '</dl>' . "\n";
  echo "</fieldset>\n";  
  
  // taps
  if( $row->pub ) {
    if( $current_user->authenticated && empty($row->user_admin_id) ) { // editables
      $editable = TRUE;
      echo "<script src='".$globals['js_url']. "jquery-ui.min.js'></script>\n";
      echo "<script type='text/javascript'> var err_brewery_miss = '". $idioma['err_brewery_miss'] ."'; </script>";
      echo "<script type='text/javascript'> var err_beer_miss = '". $idioma['err_beer_miss'] ."'; </script>";
      echo "<script src='".$globals['js_url']. "business.js'></script>\n";
    } else
      $editable = FALSE;
    echo "<fieldset id='taps_list'>\n";
    for( $i=1; $i<=$row->taps; $i++ )
    {
      echo "<dl class='tap_item' id='tap_$i'>\n";
      $query = "SELECT * FROM taps_view WHERE business_id=$id AND tap_id=$i AND actual";
//       echo "<p> query: $query </p> \n";
      $res = $mysql_link->query( $query );
      $tap = $res->fetch_object();
//       if( $tap = $res->fetch_object() ) {
	show_textfield( 'tap_num_'. $i, $idioma['bsns_tap'], $i );
	if( $editable ) {
	  input_textfield( 'brewery_'. $i, $idioma['brewery'], $tap->brewery, 'brewery', " data-tap='$i' " );
	  echo "<input type='hidden' id='brewery_id_$i' name='brewery_id_$i' value='".$tap->brewery_id."' />\n";
	  input_textfield( 'beer_'. $i, $idioma['beer'], $tap->beer );
	  echo "<input type='hidden' id='beer_id_$i' name='beer_id_$i' value='".$tap->brewery_id."' />\n";
	  show_textfield( 'user_'. $i, $idioma['id_usuario'], "<a href='". $globals['base_url']."beer?id=".$tap->beer_id."'>". $tap->beer."</a>" );
	} else {
	} // fi editable
//       } // fi tap
      echo "</dl>\n";
    }
    echo "</fieldset>\n"; // taps_list
  } // fi taps
  if( $row->brewery ) { 
    echo '        <div id="results" class="results">' . "\n";
    $_REQUEST['brewery_id'] = $row->auto_id;
    $_REQUEST['pattern'] = 8191 - 8 - 16; // not country nor brewery
    include('table_beers_view.php');
    echo '        </div>' . "\n"; // results
  } // fi brewery
} else { // no $row
  show_error( $idioma['err_no_business'] );
}

echo '	  </div> <!-- cuerpo -->'. "\n";
echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();


?>

