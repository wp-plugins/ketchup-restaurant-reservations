<?php


namespace Ketchup;

class PageHelper {

        public $datetime = NULL;

        public function __construct() {
                $timezone = get_option('timezone_string', FALSE);
                $this->datetime = new \DateTime();

                if (get_option($timezone) !== FALSE) {
                        $timezone = new \DateTimeZone($timezone);
                        $this->datetime->setTimezone($timezone);
                }
        }

        public function localize($object_name, $data) {

                foreach ((array) $data as $key => $value) {
                        if (!is_scalar($value))
                                continue;

                        $data[$key] = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
                }

                $script = "var $object_name = " . wp_json_encode($data) . ';' . "\n;";


                return $script;
        }

        public function convertMinutesToHours($time, $format = '%s:%s') {
                settype($time, 'integer');
                if ($time < 1) {
                        return;
                }
                $hours_num = floor($time / 60);
                $minutes_num = ($time % 60);

                $hours = ($hours_num < 10) ? '0' . $hours_num : $hours_num;
                $minutes = ($minutes_num < 10) ? '0' . $minutes_num : $minutes_num;

                return sprintf($format, $hours, $minutes);
        }

        //if post has date_format meta, you can run this
        private function getPostMetaDateFormat($id) {
                $format = 'Y-m-d';
                $post_format = ( is_numeric($id) ) ? get_post_meta($id, 'date_format', TRUE) : array();

                if (!empty($post_format)) {
                        $format = $post_format;
                }

                return $format;
        }

        public function getCurrentDateTime($post_id = false) {
                $format = $this->getPostMetaDateFormat($post_id);
                return $this->datetime->format($format);
        }

        public function formatDate($post_id, $date) {                
                $datetime = \DateTime::createFromFormat('Y-m-d', $date);                
                $format = $this->getPostMetaDateFormat($post_id);

                if ($datetime) {
                        return $datetime->format($format);
                }
                return $date;
        }

}
