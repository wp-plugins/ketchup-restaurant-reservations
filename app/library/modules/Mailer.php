<?php


namespace Ketchup;

/**
 * Description of Mailer
 *
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
class Mailer {

        private $booking = NULL;
        private $notification_type = NULL;
        private $admin_notification_type = NULL;

        public function __construct() {
                $this->booking = new Booking(ARRAY_A);
                $this->notification_type = Notification::REMINDER;                
        }

        public function setNotificationType($notification_type = Notification::REMINDER) {
                $this->notification_type = $notification_type;
        }

        public function send($operation, $data, $query_result) {
                $result = $this->getQueriedBooking($data); 
               
                if ($operation === 'edit') {
                        switch ($data['STATUS']) {
                                case 'confirmed' : $this->notification_type = Notification::CONFIRMATION;
                                        break;
                                case 'rejected' : $this->notification_type = Notification::REJECTION;
                                        break;
                                case 'canceled' :
                                        $this->notification_type = Notification::CANCELATION;                                       
                                        break;
                        }
                } elseif ($operation === 'cancelBooking') {
                        $this->notification_type = Notification::CANCELATION;                       
                }


                //set messages
                $message = $this->getMessage($result, $this->notification_type);                             

                wp_mail($result['EMAIL'], __('Reservation ' . $this->notification_type . 'for ' . $message['title'], KETCHUP_RR_TEXTDOMAIN), $message['msg']);                
        }

        private function getMessage(array $data, $type) {
                $BookingInfo = $this->booking->getBookingInfo($data['ID']);

                $notifications = get_post_meta($data['RESTAURANT_ID'], 'assign_notifications', TRUE); //reminder                

                $parser = new TemplateParser($BookingInfo);

                if (isset($notifications[$type]) && $notifications !== '') {
                        $message = $parser->parseNotification(intval($notifications[$type]));
                } else {
                        $message = __('Empty Notification', KETCHUP_RR_TEXTDOMAIN);
                }
                $title = $BookingInfo['restaurant_name'];

                return array(
                        'msg' => $message,
                        'title' => $title);
        }

        private function getQueriedBooking($data) {
                $parsedData = array();
                $result = NULL;                

                if (isset($data[0])) {
                        $parsedData = $this->parseFullQuery($data);
                } else {
                        $parsedData = $data;
                }

                if (isset($data['ID'])) {
                        $result = $this->booking->get(intval($data['ID']));
                } 
                elseif (isset($data['ACCESS_KEY'])) {                        
                        $result = $this->booking->getByKey($data, false);                       
                        return $result;
                } else {
                        $result = $this->booking->getQueried($parsedData);
                }

                return $result[0];
        }

        private function parseFullQuery($query) {
                
                $queryfor = array(
                        'RESTAURANT_ID' => $query[0],
                        'TABLE_ID' => $query[1],
                        'START_TIME' => $query[2],
                        'END_TIME' => $query[3],
                        'DATE' => $query[4],
                        'NAME' => $query[5],
                        'EMAIL' => $query[6],
                        'PERSON_NUM' => $query[7],
                        'PHONE_NUMBER' => $query[8],
                        'STATUS' => $query[9]
                );

                return $queryfor;
        }

}
