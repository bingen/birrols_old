<?php
// Modified and adapted by ßingen from:
// Ricardo Galli
// http://viewvc.meneame.net/index.cgi/branches/version3/
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

include(libpath.'ts.php');
include(libpath.'log.php');

cabecera('',$_SERVER['PHP_SELF']);

laterales();

echo '<div id="cuerpo">'. "\n";

	if( $current_user->authenticated )
		show_error($idioma['err_register_auth']);
	else {
		echo '<div class="genericform">'."\n";
		echo '<fieldset>'."\n";
		echo '<legend>'. $idioma['rp_recuperacion'] .'</legend>'."\n";

		if(!empty($_POST['recover'])) {
			if (!ts_is_human()) {
				show_error($idioma['err_cod_seg']);
			} else {
				require_once(libpath.'user.php');
				$user=new User();
				if (preg_match('/.+@.+/', $_POST['username'])) {
					// It's an email address
					$user->email=$_POST['username'];
				} else {
					$user->username=$_POST['username'];
				}
				if(!$user->read()) {
					show_error($idioma['rp_no_user']);
					return false;
				}
				if($user->disabled()) {
					show_error($idioma['rp_disabled']);
					return false;
				}
				require_once(libpath.'mail.php');
				$sent = send_recover_mail($user, 2);
			}
		}
		if (!$sent) {
			echo '<form action="rec_pwd.php" id="form_rec_pwd" method="post">'."\n";
			echo '<label for="name">'. $idioma['rp_user_meil'] .':</label><br />'."\n";
			echo '<input type="text" name="username" size="25" tabindex="1" id="name" value="'.$username.'" />'."\n";
			echo '<p>'. $idioma['rp_expl'] .'</p>';
			echo '<input type="hidden" name="recover" value="1"/>'."\n";
			echo '<input type="hidden" name="return" value="'.htmlspecialchars($_REQUEST['return']).'"/>'."\n";
			ts_print_form();
			echo '<br /><input type="submit" value="'. $idioma['rp_submit'] .'" class="button" />'."\n";
			echo '</form>'."\n";
		}
		echo '</fieldset>'."\n";
		echo '</div>'."\n";
	}


	echo '       <div id="fake" style="clear: both;"></div>'. "\n";	// para evitar computed height = 0
	echo '  </div>'. "\n";

	pie();

?>
