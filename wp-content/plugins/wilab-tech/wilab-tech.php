<?php
/**
 * @package  WilabTech
 */
/*
Plugin Name: Wilab Tech
Plugin URI: http://wilab.com/plugin
Description: This is my first attempt on writing a wilab tech Plugin for this amazing tutorial series.
Version: 1.0.0
Author: ham cher
Author URI: http://hamcher.com
License: GPLv2 or later
Text Domain: wilab-tech
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

// If this file is access directly, abort!!!
defined( 'ABSPATH' ) or die( 'Unauthorized access' );

// Create Shortcode for rest-ajax.
// add_shortcode( 'test', 'add' );

function middleware () {
 return '<H1 id="techiepress-text">Initial shortcode text.</H1>';
}

function add ($atts) {
          // <p id="techiepress-text">Initial shortcode text.</p>
        
       $url = 'https://jsonplaceholder.typicode.com/users';
       $arguments = array('method' => 'GET');
       $response = wp_remote_get( $url, $arguments );
       if ( is_wp_error( $response ) ) {
       		$error_message = $response->get_error_message();
		return "Something went wrong: " . $error_message;
       }
      $items = json_decode( wp_remote_retrieve_body( $response ) );
      $html = '';
      foreach( $items as  $item ) {
        $html = $html . $item->email;
      }
     return $html;
	return '<H1 id="techiepress-text">' . 'Initial shortcode text.ee'. $atts['name'] . '</H1>';
}

add_action( 'init', 'wpdocs_add_custom_shortcode' );

function wpdocs_add_custom_shortcode() {
 // do_shortcode("[shortcodeee]");

  add_shortcode( 'test', 'add' );
}

















