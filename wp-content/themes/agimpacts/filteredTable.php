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
//get_header('embed');
global $wpdb;
$type = $_REQUEST['type'];
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

if ($crop) {
  $where .= " AND e.crop = '" . $crop . "' ";
}
if ($model) {
  $where .= " AND e.impact_models = '" . $model . "' ";
}
if ($climate) {
  $where .= " AND e.climate_scenario = '" . $climate . "' ";
}
if ($baseline) {
  $baselinearray[] = explode(" - ", $baseline);
  $where .= " AND e.base_line_start = '" . $baselinearray[0][0] . "' AND e.base_line_end = '" . $baselinearray[0][1] . "' ";
}
if ($period) {
  $periodarray[] = explode(" - ", $period);
  $where .= " AND e.projection_start = '" . $periodarray[0][0] . "' AND e.projection_end='" . $periodarray[0][1] . "' ";
}
if ($scale) {
  $where .= " AND e.spatial_scale = '" . $scale . "' ";
}
if ($subcontinents) {
  $where .= " AND e.region = '" . $subcontinents . "' ";
}
if ($country) {
  $where .= " AND e.country = '" . $country . "' ";
}
if ($adaptation) {
  $adaptation = explode(',', $adaptation);
  $where .= " AND (";
  foreach ($adaptation as $val) {
    $where .= " e.adaptation LIKE '%" . trim($val) . "%' OR";
  }
  $where = substr($where, 0, -2);
  $where .= ") ";
}

if ($type != '') {
  $where .= " AND e.country <> '' AND e.country <> 'N/A' ";
} else {
  $where .= " AND e.latitude NOT IN ('','N/A') AND e.longitude NOT IN ('','N/A') ";
}

$sql1 = "SELECT e.latitude, e.longitude, e.idEstimate, "
        . " e.crop ,"
        . " a.doi_article,"
        . " a.paper_title, "
        . " e.country, "
        . " e.yield_change, "
        . " e.temp_change, "
        . " c.iso_id "
        . " FROM wp_estimate e "
        . " INNER JOIN wp_article a ON e.article_id=a.id "
        . " LEFT JOIN wp_country c ON c.name like CONCAT('%',e.country,'%') "
        . " WHERE TRUE "
        . $where
        . " ORDER BY a.doi_article $limit";
//  echo $sql1;
$result = $wpdb->get_results($sql1, ARRAY_A);
if (count($result) != 0) {
  if ($type == 'geochart') {
//    echo "[";
    $output = array();
    $output[0][] = 'Country';
    $output[0][] = 'DY';
//      echo "['Country', 'DY'],";
    for ($i = 0; $i < count($result); $i++) {
      if ($result[$i]['country'] != '' && is_numeric($result[$i]['yield_change'])) {
        $output[$i + 1][] = $result[$i]['country'];
        $output[$i + 1][] = floatval($result[$i]['yield_change']);
//          echo (array) "['".$result[$i]['country']."','".$result[$i]['yield_change']."'],";
      }
    }
    echo json_encode($output);
//    echo "]";
  } else if ($type == 'highmap') {
//    echo "[";
    $stadData = array();
    for ($i = 0; $i < count($result); $i++) {
      if ($result[$i]['iso_id'] != '' && is_numeric($result[$i]['yield_change'])) {
//        $stadData[$result[$i]['iso_id']]['code'] = $result[$i]['iso_id'];
        $stadData[$result[$i]['iso_id']][] = floatval($result[$i]['yield_change']);
//          echo (array) "['".$result[$i]['country']."','".$result[$i]['yield_change']."'],";
      }
    }
    $output = array();
    $i = 0;
    foreach ($stadData as $key => $values) {
      $output[0][$i]['code'] = $key;
      $output[0][$i]['value'] = floatval(calculateMedian($values));
      $output[0][$i]['median'] = floatval(calculateMedian($values));
      $output[0][$i]['mean'] = floatval(mean($values));
      $output[0][$i]['dev'] = floatval(stdDev($values));
      $output[0][$i]['min'] = floatval(min($values));
      $output[0][$i]['max'] = floatval(max($values));
      $output[0][$i]['num'] = floatval(count($values));

      $output[1][$i]['code'] = $key;
      $output[1][$i]['value'] = floatval(mean($values));
      $output[1][$i]['median'] = floatval(calculateMedian($values));
      $output[1][$i]['mean'] = floatval(mean($values));
      $output[1][$i]['dev'] = floatval(stdDev($values));
      $output[1][$i]['min'] = floatval(min($values));
      $output[1][$i]['max'] = floatval(max($values));
      $output[1][$i]['num'] = floatval(count($values));
      $i++;
    }
    echo json_encode($output);
  } else if ($type == 'columnChart') {
    if ($crop) {
      $stadData = array();
      for ($i = 0; $i < count($result); $i++) {
        if ($result[$i]['country'] != '' && is_numeric($result[$i]['yield_change'])) {
          $stadData[$result[$i]['country']][] = floatval($result[$i]['yield_change']);
        }
      }

      uasort($stadData, function ($a, $b) {
        return (count($b) - count($a));
      });

      $output = array();
      $i = 0;
      foreach ($stadData as $key => $values) {
        $output[0][$i][] = $key;
        $output[0][$i][] = floatval(calculateMedian($values));
//        $output[0][$i][] = count($values);
        $output[1][] = array(nearestRank($values, 5), nearestRank($values, 95));
        if ($i == 9)
          break;
        $i++;
      }
      echo json_encode($output);
    } else {
      echo "null";
    }
  } else if ($type == 'scatterChart') {
    $stadData = array();
    $output = array();
    for ($i = 0; $i < count($result); $i++) {
      if (is_numeric($result[$i]['temp_change']) && is_numeric($result[$i]['yield_change'])) {
        $stadData[$result[$i]['temp_change']][] = floatval($result[$i]['yield_change']);
        $output[0][] = array(floatval($result[$i]['temp_change']), floatval($result[$i]['yield_change']));
      }
    }
//    echo "<pre>".print_r($stadData,true)."</pre>";
    if (count($output[0]) < 25 || count($stadData) < 4) {
      echo 'null';
    } else {
      $i = 1;
      $amean = 0;
      ksort($stadData);
      foreach ($stadData as $key => $values) {
        $mean = (mean($values) + $amean) / $i;
        $output[1][] = array(floatval($key), $mean);
        $amean += $mean;
        $i++;
      }
      echo json_encode($output);
    }
  } else {
    echo 'eqfeed_callback({ "type": "FeatureCollection",
          "features": [';
    for ($i = 0; $i < count($result); $i++) {
      if (is_numeric($result[$i]['latitude']) && is_numeric($result[$i]['longitude'])) {
        echo '
             { "type": "Feature",
              "id": "' . $result[$i]['idEstimate'] . '",
              "geometry": {"type": "Point", "coordinates": [' . $result[$i]['latitude'] . ', ' . $result[$i]['longitude'] . ']},
              "properties": {
                 '
        . '"crop":"' . $result[$i]['crop'] . '", '
        . '"doi":"' . $result[$i]['doi_article'] . '"'
        . '}
             }, 
            ';
      }
    }
    echo ']
     });';
  }
} else {
  echo "null";
}
