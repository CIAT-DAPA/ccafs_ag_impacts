<?php

/*
 * Copyright (C) 2014 CRSANCHEZ
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require('../../../wp-load.php');

if ($_GET) {

  if (isset($_GET['register']) && $_GET['register'] == 1) {
    $datosUsuario = array();

    $datosUsuario['user_login'] = $_GET['reguser'];
    $datosUsuario['user_pass'] = $_GET['regpwd'];
    $datosUsuario['user_email'] = $_GET['regemail'];
    $datosUsuario['first_name'] = $_GET['regname'];
    $datosUsuario['last_name'] = $_GET['reglastname'];
    $datosUsuario['user_inst'] = $_GET['reginstitution'];

    if (registerUser($datosUsuario)) {
//      wp_redirect(get_bloginfo('url'));
    }
  }
}

function registerUser($datos) {
  $userid = wp_insert_user($datos);
//echo "$$".is_wp_error($userid);
  if (!is_wp_error($userid)) {
    update_user_meta($userid, 'user_inst', $datos['user_inst']);

    wp_set_auth_cookie($userid);
    return true;
  } else {
    $error_string = $userid->get_error_message();
    echo $error_string;
    return false;
  }
}
