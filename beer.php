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
  $id=0;
  exit;
}

cabecera($globals['app_name'], $_SERVER['PHP_SELF']);

laterales();

echo '<div id="container_cuerpo">'."\n";
echo '<div id="cuerpo">'. "\n";

$query = "SELECT * FROM beers_view WHERE auto_id = $id";
$res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
if( $row = mysqli_fetch_object( $res ) ) {

  echo '<fieldset id="business"><legend>'. $row->name;
  if($row->register_id === $current_user->id || $current_user->admin ) {
    echo ' [<a href="'. $globals['base_url'] .'beer_edit.php?id='.$id.'">'. $idioma['usr_modificar'] .'</a>]'."\n";
  }
  echo '</legend>'."\n";

  echo '<dl id="beer_list">' . "\n";
  
  show_avatar( 'beers', $row->auto_id, $row->avatar, $row->name, 80 );
  show_stars( $row->score );
//   show_textfield( 'name', $idioma['beer_name'], $row->name );

  show_textfield( 'country', $idioma['bsns_country'], $row->country );
  show_textfield( 'brewery', $idioma['brewery'], $row->brewery, $globals['base_url']. "business?id=". $row->brewery_id );
  show_textfield( 'category', $idioma['beer_category'], $row->category );
  show_textfield( 'type', $idioma['beer_type'], $row->type );
  show_textfield( 'abv', $idioma['beer_abv'], ($row->abv ? $row->abv."%" : "") );
  show_textfield( 'ibu', $idioma['beer_ibu'], $row->ibu );
  show_textfield( 'og', $idioma['beer_og'], $row->og );
  show_textfield( 'srm', $idioma['beer_srm'], $row->srm );
  show_textfield( 'ebc', $idioma['beer_ebc'], $row->ebc );
  show_textfield( 'malts', $idioma['beer_malts'], $row->malts );
  show_textfield( 'hops', $idioma['beer_hops'], $row->hops );
  show_textfield( 'description', $idioma['beer_desc'], $row->description );
//   show_textfield( '', $idioma[''], $row-> );
  
  echo '</dl>' . "\n";
  echo "</fieldset>\n";  
  
} else { // no $row
  show_error( $idioma['err_no_beer'] );
}
echo '	  </div> <!-- cuerpo -->'. "\n";
echo '	  <div id="fake-container_cuerpo" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();


?>

