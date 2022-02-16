<?php
/**
 * Template Name: Full view article
 * @package WordPress
 * @subpackage AMKNToolbox
 */
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
if (isset($_REQUEST['custom'])) {
  require('../../../wp-load.php');
  ?><script src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.11.1.min.js"></script><?php
} else {
  get_header('logo');
}
global $wpdb;
$version = '1.1';
?>
<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/jqueryui/dataTables.jqueryui.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/agimpact_filter.css?<?php echo $version;?>">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/js/tablesorter/css/theme.green.css">
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tablesorter/js/jquery.tablesorter.js"></script>
<?php
$crop = $_REQUEST['crop'];
$model = $_REQUEST['model'];
$climate = $_REQUEST['climate'];
$baseline = $_REQUEST['baseline'];
$period = $_REQUEST['period'];
$scale = $_REQUEST['scale'];
$country = $_REQUEST['country'];
$subcontinents = $_REQUEST['subcontinents'];
$adaptation = $_REQUEST['adaptation'];
//$where = "  ";
//
//if ($crop != "null") {
//  $where = $where . " AND e.crop = '" . $crop . "' ";
//}
//if ($model != "null") {
//  $where = $where . " AND e.impact_models = '" . $model . "' ";
//}
//if ($climate != "null") {
//  $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
//}
//if ($baseline != "null") {
//  $baselinearray[] = explode(" - ", $baseline);
//  $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
//}
//if ($period != "null") {
//  $periodarray[] = explode(" - ", $period);
//  $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
//}
//if ($scale != "null") {
//  $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
//}
//if ($subcontinents != "null") {
//  $where = $where . " AND e.region = '" . $subcontinents . "' ";
//}
//if ($country != "null") {
//  $where = $where . " AND e.country = '" . $country . "' ";
//}
//if ($adaptation != "null") {
//  $where = $where . " AND e.adaptation = '" . $adaptation . "' ";
//}
//
//
//$result = "SELECT a.*,e.*,"
//        . " CONCAT(e.base_line_start,' - ',e.base_line_end) as baseline,"
//        . " CONCAT(e.projection_start,' - ',e.projection_end) as projection,"
//        . " CONCAT(e.region,' - ',e.country) as geograph_scope "
//        . " FROM wp_estimate e "
//        . " INNER JOIN wp_article a ON e.article_id=a.id "
//        . " WHERE 1 "
//        . $where
//        . " ORDER BY a.id ";
//echo $result; exit();
//$dataResult = $wpdb->get_results($result, ARRAY_A);
//if (count($dataResult)) {
  ?>
	<section id='content' class='row'><table id='resulttable' name='resulttable' class='display' style=''>
	<thead>
		<tr>
			<th>DOI <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Journal</th>
                        <th>Volume</th>
                        <th>Issue</th>
                        <th>Start Page</th>
                        <th>End Page</th>
                        <th>Reference</th>
                        <th>Title</th>
                        <th>Crop</th>
                        <th>Scientific name</th>
                        <th>CO2 Projected</th>
                        <th>CO2 Baseline</th>
                        <th>Temp Change</th>
                        <th>Precipitation change</th>
                        <th>Yield Change</th>
                        <th>Projec yield change start</th>
                        <th>Projec yield change end</th>
                        <th>Adaptation</th>
                        <th>Climate scenario</th>
                        <th># GCM used</th>
                        <th>GCM(s)</th>
                        <th># Impact model used</th>
                        <th>Impact model(s)</th>
                        <th>Baseline start</th>
                        <th>Baseline end</th>
                        <th>Projection start</th>
                        <th>Projection end</th>
                        <th>Geo scope</th>
                        <th>Region</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
			<th>Spatial Scale <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Comments <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Contributor <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Status <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
		</tr>
	</thead>
	<tbody>
        </table></section>;
  <script language='javascript'>
    $(document).ready(function() {
    $('#resulttable').dataTable({
      'scrollX': true,
      'scrollY': 400,
      'jQueryUI': true,
      'processing': true,
      'serverSide': true,
      'searching': false,
      'ajax': {
        url: '<?php echo get_template_directory_uri();?>/dataTableFilter.php',
        type: 'POST',
        data: function(d) {
          d.crop = '<?php echo $_REQUEST['crop']?>';
          d.model = '<?php echo$_REQUEST['model']?>';
          d.scale = '<?php echo$_REQUEST['scale']?>';
          d.climate = '<?php echo$_REQUEST['climate']?>';
          d.baseline = '<?php echo$_REQUEST['baseline']?>';
          d.period = '<?php echo$_REQUEST['period']?>';
          d.country = '<?php echo$_REQUEST['country']?>';
          d.allfields = true;
          d.subcontinents = '<?php echo$_REQUEST['subcontinents']?>';
          d.adaptation = '<?php echo$_REQUEST['adaptation']?>';
        }
      }
    });
  });
  </script>  