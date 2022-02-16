<?php
require('../../../wp-load.php');

$option = $_REQUEST['option'];

switch ($option) {
  case 1 : doiQuery();
    break;
  case 2 : scaleQuery();
    break;
  case 3 : cropQuery();
    break;
  case 4 : modelQuery();
    break;
  case 5 : baselineQuery();
    break;
  case 6 : periodQuery();
    break;
  case 7 : countryQuery();
    break;
  case 8 : continentsQuery();
    break;
  case 9 : climateQuery();
    break;
  case 10 : adaptationQuery();
    break;
  case 11 : dataTable();
    break;
  case 12 : regionQuery();
    break;
  default:
    break;
}

function doiQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $doi = $_REQUEST['doi'];
  $result = $wpdb->get_results("SELECT id,doi_article FROM wp_article WHERE doi_article LIKE '%$doi%' ORDER BY id ASC ", ARRAY_A);
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['id'], "text" => $result[$i]['doi_article']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function cropQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $crop = $_REQUEST['crop'];
  $result = $wpdb->get_results("SELECT DISTINCT(e.crop) "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE "
          . " e.crop LIKE '%" . $crop . "%' "
          . "ORDER BY e.crop ASC ", ARRAY_A);
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['crop'], "text" => $result[$i]['crop']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function modelQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $where = "";
  if ($crop != '') {
    $where = $where . " AND e.crop = '" . $crop . "'";
  }
  $result = $wpdb->get_results("SELECT DISTINCT(e.impact_models) "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE "
          . " e.impact_models LIKE '%" . $model . "%' "
          . $where
          . " ORDER BY e.impact_models ASC", ARRAY_A);
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['impact_models'], "text" => $result[$i]['impact_models']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function climateQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $where = "";
  if ($crop != '') {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != '') {
    $where = $where . " AND e.impact_models = '" . $crop . "' ";
  }
  $result = $wpdb->get_results("SELECT DISTINCT(e.climate_scenario) "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE "
          . " e.climate_scenario LIKE '%" . $climate . "%' "
          . $where
          . " ORDER BY e.climate_scenario ASC ", ARRAY_A);
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['climate_scenario'], "text" => $result[$i]['climate_scenario']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function baselineQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $where = "  ";

  if ($baseline != "") {
    $where = $where . " AND e.base_line_start LIKE '%" . $baseline . "%' OR e.base_line_end LIKE '%" . $baseline . "%' ";
  }
  if ($crop != "") {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != "") {
    $where = $where . " AND e.impact_models = '" . $model . "' ";
  }
  if ($climate != "") {
    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
  }

  $result = $wpdb->get_results("SELECT DISTINCT e.base_line_start, e.base_line_end "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE 1 "
          . $where
          . " GROUP BY e.base_line_start "
          . " ORDER BY e.base_line_start  ASC ", ARRAY_A);

  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['base_line_start'] . " - " . $result[$i]['base_line_end'], "text" => $result[$i]['base_line_start'] . " - " . $result[$i]['base_line_end']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function periodQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $period = $_REQUEST['period'];
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $where = "  ";

  if ($period != "") {
    $where = $where . " AND e.projection_start LIKE '%" . $period . "%' OR e.projection_end LIKE '%" . $period . "%' ";
  }
  if ($crop != "") {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != "") {
    $where = $where . " AND e.impact_models = '" . $model . "' ";
  }
  if ($climate != "") {
    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
  }
  if ($baseline != "") {
    $baselinearray[] = explode(" - ", $baseline);
    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
  }

  $result = $wpdb->get_results("SELECT DISTINCT e.projection_start, e.projection_end  "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE 1 "
          . $where
          . " GROUP BY e.projection_start "
          . " ORDER BY e.projection_start ASC ", ARRAY_A);


  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['projection_start'] . " - " . $result[$i]['projection_end'], "text" => $result[$i]['projection_start'] . " - " . $result[$i]['projection_end']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function scaleQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $period = $_REQUEST['period'];
  $where = "  ";

  if ($crop != "") {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != "") {
    $where = $where . " AND e.impact_models = '" . $model . "' ";
  }
  if ($climate != "") {
    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
  }
  if ($baseline != "") {
    $baselinearray[] = explode(" - ", $baseline);
    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
  }
  if ($period != "") {
    $periodarray[] = explode(" - ", $period);
    $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
  }
  $result = $wpdb->get_results("SELECT DISTINCT(e.spatial_scale) "
          . "FROM wp_estimate e "
          . "INNER JOIN wp_article a ON e.article_id=a.id "
          . "WHERE 1 "
          . $where
          . "ORDER BY spatial_scale ASC ", ARRAY_A);
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['spatial_scale'], "text" => $result[$i]['spatial_scale']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function continentsQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $continent = $_REQUEST['continents'];
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $period = $_REQUEST['period'];
  $scale = $_REQUEST['scale'];
  $where = "  ";

  if ($crop != "") {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != "") {
    $where = $where . " AND e.impact_models = '" . $model . "' ";
  }
  if ($climate != "") {
    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
  }
  if ($baseline != "") {
    $baselinearray[] = explode(" - ", $baseline);
    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
  }
  if ($period != "") {
    $periodarray[] = explode(" - ", $period);
    $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
  }
  if ($scale != "") {
    $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
  }

//  $result = $wpdb->get_results("SELECT DISTINCT(e.region) "
//          . "FROM wp_estimate e "
//          . "INNER JOIN wp_article a ON e.article_id=a.id "
//          . "WHERE e.region LIKE'%".$subcontinents."%' "
//          . $where
//          . "ORDER BY e.region ASC ", ARRAY_A);

  $result = $wpdb->get_results("SELECT e.name "
          . "FROM wp_continent e "
//          . "INNER JOIN wp_article a ON e.article_id=a.id "
          . "WHERE e.name LIKE'%" . $continent . "%' "
//          . $where
          . "ORDER BY e.name ASC ", ARRAY_A);

  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['name'], "text" => $result[$i]['name']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function regionQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $region = $_REQUEST['region'];
  $continent = $_REQUEST['continent'];
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $period = $_REQUEST['period'];
  $scale = $_REQUEST['scale'];
  $where = "  ";

  if ($continent != "") {
    $where = $where . " AND a.name = '" . $continent . "' ";
  }
//  if ($crop != "") {
//    $where = $where . " AND e.crop = '" . $crop . "' ";
//  }
//  if ($model != "") {
//    $where = $where . " AND e.impact_models = '" . $model . "' ";
//  }
//  if ($climate != "") {
//    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
//  }
//  if ($baseline != "") {
//    $baselinearray[] = explode(" - ", $baseline);
//    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '".$baselinearray[0][1]."' ";
//  }
//  if ($period != "") {
//    $periodarray[] = explode(" - ", $period);
//    $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='". $periodarray[0][1] ."' ";
//  }
//  if ($scale != "") {
//    $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
//  }
//  $result = $wpdb->get_results("SELECT DISTINCT(e.region) "
//          . "FROM wp_estimate e "
//          . "INNER JOIN wp_article a ON e.article_id=a.id "
//          . "WHERE e.region LIKE'%".$subcontinents."%' "
//          . $where
//          . "ORDER BY e.region ASC ", ARRAY_A);

  $result = $wpdb->get_results("SELECT e.name "
          . "FROM wp_region e "
          . "INNER JOIN wp_continent a ON e.continent_id=a.id "
          . "WHERE e.name LIKE'%" . $region . "%' "
          . $where
          . "ORDER BY e.name ASC ", ARRAY_A);

  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['name'], "text" => $result[$i]['name']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function countryQuery() {
  global $wpdb;
  $answer[0] = array("id" => "", "text" => "");
  $country = $_REQUEST['country'];
  $region = $_REQUEST['region'];
  $continent = $_REQUEST['continent'];
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $period = $_REQUEST['period'];
  $scale = $_REQUEST['scale'];
  $subcontinents = $_REQUEST['subcontinents'];
  $where = "  ";

  if ($continent != "") {
    $where = $where . " AND a.name = '" . $continent . "' ";
  }
  if ($region != "") {
    $where = $where . " AND e.name = '" . $region . "' ";
  }
//  if ($crop != "") {
//    $where = $where . " AND e.crop = '" . $crop . "' ";
//  }
//  if ($model != "") {
//    $where = $where . " AND e.impact_models = '" . $model . "' ";
//  }
//  if ($climate != "") {
//    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
//  }
//  if ($baseline != "") {
//    $baselinearray[] = explode(" - ", $baseline);
//    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
//  }
//  if ($period != "") {
//    $periodarray[] = explode(" - ", $period);
//    $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
//  }
//  if ($scale != "") {
//    $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
//  }
//  if ($subcontinents != "") {
//    $where = $where . " AND e.region = '" . $subcontinents . "' ";
//  }

  $result = $wpdb->get_results("SELECT c.name "
          . "FROM wp_country c "
          . "INNER JOIN wp_region e ON c.region_id=e.id "
          . "INNER JOIN wp_continent a ON e.continent_id=a.id "
          . "WHERE c.name LIKE'%" . $country . "%' "
          . $where
          . "ORDER BY c.name ASC ", ARRAY_A);

  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $answer[] = array("id" => $result[$i]['name'], "text" => $result[$i]['name']);
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function adaptationQuery() {
  global $wpdb;
//  $answer[0] = array("id" => "", "text" => "");
  $adaptation = $_REQUEST['adaptation'];
  $crop = $_REQUEST['crop'];
  $model = $_REQUEST['model'];
  $climate = $_REQUEST['climate'];
  $baseline = $_REQUEST['baseline'];
  $period = $_REQUEST['period'];
  $scale = $_REQUEST['scale'];
  $country = $_REQUEST['country'];
  $subcontinents = $_REQUEST['subcontinents'];
  $where = "  ";
  $adptationDesc = array('CA'=>'Cultivar adaptation', 'FO'=>'Fertilizer optimization', 'TC'=>'TC', 'PDA'=>'Planting date adjustment', 'IO'=>'Irrigation optimization', 'PCA'=>'PCA');

  if ($crop != "") {
    $where = $where . " AND e.crop = '" . $crop . "' ";
  }
  if ($model != "") {
    $where = $where . " AND e.impact_models = '" . $model . "' ";
  }
  if ($climate != "") {
    $where = $where . " AND e.climate_scenario = '" . $climate . "' ";
  }
  if ($baseline != "") {
    $baselinearray[] = explode(" - ", $baseline);
    $where = $where . " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
  }
  if ($period != "") {
    $periodarray[] = explode(" - ", $period);
    $where = $where . " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
  }
  if ($scale != "") {
    $where = $where . " AND e.spatial_scale = '" . $scale . "' ";
  }
  if ($subcontinents != "") {
    $where = $where . " AND e.region = '" . $subcontinents . "' ";
  }
  if ($country != "") {
    $where = $where . " AND e.country = '" . $country . "' ";
  }
  $result = $wpdb->get_results("SELECT DISTINCT(e.adaptation) "
          . "FROM wp_estimate e "
          . "INNER JOIN wp_article a ON e.article_id=a.id "
          . "WHERE e.adaptation LIKE '%" . $adaptation . "%' "
          . $where
          . "ORDER BY e.adaptation ASC ", ARRAY_A);

  if (count($result) != 0) {
    $adaptOrg = array();
    for ($i = 0; $i < count($result); $i++) {
      $adapts = explode(',', $result[$i]['adaptation']);
      foreach ($adapts as $adapt) {
        $adaptOrg[trim($adapt)] = trim($adapt);
      }
      
    }
    foreach ($adaptOrg as $val) {
      $answer[] = array("id" => $val, "text" => ((isset($adptationDesc[$val]))? $adptationDesc[$val]: $val));
    }
  } else {
    $answer[] = array("id" => "0", "text" => "No Results Found..");
  }
  echo json_encode($answer);
}

function dataTable() {
  global $wpdb;


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

  $tr = "";
  $sql = "SELECT a.id,e.idEstimate,a.doi_article,e.spatial_scale,e.crop,e.impact_models,"
          . " CONCAT(e.base_line_start,' - ',e.base_line_end) as baseline,"
          . " CONCAT(e.projection_start,' - ',e.projection_end) as projection,"
          . " e.yield_change, CONCAT(e.region,' - ',e.country) as geograph_scope,"
          . " e.temp_change,e.climate_scenario "
          . " FROM wp_estimate e "
          . " INNER JOIN wp_article a ON e.article_id=a.id "
          . " WHERE 1 "
          . $where
          . " ORDER BY a.doi_article ";
  $result = $wpdb->get_results($sql, ARRAY_A);
//  echo $sql;
  $table = "<p>
	<div id='downloadFile'>
		<h3>Download Data</h3>
		<a href='#' onClick='downloadData()' title='Download Data for Excel'><img style='heigth:60px;width:60px;' src='" . get_template_directory_uri() . "/img/excel.png'></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='#' onClick='downloadDataCSV()'title='Download Data for CSV'><img style='heigth:60px;width:60px;' src='" . get_template_directory_uri() . "/img/csv.png'></a>
                <button class='pure-button pure-button-primary' type='button' name='viewall' id='viewall' onClick='viewAllFields()'>View all fields</button>
                <button class='pure-button pure-button-primary' type='button' name='viewall' id='viewall' onClick='viewAllFieldsh()'>View all fields header</button>
	</div>

	<table id='resulttable' name='resulttable' class='table table-striped table-bordered'>
	<thead>
		<tr>
			<th>DOI <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Spatial Scale <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Crop <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Multi-Model <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Baseline Period <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Projection Period <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Percentage Yield Change <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Geographical Scope <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Local Mean Temperature Change <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Climate Scenario <!--<input type=\"checkbox\" name='columns' value='doi'>--></th>
			<th>Validate</th>
		</tr>
	</thead>
	<tbody>";
  if (count($result) != 0) {
    for ($i = 0; $i < count($result); $i++) {
      $status = ($result[$i]['doi_article'] == 0) ? 'new' : 'Validated';
      $tr = $tr . "<tr>
                    <td>" . $result[$i]['doi_article'] . "</td>
                    <td>" . $result[$i]['spatial_scale'] . "</td>
                    <td>" . $result[$i]['crop'] . "</td>
                    <td>" . $result[$i]['impact_models'] . "</td>
                    <td>" . $result[$i]['baseline'] . "</td>
                    <td>" . $result[$i]['projection'] . "</td>
                    <td>" . $result[$i]['yield_change'] . "</td>
                    <td>" . $result[$i]['geograph_scope'] . "</td>
                    <td>" . $result[$i]['temp_change'] . "</td>
                    <td>" . $result[$i]['climate_scenario'] . "</td>
                    <td>" . $status . "</td>
            </tr>";
    }
    echo $table . $tr . "</tbody>
                </table>";
  } else {

    echo $tr = "<div style='text-align:center;'><h3>No data Found</h3></div>";
  }
}
?>