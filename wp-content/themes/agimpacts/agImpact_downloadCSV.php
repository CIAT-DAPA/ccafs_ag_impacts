<?php
require('../../../wp-load.php');
global $wpdb;
// This needs to be set, in order for the script work, 
//in the case when the request variable is empty, throws 
//an e_notice of the empty array
error_reporting(0);

//$conexion = new mysqli('localhost', 'root', '', 'agimpacts', 3306);
//if (mysqli_connect_errno()) {
//  printf("The conexion to the server failed: %s\n", mysqli_connect_error());
//  exit();
//}

$crop = $_REQUEST['crop'];
$model = $_REQUEST['model'];
$climate = $_REQUEST['climate'];
$baseline = $_REQUEST['baseline'];
$period = $_REQUEST['period'];
$scale = $_REQUEST['scale'];
$country = $_REQUEST['country'];
$subcontinents = $_REQUEST['subcontinents'];
$adaptation = $_REQUEST['adaptation'];
$where = "  ";

if ($crop != "null") {
  $where = $where . " AND e.crop = '" . $crop . "' ";
}
if ($model != "null") {
  $where = $where . " AND e.impact_models = '" . $model . "' ";
}
if ($climate != "null") {
  $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
}
if ($baseline != "null") {
  $baselinearray[] = explode(" - ", $baseline);
  $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
}
if ($period != "null") {
  $periodarray[] = explode(" - ", $period);
  $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
}
if ($scale != "null") {
  $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
}
if ($subcontinents != "null") {
  $where = $where . " AND e.region = '" . $subcontinents . "' ";
}
if ($country != "null") {
  $where = $where . " AND e.country = '" . $country . "' ";
}
if ($adaptation != "null") {
  $where = $where . " AND e.adaptation = '" . $adaptation . "' ";
}

$select = "a.doi_article, a.author, a.year, a.journal, a.volume, a.issue, a.page_start, a.page_end, a.reference, a.paper_title, e.crop, e.scientific_name, e.projection_co2, e.baseline_co2, e.temp_change, e.precipitation_change,"
          . "e.yield_change, e.projec_yield_change_start, e.project_yield_change_end, e.adaptation, e.climate_scenario, num_gcm_used, gcm, num_impact_model_used, impact_models, base_line_start, base_line_end, projection_start, projection_end,"
          . "geo_scope, region, country, state, city, latitude, longitude, spatial_scale, comments, contributor ";

$result = "SELECT $select"
        . " FROM wp_estimate e "
        . " INNER JOIN wp_article a ON e.article_id=a.id "
        . " WHERE 1 "
        . $where
        . " ORDER BY a.doi_article ";
//$resultado = $conexion->query($result);
$resultado = $wpdb->get_results($result, ARRAY_A);
$headers = array('DOI', 'Author', 'Year', 'Journal', 'Volume', 'Issue', 'Start page', 'End page', 'Reference', 'Title', 'Crop','Scientific name',  'CO2 Projected', 'CO2 Baseline', 'Temp Change', 'Precipitation change', 'Yield Change', 'Projected yield change start', 'Projected yield change end', 'Adaptation', 'Climate scenario', '# GCM used', 'GCM(s)', '# Impact model used', 'Impact model(s)', 'Baseline start', 'Baseline end', 'Projection start', 'Projection end', 'Continent', 'Region', 'Country', 'State', 'City', 'Latitude', 'Longitude', 'Spatial scale', 'Comments', 'Contributor');
if (count($resultado) > 0) {

  $fp = fopen('php://output', 'w');
  if ($fp && $resultado) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Crop_Estimate.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);
    foreach ($resultado as $row) {
      fputcsv($fp, array_values($row));
    }
    die;
  }
} else {
  echo "<script language='javascript'>alert('No Data Found');</script>";
}
?>