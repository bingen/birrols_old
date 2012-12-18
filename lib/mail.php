<?php
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
	$subject = '['.$globals['app_name'] .'] '. $idioma['mail_usr_'.$accion.'_subject'];
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

function send_free_mail ($from, $to, $subject, $message, $from_name='') {

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
