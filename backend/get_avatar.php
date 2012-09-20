<?php
include_once('../config.php');
include_once(birrolpath.'/lib/avatars.php');

if (!$_GET['id'] && !empty($_GET['user'])) {
	$res = mysql_query("select auto_id from usuarios where login = '".mysql_real_escape_string($_GET['user'])."'") or die ('ERROR:'.mysql_error());
	$id = (int) mysql_result($res);
} else {
	$id = intval($_GET['id']);
}
if (! $id > 0) die;
$size = intval($_GET['size']);
$time = intval($_GET['time']);
if (!$size > 0) $size = 80;

if (!($img=avatar_get_from_file($id, $size))) {
	$img=avatar_get_from_db($id, $size);
	if (!$img) {
		if (is_writable($globals['avatars_dir'])) {
			$res=mysql_query("select avatar, email from usuarios where auto_id=$id") or die ('ERROR:'.mysql_error());
			$user=mysql_fetch_object($res);
			if ($user) {
				header('Location: ' . get_avatar_url($id, $user->user_avatar, $size));
			}
		} else {
				header('Location: ' . get_no_avatar_url($size));
		}
		die;
	}  
}

header("Content-type: image/jpg");
//header('Cache-Control: max-age=7200');
echo $img;
?>
