<?php

if (!function_exists('kechup_rr_make_reservation_form_html')) {
        //Defines Restaurant Reservation Form
        function kechup_rr_make_reservation_form_html($restaurants, $predefined_restaurant = false) {
                
                $page_helper = new Ketchup\PageHelper;
                
                //set background image for restaurant app
                $restaurant_style= 'style="background-image: url('.plugin_dir_url(KETCHUP_RR_FILE).'assets/images/restaurant-background.jpg)"';
                
                //Part 1                              
                $start_time_label = __('Reservation Time', KETCHUP_RR_TEXTDOMAIN);
                $end_time_label = __('Ending Time', KETCHUP_RR_TEXTDOMAIN);
                $date_label = __('Date', KETCHUP_RR_TEXTDOMAIN);

                //Part 2
                $fullname_label = __('Fullname', KETCHUP_RR_TEXTDOMAIN);
                $phone_label = __('Phone Number', KETCHUP_RR_TEXTDOMAIN);
                $email_label = __('Email', KETCHUP_RR_TEXTDOMAIN);
                $email_placeholder_label = __('Type your email', KETCHUP_RR_TEXTDOMAIN);

                //Part 3
                $person_num_label = __('Persons Number', KETCHUP_RR_TEXTDOMAIN);
                $choose_table_label = __('Choose Table', KETCHUP_RR_TEXTDOMAIN);
                
                //Parth 4
                $make_reservation_label = __('Make Reservation', KETCHUP_RR_TEXTDOMAIN);

                //Add Conditional html
                $restaurant_selector = '';
                $selected_restaurant = '';

                //get keys from $restaurants
                $ids = array_keys($restaurants);
                if (in_array($predefined_restaurant, $ids)) {  
                        $selected_restaurant = sprintf('data-restaurant-id="%d"', $predefined_restaurant);
                        $restaurant_selector = '<input type="hidden" id="restaurant_name" name="restaurant_name" class="restaurant_name" value="' . $predefined_restaurant . '" />';
                } else {
                        $restaurant_selector = kechup_rr_restaurant_select_html($restaurants);
                }


                return sprintf('
                        <section class="restaurant-reservation-form" >
                                <form id="make-reservation-form" %s>
                                        <div class="part-1">
                                                       %s                                         
                                                <fieldset>
                                                        <label for="start_time">%s</label>
                                                        <input type="time" id="start_time" name="start_time" class="time start_time" required />
                                                </fieldset>
                                                
                                                <fieldset>
                                                        <label for="end_time">%s</label>
                                                        <input type="time" id="end_time" name="end_time" class="time end_time" required />
                                                </fieldset>
                                                
                                                <fieldset>
                                                        <label for="reservation-date">%s</label>
                                                        <input type="date" id="reservation-date" name="reservation-date" class="date reservation-date" required />
                                                </fieldset>
                                        </div>
                                        
                                        <div class="part-2">
                                                <fieldset>
                                                        <label for="fullname">%s</label>
                                                        <input type="text" id="fullname" name="fullname" class="fullname" required />
                                                </fieldset>
                                                
                                                <fieldset>
                                                        <label for="phone_number">%s</label>
                                                        <input type="tel" id="phone_number" name="phone_number" class="tel phone_number" required />
                                                </fieldset>
                                                
                                                <fieldset>
                                                        <label for="email">%s</label>
                                                        <input type="email" id="email" name="email" class="email" placeholder="%s" required />
                                                </fieldset>
                                        </div>
                                        
                                        <div class="part-3">
                                                <fieldset>
                                                       <label for="person_num">%s</label>
                                                       <input type="number" id="person_num" name="person_num" class="number person_num" required /> 
                                                </fieldset>
                                        </div>
                                        
                                        <div class="part-4"> 
                                                <fieldset>
                                                       <input type="hidden" class="number table-id" id="table-id" name="table-id" value="0" />
                                                       <input type="submit" id="make-reservation-button" class="make-reservation-button" name="make-reservation-button" value="%s" disabled="disabled" />
                                                       <label id="reservation-message"></label>
                                                </fieldset>
                                        </div>
                                </form>
                        </section>',$selected_restaurant, $restaurant_selector, $start_time_label, $end_time_label, $date_label, $fullname_label, $phone_label, $email_label, $email_placeholder_label, $person_num_label,  $make_reservation_label);
        }

}


if (!function_exists('kechup_rr_restaurant_select_html')) {

        function kechup_rr_restaurant_select_html($restaurants) {

                $restaurant_name_label = __('Restaurant name', KETCHUP_RR_TEXTDOMAIN);
                $restaurant_options = '<option></option>';

                foreach ($restaurants as $id => $restaurant) {
                        $restaurant_options .= '<option value="' . $id . '">' . $restaurant . '</option>';
                }

                return sprintf('
                                <fieldset>
                                        <label for="restaurant_name">%s</label>
                                        <select id="restaurant_name" name="restaurant_name" class="restaurant_name" required>%s</select>
                                </fieldset>', $restaurant_name_label, $restaurant_options);
        }

}