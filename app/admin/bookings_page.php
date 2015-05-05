<?php
function ketchup_rr_bookings_page() {
        //'manage_options'
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page(__('Bookings', KETCHUP_RR_TEXTDOMAIN), __('Bookings', KETCHUP_RR_TEXTDOMAIN), 'manage_options', 'bookings', 'ketchup_rr_bookings_page_callback', false, 8);
}

function ketchup_rr_bookings_page_callback() {
        ?>
        <section class="kechup-rr-admin-page" id="bookings-page">
            <header>
                <span class="icon-users plugin-settings-page-icon"></span><h1 class="header"><?php _e('Bookings', KETCHUP_RR_TEXTDOMAIN) ?></h1>
            </header>
            <hr class="header-underline" />
            <div id="go_pro" style="background:#ffffff; padding:20px;">
            <?php _e('If you need to setup:',KETCHUP_RR_TEXTDOMAIN); ?>
            <ul>
            <li> <?php _e('Table Positions',KETCHUP_RR_TEXTDOMAIN); ?></li> 
            <li> <?php _e('Working Periods & exceptions',KETCHUP_RR_TEXTDOMAIN); ?></li>
            <li> <?php _e('Admin notifications',KETCHUP_RR_TEXTDOMAIN); ?></li> 
            <li> <?php _e('Mailchimp integration and..',KETCHUP_RR_TEXTDOMAIN); ?></li> 
            <li> <?php _e('Premium Support',KETCHUP_RR_TEXTDOMAIN); ?></li> 
            <li> <?php _e('More cool features',KETCHUP_RR_TEXTDOMAIN); ?></li> 
                <?php _e('you might want to take a look at our Pro version!',KETCHUP_RR_TEXTDOMAIN); ?>
            </ul>
            <a href="<?php echo esc_url('http://ketchupthemes.com/restaurant-reservation-plugin/'); ?>"><?php _e('Go Pro Now',KETCHUP_RR_TEXTDOMAIN); ?></a>
                </div>
            <div>
                <?php
                /*
                  FIELDS
                 *      Restaurant Name <select>
                 *      Start Time
                 *      End Time
                 *      Date
                 *      Reservation Name
                 *      Email
                 *      Persons Number
                 *      Phone Number
                 *      Status          <select>
                 *      Table ID        <select>
                 */

                /*
                  RESERVATION STATUS VALUES
                 *      Pending
                 *      Rejected
                 *      Confirmed
                 */
                ?>
                <form name="create_booking" id="create_booking">

                    <!--Admin Info-->
                    <div class="box">
                        <header>
                            <h2><?php _e('Administrator Information', KETCHUP_RR_TEXTDOMAIN) ?></h2>
                        </header>
                        <fieldset>
                            <label><?php _e('Restaurant Name', KETCHUP_RR_TEXTDOMAIN) ?></label>                            
                            <?php
                            $page_helper = new Ketchup\PageHelper;
                            $restaurants = new \WP_Query(array(
                                    'post_type' => 'restaurant',
                                    'nopaging' => true
                            ));
                            $output = '<select name="restaurant_name" class="restaurant_name" id="restaurant_name">';                            
                            $json = array();
                            if ($restaurants->have_posts()) {
                                    $output .= "<option value=''>".__('All', KETCHUP_RR_TEXTDOMAIN)."</option>";
                                    while ($restaurants->have_posts()) {
                                            $restaurants->the_post();
                                            $id = get_the_ID();
                                            $title = get_the_title();
                                            $output .= "<option value='{$id}'>$title</option>";
                                            array_push($json, array('id' => $id, 'title' => $title));
                                    }                                    
                                    wp_reset_postdata();                                     
                            }
                            $json = $page_helper->localize('StoredRestaurants', $json);
                            $output .= '</select>';
                            echo $output;
                            ?>
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Reservation Status', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <select name="reservation_status" id="reservation_status">
                                <option value=""><?php _e('All', KETCHUP_RR_TEXTDOMAIN); ?></option>
                                <option value="pending"><?php _e('Pending', KETCHUP_RR_TEXTDOMAIN); ?></option>
                                <option value="rejected"><?php _e('Rejected', KETCHUP_RR_TEXTDOMAIN); ?></option>
                                <option value="confirmed"><?php _e('Confirmed', KETCHUP_RR_TEXTDOMAIN); ?></option>
                                <option value="canceled"><?php _e('Canceled', KETCHUP_RR_TEXTDOMAIN); ?></option>
                            </select>
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Table ID', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <select name="table-id" id="table-id">
                                <option></option>
                            </select>
                        </fieldset>
                    </div>

                    <!--Reservation Info-->
                    <div class="box">
                        <header>
                            <h2><?php _e('Reservation Information', KETCHUP_RR_TEXTDOMAIN) ?></h2>
                        </header>
                        <fieldset>
                            <label><?php _e('Starting Time', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="time" class="start-time" name="start-time" value="" />
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Ending Time', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="time" class="end-time" name="start-time" value="" />
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Date', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="date" class="date" name="date" value="" />
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Persons Number', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="number" class="persons-num" name="persons-num" min="0" value="" />
                        </fieldset>
                    </div>

                    <!--Contact Info-->
                    <div class="box">
                        <header>
                            <h2><?php _e('Contact Information', KETCHUP_RR_TEXTDOMAIN) ?></h2>
                        </header>
                        <fieldset>
                            <label><?php _e('Reservation Name', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="text" class="res-name" name="res-name" value="" />
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Phone Number', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="text" class="phone-number" name="phone-number" value="" />
                        </fieldset>

                        <fieldset>
                            <label><?php _e('Email', KETCHUP_RR_TEXTDOMAIN) ?></label>
                            <input type="email" class="email" name="email" value="" />
                        </fieldset>

                        <fieldset>
                            <button alt="<?php _e('Save', KETCHUP_RR_TEXTDOMAIN); ?>" title="<?php _e('Save', KETCHUP_RR_TEXTDOMAIN); ?>" class="save-booking icon-save"></button>
                        </fieldset> 
                    </div>
                </form>

                <!--Display Bookings Table-->                
                <div id="list_bookings" class="wp-list-table widefat fixed posts">
                    <table>
                        <thead>
                            <tr>
                                <th class="manage-column"><?php _e('Reservation Name', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Restaurant Name', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Persons Number', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Starting Time', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Ending Time', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Date', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Email', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Phone Number', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Table ID', KETCHUP_RR_TEXTDOMAIN); ?></th>
                                <th class="manage-column"><?php _e('Status', KETCHUP_RR_TEXTDOMAIN); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $bookings = new Ketchup\Booking;
                            $results = $bookings->getEverything();
                            $output = '';

                            foreach ($results as $current) {
                                    $restaurant = get_post($current->RESTAURANT_ID);
                                    $buttons = array( 
                                            'confirmed' => 'icon-check',
                                            'rejected' => 'icon-ban',
                                            'pending' => 'icon-clock',
                                            'canceled' => 'icon-circle-with-minus'
                                            );

                                    $output .= '<tr class="booking" id="booking-'.$current->ID.'">';
                                    $output .= ' <td class="name">' . $current->NAME . '</td>';
                                    $output .= ' <td class="restaurant-name" data-id="'.$current->RESTAURANT_ID.'">' . $restaurant->post_title . '</td>';
                                    $output .= ' <td class="persons-number">' . $current->PERSON_NUM . '</td>';
                                    $output .= ' <td class="start-time">' . $current->START_TIME . '</td>';
                                    $output .= ' <td class="end-time">' . $current->END_TIME . '</td>';
                                    $output .= ' <td class="date">' . $current->DATE . '</td>';
                                    $output .= ' <td class="email">' . $current->EMAIL . '</td>';
                                    $output .= ' <td class="phone-number">' . $current->PHONE_NUMBER . '</td>';
                                    $output .= ' <td class="table-id">' . $current->TABLE_ID . '</td>';
                                    $output .= ' <td class="status" data-current-status="' . $current->STATUS . '">';
                                    foreach ($buttons as $button => $class){
                                            
                                            $highlighted = '';
                                            if($current->STATUS === $button) {
                                                    $highlighted = ' highlighted';                                                    
                                            }
                                            
                                            $output .= '<button class="status-button '.$button.' '.$class.$highlighted.'" data-status="'.$button.'"></button>';
                                    }                                    
                                    $output .= '</td>';
                                    $output .= '</tr>';
                            }
                            echo $output;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr></tr>
                        </tfoot>
                    </table>
                </div>       
                <?php echo '<script type="text/javascript">'.$json.'</script>'; ?>
                <!--Template for ajax loaded booking records-->
                <script type="template/javascript" id="booking-record-template">
                    <tr class="booking" id="booking-{{id}}">
                    <td class="name">{{name}}</td>
                    <td class="restaurant-name" data-id="{{restaurant-id}}" >{{restaurant-name}}</td>
                    <td class="persons-number">{{persons-number}}</td>
                    <td class="start-time">{{start-time}}</td>
                    <td class="end-time">{{end-time}}</td>
                    <td class="date">{{date}}</td>
                    <td class="email">{{email}}</td>
                    <td class="phone-number">{{phone-number}}</td>
                    <td class="table-id">{{table-id}}</td>
                    <td class="status">
                        <button class="status-button confirmed icon-check" data-status="confirmed"></button>
                        <button class="status-button rejected icon-ban" data-status="rejected"></button>
                        <button class="status-button pending icon-clock" data-status="pending"></button>
                        <button class="status-button canceled icon-circle-with-minus" data-status="canceled"></button>
                    </td>
                    </tr>
                </script>
            </div>
        </section>
        <?php
}
