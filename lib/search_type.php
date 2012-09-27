<?php
$path =  $_REQUEST['birrolpath'];
include($path. "config.php");
$term =  $_REQUEST['term'];

$query = "SELECT auto_id, type FROM beer_types WHERE type LIKE '%$term%';";

$res = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
$str = '';
while($row=mysqli_fetch_row($res)){
// 	$str .= '{ label: "'. $row[0] .'", value: "'.$row[1]. '" }, ';
	$str .= '{ "id": "'. $row[0] .'", "label": "'. $row[1] .'", "value": "'.$row[1]. '" }, ';
}

if( !empty($str) )
  $str = '[ ' . substr($str, 0, -2). ' ]';

echo $str;
?>