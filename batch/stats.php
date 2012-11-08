<?php

include( '../config.php');
include( libpath. 'mail.php');
// TODO:
include( langpath. 'castellano.php');

$to = '';
$query = "SELECT email FROM users WHERE type='admin'";
$res = $mysql_link->query( $query );
while( $row = $res->fetch_object() ) {
  $to .= $row->email . ',';
}
if( !empty($to) ) $to = substr( $to, 0, -1);

$message = '';

$query = "SELECT count(*) AS number FROM users";
stat_result( $query, $idioma['mail_stats_users'] );

$query = "SELECT count(*) AS number FROM business";
stat_result( $query, $idioma['mail_stats_business'] );

$query = "SELECT count(*) AS number FROM beers";
stat_result( $query, $idioma['mail_stats_beers'] );

// Iconsistency: ///////////////
// Multiple actual taps
$query = "SELECT count(*) AS number FROM (SELECT business_id, tap_id, COUNT(*) taps FROM taps WHERE actual GROUP BY business_id, tap_id HAVING COUNT(*) > 1) t ";
stat_result( $query, $idioma['mail_stats_inc_taps'] );

// Cervezas sin brewery
$query = "SELECT count(*) AS number FROM beers WHERE brewery_id NOT IN (SELECT auto_id FROM business)";
stat_result( $query, $idioma['mail_stats_orphan_beers'] );

// Breweries repetidas
$query = "SELECT b.auto_id, b.name, b.score, b.url FROM business b, (SELECT name FROM business GROUP BY name, country_id HAVING count(*) > 1) g WHERE b.name=g.name ORDER BY b.name, b.auto_id";
stat_result( $query, $idioma['mail_stats_dup_breweries'] );

// ...

$subject = '['.$globals['app_name'] .'] '. $idioma['mail_stats_subject'];

send_free_mail( $globals['email'], $to, $subject, $message );

function stat_result( $query, $title ) {
  global $message, $mysql_link, $idioma;

  // title
  echo "<p> ---------  $title </p>\n";
  $message .= "\n --------- " . $title . "\n";
//   echo "<p> $query </p>\n";
  if( $res = $mysql_link->query($query) ) {
    // header
    $row = $res->fetch_array();
    $keys = array_keys( $row );
//     print_r($row);
//     print_r($keys);
    $fields = count($keys) / 2;
    $line = '';
    for( $i = 0; $i < $fields; $i++ )
      $line .= $keys[2*$i+1] . '|';
    echo "<p> $line </p>\n";
    $message .= $line . "\n";
    // 1st line
    $line = '';
    for( $i = 0; $i < $fields; $i++ )
      $line .= $row[$i] . '|';
    echo "<p> $line </p>\n";
    $message .= $line . "\n";
//     echo "<p>".mysqli_num_rows($res)."</p>";
    while( $row = $res->fetch_array() ) {
      $line = '';
      for( $i = 0; $i < $fields; $i++ )
	$line .= $row[$i] . '|';
      echo "<p> $line </p>\n";
      $message .= $line . "\n";
    } // end while
    return TRUE;
  } else {
    $line = $idioma['err_db'];
    echo "<p> $line <p>";
    echo "<p> $query <p>";
    $message .= $line . "\n";
    $message .= $query . "\n";
    return FALSE;
  } // if query

}

?>