<?php

/*
 * Copyright (C) 20'precipitation_change' CRSANCHEZ
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
global $wpdb;
$estimate = array();
$estimate['article_id'] = $_GET['article'];
$estimate['crop'] = $_POST['crop'];
$estimate['scientific_name'] = $_POST['scientific_name'];
$estimate['projection_co2'] = (trim($_POST['projection_co2']) != '' && $_POST['projection_co2'] != 'N/A') ? $_POST['projection_co2'] : 0;
$estimate['baseline_co2'] = (trim($_POST['baseline_co2']) != '' && $_POST['baseline_co2'] != 'N/A') ? $_POST['baseline_co2'] : 0;
$estimate['temp_change'] = (trim($_POST['temp_change']) != '' && $_POST['temp_change'] != 'N/A') ? $_POST['temp_change'] : 0;
$estimate['precipitation_change'] = ($_POST['precipitation_change'] != '' && $_POST['precipitation_change'] != 'N/A') ? $_POST['precipitation_change'] : 0;
$estimate['yield_change'] = ($_POST['yield_change'] != '' && $_POST['yield_change'] != 'N/A') ? $_POST['yield_change'] : 0;
$estimate['projec_yield_change_start'] = ($_POST['projec_yield_change_start'] != '' && $_POST['projec_yield_change_start'] != 'N/A') ? $_POST['projec_yield_change_start'] : 0;
$estimate['project_yield_change_end'] = ($_POST['project_yield_change_end'] != '' && $_POST['project_yield_change_end'] != 'N/A') ? $_POST['project_yield_change_end'] : 0;
$estimate['adaptation'] = implode(',', $_POST['adaptation']);
$estimate['climate_scenario'] = $_POST['climate_scenario'];
$estimate['num_gcm_used'] = count(explode(',',$_POST['gcm']));
$estimate['gcm'] = $_POST['gcm'];
$estimate['num_impact_model_used'] = count(explode(',',$_POST['impact_models']));
$estimate['impact_models'] = $_POST['impact_models'];
$estimate['base_line_start'] = ($_POST['base_line_start'] != '' && $_POST['base_line_start'] != 'N/A') ? $_POST['base_line_start'] : 0;
$estimate['base_line_end'] = ($_POST['base_line_end'] != '' && $_POST['base_line_end'] != 'N/A') ? $_POST['base_line_end'] : 0;
$estimate['projection_start'] = ($_POST['projection_start'] != '' && $_POST['projection_start'] != 'N/A') ? $_POST['projection_start'] : 0;
$estimate['projection_end'] = (trim($_POST['projection_end']) != '' && $_POST['projection_end'] != 'N/A') ? $_POST['projection_end'] : 0;
$estimate['geo_scope'] = $_POST['geo_scope'];
$estimate['region'] = $_POST['region'];
$estimate['country'] = $_POST['country'];
$estimate['state'] = $_POST['state'];
$estimate['city'] = $_POST['city'];
$estimate['latitude'] = $_POST['latitude'];
$estimate['longitude'] = $_POST['longitude'];
$estimate['spatial_scale'] = $_POST['spatial_scale'];
$estimate['comments'] = $_POST['comments'];
$estimate['contributor'] = $_POST['contributor'];
$estimate['wp_users_ID'] = get_current_user_id();
$estimate['status'] = '0';
$tablename = $wpdb->prefix . 'estimate';
if ($_POST['estimate_id']) {
  $rows_affected = $wpdb->update($tablename, $estimate, array( 'idEstimate' => $_POST['estimate_id'] ));
} else {
  $rows_affected = $wpdb->insert($tablename, $estimate);
}
if(false === $rows_affected) {
  $wpdb->show_errors();
  $wpdb->print_error();
} else {
  $tablename = $wpdb->prefix . 'article';
  $wpdb->update($tablename, array('status' => '0'), array( 'id' => $_GET['article'] ));
}
