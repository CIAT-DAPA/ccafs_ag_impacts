<?php

require('../../../wp-load.php');
global $wpdb;
// This needs to be set, in order for the script work, 
//in the case when the request variable is empty, throws 
//an e_notice of the empty array
//error_reporting(0);


require_once ("/lib/PHPExcel/PHPExcel.php");
//    require_once("conexion/conexion.php");
//    $conexion = new mysqli('localhost','root','','agimpacts',3306);
//    if (mysqli_connect_errno()) {
//        printf("The conexion to the server failed: %s\n", mysqli_connect_error());
//    exit();
//    }
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


$result = "SELECT a.*,e.*,"
        . " CONCAT(e.base_line_start,' - ',e.base_line_end) as baseline,"
        . " CONCAT(e.projection_start,' - ',e.projection_end) as projection,"
        . " CONCAT(e.region,' - ',e.country) as geograph_scope "
        . " FROM wp_estimate e "
        . " INNER JOIN wp_article a ON e.article_id=a.id "
        . " WHERE 1 "
        . $where
        . " ORDER BY a.doi_article ";
//echo $result; exit();
$dataResult = $wpdb->get_results($result, ARRAY_A);
if (count($dataResult)) {
  // Create the PHPExcel Object
  $objPHPExcel = new PHPExcel();

  // Assign the book properties
  $objPHPExcel->getProperties()->setCreator("CCAFS CGIAR - University of Leeds") //Autor
          ->setLastModifiedBy("CCAFS CGIAR - University of Leeds")
          ->setTitle("Crop_Estimate")
          ->setSubject("Crop_Estimate")
          ->setDescription("Crop_Estimate")
          ->setKeywords("Crop Estimate DOI")
          ->setCategory("Crop_Estimate");

  $titleReport = "Crops Estimate";
  $titleColumns = array('DOI', 'Author', 'Year', 'Journal', 'Volume', 'Issue', 'Start page', 'End page', 'Reference', 'Title', 'Crop','Scientific name',  'CO2 Projected', 'CO2 Baseline', 'Temp Change', 'Precipitation change', 'Yield Change', 'Projected yield change start', 'Projected yield change end', 'Adaptation', 'Climate scenario', '# GCM used', 'GCM(s)', '# Impact model used', 'Impact model(s)', 'Baseline start', 'Baseline end', 'Projection start', 'Projection end', 'Continent', 'Region', 'Country', 'State', 'City', 'Latitude', 'Longitude', 'Spatial scale', 'Comments', 'Contributor');

  $objPHPExcel->setActiveSheetIndex(0)
          ->mergeCells('A1:AM1');

  // Add the titles
  $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A1', $titleReport)
          ->setCellValue('A3', $titleColumns[0])
          ->setCellValue('B3', $titleColumns[1])
          ->setCellValue('C3', $titleColumns[2])
          ->setCellValue('D3', $titleColumns[3])
          ->setCellValue('E3', $titleColumns[4])
          ->setCellValue('F3', $titleColumns[5])
          ->setCellValue('G3', $titleColumns[6])
          ->setCellValue('H3', $titleColumns[7])
          ->setCellValue('I3', $titleColumns[8])
          ->setCellValue('J3', $titleColumns[9])
          ->setCellValue('K3', $titleColumns[10])
          ->setCellValue('L3', $titleColumns[11])
          ->setCellValue('M3', $titleColumns[12])
          ->setCellValue('N3', $titleColumns[13])
          ->setCellValue('O3', $titleColumns[14])
          ->setCellValue('P3', $titleColumns[15])
          ->setCellValue('Q3', $titleColumns[16])
          ->setCellValue('R3', $titleColumns[17])
          ->setCellValue('S3', $titleColumns[18])
          ->setCellValue('T3', $titleColumns[19])
          ->setCellValue('U3', $titleColumns[20])
          ->setCellValue('V3', $titleColumns[21])
          ->setCellValue('W3', $titleColumns[22])
          ->setCellValue('X3', $titleColumns[23])
          ->setCellValue('Y3', $titleColumns[24])
          ->setCellValue('Z3', $titleColumns[25])
          ->setCellValue('AA3', $titleColumns[26])
          ->setCellValue('AB3', $titleColumns[27])
          ->setCellValue('AC3', $titleColumns[28])
          ->setCellValue('AD3', $titleColumns[29])
          ->setCellValue('AE3', $titleColumns[30])
          ->setCellValue('AF3', $titleColumns[31])
          ->setCellValue('AG3', $titleColumns[32])
          ->setCellValue('AH3', $titleColumns[33])
          ->setCellValue('AI3', $titleColumns[34])
          ->setCellValue('AJ3', $titleColumns[35])
          ->setCellValue('AK3', $titleColumns[36])
          ->setCellValue('AL3', $titleColumns[37])
          ->setCellValue('AM3', $titleColumns[38]);

  //Then add the data
  $i = 4;
  foreach ($dataResult as $row) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $row['doi_article'])
            ->setCellValue('B' . $i, $row['author'])
            ->setCellValue('C' . $i, $row['year'])
            ->setCellValue('D' . $i, $row['journal'])
            ->setCellValue('E' . $i, $row['volume'])
            ->setCellValue('F' . $i, $row['issue'])
            ->setCellValue('G' . $i, $row['page_start'])
            ->setCellValue('H' . $i, $row['page_end'])
            ->setCellValue('I' . $i, $row['reference'])
            ->setCellValue('J' . $i, $row['paper_title'])
            ->setCellValue('K' . $i, $row['crop'])
            ->setCellValue('L' . $i, $row['scientific_name'])
            ->setCellValue('M' . $i, $row['projection_co2'])
            ->setCellValue('N' . $i, $row['baseline_co2'])
            ->setCellValue('O' . $i, $row['temp_change'])
            ->setCellValue('P' . $i, $row['precipitation_change'])
            ->setCellValue('Q' . $i, $row['yield_change'])
            ->setCellValue('R' . $i, $row['projec_yield_change_start'])
            ->setCellValue('S' . $i, $row['project_yield_change_end'])
            ->setCellValue('T' . $i, $row['adaptation'])
            ->setCellValue('U' . $i, $row['climate_scenario'])
            ->setCellValue('V' . $i, $row['num_gcm_used'])
            ->setCellValue('W' . $i, $row['gcm'])
            ->setCellValue('X' . $i, $row['num_impact_model_used'])
            ->setCellValue('Y' . $i, $row['impact_models'])
            ->setCellValue('Z' . $i, $row['base_line_start'])
            ->setCellValue('AA' . $i, $row['base_line_end'])
            ->setCellValue('AB' . $i, $row['projection_start'])
            ->setCellValue('AC' . $i, $row['projection_end'])
            ->setCellValue('AD' . $i, $row['geo_scope'])
            ->setCellValue('AE' . $i, $row['region'])
            ->setCellValue('AF' . $i, $row['country'])
            ->setCellValue('AG' . $i, $row['state'])
            ->setCellValue('AH' . $i, $row['city'])
            ->setCellValue('AI' . $i, $row['latitude'])
            ->setCellValue('AJ' . $i, $row['longitude'])
            ->setCellValue('AK' . $i, $row['spatial_scale'])
            ->setCellValue('AL' . $i, $row['comments'])
            ->setCellValue('AM' . $i, $row['contributor']);
    $i++;
  }

  $titleStyle = array(
    'font' => array(
      'name' => 'Verdana',
      'bold' => true,
      'italic' => false,
      'strike' => false,
      'size' => 11,
      'color' => array(
        'rgb' => '000000'
      )
    ),
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('argb' => '416725')
    ),
    'borders' => array(
      'allborders' => array(
        'style' => PHPExcel_Style_Border::BORDER_THIN
      )
    ),
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      'rotation' => 0,
      'wrap' => TRUE
    )
  );

  $columnStyle = array(
    'font' => array(
      'name' => 'Arial',
      'bold' => true,
      'color' => array(
        'rgb' => '000000'
      )
    ),
    'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
      'rotation' => 90,
      'startcolor' => array(
        'rgb' => 'FFFFFF'
      ),
      'endcolor' => array(
        'argb' => 'FFFFFF'
      )
    ),
    'borders' => array(
      'top' => array(
        'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        'color' => array(
          'rgb' => '416725'
        )
      ),
      'bottom' => array(
        'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
        'color' => array(
          'rgb' => '416725'
        )
      )
    ),
    'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      'wrap' => TRUE
    )
  );

  $dataStyle = new PHPExcel_Style();
  $dataStyle->applyFromArray(
          array(
            'font' => array(
              'name' => 'Arial',
              'color' => array(
                'rgb' => '000000'
              )
            ),
            'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('argb' => 'FFFFFF')
            ),
            'borders' => array(
              'left' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                  'rgb' => '3a2a47'
                )
              )
            )
          )
  );

  $objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->applyFromArray($titleStyle);
  $objPHPExcel->getActiveSheet()->getStyle('A3:AM3')->applyFromArray($columnStyle);
  $objPHPExcel->getActiveSheet()->setSharedStyle($dataStyle, "A4:AM" . ($i - 1));

  for ($i = 'A'; $i <= 'AM'; $i++) {
    $objPHPExcel->setActiveSheetIndex(0)
            ->getColumnDimension($i)->setAutoSize(TRUE);
  }

  // Name of the Sheet
  $objPHPExcel->getActiveSheet()->setTitle('Estimate');

  // Activate the Sheet, to show when the file opens.
  $objPHPExcel->setActiveSheetIndex(0);
  // Inmovilize panels 
  $objPHPExcel->getActiveSheet(0)->freezePane('A4');
  $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0, 4);

  // Send the file to the browser, with the desire name(Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Crop_Estimate.xlsx"');
  header('Cache-Control: max-age=0');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');
  exit;
} else {
  echo "<script language='javascript'>alert('No Data Found');</script>";
}
?>