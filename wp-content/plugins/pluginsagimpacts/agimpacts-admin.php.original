<?php
/*
 * Plugin Name: AgImpacts - Paginas administrativas
 * Description: Plugin para manejo de propiedades de ag-impacts
 * Author: Camilo Rodriguez
 * Version: 1.0
 */

add_action('admin_menu', 'OpcionMenuMisOpciones');

function OpcionMenuMisOpciones() {
  add_menu_page('Opciones generales del sitio', 'ag-impacts options', 'manage_options', 'pc-admin.php', 'AdminOpcionesGenerales');

  add_submenu_page('pc-admin.php', 'Importador CSV', 'Importador', 'ag_options', 'pc-admin.php-importador', 'AdminImporterCSV');
}

register_activation_hook(__FILE__, 'pcadminActive');

function pcadminActive() {
  global $wpdb;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  
  $tablename = $wpdb->prefix . 'article';

  $sql = "CREATE TABLE $tablename (
		`id` INT NOT NULL AUTO_INCREMENT,
                `doi_article` VARCHAR(45) NOT NULL,
                `author` VARCHAR(45) NULL,
                `year` INT NULL,
                `journal` VARCHAR(45) NULL,
                `volume` VARCHAR(45) NULL,
                `issue` VARCHAR(45) NULL,
                `page_start` SMALLINT NULL,
                `page_end` SMALLINT NULL,
                `reference` TEXT NULL,
                `paper_title` TEXT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `doi_article_UNIQUE` (`doi_article` ASC)
	);";

	dbDelta($sql);
  
  $tablename = $wpdb->prefix . 'estimate';

  $sql = "CREATE TABLE $tablename (
                `idEstimate` INT NOT NULL AUTO_INCREMENT,
                `article_id` INT NOT NULL,
                `crop` VARCHAR(255) NULL,
                `scientific_name` VARCHAR(255) NULL,
                `projection_co2` DOUBLE NULL,
                `baseline_co2` DOUBLE NULL,
                `temp_change` DOUBLE NULL,
                `precipitation_change` DOUBLE NULL,
                `yield_change` DOUBLE NULL,
                `projec_yield_change_start` DOUBLE NULL,
                `project_yield_change_end` DOUBLE NULL,
                `adaptation` VARCHAR(45) NULL,
                `climate_scenario` VARCHAR(45) NULL,
                `num_gcm_used` INT NULL,
                `gcm` VARCHAR(255) NULL,
                `num_impact_model_used` INT NULL,
                `impact_models` VARCHAR(255) NULL,
                `base_line_start` INT NULL,
                `base_line_end` INT NULL,
                `projection_start` INT NULL,
                `projection_end` INT NULL,
                `geo_scope` VARCHAR(255) NULL,
                `region` VARCHAR(255) NULL,
                `country` VARCHAR(45) NULL,
                `state` VARCHAR(255) NULL,
                `city` VARCHAR(255) NULL,
                `latitude` VARCHAR(45) NULL,
                `longitude` VARCHAR(45) NULL,
                `spatial_scale` VARCHAR(45) NULL,
                `comments` TEXT NULL,
                `contributor` VARCHAR(255) NULL,
                `wp_users_ID` INT NOT NULL,
                `status` VARCHAR(45) NULL,
                `multi_model` VARCHAR(45) NULL,
                PRIMARY KEY (`idEstimate`),
                INDEX `fk_Estimate_article_idx` (`article_id` ASC),
                INDEX `fk_Estimate_wp_users1_idx` (`wp_users_ID` ASC)
	);";

	dbDelta($sql);
}

function insertCotizacion($cotizacion) {
  global $wpdb;

  $tablename = $wpdb->prefix . 'cotizaciones';

  $rows_affected = $wpdb->insert($tablename, $cotizacion);

  return $rows_affected;
}

function consultarCotizaciones() {
  global $wpdb;

  $tablename = $wpdb->prefix . 'cotizaciones';

  $results = $wpdb->get_results("SELECT * FROM " . $tablename . " ORDER BY fecharegistro DESC");

  return $results;
}

function getPopularProducts($limit) {
  global $wpdb;

  $tablename = $wpdb->prefix . 'cotizaciones';

  $results = $wpdb->get_results("SELECT SUM(cantidad) AS sumcant,productoid FROM " . $tablename . " GROUP BY productoid ORDER BY sumcant DESC LIMIT " . $limit);

  return $results;
}

function AdminImporterCSV() {
//	$cots = consultarCotizaciones();
  $files = array();
  ?>
  <h2>Select the file with the data to import</h2>
  <form action='' method='POST' enctype='multipart/form-data'>
    <input id="infile" name="infile[]" type="file"  multiple="true"><br>
    <input type='submit' name='upload_btn' value='upload'>
  </form>

  <?php
  echo "No. files uploaded : " . count($_FILES['infile']['name']) . "<br>";
  $uploadDir = dirname(__FILE__) . "/tmp/";
//  $uploadDir = "tmp/";
  for ($i = 0; $i < count($_FILES['infile']['name']); $i++) {

    echo "File names : " . $_FILES['infile']['name'][$i] . "<br>";
    $ext = substr(strrchr($_FILES['infile']['name'][$i], "."), 1);

    // generate a random new file name to avoid name conflict
    $fPath = "dataFile" . $i . ".$ext";
    $files[] = $uploadDir . $fPath;
//    $fPath = $_FILES['infile']['tmp_name'][$i];
    echo "File paths : " . $_FILES['infile']['tmp_name'][$i] . "<br>";
    $result = move_uploaded_file($_FILES['infile']['tmp_name'][$i], $uploadDir . $fPath);

    if (strlen($ext) > 0) {
      echo "Uploaded " . $fPath . " succefully. <br>";
    }
//    echo "Upload complete.<br>";
  }
  if (count($files)) {
    validData($files);
  }
}

/**
 * function that valid the data file for ag-impacts data base schema
 * @param type $files array of files updated
 */
function validData($files) {
  global $wpdb;
  $validArticles = array();
  foreach ($files as $file) {
    $fh = fopen($file, 'r');
    $nl = 1;
    //jum first line of file
    fgetcsv($fh, 3000,",");
    while ($line = fgetcsv($fh, 3000,",")) {
      if (count($line) != 41) {
        echo "The line ".$nl." doesn't have the enought fields to be procesed, current line have ".count($line)." fields<br>";        
        break;
      } else {
//        echo var_dump($line);
        if (!isset($validArticles[$line[1]]) && trim($line[1]) != '' && trim($line[1]) != 'N/A') {
          $tablename = $wpdb->prefix . 'article';
          $doi_article=$wpdb->get_col( "SELECT ID FROM ".$tablename." WHERE doi_article = '".trim($line[1])."'");
          if (empty($doi_article)) {
            $tablename = $wpdb->prefix . 'article';
            $rows_affected = $wpdb->insert($tablename, createDataArticleArray($line));
//            echo "1 - ".$doi_article."<br>";
            if ($rows_affected) {
              $validArticles[$line[1]] = $wpdb->insert_id;
            }
          } else {
            $validArticles[$line[1]] = $doi_article[0];
//            echo "2";
          }
        }
        if (isset($validArticles[$line[1]])) {
          $tablename = $wpdb->prefix . 'estimate';
          $rows_affected = $wpdb->insert($tablename, createDataEstimateArray($validArticles[$line[1]], $line));
        }
      }
      
//       echo $line. "<br>";
      $nl++;
    }
    echo "<pre>CCC#".print_r($validArticles,true)."</pre>";
    fclose($fh);
  }
}

function createDataArticleArray($line) {
  $article = array();
  $article['doi_article'] = $line[1];
  $article['author'] = $line[40];
  $article['year'] = $line[8];
  $article['journal'] = $line[3];
  $article['volume'] = $line[4];
  $article['issue'] = $line[5];
  $article['page_start'] = $line[6];
  $article['page_end'] = $line[7];
  $article['reference'] = $line[0];
  $article['paper_title'] = $line[2];
  return $article;
}

function createDataEstimateArray($articleID,$line) {
  $estimate = array();
  $estimate['article_id'] = $articleID;
  $estimate['crop'] = $line[9];
  $estimate['scientific_name'] = $line[10];
  $estimate['projection_co2'] = (trim($line[11]) != '' && $line[11]!= 'N/A')?$line[11]:0;
  $estimate['baseline_co2'] = (trim($line[12]) != '' && $line[12]!= 'N/A')?$line[12]:0;
  $estimate['temp_change'] =(trim($line[13]) != '' && $line[13]!= 'N/A')?$line[13]:0;
  $estimate['precipitation_change'] = ($line[14]!= '' && $line[14]!= 'N/A')?$line[14]:0;
  $estimate['yield_change'] = ($line[15]!= '' && $line[15]!= 'N/A')?$line[15]:0;
  $estimate['projec_yield_change_start'] = ($line[17]!= '' && $line[17]!= 'N/A')?$line[17]:0;
  $estimate['project_yield_change_end'] = ($line[18]!= '' && $line[18]!= 'N/A')?$line[18]:0;
  $estimate['adaptation'] = $line[19];
  $estimate['climate_scenario'] = $line[20];
  $estimate['num_gcm_used'] = ($line[21]!= '' && $line[21]!= 'N/A')?$line[21]:0;
  $estimate['gcm'] = $line[22];
  $estimate['num_impact_model_used'] = ($line[23]!= '' && $line[23]!= 'N/A')?$line[23]:0;
  $estimate['impact_models'] = $line[24];
  $estimate['base_line_start'] = ($line[26]!= '' && $line[26]!= 'N/A')?$line[26]:0;
  $estimate['base_line_end'] = ($line[27]!= '' && $line[27]!= 'N/A')?$line[27]:0;
  $estimate['projection_start'] = ($line[29]!= '' && $line[29]!= 'N/A')?$line[29]:0;
  $estimate['projection_end'] = (trim($line[30]) != '' && $line[30]!= 'N/A')?$line[30]:0;
  $estimate['geo_scope'] = $line[31];
  $estimate['region'] = $line[32];
  $estimate['country'] = $line[33];
  $estimate['state'] = $line[34];
  $estimate['city'] = $line[35];
  $estimate['latitude'] = $line[36];
  $estimate['longitude'] = $line[37];
  $estimate['spatial_scale'] = $line[38];
  $estimate['comments'] = $line[39];
  $estimate['contributor'] = $line[40];
  $estimate['wp_users_ID'] = get_current_user_id();
  $estimate['status'] = '1';
//  echo "<pre>".print_r($estimate,true)."</pre>";
  return $estimate;
}

function AdminOpcionesGenerales() {
  if (!current_user_can('manage_options'))
    wp_die(__('No tiene suficientes permisos'));

  wp_enqueue_style('pcadminstyles', plugins_url('css/pcadmin.css', __FILE__));

  if ($_POST) {
    if (isset($_POST['op_tel'])) {
      update_option('op_tel', $_POST['op_tel']);
    }

    if (isset($_POST['op_dir'])) {
      update_option('op_dir', $_POST['op_dir']);
    }

    if (isset($_POST['op_email'])) {
      update_option('op_email', $_POST['op_email']);
    }
  }
  ?>
  <h2>Opciones generales</h2>
  <form class="formopciones" method="post">
    <label>Telefono: </label><input type="text" name="op_tel" value="<?php echo get_option('op_tel'); ?>">
    <label>Direccion: </label><input type="text" name="op_dir" value="<?php echo get_option('op_dir'); ?>">
    <label>Correo de contacto: </label><input type="text" name="op_email" value="<?php echo get_option('op_email'); ?>">
    <input type="submit" value="Guardar">
  </form>
  <?php
}
