<?php

function ketchup_rr_make_reservation($atts, $content) {
        $atts = shortcode_atts(array(
                'name' => __('Make Reservation', 'ketchup_rr'),
                'extra_classes' => '',
                'display_info' => false,
                'restaurant_id' => false
                ), $atts, 'reservation_form');

        //Include Templates
        ketchup_rr_include_file('/app/front/templates/MakeReservationTemplates');

        //Require Some Objects
        $filter = new Ketchup\FilterData;
        $booking = new Ketchup\Booking(ARRAY_A);

        $restaurants = new WP_Query(array('post_type' => 'restaurant', 'post_status' => 'publish'));
        $stored_restaurants = array();
        $predefined_restaurant = false;

        //Get key if exists
        $key = $filter->get('booking-access-key');
        $booking_for_edit = ($key === NULL || $key === FALSE) ? '' : $booking->getByKey( array( 'ACCESS_KEY' => $key) );        
        
        if(!$booking_for_edit){
        //Defines the markup for Restaurant Selector
        $inner = '<section class="restaurants-info">';

        if ($restaurants->have_posts()) {
                $inner .= '     <div class="innerContainer list" >';

                while ($restaurants->have_posts()) {
                        $restaurants->the_post();

                        $stored_restaurants[get_the_ID()] = get_the_title();
                        $inner .= '     <h3 data-id="' . get_the_ID() . '">' . get_the_title() . '</h3>';
                        $inner .= '     <div>' . get_the_excerpt() . '</div>';
                }

                $inner .= '     </div>';
        }
        $inner .= '</section>';
        wp_reset_postdata();

        if ($atts['display_info'] !== 'on') {
                $inner = '';
        }

        if (is_numeric($atts['restaurant_id'])) {
                $predefined_restaurant = $atts['restaurant_id'];
        }

        //Get the reservation form html     
        $inner .= kechup_rr_make_reservation_form_html($stored_restaurants, $predefined_restaurant);

        $output = sprintf('<div class="kechup_rr_make_reservation %s">%s</div>', $atts['extra_classes'], $inner);
        } else {
            $output = sprintf('<div class="kechup_rr_edit_reservation %s">
                    <label>%s</label>
                    <input type="button" class="cancel-reservation" id="cancel-reservation" value="%s" />
                    <input type="hidden" id="booking-access-key" value="%s" />
                    <label id="reservation-message"></label>
                    </div>', $atts['extra_classes'], __('Be Careful, if you cancel reservation, you can\'t undo the action', KETCHUP_RR_TEXTDOMAIN), __('Cancel Reservation', KETCHUP_RR_TEXTDOMAIN), $key );    
        }
        return $output;
}
