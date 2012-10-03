<?php
include_once('../config.php');
include_once(libpath.'avatars.php');

$object = $_GET['object'];
if (!$_GET['id'] && !empty($_GET['user'])) {
	$query = "SELECT auto_id FROM $object WHERE username = '".mysqli_real_escape_string( $mysql_link, $_GET['user'])."'";
// 	echo "<p> query: $query </p> \n";
	$res = mysqli_query($mysql_link, $query) or die ('ERROR:'.mysqli_error());
	$id = (int) mysqli_result($res);
} else {
	$id = intval($_GET['id']);
}
if (! $id > 0) die;
$size = intval($_GET['size']);
$time = intval($_GET['time']);
if (!$size > 0) $size = 80;

if (!($img=avatar_get_from_file($object, $id, $size))) {
	$img=avatar_get_from_db($object, $id, $size);
	if (!$img) {
		if (is_writable($globals['avatars_dir'][$object])) {
			$query = "SELECT avatar FROM $object WHERE auto_id=$id";
// 			echo "<p> query: $query </p> \n";
			$res=mysqli_query($mysql_link, $query) or die ('ERROR:'.mysqli_error($mysql_link));
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
