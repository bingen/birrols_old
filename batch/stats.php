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
$res = $mysql_link->query( $query );
$row = $res->fetch_object();
$line = $idioma['mail_stats_users'] . $row->number . "\n";
echo "<p> $line </p>\n";
$message .= $line;

$query = "SELECT count(*) AS number FROM business";
$res = $mysql_link->query( $query );
$row = $res->fetch_object();
$line = $idioma['mail_stats_business'] . $row->number . "\n";
echo "<p> $line </p>\n";
$message .= $line;

$query = "SELECT count(*) AS number FROM beers";
$res = $mysql_link->query( $query );
$row = $res->fetch_object();
$line = $idioma['mail_stats_beers'] . $row->number . "\n";
echo "<p> $line </p>\n";
$message .= $line;

$subject = '['.$globals['app_name'] .'] '. $idioma['mail_stats_subject'];

send_free_mail( $globals['email'], $to, $subject, $message );

?>