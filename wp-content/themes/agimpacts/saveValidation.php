<?php

/* 
 * Copyright (C) 2015 CRSANCHEZ
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
$article = $_GET['article'];
$updates = $_POST;
$updates['article']['status'] = 1;
foreach($updates as $table => $data) {
  if($table == 'article'){
    $rows_affected = $wpdb->update($wpdb->prefix . $table, $data, array( 'id' => $article ));
  } else if ($table == 'estimate') {
    foreach ($data as $estimate => $regs) {
      $rows_affected = $wpdb->update($wpdb->prefix . $table, $regs, array( 'idEstimate' => $estimate ));
    }
  }
}
if(!$rows_affected) {
  $wpdb->show_errors();
  $wpdb->print_error();
} else {
  rewardSystem($article);
}
//echo "<pre>".print_r($updates,true)."</pre>";

function rewardSystem($articleId) {
  global $wpdb;
  $tablename1 = $wpdb->prefix . 'article';
  $tablename2 = $wpdb->prefix . 'estimate';
  $rows = $wpdb->get_results("SELECT * FROM $tablename1 as a INNER JOIN $tablename2 as b ON (a.id = b.article_id) WHERE a.id = $articleId", ARRAY_A);
//  echo "<pre>".print_r($rows,true)."</pre>";
  foreach ($rows as $row) {
    $bronceFields = 29;
    $broncePoints = 0;
    $silverFields = 8;
    $silverPoints = 0;
    $goldFields = 2;
    $goldPoints = 0;
    if ($row['doi_article'] != '') {
      $broncePoints++;
    }
    if ($row['author'] != '') {
      $broncePoints++;
    }
    if ($row['year'] != '') {
      $broncePoints++;
    }
    if ($row['journal'] != '') {
      $broncePoints++;
    }
    if ($row['volume'] != '') {
      $broncePoints++;
    }
    if ($row['issue'] != '') {
      $broncePoints++;
    }
    if ($row['page_start'] != '') {
      $broncePoints++;
    }
    if ($row['page_end'] != '') {
      $broncePoints++;
    }
    if ($row['reference'] != '') {
      $broncePoints++;
    }
    if ($row['paper_title'] != '') {
      $broncePoints++;
    }
    if ($row['crop'] != '') {
      $broncePoints++;
    }
    if ($row['temp_change'] != '') {
      $broncePoints++;
    }
    if ($row['yield_change'] != '') {
      $broncePoints++;
    }
    if ($row['adaptation'] != '') {
      $broncePoints++;
    }
    if ($row['climate_scenario'] != '') {
      $broncePoints++;
    }
    if ($row['impact_models'] != '') {
      $broncePoints++;
    }
    if ($row['base_line_start'] != '' && $row['base_line_start'] != 'N/A') {
      $broncePoints++;
    }
    if ($row['base_line_end'] != '' && $row['base_line_end'] != 'N/A') {
      $broncePoints++;
    }
    if ($row['projection_start'] != '' && $row['projection_start'] != 'N/A') {
      $broncePoints++;
    }
    if ($row['projection_end'] != '' && $row['projection_end'] != 'N/A') {
      $broncePoints++;
    }
    if ($row['geo_scope'] != '') {
      $broncePoints++;
    }
    if ($row['region'] != '') {
      $broncePoints++;
    }
    if ($row['country'] != '') {
      $broncePoints++;
    }
    if ($row['state'] != '') {
      $broncePoints++;
    }
    if ($row['city'] != '') {
      $broncePoints++;
    }
    if ($row['latitude'] != '') {
      $broncePoints++;
    }
    if ($row['longitude'] != '') {
      $broncePoints++;
    }
    if ($row['spatial_scale'] != '') {
      $broncePoints++;
    }
    if ($row['contributor'] != '') {
      $broncePoints++;
    }

    //grupo2 silver
    if ($row['scientific_name'] != '') {
      $silverPoints++;
    }
    if ($row['projection_co2'] != '' && $row['projection_co2'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['baseline_co2'] != '' && $row['baseline_co2'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['precipitation_change'] != '' && $row['precipitation_change'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['projec_yield_change_start'] != '' && $row['projec_yield_change_start'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['project_yield_change_end'] != '' && $row['project_yield_change_end'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['num_gcm_used'] != '' && $row['num_gcm_used'] != 'N/A') {
      $silverPoints++;
    }
    if ($row['num_impact_model_used'] != '' && $row['num_impact_model_used'] != 'N/A') {
      $silverPoints++;
    }

    //grupo3 gold
    if ($row['gcm'] != '') {
      $goldPoints++;
    }
    if ($row['comments'] != '') {
      $goldPoints++;
    }

    if ($goldPoints == $goldFields) {
//      echo '\n<br>'.$goldPoints ." ". $goldFields;
      addGoldPoints($row['wp_users_ID']);
    }

    if ($silverPoints == $silverFields) {
//      echo '\n<br>'.$silverPoints ." ". $silverFields;
      addSilverPoints($row['wp_users_ID']);
    }

    if ($broncePoints == $bronceFields) {
//      echo '\n<br>'.$broncePoints ." ". $bronceFields;
      addBroncePoints($row['wp_users_ID']);
    }
  }
}

function addGoldPoints($user) {
  $points = (get_the_author_meta('gold', $user)) ? esc_attr(get_the_author_meta('gold', $user)) : 0;
  $points += 1;
  update_user_meta($user, 'gold', $points);
}

function addSilverPoints($user) {
  $points = (get_the_author_meta('silver', $user)) ? esc_attr(get_the_author_meta('silver', $user)) : 0;
  $points += 2;
  update_user_meta($user, 'silver', $points);
}

function addBroncePoints($user) {
  $points = (get_the_author_meta('bronze', $user)) ? esc_attr(get_the_author_meta('bronze', $user)) : 0;
  $points += 3;
  update_user_meta($user, 'bronze', $points);
}