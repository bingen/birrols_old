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
include(libpath.'log.php');

check_login();
cabecera('', $_SERVER['PHP_SELF']);
echo '<div id="cuerpo">'. "\n";
if( !$current_user->authenticated ) {
	  echo '<p class="error">'.$idioma['err_login'].'</p>'."\n";
} else { // current_user authenticated

  if( !empty( $_REQUEST['id'] ) ) {
    $id = $_REQUEST['id'];
  } else {
    $id = 0;
  }
  if(isset($_POST["process"])) {
    beer_insert();
  } else {
    beer_form();
  }
} // if authenticated

echo '	  </div> <!-- container_cuerpo -->'. "\n";

pie();

function beer_form(){
  global $mysql_link, $idioma, $globals, $current_user, $id;
  
  if( $id > 0 ) {
    $query = "SELECT * FROM beers_view WHERE auto_id = $id";
//     echo "<p> query: $query </p> \n";
    $res = $mysql_link->query( $query );
    if( $row = $res->fetch_array() ) {
      if( !$current_user->admin && $current_user->id != $row['register_id'] ) { // no allowed to modify
	show_error( $idioma['err_perms_beer'] );
	return;
      }
      $name = $row['name'];
      $brewery = $row['brewery'];
      $brewery_id = $row['brewery_id'];
      $category_id = $row['category_id'];
      $type = $row['type'];
      $abv = $row['abv'];
      $ibu = $row['ibu'];
      $og = $row['og'];
      $srm = $row['srm'];
      $ebc = $row['ebc'];
      $malts = $row['malts'];
      $hops = $row['hops'];
      $description = $row['description'];
      $avatar = $row['avatar'];
      $score = $row['score'];
    } else {
      show_error( $idioma['err_no_beer'] );
    }
  } else {
    $name = '';
    $brewery = '';
    $brewery_id = '';
    $category_id = '';
    $type = '';
    $abv = '';
    $ibu = '';
    $og = '';
    $srm = '';
    $ebc = '';
    $malts = '';
    $hops = '';
    $description = '';
    $avatar = 0;
    $score = 0;
  }

  echo "<script src='".$globals['js_url']. "jquery-ui.min.js'></script>\n";
  echo "<script type='text/javascript'> var err_brewery_miss = '". $idioma['err_brewery_miss'] ."'; </script>";
  echo "<script src='".$globals['js_url']. "beer_new.js'></script>\n";
  
  // http://stackoverflow.com/questions/3586919/why-would-files-be-empty-when-uploading-files-to-php
  // 7. Make sure your FORM tag has the enctype="multipart/form-data" attribute
  echo '<form enctype="multipart/form-data" action="'. $_SERVER['PHP_SELF'].'" method="post" id="thisform">' . "\n";
  echo '<fieldset>' . "\n";
  if( $id > 0 )
    echo '<legend><span class="sign">' . $name . '</span></legend>' . "\n";
  else
    echo '<legend><span class="sign">' . $idioma['beer_new'] . '</span></legend>' . "\n";
  echo '<dl>' . "\n";

  echo "<input type='hidden' id='id' name='id' value='$id' />\n";

  if( $id > 0 ) show_avatar( 'beers', $id, $avatar, '', 80 );

  echo "<dt><label for='name'>" . $idioma['beer_name'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='name' id='name' value='$name' autofocus='autofocus' /></dd>\n";
  
  // brewery ///////////
  input_textfield( 'brewery', $idioma['brewery'], $brewery );
  echo "<input type='hidden' id='brewery_id' name='brewery_id' value='$brewery_id' />\n";
  
  // category //////////////
  echo "<dt><label for='category_id'>" . $idioma['beer_category'] . ":</label></dt>\n";
  echo "<dd><select name='category_id' id='category_id' >\n";
  $res=mysqli_query($mysql_link, "SELECT auto_id, category FROM beer_categories") or die ('ERROR:'.mysqli_error($mysql_link));
  while( $category=mysqli_fetch_row($res) ) {
    echo "	<option value='". $category[0]."' ". ( $category[0] == $category_id ? "selected='selected'" : "" ) .">". $category[1] ."</option>\n";
  }
  echo "</select></dd>\n";
  
  // type //////////////
  input_textfield( 'type', $idioma['beer_type'], '' );
//   echo "<input type='hidden' id='type_id' name='type_id' />\n";
  
  // numerical parameters //////////////
  input_number( 'abv', $idioma['beer_abv'], 0, 100, 0.1, $abv );
  input_number( 'ibu', $idioma['beer_ibu'], 0, 9999, 1, $ibu );
  input_number( 'og', $idioma['beer_og'], 0, 2000, 1, $og );
  input_number( 'srm', $idioma['beer_srm'], 0, 100, 1, $srm );
  // http://en.wikipedia.org/wiki/Standard_Reference_Method#EBC
  // EBC = SRM * 1.97
  input_number( 'ebc', $idioma['beer_ebc'], 0, 200, 1, $ebc );
  
  // text desc //////////////////
  input_textfield( 'malts', $idioma['beer_malts'], $malts );
  input_textfield( 'hops', $idioma['beer_hops'], $hops );
//   input_textfield( '', $idioma['beer_'], '' );
    
  echo "<dt><label for='description'>" . $idioma['beer_desc'] . ":</label></dt>\n";
  echo "<dd><input type='text' name='description' id='description' value='$description' /></dd>\n";
  
//   echo "<dt><label for=''>" . $idioma[''] . ":</label></dt>\n";
//   echo "<dd></dd>\n";
  
  // foto
  input_avatar('beers');

  echo '<dt></dt><dd><input type="submit" class="button" id="submit" name="submit" value="'.$idioma['id_enviar'].'" /></dd>' . "\n";
  
  echo '</dl>' . "\n";
  echo '<input type="hidden" name="process" id="process" value="1"/>' . "\n";
  echo '</fieldset>' . "\n";
  echo '</form>' . "\n";

}

function beer_insert(){
  global $mysql_link, $idioma, $current_user, $messages;
  
  $name = mysqli_real_escape_string( $mysql_link, $_POST['name'] );
  $brewery = mysqli_real_escape_string( $mysql_link, $_POST['brewery'] );
  $brewery_id = ( empty($_POST['brewery_id']) ? 0 : $_POST['brewery_id'] );
  $category_id = ( empty($_POST['category_id']) ? 0 : $_POST['category_id'] );
  $type = mysqli_real_escape_string( $mysql_link, $_POST['type'] );
//   $type_id = ( empty($_POST['type_id']) ? 0 : $_POST['type_id'] );
  $abv = ( empty($_POST['abv']) ? 0 : $_POST['abv'] );
  $ibu = ( empty($_POST['ibu']) ? 0 : $_POST['ibu'] );
  $og = ( empty($_POST['og']) ? 0 : $_POST['og'] );
  $srm = ( empty($_POST['srm']) ? 0 : $_POST['srm'] );
  $ebc = ( empty($_POST['ebc']) ? 0 : $_POST['ebc'] );
  $malts = mysqli_real_escape_string( $mysql_link, $_POST['malts'] );
  $hops = mysqli_real_escape_string( $mysql_link, $_POST['hops'] );
  $description = mysqli_real_escape_string( $mysql_link, $_POST['description'] );
//   $ = $_POST[''];

  if( empty( $name ) ) {
    register_error( $idioma['err_name_miss'] );
    return FALSE;
  }

  // check brewery exist and is consistent
  $query = "SELECT auto_id FROM business WHERE brewery AND name LIKE '$brewery' AND auto_id = $brewery_id";
  $res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
  $obj = mysqli_fetch_object( $res );
  $brewery_id = $obj->auto_id;
  if( empty( $brewery_id ) ) {
    register_error( $idioma['err_brewery_miss'] );
    return FALSE;
  }
  // if type not exists, create it
  $query = "SELECT auto_id FROM beer_types WHERE type LIKE '$type' AND category_id = $category_id";
  $res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
  $obj = mysqli_fetch_object( $res );
  $type_id = $obj->auto_id;
  if( empty( $type_id ) ) {
    $query = "INSERT INTO beer_types SET type = '$type', category_id = $category_id";
    $res = mysqli_query( $mysql_link, $query ) OR die( mysqli_error( $mysql_link ) );
    $type_id = mysqli_insert_id( $mysql_link );
  }
  
  if( $id > 0 ) { // modify
  } else { // new 
    $query = "INSERT INTO beers (name, brewery_id, category_id, type_id, abv, ibu, og, srm, ebc, malts, hops, description, register_id) VALUES ('$name', $brewery_id, $category_id, $type_id, $abv, $ibu, $og, $srm, $ebc, '$malts', '$hops', '$description', $current_user->id)";
    echo "<p> query: $query </p>\n";
    if( $res = mysqli_query( $mysql_link, $query ) ) {
      $beer_id = mysqli_insert_id($mysql_link);
      log_insert('beer_new', $beer_id, $current_user->id);
      print_r($_FILES);
      print_r($_REQUEST);
      manage_avatars_upload( 'beers', $beer_id );
    } else {
      echo "<p> error: ". mysqli_error( $mysql_link ) ."</p>";
      register_error($idioma['err_insert_beer']);
    }
  } // fi id>0
  
  echo $messages ."\n";

} // beer_new_insert
?>
