<?php
/*
    Open craft beer
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

include_once('config.php');

	$tabla = 'business_view';
	$query_cond = '';
	
	if( !empty($_REQUEST['brewery']) && $_REQUEST['brewery'] == 'true' )
	    $query_cond .= " OR brewery ";
	if( !empty( $_REQUEST['pub']) && $_REQUEST['pub'] == 'true' )
	    $query_cond .= " OR pub ";
	if( !empty($_REQUEST['store']) && $_REQUEST['store'] == 'true' )
	    $query_cond .= " OR store ";
	if( !empty($query_cond) )
	    $query_cond = 'AND (0 ' . $query_cond . ')';

	if( !empty($_REQUEST['search_type']) && $_REQUEST['search_type'] == 'map' )
	    businesses_map( 0, $query_cond );
	else
	    businesses_list( 0, $query_cond );

function businesses_list($business_id=0, $query_cond='') {
	global $current_user, $globals, $idioma, $tabla;

	if( $business_id != 0 ) 
		$query_table = " WHERE auto_id = $business_id ";
	else $query_table = " WHERE 1 ";

	echo '	   <table class="principal-table" id="'. $tabla .'-table">' . "\n";
//	if( $business_id != 0 ) {
		echo '	 <thead>' . "\n";
// 		$fila_0 = 0;
// 		$num_filas = 1;

		print("
	  <tr>");
		if($current_user->admin)
		    echo " 		<th class=\"col-auto_id\"><strong>".$idioma['beer_id']."</strong></th> \n";
		print("
	    <th class=\"col-name\"><strong>".$idioma['beer_name']."</strong></th>
	    <th class=\"col-brewery\"><strong>".$idioma['brewery']."</strong></th>
	    <th class=\"col-category\"><strong>".$idioma['pub']."</strong></th>
	    <th class=\"col-type\"><strong>".$idioma['store']."</strong></th>
	    <th class=\"col-abv\"><strong>".$idioma['bsns_city']."</strong></th>
	    <th class=\"col-ibu\"><strong>".$idioma['bsns_state']."</strong></th>
	    <th class=\"col-ibu\"><strong>".$idioma['bsns_url']."</strong></th>
	    <th class=\"col-desc\"><strong>".$idioma['beer_desc']."</strong></th>
	    <th class=\"col-score\"><strong>".$idioma['beer_score']."</strong></th>
	  </tr>
	 </thead>
		");

// 	} else {
// 	}

	$truefalse_img_array = array($globals['base_url']. '/img/common/cross.png', $globals['base_url']. '/img/common/tick.png');
	
	echo '    <tbody>' . "\n";
	$query = "SELECT * FROM $tabla $query_table $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$table_list = mysql_query( $query ) or die ('ERROR:'.mysql_error());
	for( $i = 0; $i < mysql_num_rows($table_list); $i++)
	{
		$row = mysql_fetch_object($table_list);
// 		print_r($row);

//		if( $current_user->authenticated ) {
			$url_partido = $globals['base_url'].'business.php?id='.$row->auto_id;
			echo '<tr '.$zebra.' onclick="window.location=\''. $url_partido .'\'">' . "\n";
			
			if($current_user->admin)
			    echo '<td class="col-auto_id"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$row->auto_id.'</a></td>' . "\n";
			echo '<td class="col-name"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$row->name.'</a></td>' . "\n";
			echo '<td class="col-brewery"><a href="'. $url_partido .'" title="'. $idioma['put_url_jugador'] .'"><img src="'.$truefalse_img_array[$row->brewery].'" /></a></td>' . "\n";
			echo '<td class="col-pub"><a href="'. $url_partido .'" title="'. $idioma['put_url_jugador'] .'"><img src="'.$truefalse_img_array[$row->pub].'" /></a></td>' . "\n";
			echo '<td class="col-store"><a href="'. $url_partido .'" title="'. $idioma['put_url_jugador'] .'"><img src="'.$truefalse_img_array[$row->store].'" /></a></td>' . "\n";
			echo '<td class="col-abv"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$row->city.'</a></td>' . "\n";
			echo '<td class="col-ibu"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$row->state.'</a></td>' . "\n";
			echo '<td class="col-ibu"><a href="'. $row->url .'" title="'. $row->name .'">'.$row->url.'</a></td>' . "\n";
			echo '<td class="col-desc"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$row->description.'</a></td>' . "\n";
			echo '<td class="col-score"><img src="'. get_stars($row->score). '" alt="'. $row->score . '"/></td>' . "\n";
			echo '</tr>';
//		} // end if authenticated
	} // end for matches
// 	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '    </tbody>' . "\n";
	echo '	</table>' . "\n";


}

function businesses_map($business_id=0, $query_cond='') {
    echo ' <div id="map"></div>'."\n";
    
//     echo ''."\n";
//     echo ''."\n";
//     echo ''."\n";
 }
?>
