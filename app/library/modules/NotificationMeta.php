<?php


namespace Ketchup;

class NotificationMeta implements PostMetaInterface {

        function __construct() {
                ketchup_rr_include_file('/app/admin/templates/NotificationMetaTemplates');

                add_action('add_meta_boxes', array($this, 'add_metaboxes'));


                //Save Data
                add_action('save_post', array($this, 'save_metabox_notification_template'));
                add_action('save_post', array($this, 'save_metabox_notification_category'));
        }

        public function add_metaboxes() {
                $post_types = array(
                        'notification'
                );

                foreach ($post_types as $post_type) {
                        add_meta_box('notification_template', __('Notification Template', 'ketchup_rr'), array($this, 'metabox_notification_template_callback'), $post_type, 'normal');
                        add_meta_box('notification_category', __('Notification Category', 'ketchup_rr'), array($this, 'metabox_notification_category_callback'), $post_type, 'side');
                        add_meta_box('notification_cancel_link', __('Notification Cancel Link', 'ketchup_rr'), array($this, 'metabox_notification_cancel_link_callback'), $post_type, 'advanced');
                        add_meta_box('available_placeholders', __('Available PlaceHolders', 'ketchup_rr'), array($this, 'metabox_available_placeholders_callback'), $post_type, 'side');
                }
        }

        public function metabox_notification_template_callback($post) {
                $content = get_post_meta($post->ID, 'notification_template', TRUE);
                printf('<textarea class="widefat" rows="15" name="notification_template" id="notification_template-text" >%s</textarea>
                        <input type="button" class="button button-primary button-large use-notification-template" id="use-notification-template" value="%s" />
                        
                        <input type="hidden" id="notification-confirmation" value="%s" />
                        <input type="hidden" id="notification-rejection" value="%s" />
                        <input type="hidden" id="notification-cancelation" value="%s" />
                        <input type="hidden" id="notification-pending" value="%s" />
                        <input type="hidden" id="notification-reminder" value="%s" />                        
                        
                        ', isset($content) ? $content : '', __('Use Notification Template', KETCHUP_RR_TEXTDOMAIN), kechup_rr_get_confirmation_html(), kechup_rr_get_rejection_html(), kechup_rr_get_cancelation_html(), kechup_rr_get_pending_html(), kechup_rr_get_reminder_html());
        }

        public function save_metabox_notification_template($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['notification_template'])) {
                        $content = $_POST['notification_template'];
                }
                update_post_meta($post_id, 'notification_template', $content);
        }

        public function metabox_notification_category_callback($post) {
                $content = get_post_meta($post->ID, 'notification_category', TRUE);
                $categories = array(
                        Notification::REJECTION => __('Rejection', KETCHUP_RR_TEXTDOMAIN),
                        Notification::CANCELATION => __('Cancelation', KETCHUP_RR_TEXTDOMAIN),
                        Notification::CONFIRMATION => __('Confirmation', KETCHUP_RR_TEXTDOMAIN),
                        Notification::PENDING => __('Pending', KETCHUP_RR_TEXTDOMAIN),
                        Notification::REMINDER => __('Reminder', KETCHUP_RR_TEXTDOMAIN)
                );

                $output = '<select class="widefat" name="notification_category"  id="notification-category-select">';
                foreach ($categories as $value => $label) {
                        $selected = ($content === $value) ? 'selected' : '';
                        $output .= sprintf('<option value="%s" %s >%s</option>', $value, $selected, $label);
                }
                $output .= '</select>';

                printf('%s', $output);
        }

        public function save_metabox_notification_category($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['notification_category'])) {
                        $content = $_POST['notification_category'];
                }
                update_post_meta($post_id, 'notification_category', $content);
        }

        public function metabox_notification_cancel_link_callback($post) {
                $set_reservation_page_link = __('Set Reservation Page Link', KETCHUP_RR_TEXTDOMAIN);
                $reservation_cancel_link = __('Reservation Cancel Link', KETCHUP_RR_TEXTDOMAIN);
                $insert_link_to_template = __('Insert to Template', KETCHUP_RR_TEXTDOMAIN);

                $output = sprintf('
                       <fieldset>
                                <label for="reservation_page_link">%s</label>
                                <input class="widefat reservation_page_link" type="text" name="reservation_page_link" id="reservation_page_link"  placeholder="%s" value="" />
                       </fieldset>
                       <p></p>
                       <fieldset>
                                <label for="reservation_cancel_link">%s</label>
                                <input class="widefat reservation_cancel_link" type="text" name="reservation_cancel_link" id="reservation_cancel_link"  value="" readonly />
                       </fieldset>
                       <fieldset style="margin-top: 2em;">
                       <input class="button button-primary button-large insert-to-template" type="button" name="insert-to-template" id="insert-to-template"  value="%s" readonly />
                       </fieldset>
                       ', $set_reservation_page_link, get_bloginfo('url') . '/[page_with_reservation_sortcode]/', $reservation_cancel_link, $insert_link_to_template);

                echo $output;
        }

        public function metabox_available_placeholders_callback($post) {
                $booking = new \Ketchup\Booking(ARRAY_A);
                $bookings = $booking->getEverything();

                $result = $this->getDefaultPlaceholders();
                if (!empty($bookings)) {
                        $result = $booking->getBookingInfo($bookings[0]['ID']);
                        $result = array_keys($result);
                }


                foreach ($result as $key) {
                        printf('<input class="notification-placeholder" style="display:block" value="{{%s}}" readonly>', $key);
                }
        }

        private function getDefaultPlaceholders() {
                return array('restaurant_id', 'start_time', 'end_time', 'date', 'name', 'email', 'person_num', 'phone_number', 'status', 'duration', 'table_id', 'access_key', 'address', 'country', 'city', 'postal_code', 'time_zone', 'date_format', 'restaurant_name', 'table_name', 'booking_id', 'current_date');
        }

}
