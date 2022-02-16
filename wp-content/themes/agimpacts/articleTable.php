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
require('../../../wp-load.php');
//get_header('embed');
global $wpdb;
$where = "TRUE ";
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
$tablename = $wpdb->prefix . 'article';
$tablename2 = $wpdb->prefix . 'estimate';

if (isset($_GET['doi']) && trim($_GET['doi']) != '') {
  $where .= " AND a.doi_article LIKE '%".$_GET['doi']."%' ";
}

if (isset($_GET['title']) && trim($_GET['title']) != '') {
  $where .= " AND a.paper_title LIKE '%".$_GET['title']."%' ";
}

if (isset($_GET['status']) && trim($_GET['status']) != '-1') {
  $where .= " AND a.status = " . $_GET['status'] . " ";
}

if (isset($_POST['pn'])) {
  $rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
  $last = preg_replace('#[^0-9]#', '', $_POST['last']);
  $pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
  // This makes sure the page number isn't below 1, or more than our $last page
  if ($pn < 1) {
    $pn = 1;
  } else if ($pn > $last) {
    $pn = $last;
  }
  // This sets the range of rows to query for the chosen $pn
  $limit = 'LIMIT ' . ($pn - 1) * $rpp . ',' . $rpp;

//  $sql1 = "SELECT * FROM $tablename a WHERE $where ORDER BY ID $limit";
  $sql1 = "SELECT a.*, b.count as count FROM $tablename a LEFT JOIN (SELECT article_id, count(*) as count FROM $tablename2 GROUP by article_id) b ON (a.ID = b.article_id) WHERE $where ORDER BY ID $limit";
//  echo $sql1;
  $myarticles = $wpdb->get_results($sql1);
}

foreach ($myarticles as $article):
  ?>
  <tr>
    <td><?php echo $article->doi_article ?></td>
    <td><?php echo $article->paper_title ?></td>
    <td><?php echo ($article->status == 0)?'new':'Validated'; ?></td>
    <td><?php echo $article->year ?></td>
    <td><?php echo $article->author ?></td>
    <td><?php echo ($article->count)?$article->count:0 ?></td>
    <td>
      <button type="button" style="margin-bottom: 10px;" class="pure-button edit-btn" onclick="$(location).attr('href', templateUrl + '/articleDetail?article=<?php echo $article->id ?>');"><i class="fa fa-pencil-square"></i> Edit</button>
      <button type="button" style="margin-bottom: 10px;" class="pure-button" onclick="$(location).attr('href', templateUrl + '/estimate?article=<?php echo $article->id ?>');"><i class="fa fa-plus-square"></i> Add estimate</button>
      <?php if ($article->status == 0):?>
        <!--<button type="button" style="margin-bottom: 10px;" class="pure-button" onclick="validArticle(<?php // echo $article->id ?>)"><i class="fa fa-check-square"></i> Valid article</button>-->
        <button type="button" style="margin-bottom: 10px;" class="pure-button" onclick="$(location).attr('href', templateUrl + '/articlevalid?article=<?php echo $article->id ?>');"><i class="fa fa-check-square"></i> Valid article</button>
      <?php endif;?>
    </td>            
  </tr>
<?php endforeach; ?>

