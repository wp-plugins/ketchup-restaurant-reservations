<?php

namespace Ketchup;

class RestaurantMeta implements PostMetaInterface {

        public function __construct() {
                
                //the markup is located in another file for clarity
                ketchup_rr_require_file('/app/admin/templates/RestaurantMetaTemplates');
                
                add_action('add_meta_boxes', array($this, 'add_metaboxes'));


                //Save Data
                add_action('save_post', array($this, 'save_metabox_address'));
                add_action('save_post', array($this, 'save_metabox_country'));
                add_action('save_post', array($this, 'save_metabox_city'));
                add_action('save_post', array($this, 'save_metabox_postal_code'));
                add_action('save_post', array($this, 'save_metabox_phone_number'));
                add_action('save_post', array($this, 'save_metabox_email'));
                add_action('save_post', array($this, 'save_metabox_time_zone'));
                add_action('save_post', array($this, 'save_metabox_date_format'));                
                add_action('save_post', array($this, 'save_metabox_assign_notifications'));
                return;
        }

        public function add_metaboxes() {
                $post_types = array(
                        'restaurant'
                );
                foreach ($post_types as $post_type) {
                        add_meta_box('address', __('Address', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_address_callback'), $post_type, 'side');
                        add_meta_box('country', __('Country', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_country_callback'), $post_type, 'side');
                        add_meta_box('city', __('City', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_city_callback'), $post_type, 'side');
                        add_meta_box('postal_code', __('Postal Code', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_postal_code_callback'), $post_type, 'side');
                        add_meta_box('phone_number', __('Phone Number', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_phone_number_callback'), $post_type, 'side');
                        add_meta_box('email', __('Email', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_email_callback'), $post_type, 'side');
                        add_meta_box('time_zone', __('Time Zone', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_time_zone_callback'), $post_type, 'side');
                        add_meta_box('date_format', __('Date Format', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_date_format_callback'), $post_type, 'side'); 
                        add_meta_box('assign_notifications', __('Assign Notifications', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_assign_notifications_callback'), $post_type, 'advanced');
                       

                        //These doesn't actually being saved as metadata, thats why isn't any save function here!
                        
                        
                        
                        add_meta_box('working_periods', __('Working Periods', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_working_periods_callback'), $post_type, 'normal'); 
                            
                        add_meta_box('available_tables', __('Tables', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_available_tables_callback'), $post_type, 'normal');
                }
        }
        

        //Address
        public function metabox_address_callback($post) {
                $content = get_post_meta($post->ID, 'address', TRUE);
                printf('<input class="widefat" type="text" name="address" id="address" value="%s" >', isset($content) ? $content : '' );
        }

        public function save_metabox_address($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['address'])) {
                        $content = $_POST['address'];
                }
                update_post_meta($post_id, 'address', $content);
        }

        //Country
        public function metabox_country_callback($post) {
                $content = get_post_meta($post->ID, 'country', TRUE);
                $json = file_get_contents(ketchup_rr_url('/assets/data/json/countries.json'));
                $countries = json_decode($json);

                $output = '<select name="country" id="country" >';
                foreach ($countries as $country) {
                        $selected = ( $country->code === $content ) ? 'selected' : '';
                        $output .= '<option value="' . $country->code . '" ' . $selected . ' >' . $country->name . "</option>";
                }
                $output .= '</select>';
                echo $output;
        }

        public function save_metabox_country($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['country'])) {
                        $content = $_POST['country'];
                }
                update_post_meta($post_id, 'country', $content);
        }

        //City
        public function metabox_city_callback($post) {
                $content = get_post_meta($post->ID, 'city', TRUE);
                printf('<input class="widefat" type="text" name="city" id="city" value="%s" >', isset($content) ? $content : '' );
        }

        public function save_metabox_city($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['city'])) {
                        $content = $_POST['city'];
                }
                update_post_meta($post_id, 'city', $content);
        }

        //Postal Code
        public function metabox_postal_code_callback($post) {
                $content = get_post_meta($post->ID, 'postal_code', TRUE);
                printf('<input class="widefat" type="number" name="postal_code" id="postal_code" value="%d" >', isset($content) ? $content : '' );
        }

        public function save_metabox_postal_code($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['postal_code'])) {
                        $content = $_POST['postal_code'];
                }
                update_post_meta($post_id, 'postal_code', $content);
        }

        //Phone Number
        public function metabox_phone_number_callback($post) {
                $content = get_post_meta($post->ID, 'phone_number', TRUE);
                printf('<input class="widefat" type="text" name="phone_number" id="phone_number" value="%s" >', isset($content) ? $content : '' );
        }

        public function save_metabox_phone_number($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['phone_number'])) {
                        $content = $_POST['phone_number'];
                }
                update_post_meta($post_id, 'phone_number', $content);
        }

        //Email
        public function metabox_email_callback($post) {
                $content = get_post_meta($post->ID, 'email', TRUE);
                printf('<input class="widefat" type="email" name="email" id="email" value="%s" >', isset($content) ? $content : '' );
        }

        public function save_metabox_email($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['email'])) {
                        $content = $_POST['email'];
                }
                update_post_meta($post_id, 'email', $content);
        }

        //Time Zone
        public function metabox_time_zone_callback($post) {
                $content = get_post_meta($post->ID, 'time_zone', TRUE);
                $timezones = array(
                        'low-limit' => -11,
                        'up-limit' => 14
                );
                $output = '<select  class="widefat" name="time_zone" id="time_zone" data-value="' . $content . '">';
                for ($timezone = $timezones['low-limit']; $timezone <= $timezones['up-limit']; $timezone++) {
                        $selected = ( $timezone == $content ) ? 'selected' : '';
                        $label = ($timezone >= 0) ? '+' . $timezone : $timezone;
                        $output .= '<option value="' . $timezone . '" ' . $selected . ' >' . $label . "</option>";
                }
                $output .= '</select>';
                echo $output;
        }

        public function save_metabox_time_zone($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['time_zone'])) {
                        $content = $_POST['time_zone'];
                }
                update_post_meta($post_id, 'time_zone', $content);
        }

        //Date format
        public function metabox_date_format_callback($post) {
                $content = get_post_meta($post->ID, 'date_format', TRUE);
                $dateformats = array(
                        'dd/mm/yy' => 'd/m/y',
                        'dd/mm/yyyy' => 'd/m/Y',
                        'mm/dd/yy' => 'm/d/y',
                        'mm/dd/yyyy'  => 'm/d/Y'
                );

                $output = '<select class="widefat" name="date_format" id="date_format">';
                foreach ($dateformats as $dateformat => $value ) {
                        $selected = ( $value === $content ) ? 'selected' : '';
                        $output .= '<option value="' . $value . '" ' . $selected . ' >' . $dateformat . "</option>";
                }
                $output .= '</select>';
                echo $output;
        }

        public function save_metabox_date_format($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['date_format'])) {
                        $content = $_POST['date_format'];
                }
                update_post_meta($post_id, 'date_format', $content);
        }
        

        public function metabox_assign_notifications_callback($post) {
                $content = get_post_meta($post->ID, 'assign_notifications', TRUE);               
                
                $notifications = kechup_rr_get_notifications_categorized();
                
                $rejection = false;
                $cancelation = false;
                $confirmation = false;
                $pending = false;
                $reminder = false;               
                
                extract($notifications);
                
                printf('%s %s %s %s %s',
                        kechup_rr_notification_selector_html($rejection, Notification::REJECTION, $content),
                        kechup_rr_notification_selector_html($cancelation, Notification::CANCELATION, $content),
                        kechup_rr_notification_selector_html($confirmation, Notification::CONFIRMATION, $content),
                        kechup_rr_notification_selector_html($pending, Notification::PENDING, $content),
                        kechup_rr_notification_selector_html($reminder, Notification::REMINDER, $content)                      
                );         
        }

        public function save_metabox_assign_notifications($post_id) {
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                        return;
                }

                if (!current_user_can('edit_post', $post_id)) {
                        return;
                }

                $content = '';
                if (isset($_POST['assign_notifications'])) {
                        $content = $_POST['assign_notifications'];
                }
                update_post_meta($post_id, 'assign_notifications', $content);
        }
        
        
        //Working Periods        
        public function metabox_working_periods_callback($post) {
                //$content = get_post_meta($post->ID, 'working_periods', TRUE);              
                
                printf('<meta id="current-restaurant" data-id="%d" />', $post->ID );
                
                echo    kechup_rr_insert_working_period_html($post)
                        .kechup_rr_insert_working_period_exception_html($post);                        
        }
        

        //Available Tables
        public function metabox_available_tables_callback($post) {
                //$content = get_post_meta($post->ID, 'available_tables', TRUE);                
                
                echo    kechup_rr_tables_layout_html($post)
                        .kechup_rr_insert_tables_html($post)                        
                        .kechup_rr_edit_tables_html($post)
                        .'<div style="clear: both;">&nbsp;</div>';
                
                
        }

}
