<?php

namespace Ketchup;

class PluginDataHandler extends DataTable {        

        public function __construct() {
                parent::__construct();
        }

        public function createPluginTables() {
                //in the future we can add a foreach statement here
                $this->setupPluginTables();
                
                $this->createTableBookings();                             
                $this->createTables();                
        }        
        
        
        private function createTableBookings(){
                global $wpdb;
                return $wpdb->query("CREATE TABLE IF NOT EXISTS $wpdb->ketchup_rr_bookings (
                        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
                        RESTAURANT_ID INT(10) NOT NULL,
                        TABLE_ID INT(10) NOT NULL,
                        START_TIME TIME NOT NULL DEFAULT '00:00:00',
                        END_TIME TIME NOT NULL DEFAULT '00:00:00',
                        DATE DATE NOT NULL,
                        NAME VARCHAR(100) NOT NULL,
                        EMAIL VARCHAR(100) NOT NULL,
                        PERSON_NUM INT(2) NOT NULL,
                        PHONE_NUMBER VARCHAR(50) NOT NULL,
                        STATUS VARCHAR(20) NOT NULL,
                        DURATION TIME NOT NULL,
                        ACCESS_KEY VARCHAR(50) NOT NULL DEFAULT 'nokey'
                );") or die(mysql_error());
        }
        
        private function createTables(){
                global $wpdb;
                return $wpdb->query("CREATE TABLE IF NOT EXISTS $wpdb->ketchup_rr_tables (
                        ID INT(10) AUTO_INCREMENT PRIMARY KEY,
                        NAME VARCHAR(50) NOT NULL DEFAULT 'name',
                        RESTAURANT_ID INT(10) NOT NULL,
                        MIN_CAPACITY INT(10) NOT NULL DEFAULT '1',
                        CAPACITY INT(10) NOT NULL,
                        META TEXT                        
                );") or die(mysql_error());
                
        }
        
}
