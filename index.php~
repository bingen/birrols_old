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

include('config.php');

$error = false;

connect();

if(!$current_user->authenticated) {
	if(! $current_user->Authenticate($_POST['usuario'], md5($_POST['password'])) ) {
		header("Location:index.php?error_acceso=");
		//echo '<p> usuario: '. $_POST['usuario'] . ' pwd: ' . $_POST['password'] . ' md5: ' .md5($_POST['password']). "\n";
		exit();
	}
}

cabecera($app_name, $_SERVER['PHP_SELF']);

//print_r($_REQUEST);

laterales();

echo '<div id="cuerpo">'. "\n";

if( !empty($mensaje_accion) ) {
	if( $error )
		echo '<div id="mensaje" class="error">'."\n";
	else
		echo '<div id="mensaje">'."\n";
	echo $mensaje_accion;
	echo '</div>'."\n"; // mensaje
}

$tabla = 'partidos_view';
echo '    <div id="'. $tabla .'-env" class="tabla-env">' . "\n";
include('tabla_'. $tabla .'.php');
echo '    </div>' . "\n"; // partidos-env

echo '       <div id="fake" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0

pie();


?>

