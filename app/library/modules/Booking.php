<?php

namespace Ketchup;

Class Booking extends Reservation implements TableOperationsInterface {

        private $output_type = OBJECT;
        static $output_types = array(OBJECT, OBJECT_K, ARRAY_A, ARRAY_N);

        public function __construct($output_type = OBJECT) {
                parent::__construct();               

                //Set the output type
                if (\in_array($output_type, self::$output_types)) {
                        $this->output_type = $output_type;
                }
        }

        public function create(array $booking) {
                global $wpdb;
                
                $pagehelper = new PageHelper;                
                
                //Require Values
                $prepare_table_time = '00:10';
                $key = md5(microtime() . rand());

                //set duration
                array_push($booking, "ADDTIME( SUBTIME('" . $booking[3] . "','" . $booking[2] . "'), '$prepare_table_time')");

                //set key
                array_push($booking, $key);

                $query = $this->prepare("INSERT INTO $wpdb->ketchup_rr_bookings (RESTAURANT_ID, TABLE_ID, START_TIME, END_TIME, DATE, NAME, EMAIL, PERSON_NUM, PHONE_NUMBER, STATUS, DURATION, ACCESS_KEY) VALUES('%d','%d','%s','%s','%s','%s','%s','%d','%s','%s',%s, '%s');", array_values($booking));
                
                return $wpdb->query($query);
        }

        public function edit($data) {
                //Currenty not working! The allowed field to change must be set and standarize
                global $wpdb;

                $booking_id = $data['ID'];
                unset($data['ID']);

                $query = "UPDATE $wpdb->ketchup_rr_bookings SET";
                $i = 0;
                $items = count($data);
                foreach ($data as $field => $value) {
                        //If value is string include it in quotes
                        $value = ( is_numeric($value)) ? $value : "'" . $value . "'";
                        $query .= " " . $field . "=" . $value;
                        $query .= ( ++$i === $items) ? '' : ',';
                }
                $query .= ' WHERE ID=' . $booking_id . ';';

                return $wpdb->query($query);
        }

        public function cancelBooking($key) {
                global $wpdb;                
               
                return $wpdb->query(
                        $wpdb->prepare("UPDATE " . self::$BOOKING . " SET STATUS='canceled' WHERE ACCESS_KEY='%s'", $key['ACCESS_KEY'] )
                );
        }

        public function delete($id) {
                global $wpdb;
                return $wpdb->delete($wpdb->ketchup_rr_bookings, array('ID' => $id), array('%d'));
        }

        public function get($id) {
                global $wpdb;

                $query = $this->prepare("SELECT * FROM $wpdb->ketchup_rr_bookings WHERE ID = %d", array($id));
                return $wpdb->get_results($query, $this->output_type);
        }
        
        public function getByKey($key, $status = true){
                global $wpdb;

                $status_query = ($status) ? " AND STATUS!='canceled'" : '';
                
                $query = $this->prepare("SELECT * FROM $wpdb->ketchup_rr_bookings WHERE ACCESS_KEY = '%s'".$status_query, array_values($key) );
                return $wpdb->get_row($query, $this->output_type);
        }

        public function getAll($restaurant_id) {
                global $wpdb;

                $query = $this->prepare("SELECT * FROM $wpdb->ketchup_rr_bookings WHERE RESTAURANT_ID = %d", array($restaurant_id));
                return $wpdb->get_results($query);
        }

        public function getEverything() {
                global $wpdb;
                return $wpdb->get_results("SELECT * FROM $wpdb->ketchup_rr_bookings", $this->output_type);
        }

        /**
         * Array with named values
         * 
         * RESTAURANT_ID, START_TIME, END_TIME, NAME, EMAIL, PERSON_NUM, PHONE_NUMBER, STATUS
         */
        public function getQueried($data, $specific_fields = false) {
                global $wpdb;

                $query_base = "SELECT * FROM $wpdb->ketchup_rr_bookings";
                $query = '';               
                $prepare_table_time = '00:10';
                $time_query = ''; //In case START TIME and END TIME are both set

                if (is_array($specific_fields) && !empty($specific_fields)) {
                        $query_base = "SELECT" . join(',', $specific_fields) . "FROM $wpdb->ketchup_rr_bookings";
                }


                //if the time period is explicit it will be run this time query
                if (isset($data['START_TIME']) && isset($data['END_TIME'])) {
                        $time_query = " ('" . $data['START_TIME'] . "' BETWEEN START_TIME AND ADDTIME(END_TIME, '" . $prepare_table_time . "') OR '" . $data['END_TIME'] . "' BETWEEN START_TIME AND ADDTIME(END_TIME, '" . $prepare_table_time . "')) ";

                        unset($data['START_TIME']);
                        unset($data['END_TIME']);
                        $data['TIME_QUERY'] = $time_query;
                }



                //Create Counter for detecting the last value in array
                $i = 0;
                $items = count($data);

                //If array hasn't values return results from basic query
                if ($items === 0) {
                        return $wpdb->get_results($query_base);
                }

                //if has values add the WHERE statement
                $query_base .= ' WHERE ';


                //Loop in Array
                foreach ($data as $field => $value) {
                        //If value is string include it in quotes
                        $value = ( is_numeric($value)) ? $value : "'" . $value . "'";

                        //If time field use inequality
                        if ($field === 'STAR_TIME') {
                                $query .= " (" . $value . " BETWEEN START_TIME AND ADDTIME(END_TIME, '" . $prepare_table_time . "'))";
                        } elseif ($field === 'END_TIME') {
                                $query .= " (" . $value . " BETWEEN START_TIME AND ADDTIME(END_TIME, '" . $prepare_table_time . "'))";
                        } elseif ($field === 'TIME_QUERY') {
                                $query .= $time_query;
                        } elseif ($field === 'DATE') {
                                $query .= " " . $field . "=" . $value;
                        }
                        //In other case use this
                        else {
                                $query .= " " . $field . "=" . $value;
                        }
                        //If has more items add the AND
                        $query .= ( ++$i === $items) ? '' : ' AND';
                }


                //Return the constructed query
                return $wpdb->get_results($query_base . $query, $this->output_type);
        }

        /*
         * Available Restaurant Meta
         * address, country, city, postal_code, phone_number, email, time_zone, date_format
         */

        public function getBookingInfo($id) {
                $page_helper = new PageHelper;
                $table = new Table;

                //set old output value to a variable
                $old_output_type = $this->output_type;

                //set a new output type
                $this->output_type = ARRAY_A;


                //Get the booking record as assosiative array
                $result = $this->get($id);

                //get booking values to lowercase
                $booking = $this->arrayKeysToLower($result[0]);
                $restaurant_id = intval($booking['restaurant_id']);
                $table_id = intval($booking['table_id']);

                //get restaurant meta
                $restaurant_meta = $this->getRestaurantMeta($restaurant_id, array(
                        'address', 'country', 'city', 'postal_code', 'phone_number', 'email', 'time_zone', 'date_format'
                ));
                //Get Booking Table Record
                $table_cols = $table->get($table_id);

                $restaurant = get_post($restaurant_id);
                $restaurant_meta['restaurant_name'] = get_the_title($restaurant);

                $output = \array_merge($booking, $restaurant_meta);


                //some modifications
                $output['table_name'] = $table_cols[0]->NAME;
                $output['booking_id'] = $output['id'];
                $output['date'] = $page_helper->formatDate($output['restaurant_id'], $output['date']);
                $output['current_date'] = $page_helper->getCurrentDateTime($output['restaurant_id']);

                //unset id
                unset($output['id']);

                //reset the output type
                $this->output_type = $old_output_type;


                return $output;
        }

}
