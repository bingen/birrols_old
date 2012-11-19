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


	$tabla = 'beers_view';
	
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
	if( !empty( $_REQUEST['type_id']) ) {
	  $query_cond .= " AND type_id = " .$_REQUEST['type_id'];
	}

	// country //////////////////////
	if( !empty( $_REQUEST['country_id']) ) {
	  $query_cond .= " AND country_id = " .$_REQUEST['country_id'];
	}

	// abv //////////////////////
	if( !empty( $_REQUEST['abv_min']) ) {
	  $query_cond .= " AND abv >= " .$_REQUEST['abv_min'];
	}
	if( !empty( $_REQUEST['abv_max']) ) {
	  $query_cond .= " AND abv <= " .$_REQUEST['abv_max'];
	}
	
	$query_cond = list_head( $tabla, $query_cond, '&'. http_build_query( $_REQUEST ) );
	beers( $query_cond );

function beers($query_cond='') {
	global $mysql_link, $current_user, $globals, $idioma, $tabla;

	$query_beer = " WHERE 1 ";

	$query = "SELECT * FROM $tabla $query_beer $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$beer_list = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));

	echo '	   <ul class="principal-list" id="'. $tabla .'-list">' . "\n";

	for( $i = 0; $i < mysqli_num_rows($beer_list); $i++)
	{
		$row = mysqli_fetch_object($beer_list);
// 		print_r($row);

//		if( $current_user->authenticated ) {
			$url_row = $globals['base_url'].'beer.php?id='.$row->auto_id;
			echo '<li class="row" onclick="window.location=\''. $url_row .'\'">' . "\n";
// 			if($current_user->admin)
// 			    echo '<div class="column col-auto_id"><a href="'. $url_row .'" title="'. $row->auto_id .'">'.$row->auto_id.'</a></div>' . "\n";
			show_avatar( 'beers', $row->auto_id, $row->avatar, $row->name, 40 );
			echo "<div class='column col-container'>\n";
			echo '<h3 class="column col-name"><a href="'. $url_row .'" title="'. $row->name .'">'.$row->name.'</a></h3>' . "\n";
			echo '<div class="column col-country">'.$row->country.'</div>' . "\n";
			echo '<div class="column col-brewery"><a href="'.get_business_uri($row->brewery_id).'" title="'. $idioma['brewery'] .'">'.$row->brewery.'</a></div>' . "\n";
			echo '<div class="column col-category">'.$row->category.'</div>' . "\n";
			echo '<div class="column col-type">'.$row->type.'</div>' . "\n";
			echo '<div class="column col-abv">'. $idioma['beer_abv'] . ": ". (empty($row->abv) ? $idioma['NA'] : $row->abv).'</div>' . "\n";
			echo '<div class="column col-ibu">'. $idioma['beer_ibu'] . ": ". (empty($row->ibu) ? $idioma['NA'] : $row->ibu).'</div>' . "\n";
			echo '<div class="column col-desc">'.$row->description.'</div>' . "\n";
			echo '<div class="column col-score"><img src="'. get_stars($row->score). '" alt="'. $row->score . '"/></div>' . "\n";
		if( $current_user->authenticated ) // TODO:
			echo '<div class="column col-fav"><img src="'. $TODO . '" alt="'. $TODO . '"/></div>' . "\n";
			echo "</div>\n"; //class='column col-container'
			echo '</li>';
//		} // end if authenticated
	} // end for matches
	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '	</ul>' . "\n";
	//echo '    </div>' . "\n"; // partidos

}
	
?>
