<?php
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.

// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//      http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include_once('config.php');


	$campos = "auto_id,name,brewery,category,type,abv,ibu,description,score";
	$cabecera = $idioma['beer_id']. ',' .$idioma['beer_name']. ',' .$idioma['brewery']. ',' .$idioma['beer_category']. ',' .$idioma['beer_type']. ','  . $idioma['beer_abv']. ',' .$idioma['beer_ibu']. ' ,' .$idioma['beer_desc']. ',' .$idioma['beer_score'];
	$filtros_array = "1,1,1,1,1,1,1,1,1";
	$colspan_array = "1,1,1,1,1,1,1,1,1";
	$orden_array = "1,1,1,0,0,0,0,0,1,1";

	$tabla = 'beers_view';
//	if( empty($estado) )
//		$estado = $_REQUEST['estado'];

//	$where = " AND estado = $estado ";

	$query_cond = tabla_head( $tabla, $where, "&estado=$estado" );
	beers( 0, $query_cond );

function beers($beer_id=0, $query_cond='') {
	global $current_user, $globals, $idioma, $tabla;

	if( $beer_id != 0 ) 
		$query_beer = " WHERE auto_id = $beer_id ";
	//elseif( $estado != 0 )
	//	$query_partido = " WHERE estado = $estado ";
	else $query_beer = " WHERE 1 ";

	if( $beer_id != 0 ) {
// 		echo '	   <table class="'. $tabla .'-table" id="'. $tabla .'-head-1">' . "\n";
		echo '	   <table class="principal-table" id="'. $tabla .'-table">' . "\n";
		echo '	 <thead>' . "\n";
		$fila_0 = 0;
		$num_filas = 1;

		print("
	  <tr>
	    <th class=\"col-auto_id\"><strong>".$idioma['beer_id']."</strong></th>
	    <th class=\"col-name\"><strong>".$idioma['beer_name']."</strong></th>
	    <th class=\"col-brewery\"><strong>".$idioma['brewery']."</strong></th>
	    <th class=\"col-category\"><strong>".$idioma['beer_category']."</strong></th>
	    <th class=\"col-type\"><strong>".$idioma['beer_type']." 1</strong></th>
	    <th class=\"col-abv\"><strong>".$idioma['beer_abv']." 2</strong></th>
	    <th class=\"col-ibu\"><strong>".$idioma['beer_ibu']." 3</strong></th>
	    <th class=\"col-desc\"><strong>".$idioma['beer_desc']." 4</strong></th>
	    <th class=\"col-score\"><strong>".$idioma['beer_score']."</strong></th>
	  </tr>
	 </thead>
		");
// 	    <th class=\"col-nivel\" colspan=4><strong>".$idioma['pts_nivel_l']."</strong></th>
// 	    <th class=\"col-publico\"><strong>".$idioma['cp_publico']."</strong></th>

//	</table>
//		echo '    <div id="'. $tabla .'-1">' . "\n";
	} else {// if( $partido_id == 0 )
		//echo '    <div id="'. $tabla .'">' . "\n";
	}
	//echo '	<table class="'. $tabla .'-table" id="'. $tabla .'-body">' . "\n";

	echo '    <tbody>' . "\n";
	$query = "SELECT * FROM $tabla $query_beer $query_cond";
// 	echo '<p> Query: '. $query. '</p>';
	$beer_list = mysql_query( $query ) or die ('ERROR:'.mysql_error());
	for( $i = 0; $i < mysql_num_rows($beer_list); $i++)
	{
		$reg_beer = mysql_fetch_object($beer_list);
// 		print_r($reg_beer);

//		if( $current_user->authenticated ) {
			$url_partido = $globals['base_url'].'beer.php?id='.$reg_beer->auto_id;
			echo '<tr '.$zebra.' onclick="window.location=\''. $url_partido .'\'">' . "\n";
			echo '<td class="col-auto_id"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->auto_id.'</a></td>' . "\n";
			echo '<td class="col-name"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->name.'</a></td>' . "\n";
			echo '<td class="col-brewery"><a href="'.get_business_uri($reg_beer->brewery).'" title="'. $idioma['put_url_jugador'] .'">'.$reg_beer->brewery.'</a></td>' . "\n";
			echo '<td class="col-category"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->category.'</a></td>' . "\n";
			echo '<td class="col-type"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->type.'</a></td>' . "\n";
			echo '<td class="col-abv"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->abv.'</a></td>' . "\n";
			echo '<td class="col-ibu"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->ibu.'</a></td>' . "\n";
			echo '<td class="col-desc"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->description.'</a></td>' . "\n";
			echo '<td class="col-score"><a href="'. $url_partido .'" title="'. $idioma['put_url_partido'] .'">'.$reg_beer->score.'</a></td>' . "\n";
			echo '</tr>';
//		} // end if authenticated
	} // end for matches
	echo "\n<!-- Credits: using some famfamfam silk free icons -->\n";
	echo '    </tbody>' . "\n";
	echo '	</table>' . "\n";
	//echo '    </div>' . "\n"; // partidos

}
	
?>
