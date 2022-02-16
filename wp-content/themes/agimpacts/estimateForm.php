<?php
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
if (isset($_POST["id"]))
  $id = $_POST["id"];
else {
  $id = 1;
}
if (!isset($myestimates) || is_null($myestimates)) {
  if (isset($_GET)) {
    $current_estimate = $_GET;
  }
  ?>
  <input type='hidden' id='estimate_id' value='0'>
  <?php
} else {
  $current_estimate = $estimate;
  $id = $key + 1;
  ?>
  <input type='hidden' id='estimate_id' value='<?php echo $estimate['ID'] ?>'>
  <?php
}
if (!isset($articleId)) {
  $articleId = $_GET["article_id"];
}
//echo "<pre>$articleId".print_r($current_estimate,true)."</pre>";
?>
<div id="contentEstimate<?php echo $id; ?>">
  <div float="right" style="position: absolute; right: 5px">
    <button class="pure-button" onclick="$('#estimateForm<?php echo $id; ?>').submit()">Save</button>
    <button class="pure-button" onclick="addEstimate($('#estimateForm<?php echo $id; ?>').serialize())">Duplicate</button>
    <button class="pure-button" onclick="deleteEstimate(<?php echo $id; ?>);">Delete</button>
    <button id="btnHide<?php echo $id; ?>" name="btnHide<?php echo $id; ?>" class="pure-button hideSingle" onclick="$('#estimateForm<?php echo $id; ?>').hide();
        $('#btnHide<?php echo $id; ?>').hide();
        $('#btnShow<?php echo $id; ?>').show();
        $('#ahide<?php echo $id; ?>').hide();
        $('#ashow<?php echo $id; ?>').show();">Hide</button>
    <button id="btnShow<?php echo $id; ?>" name="btnShow<?php echo $id; ?>" style="display:none" class="pure-button showSingle" onclick="$('#estimateForm<?php echo $id; ?>').show();
        $('#btnShow<?php echo $id; ?>').hide();
        $('#btnHide<?php echo $id; ?>').show();
        $('#ashow<?php echo $id; ?>').hide();
        $('#ahide<?php echo $id; ?>').show();">Expand</button>
  </div>
  <div style='width:180px'><a id='ahide<?php echo $id; ?>' name='ahide' href="javascript:$('#btnHide<?php echo $id; ?>').click();"><h2>Estimate #<?php echo $id; ?></h2></a></div>
  <div style='width:180px'><a id='ashow<?php echo $id; ?>' name='ashow' href="javascript:$('#btnShow<?php echo $id; ?>').click();" style='display:none'><h2>Estimate #<?php echo $id; ?></h2></a></div>
  <form id="estimateForm<?php echo $id; ?>" class="pure-form pure-form-aligned estiamte-form" action='' method="POST">
    <fieldset>        
      <div class="pure-control-group">
        <label for="crop">Crop</label>
        <input id="crop" name="crop" type="text" class="pure-input-1-3" placeholder="Crop" value="<?php echo (isset($current_estimate['crop'])) ? $current_estimate['crop'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="scientific_name">Scientific name</label>
        <input id="scientific_name" name="scientific_name" type="text" class="pure-input-1-3" placeholder="Scientific name" value="<?php echo (isset($current_estimate['scientific_name'])) ? $current_estimate['scientific_name'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="impact_models">Impact model</label>
        <input id="impact_models" name="impact_models" type="text" class="pure-input-1-3" placeholder="Impact model" value="<?php echo (isset($current_estimate['impact_models'])) ? $current_estimate['impact_models'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="baseline">Base Line</label>
        <input id="base_line_start" name="base_line_start" type="text" class="pure-u-1-5" placeholder="Start" value="<?php echo (isset($current_estimate['base_line_start'])) ? $current_estimate['base_line_start'] : ''; ?>"> - <input id="base_line_end" name="base_line_end" type="text" class="pure-u-1-5" placeholder="End" value="<?php echo (isset($current_estimate['base_line_end'])) ? $current_estimate['base_line_end'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="projection">Projection</label>
        <input id="projection_start" name="projection_start" type="text" class="pure-u-1-5" placeholder="Start" value="<?php echo (isset($current_estimate['projection_start'])) ? $current_estimate['projection_start'] : ''; ?>"> - <input id="projection_end" name="projection_end" type="text" class="pure-u-1-5" placeholder="End" value="<?php echo (isset($current_estimate['projection_end'])) ? $current_estimate['projection_end'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="temp_change">&#916;Temp.</label>
        <input id="temp_change" name="temp_change" type="text" class="pure-input-1-3" placeholder="temp change" value="<?php echo (isset($current_estimate['temp_change'])) ? $current_estimate['temp_change'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="yield_change">Yield change (%)</label>
        <input id="yield_change" name="yield_change" type="text" class="pure-input-1-3" placeholder="%" value="<?php echo (isset($current_estimate['yield_change'])) ? $current_estimate['yield_change'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="projection_co2">CO2 projected</label>
        <input id="projection_co2" name="projection_co2" type="text" class="pure-input-1-3" placeholder="projected" value="<?php echo (isset($current_estimate['projection_co2'])) ? $current_estimate['projection_co2'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="baseline_co2">CO2 Baseline</label>
        <input id="baseline_co2" name="baseline_co2" type="text" class="pure-input-1-3" placeholder="Baseline" value="<?php echo (isset($current_estimate['baseline_co2'])) ? $current_estimate['baseline_co2'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="precipitation_change">&#916;PPT</label>
        <input id="precipitation_change" name="precipitation_change" type="text" class="pure-input-1-3" placeholder="Procipitation Change" value="<?php echo (isset($current_estimate['precipitation_change'])) ? $current_estimate['precipitation_change'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="rstart">Range of pred. change (%)</label>
        <input id="projec_yield_change_start" name="projec_yield_change_start" type="text" class="pure-u-1-5" placeholder="Start" value="<?php echo (isset($current_estimate['projec_yield_change_start'])) ? $current_estimate['projec_yield_change_start'] : ''; ?>"> - <input id="project_yield_change_end" name="project_yield_change_end" type="text" class="pure-u-1-5" placeholder="End" value="<?php echo (isset($current_estimate['project_yield_change_end'])) ? $current_estimate['project_yield_change_end'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="climate_scenario">E. scenario</label>
        <input id="climate_scenario" name="climate_scenario" type="text" class="pure-input-1-3" placeholder="E. scenario" value="<?php echo (isset($current_estimate['climate_scenario'])) ? $current_estimate['climate_scenario'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="gcm">GCM(s)</label>
        <input id="gcm" name="gcm" type="text" class="pure-input-1-3" placeholder="GCM" value="<?php echo (isset($current_estimate['gcm'])) ? $current_estimate['gcm'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="contributor">Contributor(s)</label>
        <input id="contributor" name="contributor" type="text" class="pure-input-1-3" placeholder="Contributor" value="<?php echo (isset($current_estimate['contributor'])) ? $current_estimate['contributor'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="comment">Comments</label>
        <textarea id='comments' name='comments' rows="4" cols="50"><?php echo (isset($current_estimate['comments'])) ? $current_estimate['comments'] : ''; ?></textarea>
      </div>
      <div class="pure-control-group">
        <label for="geo_scope">Geog. Scope</label>
        <input id="geo_scope" name="geo_scope" type="text" class="pure-input-1-3" placeholder="Geog. Scope" value="<?php echo (isset($current_estimate['geo_scope'])) ? $current_estimate['geo_scope'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="region">Region</label>
        <input id="region" name="region" type="text" class="pure-input-1-3" placeholder="Region" value="<?php echo (isset($current_estimate['region'])) ? $current_estimate['region'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="country">Country</label>
        <input id="country" name="country" type="text" class="pure-input-1-3" placeholder="Country" value="<?php echo (isset($current_estimate['country'])) ? $current_estimate['country'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="state">State</label>
        <input id="state" name="state" type="text" class="pure-input-1-3" placeholder="State" value="<?php echo (isset($current_estimate['state'])) ? $current_estimate['state'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="city">City</label>
        <input id="city" name="city" type="text" class="pure-input-1-3" placeholder="Country" value="<?php echo (isset($current_estimate['city'])) ? $current_estimate['city'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="spatial_scale">Spatial Scale</label>
        <input id="spatial_scale" name="spatial_scale" type="text" class="pure-input-1-3" placeholder="Spatial Scale" value="<?php echo (isset($current_estimate['spatial_scale'])) ? $current_estimate['spatial_scale'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="latitude">Latitude</label>
        <input id="latitude" name="latitude" type="text" class="pure-input-1-3" placeholder="Latitude" value="<?php echo (isset($current_estimate['latitude'])) ? $current_estimate['latitude'] : ''; ?>">
      </div>
      <div class="pure-control-group">
        <label for="longitude">Longitude</label>
        <input id="longitude" name="longitude" type="text" class="pure-input-1-3" placeholder="Longitude" value="<?php echo (isset($current_estimate['longitude'])) ? $current_estimate['longitude'] : ''; ?>">
      </div>
      <div style="height: 45px" class="pure-control-group">
        <label for="adaptation">Adaptation Column <?php // echo $current_estimate['adaptation'] ?></label>
        <!--<input type="hidden" name="adaptation" id="adaptation" class="input-xlarge" style="width:350px;" data-placeholder="Choose An Option.." />-->
        <select class="js-data-ajax" style="width: 300px;box-shadow: none!important;" name="adaptation[]" id="adaptation" multiple="multiple">
          <?php
          $adaptationDesc = array('CA' => 'Cultivar adaptation', 'FO' => 'Fertilizer optimization', 'TC' => 'TC', 'PDA' => 'Planting date adjustment', 'IO' => 'Irrigation optimization', 'PCA' => 'PCA');
          foreach ($adaptationDesc as $key => $adapt) {
            echo "<option value='" . $key . "' " . ((strpos($current_estimate['adaptation'], $key) !== false) ? "selected='selected'" : "") . ">" . $adapt . "</option>";
          }
          ?>
        </select>
      </div>
    </fieldset>
    <?php if (isset($current_estimate['idEstimate'])): ?>
      <input id="estimate_id" name="estimate_id" type="hidden" value="<?php echo $current_estimate['idEstimate'] ?>">
    <?php endif; ?>
  </form>
  <hr>
  <script>
    $(document).ready(function() {
      $("#adaptation", "#estimateForm<?php echo $id; ?>").select2();
      $("#estimateForm<?php echo $id; ?>").on('submit', function(e) {
        var form = $(this);//this refers to the form
//      alert(form.serialize());
//    var notes_id = form.find('input[name=crop]').val();
//    var notes_text = form.find('textarea[id=comment]').val();
        saveOne(form.serialize(),<?php echo $articleId ?>);
        e.preventDefault();
      });
    })
  </script>
</div>
