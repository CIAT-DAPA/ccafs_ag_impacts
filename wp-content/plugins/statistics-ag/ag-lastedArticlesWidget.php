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
 * Plugin Name: ag-impacts - Latesd articles added widget
 * Description: Widget para mostrar los ultimos articulos agregados
 * Author: Camilo Rodriguez
 * Version: 1.0
 */

class latesdArticlesWidget extends WP_Widget {

  function __construct() {
    parent::__construct(
            'latesdArticlesWidget', 'AG latesd articles', array(
      'description' => 'Widget para mostrar los ultimos articulos agregados'
            )
    );
  }

  function form($instance) {
    ?>
    <label>List limit</label>
    <input type="text" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" >

    <?php
  }

  function update($new, $old) {
    if ($new['limit'] == '')
      return $old;

    return $new;
  }

  function widget($args, $instance) {
    global $wpdb;
    $limit = apply_filters('widgets_title', $instance['limit']);
    $tablename1 = $wpdb->prefix . 'article';
    $sql = "SELECT * FROM $tablename1 as a WHERE status = '1' order by ID DESC limit $limit";
    $rows = $wpdb->get_results($sql, ARRAY_A);
    ?> 
    <div class="statistic-ag">
      <h4>Latesd <?php echo ($limit)?> articles added</h4>
      <table id="latesd-table" class="statistic-ag-table latesd-table pure-table pure-table-horizontal">
        <thead>
          <tr>
             
            <th>TITLE</th>
            <th>YEAR</th>
            <!--<th>Bronze</th>-->
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row) : ?>
          <tr>
             
            <td><?php echo trim_text($row['paper_title'],38) ?></td>
            <td class="centered"><?php echo $row['year'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
  }

}

add_action('widgets_init', function() {
  register_widget('latesdArticlesWidget');
});

