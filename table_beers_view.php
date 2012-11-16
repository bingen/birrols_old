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

	if( !empty($_REQUEST['ale']) && $_REQUEST['ale'] == 'true' )
	    $query_cond .= " OR category_id = 1 ";
	if( !empty( $_REQUEST['lager']) && $_REQUEST['lager'] == 'true' )
	    $query_cond .= " OR category_id = 2 ";
	if( !empty($_REQUEST['lambic']) && $_REQUEST['lambic'] == 'true' )
	    $query_cond .= " OR category_id = 3 ";
	if( !empty($query_cond) )
	    $query_cond = 'AND (0 ' . $query_cond . ')';

	$query_cond = list_head( $tabla, $query_cond );
	beers( $query_cond );

function beers($query_cond='') {
	global $mysql_link, $current_user, $globals, $idioma, $tabla;

	$query_beer = " WHERE 1 ";

	$query = "SELECT * FROM $tabla $query_beer $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$beer_list = mysqli_query( $mysql_link, $query ) or die ('ERROR:'.mysqli_error($mysql_link));
	for( $i = 0; $i < mysqli_num_rows($beer_list); $i++)
	{
		$row = mysqli_fetch_object($beer_list);
// 		print_r($row);

//		if( $current_user->authenticated ) {
			$url_row = $globals['base_url'].'beer.php?id='.$row->auto_id;
			echo '<li onclick="window.location=\''. $url_row .'\'">' . "\n";
			if($current_user->admin)
			    echo '<div class="col-auto_id"><a href="'. $url_row .'" title="'. $row->auto_id .'">'.$row->auto_id.'</a></v>' . "\n";
			show_avatar( 'beers', $row->auto_id, $row->avatar, $row->name, 40 );
			echo '<h3 class="col-name"><a href="'. $url_row .'" title="'. $row->name .'">'.$row->name.'</a></h3>' . "\n";
			echo '<div class="col-country">'.$row->country.'</div>' . "\n";
			echo '<div class="col-brewery"><a href="'.get_business_uri($row->brewery_id).'" title="'. $idioma['brewery'] .'">'.$row->brewery.'</a></div>' . "\n";
			echo '<div class="col-category">'.$row->category.'</div>' . "\n";
			echo '<div class="col-type">'.$row->type.'</div>' . "\n";
			echo '<div class="col-abv">'. $idioma['beer_abv'] . ": ". $row->abv.'</div>' . "\n";
			echo '<div class="col-ibu">'. $idioma['beer_ibu'] . ": ". $row->ibu.'</div>' . "\n";
			echo '<div class="col-desc">'.$row->description.'</div>' . "\n";
			echo '<div class="col-score"><img src="'. get_stars($row->score). '" alt="'. $row->score . '"/></div>' . "\n";
		if( $current_user->authenticated ) // TODO:
			echo '<div class="col-fav"><img src="'. $TODO . '" alt="'. $TODO . '"/></div>' . "\n";
			echo '</li>';
//		} // end if authenticated
	} // end for matches
	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '    </tbody>' . "\n";
	echo '	</table>' . "\n";
	//echo '    </div>' . "\n"; // partidos

}
	
?>
