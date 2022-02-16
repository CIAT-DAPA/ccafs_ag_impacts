<?php
/**
 * Template Name: Article creation
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
get_header();
?>
<!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css">
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
<style>
  th {
    border-bottom: 1px solid #d6d6d6;
  }

  tr:nth-child(even) {
    background: #e9e9e9;
  }
</style>-->
<?php
global $wpdb;
$where = "TRUE ";
$page = 1;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
if (!empty($roles)) {
  $role = $roles[0];
  if ($role != 'administrator') {
    $where .= "AND wp_user_id = " . $current_user->ID;
  }
} else {
  $role = false;
}
if (isset($_GET['doi']) && trim($_GET['doi']) != '') {
  $where .= " AND a.doi_article LIKE '" . $_GET['doi'] . "' ";
}

if (isset($_GET['title']) && trim($_GET['title']) != '') {
  $where .= " AND a.paper_title LIKE '%" . $_GET['title'] . "%' ";
}
$tablename = $wpdb->prefix . 'article';
$myarticles = $wpdb->get_results("SELECT * FROM $tablename WHERE " . $where);
$sql1 = "SELECT count(*) as total FROM $tablename a WHERE " . $where;
$result = $wpdb->get_row($sql1);

$total_rows = $result->total;
$rpp = 10;
// This tells us the page number of our last page
$last = ceil($total_rows / $rpp);
// This makes sure $last cannot be less than 1
if ($last < 1) {
  $last = 1;
}
?>
<script>
  var rpp = <?php echo $rpp; ?>; // results per page
  var last = <?php echo $last; ?>; // last page number
  function request_page(pn, form) {
    initPage = true;
    $("#loading").show();
    $.ajax({
      url: templateUrl + "/wp-content/themes/agimpacts/articleTable.php?" + form,
      type: "POST",
      data: {rpp: rpp, last: last, pn: pn},
      success: function(result) {
        $("#results_box").hide();
        $("#results_box").html(result);
        $("#results_box").fadeIn();
        $("#pagination_controls").css('display', 'inline-flex');
      },
      complete: function() {
        $("#loading").fadeOut('slow');
//        $("#results_box").html(result);
      }
    });
    // Change the pagination controls
    if (pn < 3) {
      pnt = 3;
    }
    else if ((last - pn) < 3) {
      pnt = last - 2;
    }
    else {
      pnt = pn;
    }

    var pstart = pnt - 2;
    var plimit = pnt + 2;
    if (pstart <= 0) {
      pstart = 1;
      plimit = last;
    }
    var paginationCtrls = "";
    // Only if there is more than 1 page worth of results give the user pagination controls
    if (last != 1) {
      if (pn > 1) {
        paginationCtrls += '<button style="margin: 0px 0px 5px 5px;" onclick="request_page(' + 1 + ',\'' + form + '\')">&lt;&lt;</button>';
        paginationCtrls += '<button style="margin: 0px 0px 5px 5px;" onclick="request_page(' + (pn - 1) + ',\'' + form + '\')">&lt;</button>';
      }
      paginationCtrls += ' &nbsp; &nbsp; <b>Page ' + pn + ' of ' + last + '</b> &nbsp; &nbsp; ';
      paginationCtrls += '<ul class="paginate">';
      for (i = pstart; i <= plimit; i++) {
        var sel = '';
        if (i == pn) {
          sel = 'active';
        }
        paginationCtrls += '<li><a href="javascript:onclick=request_page(' + i + ',\'' + form + '\');"  class="paginate_click ' + sel + '" id="' + i + '-page">' + i + '</a></li>';
      }
      paginationCtrls += '</ul>';
      if (pn != last) {
        paginationCtrls += '<button style="margin: 0px 0px 5px 5px;" onclick="request_page(' + (pn + 1) + ',\'' + form + '\')">&gt;</button>';
        paginationCtrls += '<button style="margin: 0px 0px 5px 5px;" onclick="request_page(' + last + ',\'' + form + '\')">&gt;&gt;</button>';
      }
    }
    $("#pagination_controls").html(paginationCtrls);
    var hash = unescape(document.location.hash).split("/");
    document.location.hash = 'page=' + pn;
  }
</script>
<div id="loading"><img style="" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="Loader" /></div>
<section id="content" class="row">
  <form id="filtersh" name="filtersh" class="pure-form pure-form-stacked">
    <fieldset>
      <legend>Filter</legend>
      <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1-3">
          <label for="doi">DOI</label>
          <input id="doi" name="doi" type="text" value="<?php echo $_GET['doi'] ?>">
        </div>
        <div class="pure-u-1 pure-u-md-1-3">
          <label for="title">Title</label>
          <input id="title" name="title" type="text" value="<?php echo $_GET['title'] ?>">
        </div>
      </div>
      <button type="submit" class="pure-button pure-button-primary">Search</button>
    </fieldset>
  </form>
  <!--  <div data-role="header">
      <h1>Article List</h1>
    </div>-->
  <div style="position: relative">
    <div style="position: absolute; right: 5px">
      <button class="pure-button pure-button-primary" onclick="$(location).attr('href', templateUrl + '/articleDetail');">Add Article</button> 
    </div>
    <br>
    <br>
  </div>
  <div data-role="main" class="ui-content">
    <?php if ($total_rows > 0) : ?>
      <table class="pure-table pure-table-bordered" id="myTable">
        <thead>
          <tr>
            <th data-priority="1">DOI</th>
            <th data-priority="2">Title</th>
            <th data-priority="3">Status</th>
            <th data-priority="4">Year</th>
            <th data-priority="5">Author(s)</th>
            <th data-priority="5"># Estimates</th>
            <th></th>
          </tr>
        </thead>
        <tbody id ="results_box">

        </tbody>
      </table>
    <?php else : ?>
      <h3> <a href="javascript:$(location).attr('href', templateUrl + '/articleDetail');">You have not articles, add one +</a></h3>
    <?php endif; ?>
  </div>
  <br>
  <div id="pagination_controls" style="float:right; display:none"></div>
  <br>
  <br>
</section>
<script> request_page(<?php echo $page ?>, $('#filtersh').serialize());</script>
<?php get_footer(); ?>

