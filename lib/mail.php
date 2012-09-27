<?
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

function send_recover_mail ($user, $tipo) {
	global $idioma, $site_key, $globals;

//	require_once(libpath.'user.php');

	$now = time();
	$key = md5($user->id.$user->pass.$now.$site_key.get_server_name());
	$url = 'http://'. get_server_name() . $globals['base_url'] .'profile.php?login='. $user->username .'&t='. $now .'&k='. $key;
	//echo "$user->username, $user->email, $url<br />";
	$to      = $user->email;
	if( $tipo == 1 )
		$subject = '[patesqu] '. $idioma['mail_reg_subject'];
	else
		$subject = '[patesqu] '. $idioma['mail_rec_subject'];
// 	echo "<p> $subject </p>";
// 	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");
// 	echo "<p> $subject </p>";
	$message = $user->username . $idioma['mail_rec_body_1'] . "\n\n$url\n\n";
	$message .= $idioma['mail_rec_body_2'] . "\nhttp://". get_server_name().$globals['base_url'] ."rec_pwd.php\n\n";
	$message .= $idioma['mail_rec_body_3'] . "\n" . "\n";
//	$message .= "\n\n". $idioma['mail_rec_body_4'] . $globals['user_ip'] . "\n\n";
	$message .= "-- \n  " . $idioma['mail_rec_body_5'];
	$message = wordwrap($message, 70);
	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '.$idioma['mail_rec_from_1'].' '.get_server_name().' <'.$idioma['mail_rec_from_2'].'@'.get_server_name().">\n".
				'Reply-To: '.$idioma['mail_rec_from_2'].'@'.get_server_name()."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	mail($to, $subject, $message, $headers);
	echo '<p><strong>' .$idioma['mail_rec_msg'] . '</strong></p>';
	return true;
}

/*  
	1: Abierto
	2: Cerrado
	3: Convocado
	4: Por evaluar
	5: Evaluado
	6: Cancelado
	7: Nulo
	8: Modificado
	9:
	10:
*/
function send_match_info ($accion, $user, $send_key=false, $player=0, $inbox=true) {
//	global $site_key, $globals;
	global $idioma, $globals, $partido;

//	require_once(libpath.'user.php');
	$error = false;

	$now = time();
//	$key = md5($user->id.$user->pass.$now.$site_key.get_server_name());
//	$key = md5($user->id.$user->pass.$now.get_server_name());
	$url = 'http://'.get_server_name().$globals['base_url'].'partido.php?id='.$partido->id;
	$from = '';
	switch( $accion ) {
		case 2: // convocar
			$url_accion = "&accion=convocar";
			break;
		case 5: // confirmar resultados
			$url_accion = "&accion=confirmar_resultados&player=" . $player;
			$from = 'patesqu';
			break;
		case 9: // confirmar plaza
			$url_accion = "&accion=confirmar&player=" . $player;
			$from = $partido->jugador[1];
			break;
		default:
			$url_accion = "";
			break;
	}
	if( !empty( $partido->clave ) && $send_key )
		$url .= '&clave=' . $partido->clave;
	//echo "$user->username, $user->email, $url<br />";

	$to      = $user->email;
	$subject = '[patesqu] '. $idioma['mail_ptd_'.$accion.'_subject'];
	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");
	$message = $idioma['mail_ptd_greeting']. $user->username. ','."\n\n";
	if( $accion == 11 || $accion == 12 )
		$message .= $partido->jugador[$player];
	$message .= $idioma['mail_ptd_'.$accion.'_body_1']."\n";
	$message .= "\n$url\n\n";
	$message .= $idioma['usr_deporte'] . ": ".$partido->get_deporte()."\n";
	$message .= $idioma['ptd_fecha'] . ": ".$partido->get_fecha()."\n";
	$message .= $idioma['ptd_hora'] . ": ".$partido->get_hora()."\n";
	$message .= $idioma['cp_club'] . ": ".$partido->club."\n\n";
	if( $accion == CONVOCADO && ( ($partido->jugador[1] == $user->username && $partido->j1_balls == 1) || ($partido->jugador[2] == $user->username && $partido->j2_balls == 1) ||($partido->jugador[3] == $user->username && $partido->j3_balls == 1) ||($partido->jugador[4] == $user->username && $partido->j4_balls == 1) ) ) {
		$message .= $idioma['mail_ptd_3_body_2']."\n";
		//$message .= 'user: '. $user->username .' jugador_1: '.$partido->jugador[1].' j1_balls: '. $partido->j1_balls .' jugador 2: '. $partido->jugador[2] .' j2 balls: '. $partido->j2_balls. ' jugador 3: ' . $partido->jugador[3] .' j3 balls: '. $partido->j3_balls.' jugador 4: '. $partido->jugador[4] .' j4 balls: '. $partido->j4_balls ."\n";
	}
	elseif( $accion == CONVOCADO && $partido->j1_balls == 0 && $partido->j2_balls == 0 && $partido->j3_balls == 0 && $partido->j4_balls == 0 ) {
		$message .= $idioma['mail_ptd_3_body_3']."\n";
		//$message .= 'user: '. $user->username .' jugador_1: '.$partido->jugador[1].' j1_balls: '. $partido->j1_balls .' jugador 2: '. $partido->jugador[2] .' j2 balls: '. $partido->j2_balls. ' jugador 3: ' . $partido->jugador[3] .' j3 balls: '. $partido->j3_balls.' jugador 4: '. $partido->jugador[4] .' j4 balls: '. $partido->j4_balls ."\n";
	} elseif( !empty($url_accion) ) {
		$message .= $idioma['mail_ptd_'.$accion.'_body_2']."\n";
		$message .= "\n\n".$url.$url_accion."\n\n";
	}
	$message .= "-- \n  " . $idioma['mail_rec_body_5'];
	$message = wordwrap($message, 70);

	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '.$idioma['mail_rec_from_1'].' '.get_server_name().' <'.$idioma['mail_rec_from_2'].'@'.get_server_name().">\n".
				'Reply-To: '.$idioma['mail_rec_from_2'].'@'.get_server_name()."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	if( !mail($to, $subject, $message, $headers) )
		$error = true;

	if( $inbox ) { // hacemos copia en el inbox interno
		if( empty($from) ) { 
			if( $player > 0 )
				$from = $partido->jugador[$player];
			else
				$from = "patesqu";
		}
		$query = "INSERT INTO mensajes (tipo, de, a, fecha, texto) VALUES (". MEIL .", '$from', '$user->username', now(), '". addslashes($message) ."')";
		if( !mysqli_query( $query ) )
			$error = true;
	}
	//echo "<p>$to</p>";
	//echo "<p>$subject</p>";
	//echo "<p>$message</p>";
	//echo "<p>$headers</p>";
	if( $error )
		return false;
	else
		return true;
} // send_match_info

function send_cuando_info ($accion, $user, $player=0, $inbox=true) {

	global $idioma, $globals, $cuando;


	$now = time();
	if( $accion != 3 )
		$url = 'http://'.get_server_name().'/cuando_jugar.php?cuando_id=' . $cuando->id;
	else
		$url = 'http://'.get_server_name().'/cuando_jugar.php';
// 	switch( $accion ) {
// 		case 1: // nuevo
// 			break;
// 		case 2: // modificar
// 			break;
// 		case 3: // borrar
// 			break;
// 		default:
// 			break;
// 	}

	$to      = $user->email;
	$subject = '[patesqu] '. $idioma['mail_cnd_'.$accion.'_subject'];
	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");
	$message = $idioma['mail_ptd_greeting']. $user->username. ','."\n\n";
	$message .= $cuando->jugador[$player];
	$message .= $idioma['mail_cnd_'.$accion.'_body_1']."\n\n";
	$query = "SELECT deporte FROM deportes WHERE auto_id=$cuando->deporte_id";
// 	echo "<p> Query: $query </p> \n";
	if( $res = mysqli_query( $query ) ) {
		$deporte = mysqli_result( $res, 0 );
		$message .= $idioma['usr_deporte'] . ": ". $deporte ."\n";
	}
	for( $i=1; $i <= $cuando->num_jugadores; $i++ )
		$message .= $idioma['cp_jugador'] . " $i: ". $cuando->jugador[$i] ."\n";
	if( $accion != 3 )
		$message .= "\n" . $idioma['mail_cnd_'.$accion.'_body_2']."\n";
	$message .= "\n$url\n\n";
	$message .= "-- \n  " . $idioma['mail_rec_body_5'];
	$message = wordwrap($message, 70);

	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '.$idioma['mail_rec_from_1'].' '.get_server_name().' <'.$idioma['mail_rec_from_2'].'@'.get_server_name().">\n".
				'Reply-To: '.$idioma['mail_rec_from_2'].'@'.get_server_name()."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	if( !mail($to, $subject, $message, $headers) )
		$error = true;

	if( $inbox ) { // hacemos copia en el inbox interno
		$query = "INSERT INTO mensajes (tipo, de, a, fecha, texto) VALUES (". MEIL .", '". $cuando->jugador[$player] ."', '$user->username', now(), '". addslashes($message) ."')";
// 		echo "<p> Query: $query </p> \n";
		if( !mysqli_query( $query ) )
			$error = true;
	}
	//echo "<p>$to</p>";
	//echo "<p>$subject</p>";
	//echo "<p>$message</p>";
	//echo "<p>$headers</p>";
	if( $error )
		return false;
	else
		return true;
} //send_cuando_info

function send_user_info ($accion, $user, $inbox=true) {

	global $idioma, $globals;

//	require_once(libpath.'user.php');
	$error = false;

	$now = time();
	//$url = 'http://'.get_server_name().$globals['base_url'].'partido.php?id='.$partido->id;
	switch( $accion ) {
		case 1: // deshabilitar por bajo patesqu
			break;
		default:
			$url_accion = "";
			break;
	}

	//echo "$user->username, $user->email, $url<br />";

	$to      = $user->email;
	$subject = '[patesqu] '. $idioma['mail_usr_'.$accion.'_subject'];
	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");

	$message = $idioma['mail_ptd_greeting']. $user->username. ','."\n\n";
	$message .= $idioma['mail_usr_'.$accion.'_body_1']."\n";
	$message .= $idioma['mail_usr_'.$accion.'_body_2']."\n";

	$message .= "-- \n  " . $idioma['mail_rec_body_5'];
	$message = wordwrap($message, 70);

	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '.$idioma['mail_rec_from_1'].' '.get_server_name().' <'.$idioma['mail_rec_from_2'].'@'.get_server_name().">\n".
				'Reply-To: '.$idioma['mail_rec_from_2'].'@'.get_server_name()."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	if( !mail($to, $subject, $message, $headers) )
		$error = true;

	if( $inbox ) { // hacemos copia en el inbox interno
		if( $player > 0 )
			$from = $partido->jugador[$player];
		else
			$from = "patesqu";
		$query = "INSERT INTO mensajes (tipo, de, a, fecha, texto) VALUES (". MEIL .", '$from', '$user->username', now(), '". addslashes($message) ."')";
		if( !mysqli_query( $query ) )
			$error = true;
	}
	//echo "<p>$to</p>";
	//echo "<p>$subject</p>";
	//echo "<p>$message</p>";
	//echo "<p>$headers</p>";
	if( $error )
		return false;
	else
		return true;
} // send_user_info

function send_friend_info ($accion, $usuario, $amigo, $inbox=true) {

	global $idioma, $globals;

	$error = false;

	require_once(libpath.'user.php');
	$user = new User();
	$user->username = $amigo;
	$user->read();

	$now = time();
	switch( $accion ) {
		case 1: // solicitud de amistad
			$url = 'http://'.get_server_name(). substr($globals['base_url'], 0, strpos($globals['base_url'],"lib")) .'user.php?login='. $usuario;
			$url_accion = 'http://' . get_server_name(). substr($globals['base_url'], 0, strpos($globals['base_url'],"lib")) . 'user.php?login='. $amigo .'&view=friends&friend='.$usuario.'&accion=';
			break;
		case 0: // rechazo de amistad
			$url = 'http://'.get_server_name(). substr($globals['base_url'], 0, strpos($globals['base_url'],"lib")) .'user.php?login='. $usuario;
			$url_accion = "";
			break;
		case 3: // aceptación como amigo
			$url = 'http://'.get_server_name(). substr($globals['base_url'], 0, strpos($globals['base_url'],"lib")) .'user.php?login='. $amigo .'&view=friends';
			$url_accion = "";
			break;
		case 4: // aceptación como conocido
			$url = 'http://'.get_server_name(). substr($globals['base_url'], 0, strpos($globals['base_url'],"lib")) .'user.php?login='. $amigo .'&view=friends';
			$url_accion = "";
			break;
		default:
			$url_accion = "";
			break;
	}

	//echo "$user->username, $user->email, $url<br />";

	$to      = $user->email;
	$subject = '[patesqu] '. $idioma['mail_fnd_'.$accion.'_subject'];
	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");

	$message = $idioma['mail_ptd_greeting']. $user->username. ','."\n\n";
	$message .= $usuario . $idioma['mail_fnd_'.$accion.'_body_1']."\n\n";
	$message .= $idioma['mail_fnd_'.$accion.'_body_2']."\n";
	$message .= "\n$url\n\n";
	if( $accion == 1 ) {
		$message .= $idioma['mail_fnd_'.$accion.'_body_3']."\n";
		$message .= "\n${url_accion}amigo\n\n";
		$message .= $idioma['mail_fnd_'.$accion.'_body_4']."\n";
		$message .= "\n${url_accion}conocido\n\n";
	}

	$message .= "-- \n  " . $idioma['mail_rec_body_5'];
	$message = wordwrap($message, 70);

	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '.$idioma['mail_rec_from_1'].' '.get_server_name().' <'.$idioma['mail_rec_from_2'].'@'.get_server_name().">\n".
				'Reply-To: '.$idioma['mail_rec_from_2'].'@'.get_server_name()."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	if( !mail($to, $subject, $message, $headers) )
		$error = true;

	if( $inbox ) { // hacemos copia en el inbox interno
		$query = "INSERT INTO mensajes (tipo, de, a, fecha, texto) VALUES (". INVITACION .", '$usuario', '$amigo', now(), '". addslashes($message) ."')";
		//echo '<p> Query: '. $query. '</p>';
		if( !mysqli_query( $query ) )
			$error = true;
	}
	//echo "<p>$to</p>";
	//echo "<p>$subject</p>";
	//echo "<p>$message</p>";
	//echo "<p>$headers</p>";
	if( $error )
		return false;
	else
		return true;
} // send_friend_info


function send_free_mail ($from, $to, $subject, $message, $from_name) {
//	global $site_key, $globals;
	global $idioma, $globals, $partido;

//	require_once(libpath.'user.php');

	$subject = mb_encode_mimeheader($subject,"UTF-8", "B", "\n");
	$message = html_entity_decode( $message, ENT_QUOTES, "UTF-8" );
	$message = wordwrap($message, 70);

	if( !empty($from_name) )
		$from_header = "$from_name <$from>";
	else
		$from_header = $from;
	$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" . 
				'From: '. $from_header ."\n".
				'Reply-To: '. $from ."\n".
				'X-Mailer: '.get_server_name().'/PHP/' . phpversion(). "\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	//$pars = '-fweb@'.get_server_name();
	mail($to, $subject, $message, $headers);
	//echo "<p>$to</p>";
	//echo "<p>$subject</p>";
	//echo "<p>$message</p>";
	//echo "<p>$headers</p>";
	return true;
}

?>
