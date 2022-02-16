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
 * Plugin Name: ag-impacts - User list widget
 * Description: Widget para mostrar el top de usuarios con mejor puntage
 * Author: Camilo Rodriguez
 * Version: 1.0
 */

class userListWidget extends WP_Widget {

  function __construct() {
    parent::__construct(
            'aguserlistwidget', 'AG user list', array(
      'description' => 'Widget para mostrar el top de usuarios con mejor puntage'
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
    $limit *= 3;
    $tablename1 = $wpdb->prefix . 'users';
    $tablename2 = $wpdb->prefix . 'usermeta';
    $sql = "SELECT * FROM $tablename1 as a INNER JOIN $tablename2 as b ON (a.ID = b.user_id) WHERE b.meta_key = 'gold' OR b.meta_key = 'silver' OR b.meta_key = 'bronze'  order by b.meta_value limit $limit";
//    echo $sql;
    $rows = $wpdb->get_results($sql, ARRAY_A);
    $users = array();
    foreach ($rows as $user) {
      $users[$user['ID']]['user_login'] = $user['user_login'];
      $users[$user['ID']][$user['meta_key']] = $user['meta_value'];
    }
    usort($users, function($a, $b) {
      if ($b['gold'] - $a['gold'] != 0)
        return $b['gold'] - $a['gold'];
      else if ($b['silver'] - $a['silver'] != 0)
        return $b['silver'] - $a['silver'];
      else if ($b['bronze'] - $a['bronze'] != 0)
        return $b['bronze'] - $a['bronze'];
      else
        return 0;
    });
//    echo "<pre>" . print_r($users, true) . "</pre>";
    ?>
    <div class="statistic-ag">
      <h4>Top <?php echo ($limit/3)?> users</h4>
      <table class="statistic-ag-table pure-table pure-table-horizontal">
        <thead>
          <tr>
            <th>User</th>
            <th>Gold</th>
            <th>Silver</th>
            <th>Bronze</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) : ?>
          <tr>
            <td><?php echo $user['user_login'] ?></td>
            <td class="centered"><?php echo $user['gold'] ?></td>
            <td class="centered"><?php echo ($user['silver']) ? $user['silver'] : 0 ?></td>
            <td class="centered"><?php echo ($user['bronze']) ? $user['bronze'] : 0 ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
        <?php
      }

    }

    add_action('widgets_init', function() {
      register_widget('userListWidget');
    });

    