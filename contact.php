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
include(libpath.'ts.php');

//print_r( $_POST);
//print_r( $_REQUEST);
cabecera($globals['app_name'] . ' - '.$idioma['hlp_contact'], $_SERVER['PHP_SELF']);

laterales();

echo '<div id="cuerpo">'."\n";

if( isset($_POST["process"]) ) {
	if( $current_user->authenticated || intval($_POST["process"]) == 2 )
		contact2();
	else
		contact1();
} else
	contact0();

echo '</div>'."\n"; // cuerpo

pie();

function contact0() {

	global $idioma, $current_user;

	echo '<form action="contact.php" method="post" id="contacto" >' . "\n";
	echo '<fieldset>' . "\n";
	echo '<legend>' . $idioma['hlp_contact'] . '</legend>' . "\n";
	echo '<dl>' . "\n";
	// nombre
	echo '<dt><label for="">'.$idioma['id_nombre'].':</label></dt>' . "\n";
	echo '<dd><input type="text" name="nombre" id="nombre" value="'. $current_user->user_login .'" />';
	echo '</dd>' . "\n";
	// e-mail
	echo '<dt><label for="email">'.$idioma['id_email'].':</label></dt>' . "\n";
	echo '<dd><input type="text" name="email" id="email" value="'. $current_user->user_email .'" />';
	echo '</dd>' . "\n";
	// asunto
	if( isset($_REQUEST['subject']) )
		$asunto = $_REQUEST['subject'];
	echo '<dt><label for="asunto">'.$idioma['cnt_asunto'].':</label></dt>' . "\n";
	echo '<dd><input type="text" name="asunto" id="asunto" value="'. $asunto .'" />';
	echo '</dd>' . "\n";
	// cuerpo mensaje
	echo '<dt><label for="mensaje">'.$idioma['cnt_mensaje'].':</label></dt>' . "\n";
	echo '<dd>';
	echo '<textarea name="mensaje" id="mensaje" value="" rows="8" /></textarea>' . "\n";
	echo '</dd>' . "\n";

	echo '</dl>' . "\n";

	echo '<p><input id="enviar" type="submit" class="button" name="enviar" value="'.$idioma['id_enviar'].'" '. $disabled .' /></p>' . "\n";
	echo '<input type="hidden" name="process" value="1"/>' . "\n";

	echo '</fieldset>' . "\n";
	echo '</form>' . "\n";
//	echo '' . "\n";
}

function contact1() {

	global $idioma;

	if (!check_user_fields()) return;

	echo '<form action="contact.php" method="post" id="thisform">' . "\n";
	echo '<fieldset><legend><span class="sign">'. $idioma['hlp_contact'] .'</span></legend>'."\n";
	// TODO: Mejor que funcione e reCaptcha
	ts_print_form();
	echo '<input type="submit" name="submit" class="button" value="'.$idioma['id_enviar'].'" />';
	echo '<input type="hidden" name="process" value="2" />';
	echo '<input type="hidden" name="nombre" value="'.$_POST["nombre"].'" />';
	echo '<input type="hidden" name="email" value="'.$_POST["email"].'" />';
	echo '<input type="hidden" name="asunto" value="'.$_POST["asunto"].'" />';
	echo '<input type="hidden" name="mensaje" value="'.$_POST["mensaje"].'" />';

	echo '</fieldset></form>'."\n";
}

function contact2() {

	global $idioma, $current_user, $globals;

	if ( !$current_user->authenticated && !ts_is_human()) {
		register_error($idioma['err_cod_seg']);
		return;
	}


	if (!check_user_fields())  return;

	$nombre=mysqli_real_escape_string( $mysql_link, $_POST['nombre']);
	$email=clean_input_string(trim($_POST['email']));
	$asunto=mysqli_real_escape_string( $mysql_link, $_POST['asunto']);
	$mensaje=mysqli_real_escape_string( $mysql_link, $_POST['mensaje']);

	$query = "INSERT INTO contact (login, login_email, ip, email, nombre, asunto, fecha, mensaje) VALUES ('$current_user->user_login', '$current_user->user_email', '". $globals['user_ip'] ."', '$email', '$nombre', '$asunto', now(), '$mensaje')";
	//echo '<p> Query: ' . $query . '</p>';
	if (mysqli_query( $query )) {
		echo '<fieldset>'."\n";
		echo '<legend>'. $idioma['hlp_contact'] .'</legend>'."\n";
		$mensaje_aux = "usuario: $current_user->user_login \n";
		$mensaje_aux .= "e-mail: $current_user->user_email \n";
		$mensaje_aux .= "ip: ". $globals['user_ip'] ." \n";
		$mensaje_aux .= "------------------------------------------- \n\n";
		$mensaje_aux .= $mensaje;
		require_once(libpath.'mail.php');
		if( $sent = send_free_mail($email, $globals['email'], $asunto, $mensaje_aux, $nombre) )
			echo '<p><strong>' .$idioma['cnt_sent'] . '</strong></p>';
		echo '</fieldset>'."\n";
	} else {
		register_error( $idioma['err_cnt_db'] );
	}
}

function check_user_fields() {

	global $idioma;

	$error = false;

/*	if(check_ban_proxy()) {
		register_error($idioma['err_ip_1']);
		$error=true;
	}*/
	if( !isset($_POST["nombre"]) ) {
		register_error($idioma['err_cnt_nombre']);
		$error=true;
	}
	if(!check_email(trim($_POST["email"]))) {
		register_error($idioma['err_invalid_email']);
		$error=true;
	}
	if( !isset($_POST["asunto"]) ) {
		register_error($idioma['err_cnt_asunto']);
		$error=true;
	}
	if( !isset($_POST["mensaje"]) ) {
		register_error($idioma['err_cnt_mensaje']);
		$error=true;
	}

/* Por ahora pasamos del tema IP
	// Check registers from the same IP network
	$user_ip = $globals['user_ip'];
	$ip_classes = explode(".", $user_ip);

	// From the same IP
	$registered = (int) $db->get_var("select count(*) from logs where log_date > date_sub(now(), interval 24 hour) and log_type in ('user_new', 'user_delete') and log_ip = '$user_ip'");
	if($registered > 0) {
		syslog(LOG_NOTICE, "Meneame, register not accepted by IP address ($_POST[username]) $user_ip");
		register_error($idioma['err_ip_2']);
		$error=true;
	}
	if ($error) return false;

	// Check class
	// nnn.nnn.nnn
	$ip_class = $ip_classes[0] . '.' . $ip_classes[1] . '.' . $ip_classes[2] . '.%';
	$registered = (int) $db->get_var("select count(*) from logs where log_date > date_sub(now(), interval 6 hour) and log_type in ('user_new', 'user_delete') and log_ip like '$ip_class'");
	if($registered > 0) {
		syslog(LOG_NOTICE, "Meneame, register not accepted by IP class ($_POST[username]) $ip_class");
		register_error($idioma['err_ip_3']. " ($ip_class)");
		$error=true;
	}
	if ($error) return false;

	// Check class
	// nnn.nnn
	$ip_class = $ip_classes[0] . '.' . $ip_classes[1] . '.%';
	$registered = (int) $db->get_var("select count(*) from logs where log_date > date_sub(now(), interval 1 hour) and log_type in ('user_new', 'user_delete') and log_ip like '$ip_class'");
	if($registered > 2) {
		syslog(LOG_NOTICE, "Meneame, register not accepted by IP class ($_POST[username]) $ip_class");
		register_error($idioma['err_ip_4'] . " ($ip_class)");
		$error=true;
	}*/
	if ($error) 
	{
		echo '<div id="botones">' . "\n";
		echo '<input type="button" class="button" name="volver" value="'. $idioma['volver'] .'" onClick="history.go(-1)"/>' . "\n";
		echo '</div>' . "\n"; // botones

		return false;
	}

	return true;
  }

?>
