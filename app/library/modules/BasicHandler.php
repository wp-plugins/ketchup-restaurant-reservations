<?php

namespace Ketchup;

/**
 * Description of BasicHandler
 *
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
class BasicHandler extends DataTable {

        private $query = NULL;

        public function __construct() {
                parent::__construct();
        }

        protected function prepare($queryString, array $data) {
                return vsprintf($queryString, $data);
        }

        protected function int_array(array $integers) {
                $temp_array = array();
                foreach ($integers as $integer) {
                        array_push($temp_array, intval($integer));
                }

                return $temp_array;
        }

        public function objectValueInArray($obj, $field) {
                $temp = array();
                foreach ($obj as $value) {
                        array_push($temp, $value->{$field});
                }
                return $temp;
        }

        public function arrayKeysToLower(array $arr) {
                $temp = array();
                if (!empty($arr)) {
                        foreach ($arr as $key => $value) {
                                $temp[strtolower($key)] = $value;
                        }
                }
                return $temp;
        }

        public function log($string) {
                $log = fopen(KETCHUP_RR_PATH . "log.txt", "a") or die("Unable to open file!");
                fputs($log, $string."\n");
                fclose($log);
        }

        public function select($table, $fields = '*') {
                $fields = ( is_array($fields) ) ? join(',', $fields) : $fields;

                $this->query = 'SELECT ' . $fields . ' FROM ' . $table;
                return $this;
        }

        public function where($statement) {
                $this->query .= ' WHERE ' . $statement . ' ';
                return $this;
        }

        public function getQuery($values = FALSE) {
                if ($values !== FALSE && is_array($values)) {
                        $_values = array_map(array($this, 'prepareValues'), $values);
                        $this->query .= join(' AND ', $_values) . ';';
                }
                return $this->query;
        }

        private function prepareValues($value) {
                $val = (is_numeric($value[2])) ? $value[2] : "'" . $value[2] . "'";
                return $value[0] . $value[1] . $val;
        }

}
