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

$idioma['err_acceso'] = 'Los datos de acceso no son correctos';

$idioma['desconectar'] = 'Salir';
$idioma['saludo'] = 'Hola, ';
$idioma['registrar'] = 'Registrarse';
$idioma['confirmar'] = '¿Estás seguro?';
$idioma['nombre'] = 'Nombre';
$idioma['usuario'] = 'Usuario';
$idioma['password'] = 'Clave';
$idioma['entrar'] = 'Entrar';
$idioma['cancelar'] = 'Cancelar';
$idioma['back'] = 'Atrás';

$idioma['forgot_pwd'] = '¿Has olvidado la contraseña?';

$idioma['volver'] = 'Volver';

$idioma['beers'] = 'Cervezas';
$idioma['businesses'] = 'Empresas';
$idioma['pubs'] = 'Bares y pubs';
$idioma['pub'] = 'Bar';
$idioma['stores'] = 'Tiendas';
$idioma['store'] = 'Tienda';
$idioma['retailers'] = 'Bares y tiendas';
$idioma['breweries'] = 'Breweries';
$idioma['brewery'] = 'Brewery';
//$idioma[''] = '';

$idioma['id_registro'] = 'Registro';
$idioma['id_verificar'] = 'Verificar';
$idioma['id_usuario'] = 'Usuario';
$idioma['id_clave'] = 'Clave';
$idioma['id_reclave'] = 'Repetir clave';
$idioma['id_nombre'] = 'Nombre';
$idioma['id_apellidos'] = 'Apellidos';
$idioma['id_lengua'] = 'Idioma';
$idioma['id_email'] = 'E-mail';
$idioma['id_email2'] = 'Repetir e-mail';
$idioma['id_email_nota'] = 'Es importante que sea correcta, recibirás un correo para validar la cuenta';
$idioma['id_legal_1'] = 'has leído y aceptas las ';
$idioma['id_legal_2'] = 'condiciones de uso';
$idioma['id_enviar'] = 'Enviar';
$idioma['id_aceptar'] = 'Aceptar';

$idioma['id_validar'] = 'Validación';
$idioma['id_registro_2'] = 'Registro de usuario';

$idioma['id_recaptcha'] = 'Introduce el texto de la imagen';

$idioma['err_cod_seg'] = 'El código de seguridad no es correcto.';
$idioma['err_inv_meil'] = 'El e-mail no coincide con el de la invitación';
$idioma['err_register_time'] = 'Invitación fuera de plazo';
$idioma['err_register_key'] = 'Datos de la invitación inconsistentes';
$idioma['err_register_db'] = 'Error accediendo a la base de datos';
$idioma['err_register_url'] = 'Error en la invitación';
$idioma['err_insert_user'] = 'Error insertando usuario en la base de datos';
$idioma['err_insert_beer'] = 'Error insertando cerveza en la base de datos';
$idioma['err_insert_business'] = 'Error insertando establecimento en la base de datos';
$idioma['err_update'] = 'Error actualizando en la base de datos';
$idioma['err_user_exists'] = 'El usuario ya existe';
$idioma['err_ip_1'] = 'IP no permitida';
$idioma['err_ip_2'] = 'Para registrar otro usuario desde la misma dirección debes esperar 24 horas.';
$idioma['err_ip_3'] = 'Para registrar otro usuario desde la misma red debes esperar 6 horas.';
$idioma['err_ip_4'] = 'Para registrar otro usuario desde la misma red debes esperar unos minutos.';
$idioma['err_short_user'] = 'Nombre de usuario erróneo, debe ser de 3 o más caracteres alfanuméricos';
$idioma['err_invalid_user'] = 'Nombre de usuario erróneo, caracteres no admitidos o no comienzan con una letra';
$idioma['err_invalid_email'] = 'El correo electrónico no es correcto';
$idioma['err_email_exists'] = 'Dirección de correo duplicada, o fue usada recientemente';
$idioma['err_email_vrf'] = 'Los e-mails no coinciden';
$idioma['err_pwd_1'] = 'Caracteres inválidos en la clave';
$idioma['err_pwd_2'] = 'Clave demasiado corta, debe ser de 6 o más caracteres e incluir mayúsculas, minúsculas y números.';
$idioma['err_pwd_3'] = 'Las claves no coinciden';
$idioma['err_long_user'] = 'Nombre demasiado largo';
$idioma['err_legal'] = 'No has aceptado las condiciones de legales de uso';
$idioma['err_hash'] = 'Falta la clave de control';
$idioma['err_avatar_1'] = 'Error guardando la imagen';
$idioma['err_avatar_2'] = 'El tamaño de la imagen excede el límite';
$idioma['err_register_auth'] = "Debes abandonar la sesión para dar de alta otro usuario";
$idioma['err_mail'] = 'Error enviando notifiación.';
$idioma['err_login'] = 'Debes estar logueado';
// TODO: existen?
$idioma['err_inv_friend_1'] = 'Error: invitado vacío';
$idioma['err_inv_friend_2'] = 'Error: invitado no existe';
$idioma['err_inv_friend_3'] = 'Error: invitado ya está en tu lista';
$idioma['err_inv_friend_4'] = 'Error: no es posible invitarse a uno mismo';
$idioma['err_invitar'] = 'Error enviando invitación';
//$idioma['err_'] = ;


$idioma['shr_general'] = $globals['app_name']. ', el portal para amantes de la cerveza artesana';

$idioma['mnu_datos'] = 'Mis datos';
$idioma['mnu_inicio'] = 'Inicio';

$idioma['mail_reg_subject'] = 'Alta de '.$globals['app_name'];
$idioma['mail_rec_subject'] = 'Recuperar o verificar contraseña de '. $globals['app_name'];
$idioma['mail_rec_body_1'] = ': para poder acceder sin la clave, conéctate a la siguiente dirección en menos de dos horas:';
$idioma['mail_rec_body_2'] = 'Pasado este tiempo puedes volver a solicitar acceso en: ';
$idioma['mail_rec_body_3'] = 'Una vez en tu perfil, puedes cambiar la clave de acceso.';
$idioma['mail_rec_body_4'] = 'Este mensaje ha sido enviado a solicitud de la dirección: ';
$idioma['mail_rec_body_5'] = 'el equipo de '. $globals['app_name'];
$idioma['mail_rec_from_1'] = 'Avisos';
$idioma['mail_rec_from_2'] = 'no_contestar';
$idioma['mail_rec_msg'] = 'Correo enviado, mira tu buzón, allí están las instrucciones. Mira también en la carpeta de spam.';

$idioma['mail_inv_subject'] = 'Invitado a nuevo portal de deportes';
$idioma['mail_inv_body_1'] = ' te ha invitado a darte de alta en '. $globals['app_name']. ', el portal para amantes de la cerveza artesana';
$idioma['mail_inv_body_2'] = 'Para darte de alta pincha en el siguiente enlace en menos de 1 semana:';
//$idioma['mail_inv_body_3'] = '';

$idioma['pwd_changed'] = 'La clave se ha cambiado';
$idioma['pro_data_updated'] = 'Datos actualizados';
$idioma['pro_opciones'] = 'Opciones de usuario';
$idioma['pro_avatar_1'] = 'Avatar';
$idioma['pro_avatar_2'] = 'El avatar debe ser una imagen cuadrada en jpeg, gif o png de no más de 100 KB, sin transparencias';
$idioma['pro_password'] = 'Introduce la nueva clave para cambiarla -no se cambiará si la dejas en blanco-:';
$idioma['pro_estado'] = 'Estado';
$idioma['pro_url'] = 'Página web';
$idioma['actualizar'] = 'Actualizar';
$idioma['pro_disable_1'] = 'Deshabilitar la cuenta';
$idioma['pro_disable_2'] = 'Atención! la cuenta será deshabilitada.';
$idioma['pro_disable_3'] = 'Se eliminarán automáticamente los datos personales.';
$idioma['pro_disable_4'] = 'TODO: Se eliminará ... No se borrarán los datos de los partidos.';
$idioma['pro_disable_5'] = 'Sí, quiero deshabilitarla';
$idioma['pro_provincia'] = 'Provincia';
$idioma['pro_volver'] = 'Volver sin realizar cambios';
//$idioma['pro_'] = 

$idioma['tit_profile'] = 'Edición del perfil del usuario';
$idioma['tit_resgistro'] = 'registro '. $globals['app_name'];
//$idioma['tit_'] = ;

$idioma['usr_info'] = 'Información personal';
$idioma['usr_modificar'] = 'Modificar';
$idioma['usr_id'] = 'Id';
$idioma['usr_desde'] = 'Desde';
$idioma['usr_sexo'] = 'Sexo';
$idioma['usr_birthday'] = 'Nacimiento';
$idioma['usr_mujer'] = 'Mujer';
$idioma['usr_hombre'] = 'Hombre';
$idioma['usr_old'] = 'antigüedad';
$idioma['usr_years'] = 'años';
$idioma['usr_perfil'] = 'Perfil';
$idioma['usr_inv_disabled'] = 'No disponible en fase de pruebas';
//$idioma['usr_'] = '';



$idioma['rp_recuperacion'] = 'Recuperación de contraseña';
$idioma['rp_no_user'] = 'El usuario o e-mail no existe';
$idioma['rp_disabled'] = 'Cuenta deshabilitada';
$idioma['rp_user_meil'] = 'Introduce nombre de usuario o email';
$idioma['rp_expl'] = 'Recibirás un e-mail para cambiar la contraseña';
$idioma['rp_submit'] = 'Recibir e-mail';
//$idioma['rp_'] = '';
//$idioma['rp_'] = '';

$idioma['err_mapa_google'] = 'Situacion de estas pistas no localizada en mapa';
$idioma['mis_amigos'] = 'Mis amigos';

// Ayuda
$idioma['hlp_about'] = 'Acerca de '. $globals['app_name'];
$idioma['hlp_legal'] = 'Legal';
$idioma['hlp_contact'] = 'Contacto';
$idioma['hlp_what'] = 'Qué es '. $globals['app_name'];
$idioma['hlp_faq'] = 'FAQ';
//$idioma['hlp_'] = '';

// Pop-Up Tutorial
//$idioma['put_block'] = 'Ayuda - Activa y desactiva la ayuda sobre los campos (como ésta)';
$idioma['put_about'] = 'Acerca de '. $globals['app_name']. ' - Qué es '. $globals['app_name'].' y FAQ';
$idioma['put_legal'] = ' - Términos y condiciones';
$idioma['put_contact'] = ' - Contacta con el equipo de '. $globals['app_name'];
$idioma['put_invitar_meil'] = 'Invitar a '. $globals['app_name'] .' - Escribe direcciones de e-mail separadas por comas para invitar a tus amigos a '. $globals['app_name'];
//$idioma['put_'] = '';

$idioma['beer_name'] = 'Nombre';
$idioma['beer_category'] = 'Categoría';
$idioma['beer_type'] = 'Tipo';
$idioma['beer_abv'] = 'Alcohol';
$idioma['beer_ibu'] = 'Amargor';
$idioma['beer_og'] = 'OG';
$idioma['beer_srm'] = 'Color (SRM)';
$idioma['beer_ebc'] = 'Color (EBC)';
$idioma['beer_malts'] = 'Maltas';
$idioma['beer_hops'] = 'Lúpulos';
$idioma['beer_desc'] = 'Descripción';
$idioma['beer_score'] = 'Puntuación';
$idioma['beer_new'] = 'Nueva cerveza';
//$idioma['beer_'] = '';

$idioma['bsns_type'] = 'Tipo de empresa';
$idioma['bsns_country'] = 'País';
$idioma['bsns_state'] = 'Provincia';
$idioma['bsns_city'] = 'Ciudad';
$idioma['bsns_address'] = 'Dirección';
$idioma['bsns_zip'] = 'Código postal';
$idioma['bsns_url'] = 'URL';
$idioma['bsns_list'] = 'Lista';
$idioma['bsns_map'] = 'Mapa';
$idioma['bsns_new'] = 'Nueva empresa';
$idioma['bsns_sel_country'] = 'Selecciona el país';
$idioma['bsns_phone'] = 'Teléfono';
$idioma['bsns_lat'] = 'Latitud';
$idioma['bsns_lon'] = 'Longitud';
//$idioma['bsns_'] = '';

$idioma['about_what_1'] = 'Bírrols, el portal de birra de buen';
$idioma['about_what_2'] = 'Es lo suyo y lo propio';

// Contacto
$idioma['cnt_asunto'] = 'Asunto';
$idioma['cnt_mensaje'] = 'Mensaje';
$idioma['err_cnt_nombre'] = 'Debes informar el nombre';
$idioma['err_cnt_asunto'] = 'Debes informar el asunto';
$idioma['err_cnt_mensaje'] = 'Debes escribir un mensaje';
$idioma['err_cnt_db'] = 'Error insertando en la base de datos';
$idioma['cnt_sent'] = 'Mensaje enviado correctamente. Muchas gracias por tu participación.';
//$idioma['cnt_'] = '';

?>