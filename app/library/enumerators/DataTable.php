<?php

namespace Ketchup;

/**
 * Description of DataTable
 *
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
class DataTable {
        static $TABLE = NULL;
        static $BOOKING = NULL;        
        
        
        public function __construct() {
                global $wpdb;
                $this->setupPluginTables();
                
                self::$TABLE = $wpdb->ketchup_rr_tables;
                self::$BOOKING = $wpdb->ketchup_rr_bookings;                
        }
        
        protected function setupPluginTables() {
                global $wpdb;               
                $wpdb->ketchup_rr_bookings = "{$wpdb->prefix}ketchup_rr_bookings";
                $wpdb->ketchup_rr_tables = "{$wpdb->prefix}ketchup_rr_tables";
        }
}
