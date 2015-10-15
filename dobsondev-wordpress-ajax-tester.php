<?php
/**
 * Plugin Name: DobsonDev WordPress AJAX Tester
 * Plugin URI: http://dobsondev.com
 * Description: A plugin for testing/illustrating how AJAX calls work with WordPress
 * Version: 0.666
 * Author: Alex Dobson
 * Author URI: http://dobsondev.com/
 * License: GPLv2
 *
 * Copyright 2015  Alex Dobson  (email : alex@dobsondev.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/*
 * Enqueue scripts and styles
 */
function dobsondev_ajax_tester_style_scripts() {
  wp_enqueue_style( 'dobsondev-ajax-tester-css', plugins_url( 'dobsondev-wordpress-ajax-tester.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'dobsondev_ajax_tester_style_scripts' );

/*
 * Create the admin menu item
 */
function dobsondev_ajax_tester_create_admin_page() {
  add_menu_page( 'AJAX Tester', 'AJAX Tester', 'edit_pages', 'dobsondev_ajax_tester_admin/ve-admin.php', 'dobsondev_ajax_tester_admin_page', 'dashicons-clipboard', 49 );
}
add_action( 'admin_menu', 'dobsondev_ajax_tester_create_admin_page' );

/*
 *
 */
function dobsondev_ajax_tester_admin_page() {
  $html = '<div class="wrap">';
  $html .= '<h2>DobsonDev WordPress AJAX Tester</h2><br />';
  $html .= '<table id="dobsondev-ajax-table">
    <thead>
      <tr>
        <th>Option ID</th>
        <th>Option Name</th>
        <th>Option Value</th>
        <th>Autoload</th>
      </tr>
    </thead>
    <tbody>';
  $html .= '</tbody></table>';
  $html .= '<input type="text" size="4" id="dobsondev-ajax-option-id" />';
  $html .= '<button id="dobsondev-wp-ajax-button">Get Option</button>';
  $html .= '</div>';
  echo $html;
}

/*
 * The JavaScript for our AJAX call
 */
function dobsondev_ajax_tester_ajax_script() {
  ?>
  <script type="text/javascript" >
  jQuery(document).ready(function($) {

    $( '#dobsondev-wp-ajax-button' ).click( function() {
      var id = $( '#dobsondev-ajax-option-id' ).val();
      $.ajax({
        method: "POST",
        url: ajaxurl,
        data: { 'action': 'dobsondev_ajax_tester_approal_action', 'id': id }
      })
      .done(function( data ) {
        console.log('Successful AJAX Call! /// Return Data: ' + data);
        data = JSON.parse( data );
        $( '#dobsondev-ajax-table' ).append('<tr><td>' + data.option_id + '</td><td>' + data.option_name + '</td><td>' + data.option_value + '</td><td>' + data.autoload + '</td></tr>');
      })
      .fail(function( data ) {
        console.log('Failed AJAX Call :( /// Return Data: ' + data);
      });
    });

  });
  </script>
  <?php
}
add_action( 'admin_footer', 'dobsondev_ajax_tester_ajax_script' );

/*
 * The AJAX handler function
 */
function dobsondev_ajax_tester_ajax_handler() {
  global $wpdb;

  $id = $_POST['id'];
  $data = $wpdb->get_row( 'SELECT * FROM wp_options WHERE option_id = ' . $id, ARRAY_A );
  echo json_encode($data);
  wp_die(); // just to be safe
}
add_action( 'wp_ajax_dobsondev_ajax_tester_approal_action', 'dobsondev_ajax_tester_ajax_handler' );

?>