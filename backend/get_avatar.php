<?php
include_once('../config.php');
include_once(libpath.'avatars.php');

$object = $_GET['object'];
if (!$_GET['id'] && !empty($_GET['user'])) {
	$res = mysqli_query($mysql_link, "SELECT auto_id FROM $object WHERE username = '".mysqli_real_escape_string( $mysql_link, $_GET['user'])."'") or die ('ERROR:'.mysqli_error());
	$id = (int) mysqli_result($res);
} else {
	$id = intval($_GET['id']);
}
if (! $id > 0) die;
$size = intval($_GET['size']);
$time = intval($_GET['time']);
if (!$size > 0) $size = 80;

if (!($img=avatar_get_from_file($object, $id, $size))) {
	$img=avatar_get_from_db($id, $size);
	if (!$img) {
		if (is_writable($globals['avatars_dir'][$object])) {
			$res=mysqli_query($mysql_link, "SELECT avatar FROM $object WHERE auto_id=$id") or die ('ERROR:'.mysqli_error($mysql_link));
			$row=mysqli_fetch_object($res);
			if ($user) {
				header('Location: ' . get_avatar_url($object, $id, $row->avatar, $size));
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
