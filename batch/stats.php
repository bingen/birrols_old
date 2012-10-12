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
stat( $query, $idioma['mail_stats_users'] );

$query = "SELECT count(*) AS number FROM business";
stat( $query, $idioma['mail_stats_business'] );

$query = "SELECT count(*) AS number FROM beers";
stat( $query, $idioma['mail_stats_beers'] );

// Iconsistency: ///////////////
// Multiple actual taps
$query = "SELECT count(*) AS number FROM (SELECT business_id, tap_id, COUNT(*) taps WHERE actual GROUP BY business_id, tap_id HAVING COUNT(*) > 1) t ";
stat( $query, $idioma['mail_stats_inc_taps'] );

// Cervezas sin brewery
$query = "SELECT count(*) AS number FROM beers WHERE brewery_id NOT IN (SELECT auto_id FROM business)";
stat( $query, $idioma['mail_stats_orphan_beers'] );

// ...

$subject = '['.$globals['app_name'] .'] '. $idioma['mail_stats_subject'];

send_free_mail( $globals['email'], $to, $subject, $message );

function stat( $query, $title ) {
  global $message;

  $res = $mysql_link->query( $query );
  if( $row = $res->fetch_object() )
    $line = $title . $row->number . "\n";
    echo "<p> $line </p>\n";
    $message .= $line;
    return TRUE;
  } else {
    return FALSE;
  }

}
?>