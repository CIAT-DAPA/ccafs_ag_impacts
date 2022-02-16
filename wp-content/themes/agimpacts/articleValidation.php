<?php
/**
 * Template Name: Article validation
 * @package WordPress
 * @subpackage ag-impacts
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

get_header();
?>
<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/jqueryui/dataTables.jqueryui.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<?php
global $wpdb;
$article = $_REQUEST['article'];

$result = "SELECT e.* "
//        . " CONCAT(a.base_line_start,' - ',a.base_line_end) as baseline,"
//        . " CONCAT(a.projection_start,' - ',a.projection_end) as projection,"
//        . " CONCAT(a.region,' - ',a.country) as geograph_scope "
        . " FROM wp_article e "
//        . " INNER JOIN wp_estimate a ON (a.article_id=e.id) "
        . " WHERE e.id = " . $article
        . " ORDER BY e.id ";
//echo $result; exit();
$dataResult = $wpdb->get_results($result, ARRAY_A);

$result = "SELECT e.* "
//        . " CONCAT(a.base_line_start,' - ',a.base_line_end) as baseline,"
//        . " CONCAT(a.projection_start,' - ',a.projection_end) as projection,"
//        . " CONCAT(a.region,' - ',a.country) as geograph_scope "
        . " FROM wp_estimate e "
//        . " INNER JOIN wp_estimate a ON (a.article_id=e.id) "
        . " WHERE e.article_id = " . $article
        . " ORDER BY e.idEstimate ";
//echo $result; exit();
$dataResultEstimate = $wpdb->get_results($result, ARRAY_A);
?>
<div id="loading" style="display: block; z-index: 999"><img style="" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="Loader" /></div>
<section id='content' class='row' style="display: block">
  <form id="validateForm" action="" method="POST">
    <div style="position: relative">
      <div style="position: absolute; right: 5px">
        <button class="pure-button pure-button-primary" type="button" id="valid">Save and mark as validated</button>
        <!--<button class="pure-button pure-button-primary" type="button" id="valid" onclick="saveValidation($('input[type=\'text\']'),<?php // echo $article  ?>)">Valid</button>-->
        <button class="pure-button pure-button-primary" type="button" onclick="$(location).attr('href', templateUrl + '/article');">Back</button>
      </div>
      <br>
      <br>
    </div>
    <?php
    if (count($dataResult)) :
      ?>
      <h3>Article Data</h3>
      <table id='table1' name='table1' class='table demo2 floatThead-table'>
        <thead>
          <tr>
            <th>DOI</th>
            <th>Author</th>
            <th>Year</th>
            <th>Journal</th>
            <th>Volume</th>
            <th>Issue</th>
            <th>Start Page</th>
            <th>End Page</th>
            <th>Reference</th>
            <th>Title</th>
          </tr>
        </thead>
        <tbody>

          <?php
          for ($i = 0; $i < count($dataResult); $i++):
            $status = ($dataResult[$i]['doi_article'] == 0) ? 'new' : 'Validated';
            ?>
            <tr>
              <td><span style='display:none'><?php echo $dataResult[$i]['doi_article'] ?></span><input type='text' name='article[doi_article]' id='doi_article' value='<?php echo $dataResult[$i]['doi_article'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['author'] ?></span><input type='text' name='article[author]' id='author' value='<?php echo $dataResult[$i]['author'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['year'] ?></span><input type='text' name='article[year]' id='year' value='<?php echo $dataResult[$i]['year'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['journal'] ?></span><input type='text' name='article[journal]' id='journal' value='<?php echo $dataResult[$i]['journal'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['volume'] ?></span><input type='text' name='article[volume]' id='volume' value='<?php echo $dataResult[$i]['volume'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['issue'] ?></span><input type='text' name='article[issue]' id='issue' value='<?php echo $dataResult[$i]['issue'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['page_start'] ?></span><input type='text' name='article[page_start]' id='page_start' value='<?php echo $dataResult[$i]['page_start'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['page_end'] ?></span><input type='text' name='article[page_end]' id='page_end' value='<?php echo $dataResult[$i]['page_end'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['reference'] ?></span><input type='text' name='article[reference]' id='reference' value='<?php echo $dataResult[$i]['reference'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResult[$i]['paper_title'] ?></span><input type='text' name='article[paper_title]' id='paper_title' value='<?php echo $dataResult[$i]['paper_title'] ?>'/></td>
            </tr>
            <?php
          endfor;
          ?>

        </tbody>
      </table>
      <script language='javascript'>

        $(document).ready(function() {
          $('#table1').DataTable({
            'scrollX': true,
            'jQueryUI': true,
            paging: false,
            "searching": false,
            "info": false
          });

        });
      </script>
      <!--<script language='javascript'>$('#table1').tablesorter({theme: 'green'});</script>-->
    <?php else: ?>
      <script language='javascript'>alert('No Data Found');</script>
    <?php
    endif;

    if (count($dataResultEstimate)) :
      ?>
      <br>
      <h3>Estimate Data</h3>
      <table id='table2' name='table2' class='table'>
        <thead>
          <tr>
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
            <th>Spatial Scale</th>
            <th>Comments</th>
            <th>Contributor</th>
          </tr>
        </thead>
        <tbody>

          <?php
          for ($i = 0; $i < count($dataResultEstimate); $i++):
            $status = ($dataResultEstimate[$i]['doi_article'] == 0) ? 'new' : 'Validated';
            ?>
            <tr>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['crop'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][crop]' id='crop' value='<?php echo $dataResultEstimate[$i]['crop'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['scientific_name'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][scientific_name]' id='scientific_name' value='<?php echo $dataResultEstimate[$i]['scientific_name'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['projection_co2'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][projection_co2]' id='projection_co2' value='<?php echo $dataResultEstimate[$i]['projection_co2'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['baseline_co2'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][baseline_co2]' id='baseline_co2' value='<?php echo $dataResultEstimate[$i]['baseline_co2'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['temp_change'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][temp_change]' id='temp_change' value='<?php echo $dataResultEstimate[$i]['temp_change'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['precipitation_change'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][precipitation_change]' id='precipitation_change' value='<?php echo $dataResultEstimate[$i]['precipitation_change'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['yield_change'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][yield_change]' id='yield_change' value='<?php echo $dataResultEstimate[$i]['yield_change'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['projec_yield_change_start'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][projec_yield_change_start]' id='projec_yield_change_start' value='<?php echo $dataResultEstimate[$i]['projec_yield_change_start'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['project_yield_change_end'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][project_yield_change_end]' id='projec_yield_change_end' value='<?php echo $dataResultEstimate[$i]['project_yield_change_end'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['adaptation'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][adaptation]' id='adaptation' value='<?php echo $dataResultEstimate[$i]['adaptation'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['climate_scenario'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][climate_scenario]' id='climate_scenario' value='<?php echo $dataResultEstimate[$i]['climate_scenario'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['num_gcm_used'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][num_gcm_used]' id='num_gcm_used' value='<?php echo $dataResultEstimate[$i]['num_gcm_used'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['gcm'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][gcm]' id='gcm' value='<?php echo $dataResultEstimate[$i]['gcm'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['num_impact_model_used'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][num_impact_model_used]' id='num_impact_model_used' value='<?php echo $dataResultEstimate[$i]['num_impact_model_used'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['impact_models'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][impact_models]' id='impact_models' value='<?php echo $dataResultEstimate[$i]['impact_models'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['base_line_start'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][base_line_start]' id='base_line_start' value='<?php echo $dataResultEstimate[$i]['base_line_start'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['base_line_end'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][base_line_end]' id='base_line_end' value='<?php echo $dataResultEstimate[$i]['base_line_end'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['projection_start'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][projection_start]' id='projection_start' value='<?php echo $dataResultEstimate[$i]['projection_start'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['projection_end'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][projection_end]' id='projection_end' value='<?php echo $dataResultEstimate[$i]['projection_end'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['geo_scope'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][geo_scope]' id='geo_scope' value='<?php echo $dataResultEstimate[$i]['geo_scope'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['region'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][region]' id='region' value='<?php echo $dataResultEstimate[$i]['region'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['country'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][country]' id='country' value='<?php echo $dataResultEstimate[$i]['country'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['state'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][state]' id='state' value='<?php echo $dataResultEstimate[$i]['state'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['city'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][city]' id='city' value='<?php echo $dataResultEstimate[$i]['city'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['latitude'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][latitude]' id='latitude' value='<?php echo $dataResultEstimate[$i]['latitude'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['longitude'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][longitude]' id='longitude' value='<?php echo $dataResultEstimate[$i]['longitude'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['spatial_scale'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][spatial_scale]' id='spatial_scale' value='<?php echo $dataResultEstimate[$i]['spatial_scale'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['comments'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][comments]' id='comments' value='<?php echo $dataResultEstimate[$i]['comments'] ?>'/></td>
              <td><span style='display:none'><?php echo $dataResultEstimate[$i]['contributor'] ?></span><input type='text' name='estimate[<?php echo $dataResultEstimate[$i]['idEstimate'] ?>][contributor]' id='contributor' value='<?php echo $dataResultEstimate[$i]['contributor'] ?>'/></td>
            </tr>
            <?php
          endfor;
          ?>

        </tbody>
      </table>
      <script language='javascript'>

        $(document).ready(function() {
          var outputs = [];
          var inputs;
          $('#table2').DataTable({
            'scrollX': true,
            'jQueryUI': true,
          });
          $('#table2').on('draw.dt', function() {
            inputs.each(function() {
              if ($(this).data('original') !== this.value) {
                outputs.push(this);
              }
            });
            inputs = $('input[type="text"]').each(function() {
              $(this).data('original', this.value);
            });
          });
          $('#content').show();
          $('#loading').hide();

          inputs = $('input[type="text"]').each(function() {
            $(this).data('original', this.value);
          });

          $('#valid').click(function() {
            inputs.each(function() {
              if ($(this).data('original') !== this.value) {
                outputs.push(this);
              }
            });
            //            console.log(outputs);
            $.ajax({
              url: templateUrl + "/wp-content/themes/agimpacts/saveValidation.php?article=<?php echo $article ?>",
              type: "POST",
              data: outputs,
              success: function(result) {
                //      $("#estimateDiv").append(result);
                if (result !== '') {
                  //                var n = noty({
                  //                  layout: 'top',
                  //                  type: 'error',
                  //                  timeout: 6000,
                  //                  text: result
                  //                });
                } else {
                  var n = noty({
                      layout: 'top',
                      type: 'success',
                      timeout: 6000,
                      text: 'Register validated'
                    });
                }
              },
              complete: function() {
              }
            });
            return false;
          });
          //          console.log(inputs);
        });
        function saveValidation(form, article) {
          $.ajax({
            url: templateUrl + "/wp-content/themes/agimpacts/saveValidation.php?article=" + article,
            type: "POST",
            data: form.serialize(),
            success: function(result) {
              //      $("#estimateDiv").append(result);
              if (result !== '') {
                //                var n = noty({
                //                  layout: 'top',
                //                  type: 'error',
                //                  timeout: 6000,
                //                  text: result
                //                });
              } else {
                //                location.reload();
              }
            },
            complete: function() {
            }
          });
        }
      </script>
      <!--<script language='javascript'>$('#table1').tablesorter({theme: 'green'});</script>-->
    <?php else: ?>
      <script language='javascript'>alert('No Data Found');</script>
    <?php
    endif;
    ?>  

  </form>
</section>
<?php
get_footer();
