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

	// type //////////////////////
	if( !empty($_REQUEST['brewery']) && $_REQUEST['brewery'] == 'true' )
	    $query_type .= " OR brewery ";
	if( !empty( $_REQUEST['pub']) && $_REQUEST['pub'] == 'true' )
	    $query_type .= " OR pub ";
	if( !empty($_REQUEST['store']) && $_REQUEST['store'] == 'true' )
	    $query_type .= " OR store ";
	if( !empty($query_type) )
	    $query_cond = 'AND (0 ' . $query_type . ')';

	// country //////////////////////
	if( !empty( $_REQUEST['country_id']) ) {
	  $query_cond .= " AND country_id = " .$_REQUEST['country_id'];
	}

	// facilities //////////////////////
	if( !empty($_REQUEST['food']) && $_REQUEST['food'] == 'true' )
	    $query_fac .= " OR food ";
	if( !empty( $_REQUEST['wifi']) && $_REQUEST['wifi'] == 'true' )
	    $query_fac .= " OR wifi ";
	if( !empty($_REQUEST['homebrew']) && $_REQUEST['homebrew'] == 'true' )
	    $query_fac .= " OR homebrew_store ";
	if( !empty($query_fac) )
	    $query_cond = 'AND (0 ' . $query_fac . ')';

	if( !empty( $_REQUEST['search']) ) {
	  $search = $_REQUEST['search'];
	  $query_cond .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR address_1 LIKE '%$search%' OR address_2 LIKE '%$search%' OR city LIKE '%$search%' OR state LIKE '%$search%' )";
	}

	if( !empty($_REQUEST['search_type']) && $_REQUEST['search_type'] == 'map' ) {
	    businesses_map($query_cond );
	} else {
	    $query_cond = list_head( $tabla, $query_cond, '&'. http_build_query( $_REQUEST ) );
	    businesses_list($query_cond );
	}

function businesses_list($query_cond='') {
	global $mysql_link, $current_user, $globals, $idioma, $tabla;

	$query_table = " WHERE 1 ";

	$truefalse_img_array = array($globals['img_url']. 'common/cross.png', $globals['img_url']. 'common/tick.png');
	
	$query = "SELECT * FROM $tabla $query_table $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$table_list = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));

	echo '	   <ul class="principal-list" id="'. $tabla .'-list">' . "\n";

	for( $i = 0; $i < mysqli_num_rows($table_list); $i++)
	{
		$row = mysqli_fetch_object($table_list);
// 		print_r($row);

//		if( $current_user->authenticated ) {
			$url_row = $globals['base_url'].'business.php?id='.$row->auto_id;
			echo '<li class="row" onclick="window.location=\''. $url_row .'\'">' . "\n";
			
// 			if($current_user->admin)
// 			    echo '<div class="col-auto_id"><a href="'. $url_row .'" title="'. $idioma['beer'] .'">'.$row->auto_id.'</a></div>' . "\n";
			show_avatar( 'business', $row->auto_id, $row->avatar, $row->name, 40 );
			echo "<div class='column col-container'>\n";
			echo '<h3 class="column col-name"><a href="'. $url_row .'" title="'. $idioma['id_nombre'] .'">'.$row->name.'</a></h3>' . "\n";
			echo '<div class="column col-type">' . "\n";
			echo '<a href="'. $url_row .'" title="'. $idioma['brewery'] .'"><img src="'.$truefalse_img_array[$row->brewery].'" /></a>' . "\n";
			echo '<a href="'. $url_row .'" title="'. $idioma['pub'] .'"><img src="'.$truefalse_img_array[$row->pub].'" /></a>' . "\n";
			echo '<a href="'. $url_row .'" title="'. $idioma['store'] .'"><img src="'.$truefalse_img_array[$row->store].'" /></a>' . "\n";
			echo '</div>' . "\n"; // class="column col-type"
			echo '<div class="column col-country"><a href="'. $url_row .'" title="'. $idioma['bsns_country'] .'">'.$row->country.'</a></div>' . "\n";
			echo '<div class="column col-url"><a href="'. $row->url .'" title="'. $row->name .'">'.$row->url.'</a></div>' . "\n";
			echo '<div class="column col-desc">'.substr($row->description, 0, 50).'</div>' . "\n";
			echo '<div class="column col-score"><img src="'. get_stars($row->score). '" alt="'. $row->score . '"/></div>' . "\n";
			echo "</div>\n"; //class='column col-container'
			echo '</li>';
//		} // end if authenticated
	} // end for matches
// 	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '	</ul>' . "\n"; // ${tabla}-list


}

function businesses_map($query_cond='') {
    echo ' <div id="map"></div>'."\n";
    
//     echo ''."\n";
//     echo ''."\n";
//     echo ''."\n";
 }
?>
