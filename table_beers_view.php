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

function query_add( $field ) {
  global $query_cond;
  
  if( !empty( $_REQUEST[$field]) ) {
    $query_cond .= " AND $field = " .$_REQUEST[$field];
  }
} // // query_add

	$tabla = 'beers_view';
// 	print_r( $_REQUEST );
	
	if( !empty($_REQUEST['pattern']) ) {
	  $pattern = $_REQUEST['pattern'];
	} else { 
	  $pattern = 8191;
	}

	if( !empty($_REQUEST['brewery_id']) ) {
	  $query_cond .= " AND brewery_id = " .$_REQUEST['brewery_id'];
	}

	if( !empty($_REQUEST['ale']) && $_REQUEST['ale'] == 'true' )
	    $query_cat .= " OR category_id = 1 ";
	if( !empty( $_REQUEST['lager']) && $_REQUEST['lager'] == 'true' )
	    $query_cat .= " OR category_id = 2 ";
	if( !empty($_REQUEST['lambic']) && $_REQUEST['lambic'] == 'true' )
	    $query_cat .= " OR category_id = 3 ";
	if( !empty($query_cat) )
	    $query_cond = 'AND (0 ' . $query_cat . ')';

	// type //////////////////////
	query_add ( 'type_id' );
	// country //////////////////////
	query_add ( 'country_id' );
	// brewery
	query_add ( 'brewery_id' );
	if( empty($_REQUEST['brewery_id']) AND !empty($_REQUEST['brewery']) ) {
	  $search_array = preg_split( '/ /', $_REQUEST['brewery'] );
	  $query_brewery = ' AND (0 ';
	  foreach( $search_array as $search_term ) {
	    $query_brewery .= " OR brewery LIKE '%$search_term%' ";
	  }
	  $query_cond .= $query_brewery . ") ";
// 	  $query_cond .= " AND brewery LIKE '%" .$_REQUEST['brewery'] . "%' ";
	}

	// abv //////////////////////
	if( !empty( $_REQUEST['abv_min']) ) {
	  $query_cond .= " AND abv >= " .$_REQUEST['abv_min'];
	}
	if( !empty( $_REQUEST['abv_max']) ) {
	  $query_cond .= " AND abv <= " .$_REQUEST['abv_max'];
	}
	
	// ibu //////////////////////
	if( !empty( $_REQUEST['ibu_min']) ) {
	  $query_cond .= " AND ibu >= " .$_REQUEST['ibu_min'];
	}
	if( !empty( $_REQUEST['ibu_max']) ) {
	  $query_cond .= " AND ibu <= " .$_REQUEST['ibu_max'];
	}
	
	if( !empty( $_REQUEST['search']) ) {
	  $search = $_REQUEST['search'];
	  $search_array = preg_split( '/ /', $search );
	  // all the words in the exact order
	  $query_search_1 = " (name LIKE '%$search%' OR description LIKE '%$search%')";
	  // any of the words
	  $query_search_2 = '';
	  foreach( $search_array as $search_term ) {
	    $query_search_2 .= " OR (name LIKE '%$search_term%' OR description LIKE '%$search_term%')";
	  }
	  $query_cond .= " AND ($query_search_1 $query_search_2)";
	}

// 	echo '<p> Query_cond: '. $query_cond. '</p>';
	$query_cond = list_head( $tabla, $query_cond, '&'. http_build_query( $_REQUEST ) );
	beers( $query_cond );

function beers($query_cond='') {
	global $mysql_link, $current_user, $globals, $idioma, $tabla, $pattern;

	$query_beer = " WHERE 1 ";

	$query = "SELECT * FROM $tabla $query_beer $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$beer_list = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));

	echo '	   <ul class="principal-list" id="'. $tabla .'-list">' . "\n";

	for( $i = 0; $i < mysqli_num_rows($beer_list); $i++)
	{
		$row = mysqli_fetch_object($beer_list);
// 		print_r($row);
		$url_row = $globals['base_url'].'beer.php?id='.$row->auto_id;
		echo '<li class="row" onclick="window.location=\''. $url_row .'\'">' . "\n";
		$binary_col = 1;
// 		if( ($pattern & $binary_col) == $binary_col && $current_user->admin )
// 			    echo '<div class="column col-auto_id"><a href="'. $url_row .'" title="'. $row->auto_id .'">'.$row->auto_id.'</a></div>' . "\n";
		$binary_col = $binary_col * 2; // 2
		if( ($pattern & $binary_col) == $binary_col )
			show_avatar( 'beers', $row->auto_id, $row->avatar, $row->name, 40 );
		echo "<div class='column col-container'>\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<h3 class="column col-name"><a href="'. $url_row .'" title="'. $row->name .'">'.$row->name.'</a></h3>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-country">'.$row->country.'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-brewery"><a href="'.get_business_uri($row->brewery_id).'" title="'. $idioma['brewery'] .'">'.$row->brewery.'</a></div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-category">'.$row->category.'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-type">'.$row->type.'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-abv">'. $idioma['beer_abv'] . ": ". (empty($row->abv) ? $idioma['NA'] : $row->abv).'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-ibu">'. $idioma['beer_ibu'] . ": ". (empty($row->ibu) ? $idioma['NA'] : $row->ibu).'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-desc">'.$row->description.'</div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col )
			echo '<div class="column col-score"><img src="'. get_stars($row->score). '" alt="'. $row->score . '"/></div>' . "\n";
		$binary_col = $binary_col * 2;
		if( ($pattern & $binary_col) == $binary_col  && $current_user->authenticated ) // TODO:
			echo '<div class="column col-fav"><img src="'. $TODO . '" alt="'. $TODO . '"/></div>' . "\n";
		echo "</div>\n"; //class='column col-container'
		echo '</li>';
	} // end for matches
	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '	</ul>' . "\n";
	//echo '    </div>' . "\n"; // partidos

}
	
?>
