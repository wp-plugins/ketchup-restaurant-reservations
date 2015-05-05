<?php

namespace Ketchup;

class Table extends BasicHandler implements TableOperationsInterface {   
        
        public function __construct() {
                parent::__construct();
        }

        /**
         * Create a table record
         * @since    1.0.0
         * @param $data contains 0: restaurant_id, 1: min_capacity, 2: capacity, 3: meta
         */
        public function create(array $data) {
                global $wpdb;

                return $wpdb->query(
                                $wpdb->prepare("INSERT INTO $wpdb->ketchup_rr_tables (RESTAURANT_ID, MIN_CAPACITY, CAPACITY, META) VALUES(%d,%d,%d,%s)", array_values($data))
                );
        }

        public function createMultiple($data) {
                global $wpdb;

                if (!is_array($data)) {
                        return false;
                }

                $values = '';
                $items = count($data);
                $i = 0;
                foreach ($data as $data_table) {

                        $values .= $this->prepare("(%d,%d,%d)", $data_table);
                        $values .= ( ++$i === $items) ? '' : ',';
                }

                $query = sprintf("INSERT INTO $wpdb->ketchup_rr_tables (RESTAURANT_ID, MIN_CAPACITY, CAPACITY) VALUES%s;", $values);
                return $wpdb->query($query);
        }

        public function delete($id) {
                global $wpdb;
                return $wpdb->delete($wpdb->ketchup_rr_tables, array('ID' => $id), array('%d'));
        }

        /**
         * Allow to edit Table Capacity value
         * @since    1.0.0
         * @param $data contains 0: capacity, 1: id
         */
        public function edit($data) {
                global $wpdb;                

                $query = $this->prepare("UPDATE $wpdb->ketchup_rr_tables SET CAPACITY=%d, MIN_CAPACITY=%d, NAME='%s' WHERE ID=%d", $data);
                return $wpdb->query($query);
        }

        public function get($id) {
                global $wpdb;

                $query = $this->prepare("SELECT * FROM $wpdb->ketchup_rr_tables WHERE ID = %d", array($id));
                return $wpdb->get_results($query);
        }

        public function getAll($restaurant_id, $specific_fields = '*') {
                global $wpdb;

                $specific_fields = (is_array($specific_fields)) ? join(',', $specific_fields) : $specific_fields;
                
                $query = $this->prepare("SELECT $specific_fields FROM $wpdb->ketchup_rr_tables WHERE RESTAURANT_ID = %d", array($restaurant_id));
                return $wpdb->get_results($query);
        }

        public function editMeta($data) {
                global $wpdb;
                
                $meta = json_encode($data[0]);
                $id = $data[1];

                $query = "UPDATE $wpdb->ketchup_rr_tables SET META='$meta' WHERE ID=$id";
                return $wpdb->query($query);
        }

}
