<?php

namespace Ketchup;

class Update extends BasicHandler {

       public function __construct() {
                parent::__construct();
        }

        public function make() {               
                $this->Bookings();
                $this->Tables();
        }

        /**
         * This function check if column exists and do the query;
         * @param $table_name string
         * @param $column_name string
         * @param $query type string
         */
        private function checkTableName($table_name) {
                global $wpdb;
                return $wpdb->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table_name';");
        }

        /**
         * This function returns 1 if exists and 0 if not exist
         * 
         * global $wpdb;         
          $check = $this->checkColumnName($wpdb->ketchup_rr_bookings, 'sss'); //returns 0
         */
        private function checkColumnName($table_name, $column_name) {
                global $wpdb;
                return $wpdb->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name';");
        }

        /**
         * HERE WE PUT CODE TO UPDATE CURRENT TABLES, TO INSERT FIELS AND SO!
         * 
         * THE DATABASE UPDATES FOR PRE-RELEASE VERSIONS WILL BE REMOVED AFTER VERSION 1.0 
         * 
         * USAGE OF ALTER TABLE QUERY:
         * $wpdb->query("ALTER TABLE $wpdb->ketchup_rr_bookings ADD TABLE_ID INT(10) NOT NULL") or die( mysql_error() );
         */
        
        private function Bookings() {
                //global $wpdb;                
        }

        private function Tables() {
                //global $wpdb;                
        }

}
