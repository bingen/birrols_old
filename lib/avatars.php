<?
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".


function get_avatars_dir() {
	global $globals;
	return birrolpath.'/'.$globals['cache_dir'].'/'.$globals['avatars_dir'];
}

function is_avatars_enabled() {
	global $globals;
	return !empty($globals['cache_dir']) && is_writable(get_avatars_dir());
}

function avatars_manage_upload($user, $name) {
//	global $globals;

	$time = time();

	$chain = get_cache_dir_chain($user);
	@mkdir(get_avatars_dir());
	create_cache_dir_chain(get_avatars_dir(), $chain);
	$subdir = get_avatars_dir() . '/'. $chain;
	if (!is_writable($subdir)) return false;
	$file_base = $subdir . "/$user-$time";

	avatars_remove_user_files($user);
	move_uploaded_file($_FILES[$name]['tmp_name'], $file_base . '-orig.img');
	$size = @getimagesize("$file_base-orig.img");
	avatar_resize("$file_base-orig.img", "$file_base-80.jpg", 80);
	$size = @getimagesize("$file_base-80.jpg");
	if (!($size[0] == 80 && $size[1] == 80 && ($mtime = avatars_db_store($user, "$file_base-80.jpg", $time)))) {
		// Mark FALSE in DB
		avatars_db_remove($user);
		avatars_remove_user_files($user);
		return false;
	}
	/*
	// Upload to DB and mark TRUE
	avatar_resize("$file_base-orig.img", "$file_base-20.jpg", 20);
	avatar_resize("$file_base-orig.img", "$file_base-25.jpg", 25);
	avatar_resize("$file_base-orig.img", "$file_base-40.jpg", 40);
	*/
	unlink("$file_base-orig.img");
	return $mtime;
}

function avatars_remove_user_files($user) {
//	global $globals;
	$subdir = @get_avatars_dir() . '/'. get_cache_dir_chain($user);
	if ( $subdir && ($handle = @opendir( $subdir )) ) {
		while ( false !== ($file = readdir($handle))) {
			if ( preg_match("/^$user-/", $file) ) {
				@unlink($subdir . '/' . $file);
			}
		}
		closedir($handle);
	}
}

function avatars_check_upload_size($name) {
	global $globals;
	return $_FILES[$name]['size'] < $globals['avatars_max_size'];
}

function avatars_db_store($user, $file, $now) {
//	global $db;
	$bytes = file_get_contents($file);
	if (strlen($bytes)>0 && strlen($bytes) < 30000) {
		$bytes = addslashes($bytes);
		mysql_query("replace into avatars set avatar_id = $user, avatar_image='$bytes'");
		mysql_query("update usuarios set avatar = $now  where auto_id=$user");
		return $now;
	}
	return false;
}

function avatars_db_remove($user) {
//	global $db;
	mysql_query("delete from avatars where avatar_id=$user");
	mysql_query("update usuarios set avatar = 0  where auto_id=$user");
}

function avatar_get_from_file($user, $size) {
//	global $globals, $db;

	$res = mysql_query("select avatar from usuarios where auto_id=$user");
	$time = mysql_result($res,0,0);
	if(! $time > 0) return false;
	$file = get_avatars_dir() . '/'. get_cache_dir_chain($user) . "/$user-$time-$size.jpg";
	if (is_readable($file)) {
		return  file_get_contents($file);
	} else {
		return false;
	}

}

function avatar_get_from_db($user, $size=0) {
	global $globals;
	$res = mysql_query("select avatar_image from avatars where avatar_id=$user");
	$img = mysql_result($res,0,0);
	if (!strlen($img) > 0) {
		return false;
	}
	$res = mysql_query("select avatar from usuarios where auto_id=$user");
	$time = mysql_result($res,0,0);

	$chain = get_cache_dir_chain($user);
	@mkdir(get_avatars_dir());
	create_cache_dir_chain(get_avatars_dir(), $chain);
	$subdir = get_avatars_dir() . '/'. $chain;
	if (!is_writable($subdir)) return false;
	$file_base = $subdir . "/$user-$time";

	file_put_contents ($file_base . '-80.jpg', $img);
	if ($size > 0 && $size != 80 && in_array($size, $globals['avatars_allowed_sizes'])) {
		avatar_resize("$file_base-80.jpg", "$file_base-$size.jpg", $size);
		return file_get_contents("$file_base-$size.jpg");
	}
	return $img;
}


function avatar_resize($infile,$outfile,$size) {
	$image_info = getImageSize($infile);
	switch ($image_info['mime']) {
		case 'image/gif':
		if (imagetypes() & IMG_GIF)  {
			$src_img = imageCreateFromGIF($infile) ;
		} else {
			$ermsg = 'GIF images are not supported<br />';
		}
		break;
		case 'image/jpeg':
		if (imagetypes() & IMG_JPG)  {
			$src_img = imageCreateFromJPEG($infile) ;
		} else {
			$ermsg = 'JPEG images are not supported<br />';
		}
		break;
		case 'image/png':
		if (imagetypes() & IMG_PNG)  {
			$src_img = imageCreateFromPNG($infile) ;
		} else {
			$ermsg = 'PNG images are not supported<br />';
		}
		break;
		case 'image/wbmp':
		if (imagetypes() & IMG_WBMP)  {
			$src_img = imageCreateFromWBMP($infile) ;
		} else {
			$ermsg = 'WBMP images are not supported<br />';
		}
		break;
		default:
		$ermsg = $image_info['mime'].' images are not supported<br />';
		break;
	}
	if (isset($ermsg)) {
		echo "Error: $ermsg";
		die;
	}
	$dst_img = ImageCreateTrueColor($size,$size);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$size,$size,imagesx($src_img),imagesy($src_img));
	imagejpeg($dst_img,$outfile,80);
}
