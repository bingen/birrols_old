<?
// modified by ßingen from:
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

class User {
	var $read = false;
	var $id = 0;
	var $username = '';
	var $password = '';
	var $email = '';
	var $type = 'lover';
	var $admin = false; 
	var $avatar = 0;
	var $modified = false;
	var $date = false;
	var $ip = '';
	var $name = '';
	var $last_name = '';
	var $language_id = 1;
	var $url = '';
	var $sex = '';
	var $country = '';
	var $birthday = 0;

	function User($id=0) {
		if ($id>0) {
			$this->id = $id;
			$this->read();
		}
	}

	function disabled() {
		return $this->type == 'disabled' || $this->tipo == 'autodisabled';
	}

	function disable($auto = false) {

		require_once(includepath.'avatars.php');
		avatars_db_remove($this->id);
		avatars_remove_user_files($this->id);

		// TODO!!

		$this->username = '--'.$this->id.'--';
		$this->email = "$this->id@disabled";
		$this->url = '';
		if ($auto) $this->type = 'autodisabled';
		else $this->type = 'disabled';
		$this->name = 'disabled';
		$this->last_name = 'disabled';
		$this->avatar = 0;
		$this->country = 'disabled';
		$this->sex = '';
		$this->birthday = strtotime('1900-01-01');
		return $this->store();
	}

	function store($full_save = true) {

//		if(!$this->date) $this->date=$globals['now'];
		if(!$this->date) $this->date=time();
	/*
		if($full_save && empty($this->ip)) {
			$this->ip=$globals['user_ip'];
		}
		*/
		$username = mysql_real_escape_string($this->username);
		$type = $this->type;
		$avatar = $this->avatar;
		$date = $this->date;
		$ip = $this->ip;
		$password = mysql_real_escape_string($this->password);
		$language_id = $this->language_id;
		$email = mysql_real_escape_string($this->email);
		$name = mysql_real_escape_string($this->name);
		$last_name = mysql_real_escape_string($this->last_name);
		$url = mysql_real_escape_string(htmlspecialchars($this->url));
		$country = $this->country;
		$sex = $this->sex;
		$birthday = $this->birthday;
		$fecha_aux = date('Y-m-d', $birthday);
		if($this->id===0) { 
			mysql_query("INSERT INTO users (username, password, email, type, date, ip, name, last_name, language_id, url, country, sex, birthday) VALUES ('$username', '$password', '$email', $type, FROM_UNIXTIME($date), '$ip', '$name', '$last_name', $language_id, '$url', '$country', '$sex', '$fecha_aux'") or die ('ERROR:'.mysql_error());
			$this->id = mysql_insert_id();
		} else {
			if ($full_save) $modified = ', modified = now() ' ;
			$query = "UPDATE usuarios set username='$username', password='$password', email='$email', type='$type', avatar=$avatar, date=FROM_UNIXTIME($date), ip='$ip', name='$name', last_name='$last_name', language_id=$language_id, url='$url', country='$country',  sex='$sex', birthday='$fecha_aux'   $modified WHERE auto_id=$this->id";
// 			echo "\n<p> sql: ". $query. " </p>\n";
			mysql_query( $query ) or die ('ERROR:'.mysql_error());
		} // if id === 0
		return true;
	}
	
	function read() {
		$id = $this->id;
		if($this->id>0) $where = "auto_id = $id";
		elseif(!empty($this->username)) $where = "username='". mysql_real_escape_string( mb_substr($this->username,0,64) ). "'";
		//elseif(!empty($this->username)) $where = "auto_id=1";
		elseif(!empty($this->email)) $where = "email='". mysql_real_escape_string( mb_substr($this->email,0,64) ). "' AND type != 'disabled' AND type != 'autodisabled'";

		$query = "SELECT SQL_CACHE *, UNIX_TIMESTAMP(date) ut_date, UNIX_TIMESTAMP(modified) ut_modified FROM usuarios WHERE $where limit 1";
// 		echo "<p> sql: ". $query. " </p>";
		$res = mysql_query( $query ) or die ('ERROR:'.mysql_error());
		$user = mysql_fetch_object($res);
		if(!empty($where) && $user) {
			$this->id =$user->auto_id;
			$this->username = $user->username;
			$this->type = $user->type;
			if ($this->type == 'admin') $this->admin = true;
			$this->date=$user->ut_date;
			$this->ip = $user->ip;
			$this->modified=$user->ut_modified;
			$this->password = $user->password;
			$this->email = $user->email;
			$this->name = $user->name;
			$this->last_name = $user->last_name;
			$this->language_id = $user->language_id;
			$this->avatar = $user->avatar;
			$this->url = $user->url;
			$this->country = $user->country;
			$this->sex = $user->sex;
//			$fecha_array = explode('/', $user->birthday);
//			$user->birthday=mktime(0,0,0,$fecha_array[1],$fecha_array[0],$fecha_array[2]);
			$this->birthday = strtotime($user->birthday);
			$this->username_register = $user->username_register;
			$this->email_register = $user->email_register;
			$this->read = true;

			return true;
		}

		$this->read = false;
		return false;
	}



/*	function get_api_key() {
		global $site_key;

		return substr(md5($this->user.$this->date.$this->pass.$site_key), 0, 10);
	}
*/
/*	function get_latlng() {
		require_once(mnminclude.'geo.php');
		return geo_latlng('user', $this->id);
	}
*/

// old login.php
	function UserAuth() {
		global $site_key;

		$this->now = time();
		if(!empty($_COOKIE['ocb_user'])) {
			$this->ocb_user=explode(":", $_COOKIE['ocb_user']);
		}

		if($this->ocb_user[0] && !empty($_COOKIE['ocb_key'])) {
			$userInfo=explode(":", base64_decode($_COOKIE['ocb_key']));
			if($this->ocb_user[0] === $userInfo[0]) {
				$cookietime = (int) $userInfo[3];
				$dbusername = mysql_real_escape_string($this->ocb_user[0]);
				$res=mysql_query("SELECT SQL_CACHE auto_id, password, type, UNIX_TIMESTAMP(validated_date) as validated_date, email, avatar, language_id FROM users WHERE username = '$dbusername'") or die ('ERROR:'.mysql_error());
				$user = mysql_fetch_object($res);

				// We have two versions from now
				// The second is more strong agains brute force md5 attacks
				switch ($userInfo[2]) {
					case '3':
						if (($this->now - $cookietime) > 864000) $cookietime = 'expired'; // after 10 days expiration is forced
						$key = md5($user->email.$site_key.$dbusername.$user->auto_id.$cookietime);
						break;
					case '2':
						$key = md5($user->email.$site_key.$dbusername.$user->auto_id);
						$cookietime = 0;
						break;
					default:
						$key = md5($site_key.$dbusername.$user->auto_id);
						$cookietime = 0;
				}

				if ( !$user || !$user->auto_id > 0 || $key !== $userInfo[1] || 
					$user->type == 'disabled' || $user->type == 'autodisabled' || 
					empty($user->validated_date)) {
						$this->Logout();
						return;
				}

				$this->id = $user->auto_id;
				$this->username  = $userInfo[0];
				$this->md5_pass = $user->password;
				$this->type = $user->type;
				if ($this->type == 'admin') $this->admin = true;
				$this->email = $user->email;
				$this->avatar = $user->avatar;
				$this->validated_date = $user->validated_date;
				$this->language_id = $user->language_id;
				$this->authenticated = TRUE;

				if ($userInfo[2] != '3') { // Update the cookie to version 3
					$this->SetIDCookie(2, true);
				} elseif ($this->now - $cookietime > 3600) { // Update the time each hour
					$this->SetIDCookie(2, $userInfo[4] > 0 ? true : false);
				}
			}
		}
	} // UserAuth


	function SetIDCookie($what, $remember) {
		global $site_key, $globals;
		switch ($what) {
			case 0:	// Borra cookie, logout
				setcookie ("ocb_key", '', $this->now - 3600, $globals['base_url']); // Expiramos el cookie
				$this->SetUserCookie(false);
				break;
			case 1: // Usuario logeado, actualiza el cookie
				$this->AddClone();
				$this->SetUserCookie(true);
			case 2: // Only update the key
				// Atencion, cambiar aquí cuando se cambie el password de base de datos a MD5
				if($remember) $time = $this->now + 3600000; // Valid for 1000 hours
				else $time = 0;
				$strCookie=base64_encode(
						$this->username.':'
						.md5($this->email.$site_key.$this->username.$this->id.$this->now).':'
						.'3'.':' // Version number
						.$this->now.':'
						.$time);
				setcookie("ocb_key", $strCookie, $time, $globals['base_url'].'; HttpOnly');
				break;
		}
	} // SetIDCookie

	function Authenticate($username, $hash, $remember=false) {

		$dbusername=mysql_real_escape_string($username);
		$query = "SELECT auto_id, password, type, UNIX_TIMESTAMP(validated_date) as validated_date, email, avatar, language_id FROM users WHERE login = BINARY '$dbusername'";
// 		echo '<p> query: ' . $query . '</p>';
		$res=mysql_query( $query ) or die ('ERROR:'.mysql_error());
		$user = mysql_fetch_object($res);
		if ($user->tipo == 'disabled' || $user->tipo == 'autodisabled' || ! $user->validated_date) return false;
		if ($user->auto_id > 0 && $user->password == $hash) {
			$this->id = $user->auto_id;
			$this->username = $username;
			$this->md5_pass = $user->password;
			$this->email = $user->email;
			$this->type = $user->type;
			$this->avatar = $user->avatar;
			$this->validated_date = $user->validated_date;
			$this->language_id = $user->language_id;
			$this->authenticated = TRUE;
			$this->SetIDCookie(1, $remember);
			return true;
		}
		return false;
	} // Authenticate

	function Logout($url='./') {
		$this->id = 0;
		$this->user = "";
		$this->authenticated = FALSE;
		$this->SetIDCookie (0, false);

		//header("Pragma: no-cache");
		header("Cache-Control: no-cache, must-revalidate");
		header("Location: $url");
		header("Expires: " . gmdate("r", $this->now - 3600));
		header('ETag: "logingout' . $this->now . '"');
		die;
	}

	function SetUserCookie($do_login) {
		global $globals;
		if ($do_login) {
			setcookie("ocb_user", $this->username.':'.$this->ocb_user[1], $this->now + 3600000, $globals['base_url']);
		} else {
			setcookie("ocb_user", '_:'.$this->ocb_user[1], $this->now + 360000, $globals['base_url']);
		}
	}
        function AddClone() {
                        if (!empty($this->ocb_user[1])) {
                                $ids = explode("x", $this->ocb_user[1]);
                                while(count($ids) > 4) {
                                        array_shift($ids);
                                }
                        } else {
                                $ids = array();
                        }
                        array_push($ids, $this->id);
                        $this->ocb_user[1] = implode('x', $ids);
        } // AddClone

        function GetClones() {
                $clones = array();
                foreach (explode('x', $this->ocb_user[1]) as $id) {
                        $id = intval($id);
                        if ($id > 0) {
                                array_push($clones, $id);
                        }
                }
                return $clones;
        } // GetClones

}

conecta();
$current_user = new User();
$current_user->UserAuth();

