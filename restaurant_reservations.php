<?php
/*
  Plugin Name: Ketchup Restaurant Reservations
  Plugin URI: http://ketchupthemes.com/restaurant-reservation-plugin/
  Description: Your one stop destination to professional WordPress restaurant reservation systems! It is the ideal for small and big restaurants!
  Author: Alex Itsios
  Version: 1.0.0
  Author URI: http://ketchupthemes.com/alex-itsios
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0.txt
  Text Domain: kechup_restaurant_reservations
  Domain Path: /languages
  Network: true
 */

//Setup version and identification sasdsad
if (!defined('KETCHUP_RR')) {        
        //IF YOU CHANGE THE VERSION, MAY YOU TRIGGER AN AUTOMATIC PLUGIN DATABASE UPDATE!
        //BE CAREFULL
        define('KETCHUP_RR', '1.00');  
}

//Checking and updates the plugin version
if (!function_exists('kechup_rr_check_version')) {

        function kechup_rr_is_current_version() {
                
                //Setup variables
                $option_key = 'kechup_rr_version';
                $current_version = KETCHUP_RR;

                if (get_option($option_key, false) === false) {
                        
                        //Set action when plugin version is added to options
                        do_action('kechup_rr_set_version', $current_version);
                        
                        //Add option
                        add_option($option_key, $current_version);                        
                        
                        return true;
                } elseif (get_option($option_key, false) !== $current_version) {
                        
                        //Set action just before the version change to current!
                        do_action('kechup_rr_update_version',$current_version);
                        
                        //Update plugin version to current
                        update_option($option_key, $current_version);                        
                        
                        return false;
                }
                return true;
        }

}


//Function witch initiates all plugin functionality! BEWARE!!!
if (!function_exists('ketchup_rr_init_plugin')) {

        function ketchup_rr_init_plugin() {
                define('KETCHUP_RR_FILE', __FILE__);
                define('KETCHUP_RR_TEXTDOMAIN', 'kechup_restaurant_reservations');
                define('KETCHUP_RR_PATH', plugin_dir_path(__FILE__));
                define('KETCHUP_RR_BASEFOLDER', dirname(__FILE__));

                ketchup_rr_include_file('/app/app');
                
                kechup_rr_is_current_version();
        }

}
ketchup_rr_init_plugin();

//Useful Functions
function ketchup_rr_include_file($relative_path) {
        if (defined('KETCHUP_RR_PATH')) {
                include KETCHUP_RR_PATH . $relative_path . '.php';
        } else {
                include plugin_dir_path(__FILE__) . $relative_path . '.php';
        }
}

function ketchup_rr_require_file($relative_path) {
        if (defined('KETCHUP_RR_PATH')) {
                require KETCHUP_RR_PATH . $relative_path . '.php';
        } else {
                require plugin_dir_path(__FILE__) . $relative_path . '.php';
        }
}

//Shorthand function for plugins url
function ketchup_rr_url($asset_path) {
        return plugins_url($asset_path, KETCHUP_RR_FILE);
}
