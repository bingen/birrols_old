<?php
/*
    Open craft beer
    Web app for craft beer lovers
    Copyright (C) 2005 Ricardo Galli <gallir at uib dot es>
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

function conecta() {
//	require('config.php');
	global $db;
 	$mysql_link = mysqli_connect($db['server'],$db['user'],$db['password']) or die('ERROR:'.mysqli_error($mysql_link));
	mysqli_select_db($mysql_link, $db['name']) or die('ERROR:'.mysqli_error($mysql_link));
	// TODO:
	mysqli_set_charset($mysql_link, "utf8");
	mysqli_query( $mysql_link, "SET NAMES utf8");
	mysqli_query( $mysql_link, "SET lc_time_names = 'es_ES'");

	return $mysql_link;
}
// http://php.net/manual/en/class.mysqli-result.php
// Converting an old project from using the mysql extension to the mysqli extension, I found the most annoying change to be the lack of a corresponding mysql_result function in mysqli. 
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
} 

function check_login(){
  global $current_user;
  
  if( !$current_user->authenticated && empty($_POST['usuario']) && $_REQUEST['error'] != 'login' && !isset($_GET['error_acceso']) ) {
	$url = $_SERVER['PHP_SELF'];
	$_REQUEST['error'] = 'login';
	$query_string = http_build_query( $_REQUEST );
	$url = $_SERVER['PHP_SELF'] . (empty($query_string) ? '' : '?'. http_build_query( $_REQUEST ));
	header("Location:". $url);
	exit();
  }
}
function cabecera($title='',$script='', $no_cache=false) {
	global $idioma, $current_user, $globals, $url, $error_acceso;
	
// 	$url = urlencode($_SERVER['REQUEST_URI']);
// 	$url = $_SERVER['REQUEST_URI'];
	if(isset($_GET['error_acceso'])){
	        $error_acceso = true;
	}
	unset( $_REQUEST['error_acceso'] );
	unset( $_REQUEST['error'] );
	unset( $_REQUEST['usuario'] );
	unset( $_REQUEST['password'] );
	$query_string = http_build_query( $_REQUEST );
	$url = $_SERVER['PHP_SELF'] . (empty($query_string) ? '' : '?'. http_build_query( $_REQUEST ));
	
	if(!$current_user->authenticated && !empty($_POST['usuario']) && !empty($_POST['password']) ) {
		if(! $current_user->Authenticate($_POST['usuario'], md5($_POST['password'])) ) {
			header("Location:". $url ."?error_acceso=");
			//echo '<p> usuario: '. $_POST['usuario'] . ' pwd: ' . $_POST['password'] . ' md5: ' .md5($_POST['password']). "\n";
			exit();
		}
	}
	
// 	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	echo '<!DOCTYPE html>'."\n";
// 	echo '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
	echo '<html>'."\n";
	echo '<head>'."\n";
// 	echo '  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
	echo '  <meta charset="utf-8" />'."\n";
// 	if( $no_cache ) {
// 		echo '  <meta http-equiv="Pragma" content="no-cache" />'."\n";
// 		echo '  <meta http-equiv="Expires" content="-1" />'."\n";
// 	}
	if(empty($title)) { $title=$globals['app_name']; }
	echo '  <title>'.$title.'</title>'."\n";
	if(empty($script)) { 
		$estilo='index';
	} else {
		$aux = pathinfo($script);
		$aux = explode('.',$aux['basename']);
		$estilo = $aux[0];
//		echo '<p>'.$estilo.'</p>'."\n";
	}
	echo '  <link href="'.$globals['css_url']. 'ocb.css" rel="stylesheet" type="text/css" />'."\n";
	echo '  <link href="'.$globals['css_url']. $estilo.'.css" rel="stylesheet" type="text/css" />'."\n";
	echo '  <link rel="icon" href="'.$globals['img_url'] .'favicon.ico" type="image/x-icon">'."\n";
	echo '  <link rel="shortcut icon" href="'.$globals['img_url'] .'favicon.ico" type="image/x-icon">'."\n";
	print("
	  <script type='text/javascript'> 
	    var base_url = '".$globals['base_url']."';
	    var lib_url = '".$globals['lib_url']."';
	    var birrolpath = '". birrolpath ."';
	  </script>
	");
	echo '    <script src="'.$globals['js_url']. 'jquery.min.js" type="text/javascript" charset="utf-8"></script>'."\n";
	echo '    <script src="'.$globals['js_url']. 'funciones.js" type="text/javascript" charset="utf-8"></script>'."\n";
	echo '    <script src="'.$globals['js_url']. 'reload.js" type="text/javascript" charset="utf-8"></script>'."\n";
	// google login
// 	echo '    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>'."\n";
  // TODO: google login with javascript:
// 	echo '    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>'."\n";
// 	echo '    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/googleapis/0.0.4/googleapis.min.js"></script>'."\n";
// 	echo '    <script type="text/javascript" src="//ajax.googleapis.com/jsapi"></script>'."\n";
// 	echo "<script src='". $globals['js_url'] ."google_login.js' type='text/javascript' charset='utf-8'> </script> \n";
///////////////////////

//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.bgiframe.min.js"></script>' ."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.dimensions.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.autocomplete.js"></script>'."\n";
// !!!!!!!!!!!!!!!!!
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'date.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'date_es.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.datePicker-2.1.2.js"></script>'."\n";
// 	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.timePicker.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jcarousellite_1.0.1.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.selectboxes.min.js"></script>' ."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'jquery.tooltip.js"></script>'."\n";
//	echo '    <script type="text/javascript" src="'.$globals['js_url']. 'superfish.js"></script>'."\n";


	echo '</head>'."\n"."\n";

	echo '<body>'."\n"."\n";

// 	echo "<p>".birrolpath."</p>\n";
// 	echo "<p>".$globals['base_url']."</p>\n";
	
// 	echo '<div id="container">'."\n";
// 	echo '  <div id="cabecera">'."\n";
	echo "  <header>\n";
	echo '  <div id="cabecera_in">'."\n";
	echo '	<div id="aux_1">'."\n";
	echo '	<div id="titulo">'."\n";
	echo '	  <h1><a href="'.$globals['base_url'].'index.php" title=""><img src="'. $globals['logo'] .'" alt="'. $globals['app_name'] .'"/></a></h1>'."\n";
	echo '	</div> <!-- titulo -->'."\n"; // titulo

	echo '	<div id="aux_2">'."\n";
// 	echo "<p> session </p> \n";
// 	print_r($_SESSION);
// 	echo "<p> user </p> \n";
// 	print_r($current_user);
// 	echo "<p> cookie </p> \n";
// 	print_r( $_COOKIE );
	if($current_user->authenticated) {
		echo '	<div id="login" class="login">'."\n";
		echo '<ul id="headtools">' . "\n";
 		echo '<li class="noborder">'.$idioma['saludo'].'&nbsp; <a href="'.get_user_uri($current_user->username).'" title="'.$idioma['usr_info'].'">'.$current_user->username.'&nbsp;<img src="'.get_avatar_url('users', $current_user->id, $current_user->avatar, 20).'" width="15" height="15" alt="'.$current_user->username.'"/></a></li>' . "\n";
		echo '<li><a href="'.$globals['base_url']. 'index.php?op=logout">'. $idioma['desconectar'].' <img src="'.$globals['img_url'].'common/door_out.png" alt="logout button" title="logout" width="16" height="16" /></a></li>' . "\n";
		echo '</ul>' . "\n";
		echo '	</div> <!-- login -->'."\n"; // login
		echo '<input type="hidden" id="current_user_id" value="'. $current_user->id .'">' . "\n";
		echo '<input type="hidden" id="current_user_login" value="'. $current_user->username .'">' . "\n";
	} else {
	  login_no();
	}
	// TODO: google login with javascript: echo "<div id='navbar'></div> \n";
	menu();
	echo '	</div> <!-- aux_2 -->'."\n"; // aux_2
	echo '	<div id="fake-aux_1" style="clear: both;"></div>'. "\n";
	echo '	</div> <!-- aux_1 -->'."\n"; // aux_1

	$url = get_server_name();
	compartir( $url, $idioma['shr_general'] );

	echo '	<div id="ayuda" class="login">'."\n";
	echo '<ul>' . "\n";
	echo '<li class="noborder"><a href="about.php" title="'.$idioma['put_about'].'">' . $idioma['hlp_about'] .'</a></li>' . "\n";
	echo '<li><a href="legal.php" title="'.$idioma['put_legal'].'">'.$idioma['hlp_legal'].'</a></li>' . "\n";
	echo '<li><a href="contact.php" title="'.$idioma['put_contact'].'">'.$idioma['hlp_contact'].'</a></li>' . "\n";
	echo '</ul>' . "\n";
	echo '</div> <!-- ayuda -->'."\n"; //ayuda

	echo '	<div id="fake-cabecera" style="clear: both;"></div>'. "\n"; // para ajustar automáticamente el alto de la cabecera
	echo '  </div> <!-- cabecera_in -->'."\n"; // cabecera_in
	echo "  </header>\n";
// 	echo '  </div> <!-- cabecera -->'."\n"; // cabecera
}
function login_no() {
	global $idioma, $error_acceso, $url;
	
	$google_client = google_client();

		echo '<div id="login-no">'."\n";
		if( !empty($url) )
			echo '  <form action="'. urldecode($url) .'" method="post">'."\n";
		else
			echo '  <form action="index.php" method="post">'."\n";
		echo $idioma['usuario'] .': <br /><input type="text" name="usuario" maxlength="24" size="10" />'."\n";
		echo '<br />'. $idioma['password'] .': <br /><input type="password" name="password" maxlength="24" size="10" />'."\n";
		echo '<input type="submit" value="'. $idioma['entrar'] .'" />'."\n";
		echo '<p style="margin:0"><a href="register.php" title="registrar">'.$idioma['registrar'].'</a></p>'."\n";
//		echo '<p style="margin:0"><a href="proximamente.php" title="registrar">'.$idioma['registrar'].'</a></p>'."\n";
		echo '<p style="margin:0"><a href="rec_pwd.php" title="registrar">'.$idioma['forgot_pwd'].'</a></p>'."\n";
		echo '<p style="margin:0"><a href="contact.php" title="registrar">'.$idioma['hlp_contact'].'</a></p>'."\n";
		echo '  </form>'."\n";
		if($error_acceso){echo '<p class="error">'.$idioma['err_acceso'].'</p>'."\n";}
		echo "<a class='button' href='".$google_client->createAuthUrl()."' title='". $idioma['google_login'] ."'>". $idioma['google_login'] ." </a> \n";
		echo '</div> <!-- login_no -->'."\n"; // login-no
}
function google_client() {
  global $globals;
  
  require_once birrolpath. 'google-api-php-client/src/apiClient.php';
  require_once birrolpath. 'google-api-php-client/src/contrib/apiOauth2Service.php';

  $client = new apiClient();
  $client->setApplicationName($globals['google_app_name']);
  // Visit https://code.google.com/apis/console?api=plus to generate your
  // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
  $client->setClientId($globals['google_client_id']);
  $client->setClientSecret($globals['google_client_secret']);
  $client->setRedirectUri($globals['google_redirect_uri']);
  $client->setDeveloperKey($globals['google_api_key']);
  $client->setScopes(array('https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email'));
  
  return $client;
}
function compartir( $url, $texto='', $label=false ) {

	global $globals, $idioma;

	$url = clean_input_url( $url );
	$texto = clean_input_url( $texto );

// 	echo "	<p> url:  $url </p> \n";

	echo '	<div class="social-env">' ."\n";
	if( $label )
		echo '		<label>'. $idioma['cp_publicar'] .'</label>' ."\n";
	echo '		<ul>' ."\n";
	//echo '		<li><a rel="external"  href="http://www.facebook.com/share.php?u=http%3A%2F%2F'. get_server_name() .'" title="Facebook"><img src="'. $globals['img_url'] .'facebook_16x16.png" title="Facebook" alt="Facebook" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a href="http://www.facebook.com/share.php?u=http%3A%2F%2F'. $url .'&amp;t='. $texto .'" title="Facebook" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'facebook_16x16.png" title="Facebook" alt="Facebook" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a rel="nofollow"  href="http://identi.ca/notice/new?status_textarea=http%3A%2F%2F'. $url .'" title="Identi.ca" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'identi.ca.png" title="Identi.ca" alt="Identi.ca" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a rel="nofollow"  href="http://twitter.com/home?status='. $globals['app_name'] .'%20-%20http%3A%2F%2F'. $url .'%2F" title="Twitter" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'twitter-icon.png" title="Twitter" alt="Twitter" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a rel="nofollow"  href="http://www.tuenti.com/share?url=http%3A%2F%2F'. $url .'" title="Twitter" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'tuenti.png" title="Tuenti" alt="Tuenti" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
// 	echo '		<li><a rel="nofollow"  href="http://www.meneame.net/" title="Menéame"><img src="http://neversfelde.de/wp-content/plugins/sociable/images/services-sprite.gif" title="Menéame" alt="Menéame" style="width: 16px; height: 16px; background: transparent class="social" /></a></li>' ."\n";
 	echo '		<li><a rel="nofollow"  href="http://www.google.com/buzz/post?url=http%3A%2F%2F'. $url .'&amp;imageurl=http%3A%2F%2F'. get_server_name() .'%2F'. $globals['base_url'] . $globals['logo'] .'" title="Buzz" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'buzz.png" title="Buzz" alt="Buzz" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a rel="nofollow"  href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=http%3A%2F%2F'. $url .'&amp;annotation='. $globals['app_name'] .'" title="Google Bookmarks" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'google_bookmarks.png" title="Google Bookmarks" alt="Google Bookmarks" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
	echo '		<li><a rel="nofollow"  href="mailto:?subject='. $texto .'&amp;body=http%3A%2F%2F'. $url .'" title="email" onClick="window.open(this.href); return false;"><img src="'. $globals['img_url'] .'common/email.png" title="email" alt="email" style="width: 16px; height: 16px; background: transparent" class="social" /></a></li>' ."\n";
 	//echo '		<li><a title="Publicar en Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="link" data-locale="es" data-url="http://'. get_server_name() .'"></a> <script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script></li>' ."\n";

	echo '		</ul>' ."\n";
	echo '	</div> <!-- social-env -->' ."\n"; // social

}
function menu() {
	global $globals, $current_user, $idioma;
//	print_r($current_user);
  print('
    <div id="menu">
	<ul id="menu_ul" class="superfish">
  ');
	//echo substr($_SERVER['PHP_SELF'],1);
	$array_selected = Array();
	$array_selected[$globals['base_url'] .'beers.php'] = '';
	$array_selected[$globals['base_url']. 'businesses.php'] = '';
	$array_selected[$globals['base_url']. 'user.php'] = '';
	$array_selected[$_SERVER['PHP_SELF']] = ' class="Selected" ';
	echo '		<li><a '. $array_selected[$globals['base_url'] .'beers.php'] .'href="'.$globals['base_url'].'beers.php" title="'. $idioma['beers'] .'">'. $idioma['beers']  .'</a></li>'."\n";
	echo '		<li><a '. $array_selected[$globals['base_url'] .'businesses.php'] .'href="'.$globals['base_url'].'businesses.php" title="'. $idioma['businesses'] .'">'. $idioma['businesses']  .'</a></li>'."\n";
	if( $current_user->authenticated )
	    echo '<li><a '.$array_selected[$globals['base_url']. 'user.php'].' href="'. get_user_uri($current_user->username) .'" title="">'.$idioma['mnu_datos'].'</a></li>' . "\n";
  print('
	</ul>
    </div> <!-- menu -->
  ');
}
function laterales() {
	global $idioma, $current_user;

// 	echo '<div id="izquierda">' . "\n";
//	print_r($current_user);
/*  print('
	<div id="banners_left">
		<ul>
		<li><a href=""><img src="'.$globals['img_url'].'banner_2.png" alt="prueba banner" title="banner" /></a></li>
		<li>Aquí irían banners, por ejemplo</p>
		<li>Y más banners</li>
		<li>Y más banners</li>
		<li>Y más banners</li>
		<li>Y más banners</li>
		</ul>
	</div>
  </div>
  ');*/
/*  <div id=\"derecha\">
	<p>Aquí también irían banners</p>
	<p>Y más banners</p>
  </div>
  ");*/
	echo '<div id="derecha">' . "\n";
	echo "<div id='banner'>Espacio reservado para publicidad.</div> \n";
	echo '</div> <!-- derecha -->' . "\n";
}
function pie($no_cache=false) {
  global $globals;
  
  echo '	<div id="fake-pie" style="clear: both;"></div>'. "\n"; // para que el pie no se monte a la derecha del cuerpo
//   echo '	<div id="pie">'. "\n";
  echo "	<footer>\n";
  echo '		<p>'. $globals['app_name'] . ' Todos los derechos reservados </p>'. "\n";
  echo '		<p>'. $globals['app_name'] . ' es Software Libre bajo licencia <a id="gnu" href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public License</a></p>'. "\n";
  echo '		<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a></p>'. "\n";

//   echo '	  </div> <!-- pie -->'. "\n";
  echo "	</footer>\n";
//   echo '	</div> <!-- container -->'. "\n";

echo '</body>'."\n";
// 	if( $no_cache ) {
// 		echo '  <head>'."\n";
// 		echo '  <meta http-equiv="Pragma" content="no-cache" />'."\n";
// 		echo '  <meta http-equiv="Expires" content="-1" />'."\n";
// 		echo '  </head>'."\n";
// 	}
echo '</html>'."\n";
}

function show_textfield( $field, $label, $value, $link='' ){
  echo "<dt><label for='$field'>" . $label . ":</label></dt>\n";
  echo "<dd><span name='$field' id='$field'> ".( empty($link) ? $value : "<a href='$link' alt='$value'>$value</a>" ) ." </span></dd>\n";
}
function show_checkbox( $field, $label, $value ){
  echo "<dt><label for='$field'>" . $label . ":</label></dt>\n";
  echo "<dd><input type='checkbox' name='$field' id='$field' ". ( $value ? "checked='checked'" : " " ) ." />\n";
  echo "</dd>\n";
}
function show_avatar( $object, $id, $avatar, $name, $size=80  ){
  global $globals;
  
  $url = $globals['base_url']. $object .'.php?id='.$id;
  echo "<a href='$url' alt='$name'><img class='thumbnail' src='".get_avatar_url($object, $id, $avatar, $size)."' width='$size' height='$size' alt='".$name."' title='logo' /> </a> \n";
}
function show_stars( $score ){
  echo '<img src="'. get_stars($score). '" alt="'. $score . '"/>'."\n";
}
function show_error($message) {
	global $idioma;

	echo '<div class="form-error">';
	echo "<p>$message</p>";
	echo '<input type=button value="'.$idioma['back'].'" onClick="history.go(-1)">'. "\n";
	echo "</div>\n";
}
function input_textfield( $field, $label, $value='', $class='', $data='' ){
  echo "<dt><label for='$field'>" . $label . ":</label></dt>\n";
  echo "<dd><input type='text' name='$field' id='$field' value='$value' ".( empty($class) ? "" : "class='$class'" )."  ".( empty($data) ? "" : $data )." />\n";
  echo "</dd>\n";
}
function input_number( $field, $label, $min='', $max='', $step='', $value='' ){
  echo "<dt><label for='$field'>" . $label . ":</label></dt>\n";
  echo "<dd><input type='number' name='$field' id='$field' ". ( empty($min) ? "" : "min='$min' " ) . ( empty($max) ? "" : "max='$max' " ) . ( empty($step) ? "" : "step='$step' " ) ." value='$value' /></dd>\n";
}
function input_checkbox( $field, $label, $value=1, $checked=false ){
  echo "<dt><label for='$field'>" . $label . ":</label></dt>\n";
  echo "<dd><input type='checkbox' name='$field' id='$field' value='$value' ". ( $checked ? "checked='checked'": "" )." />\n";
  echo "</dd>\n";
}
function input_country() {
  global $mysql_link, $idioma, $country_id;
  
  echo "<select name='country_id' id='country_id' class='turn-to-ac' >\n";
  echo "<option value='' ". ( $country_id=='' ? "selected='selected'" : "" ) .">". $idioma['bsns_sel_country'] ."</option> \n";
  // TODO: language_id
  $query = "SELECT auto_id, name, alternative_spellings, relevancy FROM countries WHERE language_id = 3";
  $res = mysqli_query( $mysql_link, $query );
  while( $country = mysqli_fetch_object( $res ) )
    echo "<option value='". $country->auto_id ."' ". ( $country_id == $country->auto_id ? "selected='selected'" : "" ) ." data-alternative-spellings='". $country->alternative_spellings ."' data-relevancy-booster='". $country->relevancy . "'>". $country->name ."</option> \n";
  echo "</select>\n";

}
function input_avatar($object) {
  global $globals, $idioma;
  
  include_once(libpath.'avatars.php');
  
  if (is_avatars_enabled($object)) {
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$globals['avatars_max_size'].'" />' . "\n";
    echo '<dt><label>'.$idioma['bsns_avatar_1'].':</label></dt>' . "\n";
    echo '<dd><input type="file" class="button" autocomplete="off" name="image" />' . "\n";
    echo '</dd>' . "\n";
    echo '<dt></dt>' . "\n";
    echo '<dd><span class="note">' . $idioma['bsns_avatar_2'] . '</span></dd>' . "\n";
  }
}
function manage_avatars_upload( $object, $id ){
  global $messages;
  
  include_once(libpath.'avatars.php');
  
//   print_r($_FILES);
  // Manage avatars upload
  if (!empty($_FILES['image']['tmp_name']) ) {
    if(avatars_check_upload_size('image')) {
      $avatar_mtime = avatars_manage_upload($object, $id, 'image');
      if (!$avatar_mtime) {
	$messages = $idioma['err_avatar_1'];
	return false;
      }
    } else { // check size error
      $messages = $idioma['err_avatar_2'];
      return false;
    }
  }
  return TRUE;
} // manage avatars upload

function clean_input_string($string) {
	return preg_replace('/[ <>\'\"\r\n\t\(\)]/', '', stripslashes($string));
}
function clean_input_url($string) {
	$string = preg_replace('/ /', '+', trim(stripslashes(mb_substr($string, 0, 512))));
	return preg_replace('/[<>\r\n\t]/', '', $string);
}
// Used to get the text content for stories and comments
function clean_text($string, $wrap=0, $replace_nl=true, $maxlength=0) {
	$string = stripslashes(trim($string));
	$string = clear_whitespace($string);
	$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
	// Replace two "-" by a single longer one, to avoid problems with xhtml comments
	//$string = preg_replace('/--/', '–', $string);
	if ($wrap>0) $string = wordwrap($string, $wrap, " ", 1);
	if ($replace_nl) $string = preg_replace('/[\n\t\r]+/s', ' ', $string);
	if ($maxlength > 0) $string = mb_substr($string, 0, $maxlength);
	return @htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
}
function clear_invisible_unicode($input){
	$invisible = array(
	"\0",
	"\xc2\xad", // 'SOFT HYPHEN' (U+00AD)
	"\xcc\xb7", // 'COMBINING SHORT SOLIDUS OVERLAY' (U+0337)
	"\xcc\xb8", // 'COMBINING LONG SOLIDUS OVERLAY' (U+0338)
	"\xcd\x8f", // 'COMBINING GRAPHEME JOINER' (U+034F)
	"\xe1\x85\x9f", // 'HANGUL CHOSEONG FILLER' (U+115F)
	"\xe1\x85\xa0", // 'HANGUL JUNGSEONG FILLER' (U+1160)
	"\xe2\x80\x8b", // 'ZERO WIDTH SPACE' (U+200B)
	"\xe2\x80\x8c", // 'ZERO WIDTH NON-JOINER' (U+200C)
	"\xe2\x80\x8d", // 'ZERO WIDTH JOINER' (U+200D)
	"\xe2\x80\x8e", // 'LEFT-TO-RIGHT MARK' (U+200E)
	"\xe2\x80\x8f", // 'RIGHT-TO-LEFT MARK' (U+200F)
	"\xe2\x80\xaa", // 'LEFT-TO-RIGHT EMBEDDING' (U+202A)
	"\xe2\x80\xab", // 'RIGHT-TO-LEFT EMBEDDING' (U+202B)
	"\xe2\x80\xac", // 'POP DIRECTIONAL FORMATTING' (U+202C)
	"\xe2\x80\xad", // 'LEFT-TO-RIGHT OVERRIDE' (U+202D)
	"\xe2\x80\xae", // 'RIGHT-TO-LEFT OVERRIDE' (U+202E)
	"\xe3\x85\xa4", // 'HANGUL FILLER' (U+3164)
	"\xef\xbb\xbf", // 'ZERO WIDTH NO-BREAK SPACE' (U+FEFF)
	"\xef\xbe\xa0", // 'HALFWIDTH HANGUL FILLER' (U+FFA0)
	"\xef\xbf\xb9", // 'INTERLINEAR ANNOTATION ANCHOR' (U+FFF9)
	"\xef\xbf\xba", // 'INTERLINEAR ANNOTATION SEPARATOR' (U+FFFA)
	"\xef\xbf\xbb", // 'INTERLINEAR ANNOTATION TERMINATOR' (U+FFFB)
	);

	return str_replace($invisible, '', $input);

}

function clear_unicode_spaces($input){
	$spaces = array(
	"\x9", // 'CHARACTER TABULATION' (U+0009)
	//  "\xa", // 'LINE FEED (LF)' (U+000A)
	"\xb", // 'LINE TABULATION' (U+000B)
	"\xc", // 'FORM FEED (FF)' (U+000C)
	//  "\xd", // 'CARRIAGE RETURN (CR)' (U+000D)
	"\x20", // 'SPACE' (U+0020)
	"\xc2\xa0", // 'NO-BREAK SPACE' (U+00A0)
	"\xe1\x9a\x80", // 'OGHAM SPACE MARK' (U+1680)
	"\xe1\xa0\x8e", // 'MONGOLIAN VOWEL SEPARATOR' (U+180E)
	"\xe2\x80\x80", // 'EN QUAD' (U+2000)
	"\xe2\x80\x81", // 'EM QUAD' (U+2001)
	"\xe2\x80\x82", // 'EN SPACE' (U+2002)
	"\xe2\x80\x83", // 'EM SPACE' (U+2003)
	"\xe2\x80\x84", // 'THREE-PER-EM SPACE' (U+2004)
	"\xe2\x80\x85", // 'FOUR-PER-EM SPACE' (U+2005)
	"\xe2\x80\x86", // 'SIX-PER-EM SPACE' (U+2006)
	"\xe2\x80\x87", // 'FIGURE SPACE' (U+2007)
	"\xe2\x80\x88", // 'PUNCTUATION SPACE' (U+2008)
	"\xe2\x80\x89", // 'THIN SPACE' (U+2009)
	"\xe2\x80\x8a", // 'HAIR SPACE' (U+200A)
	"\xe2\x80\xa8", // 'LINE SEPARATOR' (U+2028)
	"\xe2\x80\xa9", // 'PARAGRAPH SEPARATOR' (U+2029)
	"\xe2\x80\xaf", // 'NARROW NO-BREAK SPACE' (U+202F)
	"\xe2\x81\x9f", // 'MEDIUM MATHEMATICAL SPACE' (U+205F)
	"\xe3\x80\x80", // 'IDEOGRAPHIC SPACE' (U+3000)
	);
	
	return str_replace($spaces, ' ', $input);
}

function clear_whitespace($input){
	$input = clear_unicode_spaces(clear_invisible_unicode($input));
	return ereg_replace('/  +/', ' ', $input);
}

function string2url($cadena) {
	$cadena = trim($cadena);
	$cadena = strtr($cadena,
"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
"aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
	$cadena = strtr($cadena,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz");
	$cadena = preg_replace('#([^.a-z0-9]+)#i', '-', $cadena);
        $cadena = preg_replace('#-{2,}#','-',$cadena);
        $cadena = preg_replace('#-$#','',$cadena);
        $cadena = preg_replace('#^-#','',$cadena);
	return $cadena;
}

function user_exists($username) {
	global $mysql_link;
	$username = mysqli_real_escape_string( $mysql_link, $username);
	$res = mysqli_query($mysql_link, "SELECT count(*) FROM users WHERE username='$username'") or die ('ERROR:'.mysqli_error($mysql_link));
	$num=mysqli_result($res,0,0);
	if ($num>0) return true;
	return false;
}
function check_username($name) {
//	echo '<p> name: '.$name.'</p>';
	return (preg_match('/^[a-zçÇñÑ][a-z0-9_\-\.çÇñÑ·]+$/i', $name) && mb_strlen($name) <= 24 &&
				! preg_match('/^admin/i', $name) ); // Does not allow nicks begining with "admin"
}
function email_exists($email, $check_previous_registered = true) {
	global $mysql_link;

	$parts = explode('@', $email);
	$domain = $parts[1];
	$subparts = explode('+', $parts[0]); // Because we allow user+extension@gmail.com
	$user = $subparts[0];
	$user = mysqli_real_escape_string( $mysql_link, $user);
	$domain = mysqli_real_escape_string( $mysql_link, $domain);
	$res=mysqli_query($mysql_link, "SELECT count(*) FROM users WHERE email = '$user@$domain' or email LIKE '$user+%@$domain'") or die ('ERROR:'.mysqli_error($mysql_link));
	$num=mysqli_result($res,0,0);
	if ($num>0) return $num;
	if ($check_previous_registered) {
		// Check the same email wasn't used recently for another account
		$res=mysqli_query($mysql_link, "SELECT count(*) FROM users WHERE (email_register = '$user@$domain' or email_register LIKE '$user+%@$domain') and date > date_sub(now(), interval 1 year)") or die ('ERROR:'.mysqli_error($mysql_link));
		$num=mysqli_result($res,0,0);
		if ($num>0) return $num;
	}
	return false;
}

function check_email($email) {
/* por ahora pasamos de esto
	require_once(mnminclude.'ban.php');*/

	if (! preg_match('/^[a-z0-9_\-\.]+(\+[a-z0-9_\-\.]+)*@[a-z0-9_\-\.]+\.[a-z]{2,4}$/i', $email)) return false;

	$username = preg_replace('/@.+$/', '', $email);
	if ( substr_count($username, '.') > 2 || preg_match('/\.{2,}/', $username) ) return false; // Doesn't allow "..+" or more than 2 dots

/* por ahora pasamos de esto
	if(check_ban(preg_replace('/^.*@/', '', $email), 'email')) return false;*/
	return true;
}
function check_password($password) {
//	 echo '<p> Check Password: '. $password .': '. preg_match("/^(?=.{6,})(?=(.*[a-z].*))(?=(.*[A-Z0-9].*)).*$/", $password). '</p>';
	 return preg_match("/^(?=.{6,})(?=(.*[a-z].*))(?=(.*[A-Z0-9].*)).*$/", $password);
}

function phone_exists($telefono) {
	global $mysql_link;
	
	$telefono = mysqli_real_escape_string( $mysql_link, trim($telefono));
	$res = mysqli_query($mysql_link, "SELECT count(*) FROM users WHERE telefono='$telefono'") or die ('ERROR:'.mysqli_error($mysql_link));
	$num=mysqli_result($res,0,0);
	if ($num>0) return true;
	return false;
}
function check_phone($telefono) {
//	echo '<p>'.$telefono.preg_match('/^6[0-9]{8}$/i', $telefono).'. long.: '.mb_strlen($telefono).'</p>';
	return (preg_match('/^6[0-9]{8}$/i', $telefono) && mb_strlen($telefono) == 9);
}

function get_server_name() {
	global $server_name;
	$server_port = '';

	if( !array_key_exists( 'SERVER_NAME', $_SERVER ) )
		return 'www.birrols.com';

	// Alert, if does not work with port 443, in order to avoid standard HTTP connections to SSL port
	if($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != 443) $server_port = ':' . $_SERVER['SERVER_PORT'];
	if($_SERVER['SERVER_NAME']) {
		return $_SERVER['SERVER_NAME'] . $server_port;
	} else {
		if ($server_name) return $server_name;
		else return 'www.birrols.com'; // Warn: did you put the right server name?
	}
}
function get_user_uri($user, $view='') {
	global $globals;

	if (!empty($globals['base_user_url'])) {
		$uri= $globals['base_user_url'] . htmlspecialchars($user);
		if (!empty($view)) $uri .= "/$view";
	} else {
		$uri = $globals['base_url'].'user.php?username='.htmlspecialchars($user);
		if (!empty($view)) $uri .= "&amp;view=$view";
	}
	return $uri;
}
function get_user_uri_by_uid($user, $view='') {
	global $globals;

	$uid = guess_user_id($user);
	if ($uid == 0) $uid = -1; // User does not exist, ensure it will give error later
	$uri = get_user_uri($user, $view);
	if (!empty($globals['base_user_url'])) {
		$uri .= "/$uid";
	} else {
		$uri .= "&amp;uid=$uid";
	}
	return $uri;
}
function guess_user_id ($str) {
	global $mysql_link;

	if (preg_match('/^[0-9]+$/', $str)) {
		// It's a number, return it as id
		return (int) $str;
	} else {
		$str = mysqli_real_escape_string( $mysql_link, mb_substr($str,0,64));
		$res = mysqli_query($mysql_link, "select auto_id from users where username = '$str'") or die ('ERROR:'.mysqli_error($mysql_link));
		$id = (int) mysqli_result($res,0 , 0);
		return $id;
	}
}

function get_business_uri($business, $view='') {
	global $globals;

	$uri = $globals['base_url'].'business.php?id='.htmlspecialchars($business);
	if (!empty($view)) $uri .= "&amp;view=$view";

	return $uri;
}

function get_cache_dir_chain($key) {
	// Very fast cache dir generator for two levels
	// mask == 2^8 - 1 or 1 << 8 -1
	return sprintf("%02x/%02x", ($key >> 8) & 255, $key & 255) . '/';
}

function create_cache_dir_chain($base, $chain) {
	// Helper function for get_cache_dir_chain
	$dirs = explode('/', $chain);
	for ($i=0; $i < count($dirs); $i++) {
		$base .= '/'.$dirs[$i];
		@mkdir($base);
	}
}

function get_avatar_url($object, $id, $avatar, $size) {
	global $mysql_link, $globals;

	// If it does not get avatar status, check the database
	if ($id > 0 && $avatar < 0) {
		$query = "SELECT avatar FROM $object where auto_id = $id";
// 		echo "<p> query: $query </p> \n";
		mysqli_query($mysql_link, $query) or die ('ERROR:'.mysqli_error($mysql_link));
		$avatar = (int) mysqli_result($res,0,0);
	}

	if ($avatar > 0 && $globals['cache_dir']) {
		$file = $globals['cache_dir'] . '/'.$globals['avatars_dir'][$object]. get_cache_dir_chain($id). "$id-$avatar-$size.jpg";
		//echo '<p> avatar: '.$avatar.'. file:'.$file.'</p>';
		// Don't check every time, but 1/10, decrease VM pressure 
		// Disabled for the moment, it fails just too much for size 40
		//if (rand(0, 10) < 10) return $globals['base_url'] . $file;
		$file_path = birrolpath.$file;
		if (is_readable($file_path)) {
			return $globals['base_static'] . $file;
		} else {
			return $globals['base_url'] . "backend/get_avatar.php?object=$object&amp;id=$id&amp;size=$size&amp;time=$avatar";
		}
	} 
	return get_no_avatar_url($size);
}

function get_no_avatar_url($size) {
	global $globals;
	return $globals['img_url'].'no_gravatar_'.$size.'.png';
}

function get_date_time($epoch) {
//		global $globals;
	    //return date("Y-m-d H:i", $epoch);
		if (abs(time() - $epoch) < 43200) // Difference is less than 12 hours
	    	return date(" H:i e", $epoch);
		else
	    	return date(" d-m-Y", $epoch);
}

function not_found($mess = '') { // TODO !!!
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$dblang.'" lang="'.$dblang.'">' . "\n";
    echo '<head>' . "\n";
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
    echo "<title>". _('error') . "</title>\n";
    echo '<meta name="generator" content="meneame" />' . "\n";
    echo '<link rel="icon" href="'.$globals['img_url'].'favicons/favicon4.ico" type="image/x-icon" />' . "\n";
    echo '</head>' . "\n";
    echo "<body>\n";
	if (empty($mess)) {
		echo '<h1>' . _('error') . ' 3.1415926536</h1><p>' . _('no encontrado') . '</p>';
	} else {
		echo $mess;
	}
	echo "</body></html>\n";
	exit;
}
function do_error($mess = false, $error = false, $send_status = true) {
	global $idioma;

	if (! $mess ) $mess = $idioma['err_unknown'];

	if ($error && $send_status) {
		header("HTTP/1.0 $error $mess");
		header("Status: $error $mess");
	}

	cabecera($globals['app_name'],$_SERVER['PHP_SELF']);

	echo '<p class="errt">'.$mess.'<br />'."\n";
	if ($error) echo _('(error').' '.$error.')</p>'."\n";
	echo '<div class="errl"><img src="'. $globals['logo_grande'] .'" alt="ooops logo" /></div>'."\n";	// imagen? TODO

	pie();
	die;
}

function paginacion( $url, $num_registros, $num_filas, $fila_0, $div='' ) {
//function paginacion(  ) {
	global $globals, $idioma;
//	global 	$num_registros, $num_filas, $fila_0;

	//$url_aux = $_SERVER['PHP_SELF'];
	//echo '<p> server name: '. $_SERVER['SERVER_NAME'] . ' php self: ' . $_SERVER['PHP_SELF'] . ' SCRIPT name: '.$_SERVER['SCRIPT_NAME'].' base_url: '.$globals['base_url']. ' url_aux: '. $url_aux .'</p>';

	echo '	  <div id="num_filas">'. "\n";
	if( $num_filas != 10 )
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$fila_0 . '&num_filas='. 10 . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_10'].'"> 10 </a>'. "\n";
	else 
		echo '10'. "\n";
	if( $num_filas != 25 )
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$fila_0 . '&num_filas='. 25 . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_25'].'"> 25 </a>'. "\n";
	else 
		echo '25'. "\n";
	if( $num_filas != 50 )
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$fila_0 . '&num_filas='. 50 . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_50'].'"> 50 </a>'. "\n";
	else 
		echo '50'. "\n";
	if( $num_filas != 100 )
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$fila_0 . '&num_filas='. 100 . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_100'].'"> 100 </a>'. "\n";
	else 
		echo '100'. "\n";
	echo '	  </div>'. "\n"; //num_filas
	echo '	<div id="fake-paginacion" style="clear: both;"></div>'. "\n"; 
	echo '	  <div id="paginacion">'. "\n";
	//echo '	  <p>'. "\n";
 	if( $fila_0 > 0 ) {
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' . 0 . '&num_filas='. $num_filas . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_p_primera'].'"> << </a>'. "\n";
		$aux = max(0, $fila_0-$num_filas);
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$aux . '&num_filas='. $num_filas . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_p_anterior'].'"> < </a>'. "\n";
	} else {
		echo '<<'. "\n";
		echo '<'. "\n";
	}
	if( $fila_0+$num_filas < $num_registros ) {
		$aux = $fila_0+$num_filas;
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$aux . '&num_filas='. $num_filas . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_p_siguiente'].'"> > </a>'. "\n";
		$aux = floor(($num_registros-1) / $num_filas) * $num_filas;
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' .$aux . '&num_filas='. $num_filas . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'.$idioma['cc_p_ultima'].'"> >> </a>'. "\n";
	} else {
		echo '>'. "\n";
		echo '>>'. "\n";
	}
	//echo '	  </p>'. "\n";
	//echo '	  <p>'. "\n";
	echo '	  <br />'. "\n";
	$num_paginas = ceil($num_registros / $num_filas);
	$pagina = ceil(($fila_0+1) / $num_filas);
// 	echo "<p> pag: $pagina Np: $num_paginas NR: $num_registros NF: $num_filas </p> \n";
	//echo '		<a href="'. $url_aux . '?fila_0=' . 0 . '&num_filas='. $num_filas .'" title="'. 1 .'"> 1 </a>'. "\n";
	for( $i = max($pagina-2, 1); $i < min($pagina+2, $num_paginas)+1; $i++ ) {
		$aux = ($i-1) * $num_filas;
		if( $i == $pagina ) $class_pag = 'actual'; else $class_pag = '';
		echo '		<a href="#" onclick="reload_div(\''.$url['base'].'fila_0=' . $aux . '&num_filas='. $num_filas . $url['orden'] . $url['filtros'] . $url['extra'] . '\', \''. $div .'\')" title="'. $i .'" class="'. $class_pag .'"> ' . $i .' </a>'. "\n";
	}
	if( $num_paginas == 0 )
		echo '		<a href="#" title="1" class="actual"> 1 </a>'. "\n";
	//$aux = $num_paginas * $num_filas;
	//echo '		<a href="'. $url_aux . '?fila_0=' . $aux . '&num_filas='. $num_filas .'" title="'. $num_paginas .'"> ' . $num_paginas .' </a>'. "\n";
	//echo '	  </p>'. "\n";

	echo '	  </div>'. "\n"; // paginacion
// 	echo '	<div id="fake-paginacion-2" style="clear: both;"></div>'. "\n"; 
}

function tabla_head( $tabla, $where='', $url_extra='', $filtros=1, $campo_ini=null, $orden_ini=null, $titulo='' )
{
	global $mysql_link, $campos, $cabecera, $filtros_array, $colspan_array, $orden_array, $idioma, $globals;
	
	$num_filas = $_REQUEST['num_filas'];
	if( empty($num_filas) || $num_filas == 0 ) $num_filas = 10;
	$fila_0 = $_REQUEST['fila_0'];
	if( empty($fila_0) ) $fila_0 = 0;

	$campo = $_REQUEST['campo'];
	if( empty($campo) ){
		if( empty($campo_ini) )
			$campo = '1';
		else
			$campo = $campo_ini;
	}
	$orden = $_REQUEST['orden'];
	if( $_REQUEST['orden'] == NULL ){
		if( empty($orden_ini) )
			$orden = 0;
		else
			$orden = $orden_ini;
	}

	$cabecera = explode(",",$cabecera);
	$campos = explode(",",$campos);
	$filtros_array = explode(",",$filtros_array);
	$orden_array = explode(",",$orden_array);
	$num_campos = count($campos);
	//print_r($campos);
	//print_r($cabecera);
	//print_r( $filtros_array );

	$url['base'] = 'table_'. $tabla .'.php?';
	$url['paginacion'] = 'num_filas='. $num_filas . '&fila_0=' . $fila_0;
	$url['orden'] = '&campo=' . $campo . '&orden=' . $orden;
	$url['extra'] = $url_extra;
	$url['filtros'] = '';
	for( $i = 0; $i < $num_campos; $i++ ) {
		if( $_REQUEST["$campos[$i]"] != NULL && $filtros_array[$i] != -1 ) {
			if( $filtros_array[$i] == 1 || (empty($filtros_array[$i]) && $filtros == 1) )
				$where .= ' AND ' . $campos[$i] . ' like \'%' . mysqli_real_escape_string( $mysql_link, $_REQUEST["$campos[$i]"]) . '%\'';
// 			elseif( $filtros_array[$i] == 2 || (empty($filtros_array[$i]) && $filtros == 2) )
			elseif( $filtros_array[$i] >= 2 || (empty($filtros_array[$i]) && $filtros != -1) )
				$where .= ' AND ' . $campos[$i] . ' = \'' . mysqli_real_escape_string( $mysql_link, $_REQUEST["$campos[$i]"]) . '\'';
			$url['filtros'] .= '&'. $campos[$i] . '=' . $_REQUEST["$campos[$i]"];
		}
	}
	$url['final'] = $url['base'] . $url['paginacion'] . $url['orden'] . $url['filtros']. $url['extra'];
	//print_r( $url );
	//echo '<p> url: '. $url. '</p>';
	
	$query = "SELECT count(*) FROM $tabla WHERE 1 $where";
// 	echo '<p> Query: '. $query. '</p>';
	$res = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
	$num_registros = mysqli_result( $res, 0 );

	// cabecera
	$colspan_array = explode(",",$colspan_array);
	if( !empty($colspan_array) ){
// 		print_r( $colspan_array );
		$aux_cont = 0;	// comprobamos que los colspan cuadran
		foreach( $colspan_array as $colspan_row )
			$aux_cont += $colspan_row;
		if( $aux_cont == $num_campos )
			$colspan = true;
	}
	paginacion( $url, $num_registros, $num_filas, $fila_0, $tabla );
	if( !empty($titulo) ) {
		echo "<div class='principal-table-titulo'>$titulo</div> \n";
	}
	echo '	<div id="fake-paginacion-2" style="clear: both;"></div>'. "\n"; 
	echo '	<table class="principal-table" id="'. $tabla .'-table">'. "\n";
	echo '	 <thead>'. "\n";
	echo '	  <tr>'. "\n";
	for( $i = 0; $i < $num_campos; $i++ ) {
		if( $colspan && $colspan_array[$i] > 0 )
				$colspan_class = " colspan=".$colspan_array[$i];
		if( !$colspan || $colspan_array[$i] != 0 ) {
			if( $cabecera[$i] == "chk" ) {
				echo '    <th class="col-'.$campos[$i].'" '. $colspan_class .' ><input type="checkbox" id="chk-head" onClick="select_all(\''. $tabla.'-table\');" value="1" /></th>'. "\n";
			} else if( $orden_array[$i] ) {
				if( $campo == $campos[$i] ) {
					$orden_aux = 1 - $orden;
					if( $orden == 0 )
						$flecha = '<img src="'. $globals['img_url']. 'common/bullet_arrow_up.png" alt=""/>';
					else
						$flecha = '<img src="'. $globals['img_url']. 'common/bullet_arrow_down.png" alt=""/>';
				} else {
					$orden_aux = 0;
					$flecha = '';
				}
				if( !empty($idioma['put_th_'.$campos[$i]]) )
					$texto_put = ' title="' .$idioma['put_th_'.$campos[$i]]. '"';
				echo '    <th class="col-'.$campos[$i].'" '. $colspan_class .' ><a href="#" onclick="reload_div(\'' . $url['base'] . $url['paginacion'] . '&campo='.$campos[$i] . '&orden=' . $orden_aux . $url['filtros']. $url['extra'] . '\', \''. $tabla .'-env\')" '. $texto_put .'> '.$cabecera[$i]. $flecha .'</a></th>'. "\n";
			} else {
				echo '    <th class="col-'.$campos[$i].'" '. $colspan_class .' >'.$cabecera[$i] .'</th>'. "\n";
			}
		} /*else
			echo '    <th class="col-'.$campos[$i].'" ></th>'. "\n";*/
	}
//	echo '    <th class="col-">'.$idioma['cc_'].'</th>'. "\n";*/
	//echo '    <th class="col-relleno"></th>'. "\n";
	echo '  </tr>'. "\n";
	// filtros
	if( $filtros > 0 ) {
		echo '  <tr>'. "\n";
		for( $i = 0; $i < $num_campos; $i++ ) {
			switch ( $filtros_array[$i] ) {
				case 3: // select amigos
					echo '    <td class="col-'.$campos[$i].'" ><select class="filtros_select" name="filtro_'. $tabla .'_'. $campos[$i] .'" id="filtro_'. $tabla .'_'. $campos[$i] .'" onChange="reload_div(\''. $url['final'] . '&'. $campos[$i] .'=\'+document.getElementById(\'filtro_'. $tabla. '_'. $campos[$i] .'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. $campos[$i] .'\');">';
					echo '		<option value=""></option>' . "\n";
					echo '		<option value='. PENDIENTE_OUT .' '. ($_REQUEST[$campos[$i]]==PENDIENTE_OUT?' selected="selected"':''). '>'. $idioma['am_pendiente'] .'</option>' . "\n";
					echo '		<option value='. AMIGO .' '. ($_REQUEST[$campos[$i]]==AMIGO?' selected="selected"':''). '>'.$idioma['am_amigo'].'</option>' . "\n";
					echo '		<option value='. CONOCIDO .' '. ($_REQUEST[$campos[$i]]==CONOCIDO?' selected="selected"':''). '>'. $idioma['am_conocido'] .'</option>' . "\n";
					echo '		<option value='. BLOQUEADO .' '. ($_REQUEST[$campos[$i]]==BLOQUEADO?' selected="selected"':''). '>'. $idioma['am_bloqueado'] .'</option>' . "\n";
					echo '</select></td>' . "\n";
					break;
/*				case 4: // select deporte
					echo '    <td><select class="filtros_select" name="filtro_'. $tabla .'_'. $campos[$i] .'" id="filtro_'. $tabla .'_'. $campos[$i] .'" onChange="sel_deporte(this.value);  reload_div(\''. $url['final'] . '&'. $campos[$i] .'=\'+document.getElementById(\'filtro_'. $tabla. $campos[$i] .'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. $campos[$i] .'\');">' . "\n";
					echo '		<option value=""></option>' . "\n";
					$res=mysqli_query($mysql_link, "SELECT auto_id, deporte from deportes") or die ('ERROR:'.mysqli_error($mysql_link));
					while($mnu_deporte=mysqli_fetch_row($res)){
						echo '<option value="'. $mnu_deporte[0] . ($_REQUEST[$campos[$i]]==$mnu_deporte[0]' selected="selected"':''). '">'.$mnu_deporte[1].'</option>' . "\n";
					}
					echo '</select></td>' . "\n";
					print('<script>
					function sel_deporte(valor) {
						if( valor=="" ) {
							document.getElementById(\'filtro_categoria\').disabled = true;
						}
						else {
							document.getElementById(\'filtro_categoria\').disabled = false;
							var url = "lib/get_categoria.php?deporte_id="+valor;
							$.get(url,
						 		function(html) {
									$("#filtro_categoria").removeOption(/./);
									var aux = html.split(",");
									var opciones = new Array();
									for( i in aux ) {
										opciones[aux[i]] = aux[i];
									}
									$("#filtro_categoria").addOption(opciones);
								}
							);
						} // if valor
					} // function sel_deporte
					</script>');
					break;
				case 5: // select categorias
					echo '    <td><select class="filtros_select" name="filtro_'. $tabla .'_'. $campos[$i] .'" id="filtro_'. $tabla .'_'. $campos[$i] .'" onChange="reload_div(\''. $url['final'] . '&'. $campos[$i] .'=\'+document.getElementById(\'filtro_'. $tabla. $campos[$i] .'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. $campos[$i] .'\');">';
					echo '</select></td>' . "\n";
					break;
				case 6: // select provincia
					echo '    <td><select class="filtros_select" name="filtro_'. $tabla .'_'. $campos[$i] .'" id="filtro_'. $tabla .'_'. $campos[$i] .'" onChange="sel_deporte(this.value);  reload_div(\''. $url['final'] . '&'. $campos[$i] .'=\'+document.getElementById(\'filtro_'. $tabla. $campos[$i] .'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. $campos[$i] .'\');">' . "\n";
					echo '		<option value=""></option>' . "\n";
					$res=mysqli_query($mysql_link, "SELECT auto_id, deporte from deportes") or die ('ERROR:'.mysqli_error($mysql_link));
					while($mnu_deporte=mysqli_fetch_row($res)){
						echo '<option value="'. $mnu_deporte[0] . ($_REQUEST[$campos[$i]]==$mnu_deporte[0]' selected="selected"':''). '">'.$mnu_deporte[1].'</option>' . "\n";
					}
					echo '</select></td>' . "\n";
					break;
				case 7: // select sexo
					break;*/
				case -1:
					echo '    <td class="col-'.$campos[$i].'" ></td>'. "\n";
					break;
				default:
				case 1:	// texto "like"
				case 2: // texto exacto
					echo '    <td class="col-'.$campos[$i].'" ><input class="filtros" type="text" name="filtro_'. $tabla .'_'. $campos[$i] .'" id="filtro_'. $tabla .'_'. $campos[$i] .'" value="'. $_REQUEST[$campos[$i]] .'" autocomplete="off" onkeyup="reload_div(\''. $url['final'] . '&'.$campos[$i].'=\'+document.getElementById(\'filtro_'. $tabla. '_'. $campos[$i].'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. '_'. $campos[$i] .'\');" title="'. $idioma['put_filtros'] .'"/></td>'. "\n";
					break;
			} // switch filtro
/*			if(  $filtros_array[$i] != -1 )
				echo '    <td><input class="filtros" type="text" name="filtro_'. $tabla. $campos[$i] .'" id="filtro_'. $tabla. $campos[$i] .'" value="'.$_REQUEST[$campos[$i]] .'" autocomplete="off" onkeyup="reload_div(\''. $url['final'] . '&'.$campos[$i].'=\'+document.getElementById(\'filtro_'. $tabla.$campos[$i].'\').value, \''. $tabla .'-env\', \'filtro_'. $tabla. $campos[$i] .'\');"/></td>'. "\n";
			else
				echo '    <td></td>'. "\n";*/
		}
		//echo '    <td class="col-relleno"></td>'. "\n";
		echo '  </tr>'. "\n";
	}
	echo ' </thead>'. "\n";

	return "$where ORDER BY $campo ". $globals['ordenes'][$orden] ." LIMIT $fila_0, $num_filas";

} // function tabla head

function list_head( $tabla, $where='', $url_extra='', $filtros=1, $campo_ini=null, $orden_ini=null, $titulo='', $div = '' )
{
	global $mysql_link, $orden_array, $idioma, $globals;

	if( empty($div) )
	  $div = 'results';

	$num_filas = $_REQUEST['num_filas'];
	if( empty($num_filas) || $num_filas == 0 ) $num_filas = 10;
	$fila_0 = $_REQUEST['fila_0'];
	if( empty($fila_0) ) $fila_0 = 0;

	$campo = $_REQUEST['campo'];
	if( empty($campo) ){
		if( empty($campo_ini) )
			$campo = '1';
		else
			$campo = $campo_ini;
	}
	$orden = $_REQUEST['orden'];
	if( $_REQUEST['orden'] == NULL ){
		if( empty($orden_ini) )
			$orden = 0;
		else
			$orden = $orden_ini;
	}

	$orden_array = explode(",",$orden_array);
	$num_campos = count($campos);
	//print_r($campos);
	//print_r($cabecera);
	//print_r( $filtros_array );

	$url['base'] = 'table_'. $tabla .'.php?';
	$url['paginacion'] = 'num_filas='. $num_filas . '&fila_0=' . $fila_0;
	$url['orden'] = '&campo=' . $campo . '&orden=' . $orden;
	$url['extra'] = $url_extra;
	$url['final'] = $url['base'] . $url['paginacion'] . $url['orden'] . $url['extra'];
	//print_r( $url );
	
	$query = "SELECT count(*) FROM $tabla WHERE 1 $where";
// 	echo '<p> Query: '. $query. '</p>';
	$res = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
	$num_registros = mysqli_result( $res, 0 );

	paginacion( $url, $num_registros, $num_filas, $fila_0, $div );
	if( !empty($titulo) ) {
		echo "<div class='principal-table-titulo'>$titulo</div> \n";
	}
	echo '	<div id="fake-paginacion-2" style="clear: both;"></div>'. "\n"; 

	return "$where ORDER BY $campo ". $globals['ordenes'][$orden] ." LIMIT $fila_0, $num_filas";

} // function list head

function get_stars( $points ) {

	global $globals;

	if( $points < 1 )
		$stars = 0;
	elseif( $points < 2 )
		$stars = 1;
	elseif( $points < 3 )
		$stars = 2;
	elseif( $points < 4 )
		$stars = 3;
	elseif( $points < 5 )
		$stars = 4;
	else
		$stars = 5;

	$file = "star_$stars.png";
	$file_path = imgpath.$file;
	if (is_readable($file_path))
		return $globals['img_url'] . $file;

} // get_stars

?>
