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

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
require('../../../wp-load.php');
$crop = $_REQUEST['crop'];
$model = $_REQUEST['model'];
$climate = $_REQUEST['climate'];
$baseline = $_REQUEST['baseline'];
$period = $_REQUEST['period'];
$scale = $_REQUEST['scale'];
$country = $_REQUEST['country'];
$continents = $_REQUEST['continents'];
$regions = $_REQUEST['regions'];
$adaptation = $_REQUEST['adaptation'];
$where = "  ";

if ($crop != "" && $crop != 'null') {
  $where = $where . " AND e.crop = '" . $crop . "' ";
}
if ($model!= 'null' && $model!= '') {
  $where = $where . " AND e.impact_models = '" . $model . "' ";
}
if ($climate!= 'null' && $climate!= '') {
  $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
}
if ($baseline!= 'null' && $baseline!= '') {
  $baselinearray[] = explode(" - ", $baseline);
  $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
}
if ($period != '' && $period != 'null') {
  $periodarray[] = explode(" - ", $period);
  $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
}
if ($scale!= 'null' && $scale!= '') {
  $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
}
if ($country != 'null' && $country != '') {
  $where = $where . " AND e.country = '" . $country . "' ";
}
elseif ($continents!= 'null' && $continents!= '') {
  $where = $where . " AND e.geo_scope = '" . $continents . "' ";
}
elseif ($regions!= 'null' && $regions!= '') {
  $where = $where . " AND e.region = '" . $regions . "' ";
}
if ($adaptation != 'null' && $adaptation != '') {
//  $adaptation = explode(',', $adaptation);
  $where .= " AND (";
  foreach ($adaptation as $val) {
    $where .= " e.adaptation LIKE '%" . trim($val) . "%' OR";
  }
  $where = substr($where, 0, -2);
  $where .= ") ";
}
//$where = "  ";
$sql1 = "SELECT count(*) as total"
        . " FROM wp_estimate e "
        . " INNER JOIN wp_article a ON e.article_id=a.id "
        . " WHERE 1 "
        . $where;
//echo $sql1;
$result = $wpdb->get_row($sql1);

$total_rows = $result->total;
// DB table to use
$limit = 'LIMIT ' . $_REQUEST['start'] . ',' . $_REQUEST['length'];
$select = '';
if (isset($_REQUEST['allfields'])) {
  $select = "a.doi_article, a.author, a.year, a.journal, a.volume, a.issue, a.page_start, a.page_end, a.reference, a.paper_title, e.crop, e.scientific_name, e.projection_co2, e.baseline_co2, e.temp_change, e.precipitation_change,"
          . "e.yield_change, e.projec_yield_change_start, e.project_yield_change_end, e.adaptation, e.climate_scenario, num_gcm_used, gcm, num_impact_model_used, impact_models, base_line_start, base_line_end, projection_start, projection_end,"
          . "geo_scope, region, country, state, city, latitude, longitude, spatial_scale, comments, contributor,  CASE e.status WHEN 1 THEN 'Validated' ELSE 'New' END as status ";
} else {
  $select = "e.crop,e.impact_models,"
          . " CONCAT(e.base_line_start,' - ',e.base_line_end) as baseline,"
          . " CONCAT(e.projection_start,' - ',e.projection_end) as projection,"
          . " e.yield_change, CONCAT(e.region,' - ',e.country) as geograph_scope,"
          . " e.temp_change,e.climate_scenario, e.adaptation";
}

$sql1 = "SELECT " 
        . $select
        . " FROM wp_estimate e "
        . " INNER JOIN wp_article a ON e.article_id=a.id "
        . " WHERE 1 "
        . $where
        . " ORDER BY a.doi_article $limit";
//  echo $sql1;
$result = $wpdb->get_results($sql1, ARRAY_N);

echo json_encode(
        array(
//          "draw"=> 1,
          "recordsTotal" => $total_rows,
          "recordsFiltered" => $total_rows,
          "data" => $result
        )
);

