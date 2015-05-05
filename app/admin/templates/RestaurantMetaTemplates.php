<?php
if (!function_exists('kechup_rr_insert_working_period_html')) {

        function kechup_rr_insert_working_period_html($post) {
                $add_working_period_label = __('Add Working Period', KETCHUP_RR_TEXTDOMAIN);
                return sprintf(
                        '
                        <!-- Add Working Day -->
                        <div class="add-working-day" id="add-for-%d">
                                <div class="kechup-premium-deflactor"><span>Premium Features</span></div>
                                <h2>%s</h2>
                                <fieldset>
                                        <select class="select-day" >
                                                        <option value="0">%s</option>
                                                        <option value="1">%s</option>
                                                        <option value="2">%s</option>
                                                        <option value="3">%s</option>
                                                        <option value="4">%s</option>
                                                        <option value="5">%s</option>
                                                        <option value="6">%s</option>
                                        </select>
                                        <input type="time" class="start-field" />
                                        <input type="time" class="end-field" />                                        
                                        <button class="button button-primary button-large add">%s</button>
                                </fieldset>
                                
                        </div>', $post->ID, $add_working_period_label, __('Sunday', KETCHUP_RR_TEXTDOMAIN), __('Monday', KETCHUP_RR_TEXTDOMAIN), __('Tuesday', KETCHUP_RR_TEXTDOMAIN), __('Wednesday', KETCHUP_RR_TEXTDOMAIN), __('Thursday', KETCHUP_RR_TEXTDOMAIN), __('Friday', KETCHUP_RR_TEXTDOMAIN), __('Saturday', KETCHUP_RR_TEXTDOMAIN), __('Add', KETCHUP_RR_TEXTDOMAIN));
        }

}

if (!function_exists('kechup_rr_insert_working_period_exception_html')) {

        function kechup_rr_insert_working_period_exception_html($post) {
                $create_exception_label = __('Create Exception', 'kechup_rr');
                return sprintf(
                        '<div class="add-exception" id="exception-for-%d">
                                <div class="kechup-premium-deflactor"><span>Premium Features</span></div>
                                <h2>%s</h2>
                                <fieldset>                                        
                                        <input type="time" class="start-field"  />
                                        <input type="time" class="end-field"  />                                        
                                        <input type="date" class="exception-date"  />                                        
                                        <button class="button button-primary button-large warning-btn add-exception">%s</button>
                                </fieldset>
                         </div>', $post->ID, $create_exception_label, __('Create Exception', KETCHUP_RR_TEXTDOMAIN)
                );
        }

}

if (!function_exists('kechup_rr_tables_layout_html')) {

        function kechup_rr_tables_layout_html($post) {
                $insert_table_label = __('Insert Table', KETCHUP_RR_TEXTDOMAIN);
                $delete_table_label = __('Insert Table', KETCHUP_RR_TEXTDOMAIN);
                $table_id_label = __('Table ID', KETCHUP_RR_TEXTDOMAIN);
                $view_table_cap_label = __('Table Capacity', KETCHUP_RR_TEXTDOMAIN);

                $restaurant_style = 'style="background-image: url(' . plugin_dir_url(KETCHUP_RR_FILE) . 'assets/images/restaurant-background.jpg)"';

                printf('
                        <div class="kechup_rr_tables kechup_rr_reservation_admin_app">
                                <div class="kechup-premium-deflactor"><span>Premium Features</span></div>
                                <h2>Setup Tables Positions</h2>
                                <div class="inner-container" id="restaurant-interface-%d" %s>
                                        <span class="table-counter">0</span>                                        
                                </div>
                                <div class="inner-container" id="manual-layout-interface-for-%d">
                                        <fieldset>
                                                <label for="table-id">%s</label>
                                                <input type="number" id="table-id" class="table-id" value="0"  min="0" readonly />
                                        </fieldset>

                                        <fieldset>
                                                <label for="view-table-cap">%s</label>
                                                <input type="number" id="view-table-cap" class="view-table-cap" value="0" min="0" />
                                        </fieldset>
                                        
                                        <fieldset>                                                
                                                <button class="button button-primary button-large layout-insert-table">%s</button>
                                                <button class="button button-primary button-large warning-btn button-large layout-delete-table icon-close"></button>
                                                <button title="Rotate Table" class="button button-primary button-large success-btn layout-rotate-table icon-rotate-left"></button>
                                        </fieldset>
                                </div>
                        </div>', $post->ID, $restaurant_style, $post->ID, $table_id_label, $view_table_cap_label, $insert_table_label);
        }

}//, $post->ID, $number_of_tables, $persons_capacity, $persons__min_capacity, $button_label

if (!function_exists('kechup_rr_insert_tables_html')) {

        function kechup_rr_insert_tables_html($post) {

                $number_of_tables = __('Number of Tables', KETCHUP_RR_TEXTDOMAIN);
                $persons_capacity = __('Maximum Persons per Table', KETCHUP_RR_TEXTDOMAIN);
                $persons__min_capacity = __('Minimum Persons per Table', KETCHUP_RR_TEXTDOMAIN);
                $button_label = __('Add Tables', KETCHUP_RR_TEXTDOMAIN);
                $setup_available_tables_label = __('Setup Available', KETCHUP_RR_TEXTDOMAIN);

                $minimum_persons = get_option('minimum_persons', 1);

                printf('<div class="kechup_rr_tables small" id="tables-manual-insert-for-%d">
                                <h2>%s</h2>
                                <div class="inner-container kechup_rr_reservation_manual_insert">                                        
                                        <fieldset>
                                                <label for="table-num">%s</label>
                                                <input type="number" id="table-num" min="1" class="table-num" value="1" />
                                        </fieldset>
                                        
                                        <fieldset>
                                                <label for="table-min-cap">%s</label>
                                                <input type="number" id="table-min-cap" min="%d" class="table-min-cap" value="%d" />
                                        </fieldset>
                                        
                                        <fieldset>
                                                <label for="table-cap">%s</label>
                                                <input type="number" id="table-cap" min="%d" class="table-cap" value="%d" />
                                        </fieldset>

                                        <button class="button button-primary button-large add-multiple-tables">%s</button>                                        
                                </div>
                        </div>', $post->ID, $setup_available_tables_label, $number_of_tables, $persons__min_capacity, $minimum_persons, $minimum_persons, $persons_capacity, $minimum_persons, $minimum_persons, $button_label);
        }

}

if (!function_exists('kechup_rr_edit_tables_html')) {

        function kechup_rr_edit_tables_html($post) {

                //Inti necessary classes
                $tables = new Ketchup\Table;
                $page_helper = new Ketchup\PageHelper;
                $filter = new Ketchup\FilterData;

                //Set initial valies
                $query_results = $tables->getAll($post->ID);
                $minimum_persons = get_option('minimum_persons', 1);
                $initial_values = '';
                $json = array(); //this is for store the already stored tables, when the pages is loaded!
                //Set translations
                $min_capacity_placeholder_label = __('Min Capacity', KETCHUP_RR_TEXTDOMAIN);
                $max_capacity_placeholder_label = __('Max Capacity', KETCHUP_RR_TEXTDOMAIN);
                $edit_tables_label = __('Edit Tables', KETCHUP_RR_TEXTDOMAIN);
                $update_table_label = __("Update Table", KETCHUP_RR_TEXTDOMAIN);
                $delete_table_label = __("Delete Table", KETCHUP_RR_TEXTDOMAIN);

                foreach ($query_results as $table) {
                        $initial_values .= '<div class="table-record" data-table-id="' . $table->ID . '">';

                        $initial_values .= '<input type="number" class="table-id" id="id-indicator-' . $table->ID . '" min="0" value="' . $table->ID . '" readonly />';
                        $initial_values .= '<input type="text" class="table-name" id="table-named-' . $table->NAME . '" min="0" value="' . $table->NAME . '"/>';
                        $initial_values .= '<input type="number" class="table-min-cap" min="' . $minimum_persons . '" value="' . $table->MIN_CAPACITY . '" placeholder="' . $min_capacity_placeholder_label . '"/>';
                        $initial_values .= '<input type="number" class="table-cap" min="' . $minimum_persons . '" value="' . $table->CAPACITY . '" placeholder="' . $max_capacity_placeholder_label . '"/>';

                        $initial_values .= '<button class="button button-primary button-large update-table">' . $update_table_label . '</button>';
                        $initial_values .= '<button class="button button-primary button-large warning-btn delete-table">' . $delete_table_label . '</button>';

                        $initial_values .= '</div>';


                        $meta = $filter->jsontoarray($table->META);

                        //Storing (array) META values to (array) $json
                        array_push($json, array($table->ID, $table->MIN_CAPACITY, $table->CAPACITY, $meta));
                }

                //Create json from $json and intergrate it in the template
                $json = $page_helper->localize('stored_tables', $json);

                printf('<div class="kechup_rr_tables" id="edit-tables-for-%d">
                               <h2>%s</h2>
                               <div class="inner-container">
                                   %s
                               </div>
                        </div>
                        <script type="template/javascript" id="table-record-template">
                                <div class="table-record" data-table-id="{{table-id}}">
                                      <input type="number" class="table-id" id="id-indicator-{{table-id}}" min="0" value="{{table-id}}" readonly  />
                                      <input type="text" class="table-name" id="table-named-{{name}}" min="0" value="{{name}}" />
                                      <input type="number" class="table-min-cap" min="{{min-allowed-cap}}" value="{{table-min-cap}}" />
                                      <input type="number" id="table-cap" class="table-cap" min="{{min-allowed-cap}}" value="{{table-cap}}" />
                                      
                                      <button class="button button-primary button-large update-table">%s</button>
                                      <button class="button button-primary button-large warning-btn delete-table">%s</button>
                                </div>
                        </script>
                        <!-- Here is the stored tables JSON -->
                        <script type="text/javascript">%s</script>
                        ', $post->ID, $edit_tables_label, $initial_values, $update_table_label, $delete_table_label, $json);
        }

}

if (!function_exists('kechup_rr_get_notifications_categorized')) {

        function kechup_rr_get_notifications_categorized() {
                $notifications = new WP_Query(array('post_type' => 'notification', 'status' => 'publish'));

                $notifications_in_categories = array(
                        Ketchup\Notification::REJECTION => array(),
                        Ketchup\Notification::CANCELATION => array(),
                        Ketchup\Notification::CONFIRMATION => array(),
                        Ketchup\Notification::PENDING => array(),
                        Ketchup\Notification::REMINDER => array()
                );

                if ($notifications->have_posts()) {
                        while ($notifications->have_posts()) {
                                $notifications->the_post();

                                //get the post id
                                $id = get_the_ID();

                                $category = get_post_meta($id, 'notification_category', TRUE);


                                $obj = new stdClass();
                                $obj->title = get_the_title();
                                $obj->ID = $id;

                                switch ($category) {
                                        case Ketchup\Notification::REJECTION : array_push($notifications_in_categories[Ketchup\Notification::REJECTION], $obj);
                                                break;
                                        case Ketchup\Notification::CANCELATION : array_push($notifications_in_categories[Ketchup\Notification::CANCELATION], $obj);
                                                break;
                                        case Ketchup\Notification::CONFIRMATION : array_push($notifications_in_categories[Ketchup\Notification::CONFIRMATION], $obj);
                                                break;
                                        case Ketchup\Notification::PENDING : array_push($notifications_in_categories[Ketchup\Notification::PENDING], $obj);
                                                break;
                                        case Ketchup\Notification::REMINDER : array_push($notifications_in_categories[Ketchup\Notification::REMINDER], $obj);
                                                break;
                                }
                        }
                }
                return $notifications_in_categories;
        }

}

if (!function_exists('kechup_rr_notification_selector_html')) {

        function kechup_rr_notification_selector_html($category, $name, $current) {

                $result = '<div class="kechup_rr_tables">';
                $result .= '<h2 style="font-size: 133%;">' . __(ucfirst($name), KETCHUP_RR_TEXTDOMAIN) . '</h2>';
                $result .= '<select class="widefat" id="assign_notifications-' . $name . '" name="assign_notifications[' . $name . ']">';

                if (!empty($category)) {
                        foreach ($category as $post) {

                                $selected = '';
                                $keys = array_keys($current);

                                if (in_array($name, $keys)) {
                                        $selected = ( intval($post->ID) === intval($current[$name]) ) ? 'selected' : '';
                                }
                                $result .= '<option value="' . $post->ID . '" ' . $selected . '>' . $post->title . '</option>';
                        }
                } else {
                        $result .= '<option>' . __('None', KETCHUP_RR_TEXTDOMAIN) . '</option>';
                }

                $result .= '<select></div>';

                return sprintf('%s', $result);
        }

}