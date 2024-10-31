<?php
/*
   *   PriceCrossing
 
  *This plugin is for adding PriceCrossing Menus into wordpress site using a shortcode [my_menu] 
  
 * @package PriceCrossing
 *
 * @author PriceCrossing
 * @version 1.0
 */
/*
Plugin Name: PriceCrossing for WordPress
Plugin URI: http://www.pricecrossing.com/
Description: Easily add your Beauty Salon or Auto Shop service menus with the shortcode [my_menu] 
Version: 1.0
Author: PriceCrossing
Author URI: http://www.pricecrossing.com
License: GPL2

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class PCForWordPress {
    var $longname = "PriceCrossing for WordPress";
    var $shortname = "PC";
    var $namespace = 'pc-for-wordpress';
    var $version = '1.0';
    var $defaults = array(
        'pc_code' => "",
    );

    function __construct() {
        $this->url_path = WP_PLUGIN_URL . "/" . plugin_basename( dirname( __FILE__ ) );
        if( isset( $_SERVER['HTTPS'] ) ) {
            if( (boolean) $_SERVER['HTTPS'] === true ) {
                $this->url_path = str_replace( 'http://', 'https://', $this->url_path );
            }
        }
       
        $this->option_name = '_' . $this->namespace . '--options'; 
        $this->options = get_option( $this->option_name, $this->defaults );
               
        add_shortcode('my_menu', array( &$this, 'pc_print_script' ));
		add_filter('widget_text', 'do_shortcode');
       	add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
          
    }
        	  
 
 
	function pc_print_script () {
	    $pc_code = $this->get_option( 'pc_code' );

			if( !empty( $pc_code ) ) {

  			$pc_code = html_entity_decode( $pc_code );

  			return "\n" . $pc_code;
		}
	}
	
    function admin_menu() {
        add_menu_page( $this->shortname, $this->shortname, 2, basename( __FILE__ ), array( &$this, 'admin_options_page' ), ( $this->url_path.'/images/pc_icon.png' ) );
    }

    function admin_options_page() {
        if( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page' );
        }
        
        if( isset( $_POST ) && !empty( $_POST ) ) {
            if( wp_verify_nonce( $_REQUEST[$this->namespace . '_update_wpnonce'], $this->namespace . '_ plugin' ) ) {
                $data = array();
                foreach( $_POST as $key => $val ) {
                    $data[$key] = $this->sanitize_data( $val );
                }
                
                switch( $data['form_action'] ) {
                    case "update_options":
                        $options = array(
                          'pc_code' => (string) $data['pc_code']
                        );

                        update_option( $this->option_name, $options );
                        $this->options = get_option( $this->option_name );
                    break;
                }
            }
        }
        
        $page_title = $this->longname . ' Options';
        $namespace = $this->namespace;
        $options = $this->options;
        $defaults = $this->defaults;
        $plugin_path = $this->url_path;

        foreach( $this->defaults as $name => $default_value ) {
            $$name = $this->get_option( $name );
        }
        include( dirname( __FILE__ ) . '/interface/view.php' );
    }
        
 
    private function get_option( $option_name ) {

        if( !isset( $this->options ) || empty( $this->options ) ) {
            $this->options = get_option( $this->option_name, $this->defaults );
        }
        
        if( isset( $this->options[$option_name] ) ) {
            return $this->options[$option_name];    // Return user's specified option value
        } elseif( isset( $this->defaults[$option_name] ) ) {
            return $this->defaults[$option_name];   // Return default option value
        }
        return false;
    }
        
    private function sanitize_data( $str="" ) {
        if ( !function_exists( 'wp_kses' ) ) {
            require_once( ABSPATH . 'wp-includes/kses.php' );
        }
        global $allowedposttags;
        global $allowedprotocols;
        
        if ( is_string( $str ) ) {
            $str = htmlentities( stripslashes( $str ), ENT_QUOTES, 'UTF-8' );
        }
        
        $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
        
        return $str;
    }
    
}

add_action( 'init', 'PCForWordPress' );

function PCForWordPress() {
    global $PCForWordPress;
    
    $PCForWordPress = new PCForWordPress();
}
?>