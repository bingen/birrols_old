<?php
$path =  $_REQUEST['birrolpath'];
include($path. "config.php");
$brewery_id =  $_REQUEST['brewery_id'];
$term =  $_REQUEST['term'];

if( !empty($brewery_id) ) {
  $brewery_cond = " AND brewery_id=$brewery_id  ";
} else {
  $brewery_cond = '';
}
$query = "SELECT auto_id, name FROM beers WHERE name LIKE '%$term%';";

$res = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
$str = '';
while($row=mysqli_fetch_row($res)){
	$str .= '{ "id": "'. $row[0] .'", "label": "'. $row[1] .'", "value": "'.$row[1]. '" }, ';
}

if( !empty($str) )
  $str = '[ ' . substr($str, 0, -2). ' ]';

echo $str;
?>