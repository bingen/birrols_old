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


include('config.php');

cabecera($idioma['hlp_about'],$_SERVER['PHP_SELF']);

laterales();

echo '  <div id="cuerpo">' . "\n";

//echo '<div id="menu_acerca_env">' . "\n";
echo ' <ul id="menu_acerca">' . "\n";
echo '	<li><a href="#" onClick="$(\'#faq\').hide();$(\'#what\').show();">'. $idioma['hlp_what'].'</a></li>' . "\n";
echo '	<li><a href="#" onClick="$(\'#what\').hide();$(\'#faq\').show();">'. $idioma['hlp_faq'].'</a></li>' . "\n";
echo ' </ul>' . "\n";
//echo '</div>' . "\n";
echo '<div id="what">' . "\n";
echo $idioma['about_what_1'] . "\n";
echo $idioma['about_what_2'] . "\n";
//echo '' . "\n";
echo '</div>' . "\n"; // what
echo '<div id="faq"  style="display:none">' . "\n";
// echo '	<ol>' . "\n";
// echo '		<li>' . "\n";
// echo '<h3>Question sample</h3>' . "\n";
// echo 'Answer sample' . "\n";
// echo '		</li>' . "\n";
// echo '</ol>' . "\n";
echo "<details>\n";
echo "	<summary>Question sample</summary>\n";
echo "	<p>Answer sample</p>\n";
echo "</details>\n";
echo "<details>\n";
echo "	<summary>Another question</summary>\n";
echo "	<p>Another answer</p>\n";
echo "</details>\n";
echo '</div>' . "\n"; // faq
//echo '' . "\n";
echo '    </div>' . "\n"; // cuerpo

pie();



?>
