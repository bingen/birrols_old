<?php
// Modified by ßingen
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".


function get_avatars_dir($object) {
	global $globals;
	return birrolpath.$globals['cache_dir'].$globals['avatars_dir'][$object];
}

function is_avatars_enabled($object) {
	global $globals;
	return !empty($globals['cache_dir']) && is_writable(get_avatars_dir($object));
}

function avatars_manage_upload($object, $id, $name, $url='') {
//	global $globals;

	$time = time();

	$chain = get_cache_dir_chain($id);
	@mkdir(get_avatars_dir($object));
	create_cache_dir_chain(get_avatars_dir($object), $chain);
	$subdir = get_avatars_dir($object) . $chain;
	if (!is_writable($subdir)) return false;
	$file_base = $subdir . "$id-$time";

	avatars_remove_user_files($object, $id);
	if( !empty($name) ) { // file uploaded
	  move_uploaded_file($_FILES[$name]['tmp_name'], $file_base . '-orig.img');
	} else { // file from url
	  $file = fopen($url,"rb");
	  $newfile = fopen($file_base . '-orig.img', "wb");
	  if($newfile){
	    while(!feof($file)) {
	    // Write the url file to the directory.
	      fwrite($newfile,fread($file,1024 * 8),1024 * 8); // write the file to the new directory at a rate of 8kb until we reach the end.
	    } // while
	  }// if newfile
	} // if !empty($name)
	$size = @getimagesize("$file_base-orig.img");
	avatar_resize("$file_base-orig.img", "$file_base-80.jpg", 80);
	$size = @getimagesize("$file_base-80.jpg");
	if ( !( $size[0] == 80 && $size[1] == 80 && ($mtime = avatars_db_store($object, $id, "$file_base-80.jpg", $time)) ) ) {
		// Mark FALSE in DB
		avatars_db_remove($object, $id);
		avatars_remove_user_files($object, $id);
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

function avatars_remove_user_files($object, $id) {
//	global $globals;
	$subdir = @get_avatars_dir($object) . get_cache_dir_chain($id);
	if ( $subdir && ($handle = @opendir( $subdir )) ) {
		while ( false !== ($file = readdir($handle))) {
			if ( preg_match("/^$id-/", $file) ) {
				@unlink($subdir . $file);
			}
		}
		closedir($handle);
	}
}

function avatars_check_upload_size($name) {
	global $globals;
	return $_FILES[$name]['size'] < $globals['avatars_max_size'];
}

function avatars_db_store($object, $id, $file, $now) {
	global $mysql_link;
	
	$bytes = file_get_contents($file);
	if (strlen($bytes)>0 && strlen($bytes) < 30000) {
		$bytes = addslashes($bytes);
		mysqli_query($mysql_link, "REPLACE INTO ". $object. "_avatars SET avatar_id = $id, avatar_image='$bytes'");
		mysqli_query($mysql_link, "UPDATE $object SET avatar = $now  WHERE auto_id=$id");
		return $now;
	}
	return false;
}

function avatars_db_remove($object, $id) {
	global $mysql_link;
	mysqli_query($mysql_link, "DELETE FROM ". $object. "_avatars where avatar_id=$id");
	mysqli_query($mysql_link, "UPDATE $object SET avatar = 0  WHERE auto_id=$id");
}

function avatar_get_from_file($object, $id, $size) {
	global $mysql_link;

	$query = "SELECT avatar FROM $object WHERE auto_id=$id";
// 	echo "<p> query: $query </p> \n";
	$res = mysqli_query($mysql_link, $query);
	$time = mysqli_result($res,0,0);
	if(! $time > 0) return false;
	$file = get_avatars_dir($object) . get_cache_dir_chain($id) . "$id-$time-$size.jpg";
	if (is_readable($file)) {
		return  file_get_contents($file);
	} else {
		return false;
	}

}

function avatar_get_from_db($object, $id, $size=0) {
	global $mysql_link, $globals;
	
	$query = "SELECT avatar_image FROM ". $object. "_avatars WHERE avatar_id=$id";
// 	echo "<p> query: $query </p> \n";
	$res = mysqli_query($mysql_link, $query);
	$img = mysqli_result($res,0,0);
	if (!strlen($img) > 0) {
		return false;
	}
	$query = "SELECT avatar FROM $object WHERE auto_id=$id";
// 	echo "<p> query: $query </p> \n";
	$res = mysqli_query($mysql_link, $query);
	$time = mysqli_result($res,0,0);

	$chain = get_cache_dir_chain($id);
	@mkdir(get_avatars_dir());
	create_cache_dir_chain(get_avatars_dir($object), $chain);
	$subdir = get_avatars_dir($object) . $chain;
	if (!is_writable($subdir)) return false;
	$file_base = $subdir . "$id-$time";

	file_put_contents ($file_base . '-80.jpg', $img);
	if ($size > 0 && $size != 80 && in_array($size, $globals['avatars_allowed_sizes'])) {
		avatar_resize("$file_base-80.jpg", "$file_base-$size.jpg", $size);
		return file_get_contents("$file_base-$size.jpg");
	}
	return $img;
}


function avatar_resize($infile,$outfile,$size) {
  global $idioma;
  
	$image_info = getImageSize($infile);
	switch ($image_info['mime']) {
		case 'image/gif':
		if (imagetypes() & IMG_GIF)  {
			$src_img = imageCreateFromGIF($infile) ;
		} else {
			$ermsg = $idioma['err_img'] . 'GIF';
		}
		break;
		case 'image/jpeg':
		if (imagetypes() & IMG_JPG)  {
			$src_img = imageCreateFromJPEG($infile) ;
		} else {
			$ermsg = $idioma['err_img'] . 'JPEG ';
		}
		break;
		case 'image/png':
		if (imagetypes() & IMG_PNG)  {
			$src_img = imageCreateFromPNG($infile) ;
		} else {
			$ermsg = $idioma['err_img'] . 'PNG';
		}
		break;
		case 'image/wbmp':
		if (imagetypes() & IMG_WBMP)  {
			$src_img = imageCreateFromWBMP($infile) ;
		} else {
			$ermsg = $idioma['err_img'] . 'WBMP';
		}
		break;
		default:
		$ermsg = $idioma['err_img'] . $image_info['mime'];
		break;
	}
	if (isset($ermsg)) {
		echo "Error: $ermsg <br />";
		die;
	}
	$dst_img = ImageCreateTrueColor($size,$size);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$size,$size,imagesx($src_img),imagesy($src_img));
	imagejpeg($dst_img,$outfile,80);
}
