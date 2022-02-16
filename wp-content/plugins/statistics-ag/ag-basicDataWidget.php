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
/*
 * Plugin Name: ag-impacts - Basic statistics widget
 * Description: Widget para mostrar los ultimos articulos agregados
 * Author: Camilo Rodriguez
 * Version: 1.0
 */

class basicDataWidget extends WP_Widget {

  function __construct() {
    parent::__construct(
            'basicDataWidget', 'AG basic information', array(
      'description' => 'Widget para mostrar informacion basica'
            )
    );
  }

  function form($instance) {
    
  }

  function update($new, $old) {
//    if ($new['limit'] == '')
//      return $old;

    return $new;
  }

  function widget($args, $instance) {
    global $wpdb;
//    $limit = apply_filters('widgets_title', $instance['limit']);
    $tablename1 = $wpdb->prefix . 'article';
    $tablename2 = $wpdb->prefix . 'estimate';
//    $sql = "SELECT * FROM $tablename1 as a WHERE status = '1' order by ID DESC limit $limit";
    $article_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename1" );
    $estimate_count = $wpdb->get_var( "SELECT COUNT(*) FROM $tablename2" );
//    $rows = $wpdb->get_results($sql, ARRAY_A);
    ?> 
    <div class="statistic-ag">
      <h4>Basic statistics</h4>
      <table id="basic-table" class="statistic-ag-table latesd-table pure-table pure-table-horizontal">
        <tbody>
          <tr>
            <td># Total of articles</td>
            <td><?php echo $article_count?></td>
          </tr>
          <tr>
            <td># Total of estimates</td>
            <td><?php echo $estimate_count?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
  }

}

add_action('widgets_init', function() {
  register_widget('basicDataWidget');
});

