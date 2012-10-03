<?php
/*
    Birrols
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

// print_r($_REQUEST);
// print_r($_SERVER);

// session_start();

$client = google_client();

$oauth2 = new apiOauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate();
  if ( $token = $client->getAccessToken()) {
    $_SESSION['token'] = $token;
    $user = $oauth2->userinfo->get();
  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
    echo "\n <p> user </p> \n";
    print_r( $user);
//     echo "\n <p> email: $email </p> \n";
//     echo "\n <p> img: $img </p> \n";
//     $personMarkup = "<div><img src='$img?sz=50'></div>";
//     print $personMarkup;
    
    $query = "SELECT auto_id FROM users WHERE email = '$email'";
    echo "<p> query: $query </p>";
    $res = mysqli_query( $mysql_link, $query );
    $row = mysqli_fetch_object( $res );
    if( !empty($row->auto_id) ) { // user exists
      echo "<p> userl: ". $row->auto_id . " </p>\n";
      $current_user->User( $row->auto_id );
    } else { // new user
      $current_user->username = $email;
      $current_user->email = $email;
      $current_user->username_register = $email;
      $current_user->email_register = $email;
      $current_user->name = filter_var($user['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $current_user->last_name = filter_var($user['family_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $current_user->sex = strtoupper(substr($user['gender'],0,1));
      $query = "SELECT auto_id FROM languages WHERE locale = '". $user['locale'] ."'";
      $res = mysqli_query( $mysql_link, $query );
      $row = mysqli_fetch_object( $res );
      $current_user->language_id = $row->auto_id;
      $current_user->validated_date = time();
      $current_user->google_id = $user['id'];
//       $current_user-> = filter_var($user[''], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $current_user->store();
      $current_user->read();
      // avatar
      if( !empty($user['picture']) ) {
	include_once(libpath. 'avatars.php');
	$avatar_mtime = avatars_manage_upload( 'users', $current_user->id, '', $user['picture'] );
	echo "<p> mtime: $avatar_mtime </p>\n";
      }
      echo "<p> url: ". $user['picture']. " </p>\n";
    } // if user exists
    $current_user->authenticated = TRUE;
    $current_user->SetIDCookie(1, FALSE);
  }
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $globals['base_url']. "index.php";
// 	echo "\n redirect: $redirect \n";
// 	echo "<p> session </p> \n";
// 	print_r($_SESSION);
// 	echo "<p> user </p> \n";
// 	print_r($current_user);
// 	echo "<p> cookie </p> \n";
// 	print_r( $_COOKIE );


  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));

}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
//  echo "<p> ". $_SESSION['token'] ." </p>\n";
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if (!$client->getAccessToken()) {
  $authUrl = $client->createAuthUrl();
//   echo "<p> url: $authUrl </p>\n";
}

?>

