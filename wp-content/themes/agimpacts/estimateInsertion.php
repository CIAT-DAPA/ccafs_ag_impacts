<?php
/**
 * Template Name: Estimate creation
 * @package WordPress
 * @subpackage AMKNToolbox
 */
/*
 * Copyright (C) 2014 CRSANCHEZ
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

//session_start();
//include( locate_template( 'estimateForm.php' ) );
get_header();
global $wpdb;
$myarticle = null;
$myestimates = null;
if ($_GET['article']) {
  $articleId = $_GET['article'];
  $tablename = $wpdb->prefix . 'article';
  $myarticle = $wpdb->get_row("SELECT * FROM $tablename WHERE ID = " . $_GET['article']);
  if ($myarticle) {
//    echo "##";
    $tablename = $wpdb->prefix . 'estimate';
    $myestimates = $wpdb->get_results("SELECT * FROM $tablename WHERE article_id = " . $myarticle->id, ARRAY_A);
//    echo "<pre>".print_r($myestimates,true)."</pre>";
  }
}
//echo "$$".$myestimates."$$";
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/js/select2/4.0.0/select2.css?<?php echo $version;?>">
<script src="<?php echo get_template_directory_uri(); ?>/js/select2/4.0.0/select2.min.js?<?php echo $version;?>"></script>
<div id="loading"><img style="" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="Loader" /></div>
<input type="hidden" name="estimate_count" id="estimate_count" value="<?php echo (count($myestimates) > 0) ? count($myestimates) : 1 ?>">
<section id="content" class="row"> 
  <div id="home-content" style="position: relative">
    <h1>Reference Article</h1>
    <?php if (isset($myarticle) && !empty($myarticle)): ?>
      <b>DOI:</b> <?php echo $myarticle->doi_article ?><br>
      <b>Title:</b> <?php echo $myarticle->paper_title ?>
    <?php else : ?>
    <?php endif; ?>
    <div float="right" style="position: absolute; right: 5px">
      <button class="pure-button" onclick="addEstimate('article_id=<?php echo $_GET['article']?>')">Add Entry</button> 
      <button class="pure-button" onclick='saveAll()'>Save all</button> 
      <button id="btnha" class="pure-button btnha" onclick="$('.pure-form').hide();
          $('.hideSingle').hide();
          $('.showSingle').show();
          $('.btnsa').show();
          $('.btnha').hide()">Hide all</button>
      <button id="btnsa" style="display:none" class="pure-button btnsa" onclick="$('.pure-form').show();
          $('.hideSingle').show();
          $('.showSingle').hide();
          $('.btnsa').hide();
          $('.btnha').show()">Show all</button>
      <button type="button" class="pure-button pure-button-primary" onclick="$(location).attr('href',templateUrl+'/article');">Back</button>
    </div>
    <h1>Estimate data</h1>
    <hr>
    <div id="estimateDiv">
      <?php
      if (is_null($myestimates) || !$myestimates) {
        include(locate_template('estimateForm.php'));
      } else {
        foreach ($myestimates as $key => $estimate) {
          include(locate_template('estimateForm.php'));
          ?>
          <script>
            $("#btnHide<?php echo $key+1?>").click();
          </script>
          <?php
        }
      }
      ?>
    </div>
    <div float="right" style="position: absolute; right: 5px">
      <button class="pure-button" onclick="addEstimate('article_id=<?php echo $_GET['article']?>')">Add Entry</button> 
      <button class="pure-button" onclick='saveAll()'>Save all</button>
      <button id="btnha" class="pure-button btnha" onclick="$('.pure-form').hide();
          $('.hideSingle').hide();
          $('.showSingle').show();
          $('.btnsa').show();
          $('.btnha').hide()">Hide all</button>
      <button id="btnsa" style="display:none" class="pure-button btnsa" onclick="$('.pure-form').show();
          $('.hideSingle').show();
          $('.showSingle').hide();
          $('.btnsa').hide();
          $('.btnha').show()">Show all</button>
      <button type="button" class="pure-button pure-button-primary" onclick="$(location).attr('href',templateUrl+'/article');">Back</button>
    </div>
    <br>
      <br>
  </div>
</section>
<!--<script>
  $(".estiamte-form").on('submit', function(e) {
//    alert();
    var form = $(this);//this refers to the form
//    alert(form.serialize());
//    var notes_id = form.find('input[name=crop]').val();
//    var notes_text = form.find('textarea[id=comment]').val();
    saveall(form.serialize(),<?php // echo $_GET['article'] ?>);
//    alert(notes_id+' '+notes_text);
    e.preventDefault();
  });
</script>-->
<?php
get_footer();

