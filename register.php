<?php
// Modified and adapted by ßingen from:
// Ricardo Galli
// http://viewvc.meneame.net/index.cgi/branches/version3/
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.

// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('config.php');
include(libpath.'ts.php');

  cabecera($idioma['tit_resgistro'], $_SERVER['PHP_SELF']);
  laterales();
  echo "<div id='registro'>\n";
//	print_r($_POST);
	if( $current_user->authenticated )
		show_error($idioma['err_register_auth']);
	elseif(isset($_POST["process"])) {

		switch (intval($_POST["process"])) {
			case 1:
				do_register1();
				break;
			case 2:
				do_register2();
				break;
		}
	} else {
		if( $globals['invitaciones_alta'] ) {
// TODO: volver a ponerlo bien!!!
// 		if( $globals['invitaciones_alta'] && $_GET['k'] != 'ocb20091004' ) {
			// Alta por invitación
			// echo "<p> !empty(". $_GET['id'] .") && !empty(". $_GET['t'] .") && !empty(". $_GET['k'] .")</p>";
			if (!empty($_GET['id']) && !empty($_GET['t']) && !empty($_GET['k'])) {
				$inv_id = $_GET['id'];
				$time = intval($_GET['t']);
				$key = $_GET['k'];

				$query = "SELECT de, a, UNIX_TIMESTAMP(fecha), clave FROM invitaciones_alta WHERE auto_id = $inv_id";
				if($res = mysqli_query( $query )) {
					$invitacion = mysqli_fetch_object( $res );
					$now = time();
 					//echo "$invitacion->de . $invitacion->a . $time.$site_key.".get_server_name()."\n";
					//$key2 = md5( $invitacion->de . $invitacion->a . $time . $site_key . get_server_name() );
					$key2 = $invitacion->clave;
// 					echo "$now, $time; $key == $key2\n";
					if( $time > $now || $now > $time + $globals['inv_time'] ) {
						show_error($idioma['err_register_time']);
						$error = true;
 						//header('Location: ./index.php');
 						//die;
					}
					if( $key != $key2 ) {
						show_error($idioma['err_register_key']);
						$error = true;
					}
				} else {
					show_error($idioma['err_db']);
					$error = true;
				}
			} else {
				show_error($idioma['err_register_url']);
				$error = true;
			}
		} // if alta por invitación
		if( ! $error )
			do_register0();
	}
  echo "</div>\n";

  function do_register0() {
	global $idioma, $globals, $invitacion;

		if( $globals['invitaciones_alta'] ) {
			$email = $invitacion->a;
		}

	echo '<div class="recoverpass"><h4><a href="rec_pwd.php">'.$idioma['forgot_pwd'].'</a></h4></div>';

	echo '<form enctype="multipart/form-data" action="register.php" method="post" id="thisform" onSubmit="return check_checkfield(\'acceptlegal\', \''.$idioma['err_legal'].'\')">' . "\n";
	echo '<fieldset>' . "\n";
	echo '<legend><span class="sign">' . $idioma['id_registro'] . '</span></legend>' . "\n";

	echo '<dl>' . "\n";
	echo '<dt><label for="name">' . $idioma['id_usuario'] . ':</label></dt>' . "\n";
	echo '<dd><input type="text" name="username" id="name" value="" onkeyup="enablebutton(this.form.checkbutton1, this.form.submit, this)" size="25" tabindex="1"/></dd>' . "\n";
	echo '<dt><span id="checkit"><input type="button" class="button" id="checkbutton1" disabled="disabled" value="'.$idioma['id_verificar'].'" onclick="checkfield(\'username\', this.form, this.form.username)"/></span></dt>' . "\n";
	echo '<dd><span id="usernamecheckitvalue"></span>&nbsp;</dd>' . "\n";

	echo '<dt><label for="email">'.$idioma['id_email'].':</label></dt>' . "\n";
	echo '<dd><input type="text" id="email" name="email" value="'. $email .'"  onkeyup="enablebutton(this.form.checkbutton2, this.form.submit, this)" size="25" tabindex="2"/>' . "\n";
	echo '</dd>' . "\n";
	echo '<dt><input type="button" class="button" id="checkbutton2" disabled="disabled" value="'.$idioma['id_verificar'].'" onclick="checkfield(\'email\', this.form, this.form.email)"/></dt>' . "\n";
	echo '<dd>&nbsp;<span id="emailcheckitvalue"></span></dd>' . "\n";
	echo '<dt></dt><dd><span class="note">'.$idioma['id_email_nota'].'</span></dd>';
	echo '<dt><label for="email2">'.$idioma['id_email2'].':</label></dt>' . "\n";
	echo '<dd><input type="text" id="email2" name="email2" value="'. $email .'" onkeyup="checkEqualFields(this.form.email2, this.form.email)" size="25" tabindex="2"/>' . "\n";
	echo '</dd>' . "\n";

	echo '<dt><label for="password">' . $idioma['id_clave'] . ':</label></dt>' . "\n";
//	echo '<span class="note">'._('al menos ocho caracteres, incluyendo mayúsculas, minúsculas y números').' </span><br />';
	echo '<dd><input type="password" id="password" name="password" size="25" tabindex="3" onkeyup="return securePasswordCheck(this.form.password);"/><span id="password1-warning"></span></dd>' . "\n";

	echo '<dt><label for="verify">' . $idioma['id_reclave'] . ': </label></dt>' . "\n";
	echo '<dd><input type="password" id="verify" name="password2" size="25" tabindex="4" onkeyup="checkEqualFields(this.form.password2, this.form.password)"/></dd>' . "\n";

	echo '<dt><label for="nombre">'.$idioma['id_nombre'].':</label></dt>' . "\n";
	echo '<dd><input type="text" id="nombre" name="nombre" value="'. $nombre .'" size="25" tabindex="5"/>' . "\n";
	echo '</dd>' . "\n";

	echo '<dt><label for="apellidos">'.$idioma['id_apellidos'].':</label></dt>' . "\n";
	echo '<dd><input type="text" id="apellidos" name="apellidos" value="'. $apellido .'" size="25" tabindex="6"/>' . "\n";
	echo '</dd>' . "\n";

	echo '<dt><label for="sexo">'.$idioma['usr_sexo'].':</label></dt>' . "\n";
	echo '<dd><select name="sexo" id="sexo"  tabindex="7">';
		echo '<option value="Hombre">'.$idioma['usr_hombre'].'</option>';
		echo '<option value="Mujer">'.$idioma['usr_mujer'].'</option>';
	echo '</select></dd>' . "\n";

	print('
	<script type="text/javascript">
	$(function()
        	    {
			$(\'.date-pick\').datePicker({
			year: 1970,
			month: 0,
			startDate: \'01/01/1900\'
			});
        	    });
		</script>
	');
	echo '<dt><label id="lfecha" for="fecha">'.$idioma['usr_birthday'].':</label></dt>' . "\n";
	echo '<dd><input name="fecha" id="fecha" value="" class="date-pick" tabindex="8">' . "\n";
	echo '</dd>' . "\n";

	echo '<dt><label id="llengua" for="lengua">'.$idioma['id_lengua'].':</label></dt>' . "\n";
	echo '<dd><select name="lengua" id="lengua"  tabindex="9">';
	$res=mysqli_query("SELECT auto_id, idioma from idiomas") or die ('ERROR:'.mysqli_error());
	while($mnu_idioma=mysqli_fetch_row($res)){
		echo '<option value="'.$mnu_idioma[0].'">'.$mnu_idioma[1].'</option>';
	}
	echo '</select></dd>' . "\n";


	echo '<dt></dt><dd><label><span class="note">'.$idioma['id_legal_1'].'<a href="legal.php">'.$idioma['id_legal_2'].'</a>';
	echo ' <input type="checkbox" id="acceptlegal" name="acceptlegal" value="accept"/></span></label></dd>' . "\n";

	echo '<dt></dt><dd><input type="submit" class="button" disabled="disabled" name="submit" value="'.$idioma['id_enviar'].'" /></dd>' . "\n";
	echo '<input type="hidden" name="process" value="1"/>' . "\n";
	echo '<input type="hidden" name="inv_email" value="'. $invitacion->a .'"/>' . "\n";

	echo '</fieldset>' . "\n";
	echo '</form>' . "\n";
  }


  function do_register1() {
	global $idioma;

	if($_POST["acceptlegal"] !== 'accept' ) {
		show_error($idioma['err_legal']);
		return;
	}
	if (!check_user_fields()) return;
	echo '<br style="clear:both" />';


	echo '<form action="register.php" method="post" id="thisform">' . "\n";
	echo '<fieldset><legend><span class="sign">'.$idioma['id_validar'].'</span></legend>'."\n";
	// TODO: Mejor que funcione e reCaptcha
	ts_print_form();
	echo '<input type="submit" name="submit" class="button" value="'.$idioma['id_enviar'].'" />';
	echo '<input type="hidden" name="process" value="2" />';
	echo '<input type="hidden" name="email" value="'.clean_input_string($_POST["email"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="email2" value="'.clean_input_string($_POST["email2"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="inv_email" value="'.clean_input_string($_POST["inv_email"]).'" />';
	echo '<input type="hidden" name="username" value="'.clean_input_string($_POST["username"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="password" value="'.clean_input_string($_POST["password"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="password2" value="'.clean_input_string($_POST["password2"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="nombre" value="'.clean_input_string($_POST["nombre"]).'" />'; // extra sanity, in fact not needed
// TODO: clean_input_string quita espacios, y no debería en nombre y apellidos!!
	echo '<input type="hidden" name="apellidos" value="'.clean_input_string($_POST["apellidos"]).'" />'; // extra sanity, in fact not needed
	echo '<input type="hidden" name="telefono" value="'.clean_input_string($_POST["telefono"]).'" />'; // extra sanity, in fact not needed
//	echo '<input type="hidden" name="provincia" value="'.clean_input_string($_POST["provincia"]).'" />';
	echo '<input type="hidden" name="provincia" value="'.$_POST["provincia"].'" />';
	echo '<input type="hidden" name="sexo" value="'.clean_input_string($_POST["sexo"]).'" />';
	echo '<input type="hidden" name="lengua" value="'.clean_input_string($_POST["lengua"]).'" />';
	echo '<input type="hidden" name="fecha" value="'.clean_input_string($_POST["fecha"]).'" />';

	$res = mysqli_query("SELECT auto_id, deporte FROM deportes") or die ('ERROR:'.mysqli_error());
	while( $deportes=mysqli_fetch_row($res) ) {
		echo '<input type="hidden" name="'.$deportes[1].'" value="'.$_POST[str_replace(' ', '_',"$deportes[1]")].'" />';
	}
	echo '<input type="hidden" name="prv_email" value="'. $_POST["prv_email"] .'" />';
	echo '<input type="hidden" name="prv_nombre" value="'. $_POST["prv_nombre"] .'" />';
	echo '<input type="hidden" name="prv_apellidos" value="'. $_POST["prv_apellidos"] .'" />';
	echo '<input type="hidden" name="prv_fecha" value="'. $_POST["prv_fecha"] .'" />';
	echo '<input type="hidden" name="prv_telefono" value="'. $_POST["prv_telefono"] .'" />';

	echo '</fieldset></form>'."\n";
  }

  function do_register2() {
	global $idioma, $globals;
//	global $db, $current_user, $globals;
	if ( !ts_is_human()) {
		show_error($idioma['err_cod_seg']);
		return;
	}

	// comprobar invitación
	if( $globals['invitaciones_alta'] && trim($_POST["email"]) != trim($_POST["inv_email"]) ) {
		show_error($idioma['err_inv_meil']);
		return;
	}

	if (!check_user_fields())  return;

	$username=clean_input_string(trim($_POST['username'])); // sanity check
	$dbusername=mysqli_real_escape_string( $mysql_link, $username); // sanity check
	$password=md5(trim($_POST['password']));
//	$password=trim($_POST['password']);
	$email=clean_input_string(trim($_POST['email'])); // sanity check
	$dbemail=mysqli_real_escape_string( $mysql_link, $email); // sanity check
	$telefono=trim($_POST['telefono']);
	$nombre=mysqli_real_escape_string( $mysql_link, $_POST['nombre']);
	$apellidos=mysqli_real_escape_string( $mysql_link, $_POST['apellidos']);
//	$user_ip = $globals['user_ip'];
	$provincia=$_POST['provincia'];
	$sexo=$_POST['sexo'];
	$lengua=$_POST['lengua'];
	//echo '<p> fecha: ' . $_POST['fecha'] . '</p>';
	if( !empty($_POST['fecha']) )
	{
		$fecha_array = explode('/', $_POST['fecha']);
		//$birthday=mktime(0,0,0,$fecha_array[1],$fecha_array[0],$fecha_array[2]);
		//$birthday=date('Y-m-d', $birthday)
		$birthday = $fecha_array[2] .'-'. $fecha_array[1] .'-'. $fecha_array[0];
	} else
		$birthday = '1970-00-00';
	//print_r($fecha_array);
	//echo '<p> fecha: ' . $birthday . '</p>';
	if (!user_exists($username)) {
		$query = "INSERT INTO usuarios (login, login_register, email, email_register, password, date, ip, nombre, apellidos, telefono, provincia, sexo, lang, birthday) VALUES ('$dbusername', '$dbusername', '$dbemail', '$dbemail', '$password', now(), '','$nombre', '$apellidos', '$telefono', '$provincia', '$sexo', '$lengua', '$birthday')";
		//echo '<p> Query: ' . $query . '</p>';
		if (mysqli_query( $query )) {
			echo '<fieldset>'."\n";
			echo '<legend><span class="sign">'.$idioma['id_registro_2'].'</span></legend>'."\n";
			require_once(libpath.'user.php');
			$user=new User();
			$user->username=$username;
			if(!$user->read()) {
				show_error($idioma['err_insert_user']);
			} else {
				require_once(libpath.'mail.php');
				$sent = send_recover_mail($user, 1);
			}
			echo '</fieldset>'."\n";
		} else {
			show_error( $query . $idioma['err_insert_user'] );
		}
		$res = mysqli_query("SELECT auto_id, deporte FROM deportes") or die ('ERROR:'.mysqli_error());
		while( $deportes=mysqli_fetch_row($res) ) {
			$puntos=$_POST[str_replace(' ', '_',"$deportes[1]")];
			if( empty($puntos) ) $puntos=-1;
			if (mysqli_query("INSERT INTO puntuaciones (usuario_id, deporte_id, puntos) VALUES ($user->id, $deportes[0],$puntos)")) {
			} else {
				show_error("INSERT INTO puntuaciones (usuario_id, deporte_id, puntos) VALUES ($user->id, $deportes[0],$puntos)".$idioma['err_insert_user']);
			}
		}
		insert_privacidad( $user->id, 'email', $_POST['prv_email'] );
		insert_privacidad( $user->id, 'nombre', $_POST['prv_nombre'] );
		insert_privacidad( $user->id, 'apellidos', $_POST['prv_apellidos'] );
		insert_privacidad( $user->id, 'fecha', $_POST['prv_fecha'] );
		insert_privacidad( $user->id, 'telefono', $_POST['prv_telefono'] );
		insert_privacidad( $user->id, 'direccion', NADIE );
		insert_privacidad( $user->id, 'url', NADIE );
	} else {
		show_error($idioma['err_user_exists']);
	}
  }

  function insert_privacidad( $userid, $campo, $valor ) {
	global $idioma;

	$query = "INSERT INTO privacidad (usuario_id, campo, valor) VALUES ($userid, '$campo', $valor)";
	if (mysqli_query( $query )) {
	} else {
		show_error( $idioma['err_insert_user'] );
	}
  }
  function check_user_fields() {
//	global $globals, $db;
	global $con_mysql, $idioma;

	$error = false;

/*	if(check_ban_proxy()) {
		show_error($idioma['err_ip_1']);
		$error=true;
	}*/
	if(!isset($_POST["username"]) || strlen($_POST["username"]) < 3) {
		show_error($idioma['err_short_user']);
		$error=true;
	}
	if(!check_username($_POST["username"])) {
		show_error($idioma['err_invalid_user']);
		$error=true;
	}
	if(user_exists(trim($_POST["username"])) ) {
		show_error($idioma['err_user_exists']);
		$error=true;
	}
	if(!check_email(trim($_POST["email"]))) {
		show_error($idioma['err_invalid_email']);
		$error=true;
	}
// TODO: volver a poner lo del meil único!!!!!!!!!!
/*	if(email_exists(trim($_POST["email"])) ) {
		show_error($idioma['err_email_exists']);
		$error=true;
	}*/
	if($_POST["email"] !== $_POST["email2"] ) {
		show_error($idioma['err_email_vrf']);
		$error=true;
	}
	if(preg_match('/[ \']/', $_POST["password"]) || preg_match('/[ \']/', $_POST["password2"]) ) {
		show_error($idioma['err_pwd_1']);
		$error=true;
	}
	/*$comp = check_password($_POST["password"]);
	if(! $comp) {*/
	if(! check_password($_POST["password"])) {
//		echo '<p> La bola no entrou: ' . $_POST["password"] . ': '. $comp . '</p>';
		show_error($idioma['err_pwd_2']);
		$error=true;
	}
	/*else {
		echo '<p> La bola entrou: ' . $_POST["password"] . ': '. $comp . '</p>';
	}*/
	if($_POST["password"] !== $_POST["password2"] ) {
		show_error($idioma['err_pwd_3']);
		$error=true;
	}
	if(! check_phone(trim($_POST["telefono"]))) {
		show_error($idioma['err_phone']);
		$error=true;
	}
	if(phone_exists(trim($_POST["telefono"])) ) {
		show_error($idioma['err_phone_exists']);
		$error=true;
	}

	if(! check_provincia($_POST["provincia"]) ) {
		show_error($idioma['err_provincia']);
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
		show_error($idioma['err_ip_2']);
		$error=true;
	}
	if ($error) return false;

	// Check class
	// nnn.nnn.nnn
	$ip_class = $ip_classes[0] . '.' . $ip_classes[1] . '.' . $ip_classes[2] . '.%';
	$registered = (int) $db->get_var("select count(*) from logs where log_date > date_sub(now(), interval 6 hour) and log_type in ('user_new', 'user_delete') and log_ip like '$ip_class'");
	if($registered > 0) {
		syslog(LOG_NOTICE, "Meneame, register not accepted by IP class ($_POST[username]) $ip_class");
		show_error($idioma['err_ip_3']. " ($ip_class)");
		$error=true;
	}
	if ($error) return false;

	// Check class
	// nnn.nnn
	$ip_class = $ip_classes[0] . '.' . $ip_classes[1] . '.%';
	$registered = (int) $db->get_var("select count(*) from logs where log_date > date_sub(now(), interval 1 hour) and log_type in ('user_new', 'user_delete') and log_ip like '$ip_class'");
	if($registered > 2) {
		syslog(LOG_NOTICE, "Meneame, register not accepted by IP class ($_POST[username]) $ip_class");
		show_error($idioma['err_ip_4'] . " ($ip_class)");
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

  <?php pie(); ?>

