<?php

//Autoload Interfaces
if (!function_exists('ketchup_rr_load_interfaces')) {

        function ketchup_rr_load_interfaces() {

                //If your want to call another interface, your must call it here!
                $interfaces = array(
                        'PostMetaInterface',
                        'PluginActivationsStatusHandlerInterface',
                        'TableOperationsInterface',
                        'EmailServiceInterface'
                );
                foreach ($interfaces as $interface) {
                        ketchup_rr_load_interface($interface);
                }
        }

        function ketchup_rr_load_interface($interfaceName) {
                ketchup_rr_require_file('/app/library/interfaces/' . $interfaceName);
        }

}
ketchup_rr_load_interfaces();

/**
 * Autoload Enumerators
 * Enumerators here are java style, a little more dynamic
 */
if (!function_exists('ketchup_rr_load_enumerators')) {

        function ketchup_rr_load_enumerators() {
                $enumerators = array(
                        'Notification',
                        'DataTable'
                );

                foreach ($enumerators as $enumerator) {
                        ketchup_rr_load_enumerator($enumerator);
                }
        }

        function ketchup_rr_load_enumerator($enumName) {
                ketchup_rr_require_file('/app/library/enumerators/' . $enumName);
        }

}
ketchup_rr_load_enumerators();

/*
 * Autoload Modules / Classes (this function loads the files in modules directory!!!)
 * 
 * FYI:
  If you ask, what is a module? In this case, is a class or a like class organized file!
  Means, it must has at least only one responsibility, like change css or create pages logic
  and stuff... you can't load shortcodes, create scripts in a module.
  Not because you can't, but because you break the organization paradigm!
 * 
 */
if (!function_exists('ketchup_rr_load_modules')) {

        function ketchup_rr_load_modules() {

                //If your want to call another module, your must call it here!
                $modules = array(
                        'Browser',
                        'FilterData',
                        'BasicHandler',
                        'PluginDataHandler',
                        'Reservation',
                        'Booking',
                        'Table',
                        'PageHelper',
                        'RestaurantMeta',
                        'NotificationMeta',
                        'GeneralMeta',
                        'HandleActivationStatus',
                        'TemplateParser',
                        'Mailer',
                        'Update'
                );

                foreach ($modules as $module) {
                        ketchup_rr_load_module($module);
                }
        }

        function ketchup_rr_load_module($moduleName) {
                ketchup_rr_require_file('/app/library/modules/' . $moduleName);
        }

}
ketchup_rr_load_modules();

//Register Activation / Deactivation Of the plugin
if (!function_exists('ketchup_rr_handle_activation_status')) {

        function ketchup_rr_handle_activation_status() {
                register_activation_hook(KETCHUP_RR_FILE, 'ketchup_rr_install_tables');
        }

        function ketchup_rr_install_tables() {
                $datahandler = new Ketchup\HandleActivationStatus();
                $datahandler->install();
        }

}
ketchup_rr_handle_activation_status();


if (!function_exists('kechup_rr_update_database')) {

        function kechup_rr_update_database() {
                $updater = new Ketchup\Update;
                $updater->make();
        }

        add_action('kechup_rr_set_version', 'kechup_rr_update_database');
        add_action('kechup_rr_update_version', 'kechup_rr_update_database');
}

//Load Text Domain
if (!function_exists('kechup_rr_load_language')) {

        function kechup_rr_load_language() {
                load_plugin_textdomain(KETCHUP_RR_TEXTDOMAIN, false, basename(KETCHUP_RR_BASEFOLDER) . '/languages');
        }

        add_action('init', 'kechup_rr_load_language', 1);
}


//Register all Post Types Here!
if (!function_exists('ketchup_rr_register_post_types')) {

        // Register Custom Post Type
        function ketchup_rr_register_restaurant_post_type() {

                $labels = array(
                        'name' => _x('Restaurants', 'Post Type General Name', KETCHUP_RR_TEXTDOMAIN),
                        'singular_name' => _x('Restaurant', 'Post Type Singular Name', KETCHUP_RR_TEXTDOMAIN),
                        'menu_name' => __('Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'parent_item_colon' => __('Parent Restaurant:', KETCHUP_RR_TEXTDOMAIN),
                        'all_items' => __('All restaurants', KETCHUP_RR_TEXTDOMAIN),
                        'view_item' => __('View Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'add_new_item' => __('Add New Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'add_new' => __('Add  New', KETCHUP_RR_TEXTDOMAIN),
                        'edit_item' => __('Edit Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'update_item' => __('Update Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'search_items' => __('Search Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'not_found' => __('Not found', KETCHUP_RR_TEXTDOMAIN),
                        'not_found_in_trash' => __('Not found in Trash', KETCHUP_RR_TEXTDOMAIN),
                );
                $args = array(
                        'label' => __('Restaurant', KETCHUP_RR_TEXTDOMAIN),
                        'description' => __('A restaurant registry', KETCHUP_RR_TEXTDOMAIN),
                        'labels' => $labels,
                        'supports' => array('title', 'excerpt', 'author', 'thumbnail', 'revisions',),
                        'taxonomies' => array(),
                        'hierarchical' => false,
                        'public' => true,
                        'show_ui' => true,
                        'show_in_menu' => true,
                        'show_in_nav_menus' => true,
                        'show_in_admin_bar' => true,
                        'menu_position' => 5,
                        'can_export' => true,
                        'has_archive' => true,
                        'exclude_from_search' => false,
                        'publicly_queryable' => true,
                        'capability_type' => 'page',
                );
                register_post_type('restaurant', $args);
        }

        // Register Custom Post Type
        function ketchup_rr_register_notification_post_type() {

                $labels = array(
                        'name' => _x('Notification', 'Post Type General Name', KETCHUP_RR_TEXTDOMAIN),
                        'singular_name' => _x('Notification', 'Post Type Singular Name', KETCHUP_RR_TEXTDOMAIN),
                        'menu_name' => __('Notification', KETCHUP_RR_TEXTDOMAIN),
                        'parent_item_colon' => __('Parent Notification:', KETCHUP_RR_TEXTDOMAIN),
                        'all_items' => __('All Notifications', KETCHUP_RR_TEXTDOMAIN),
                        'view_item' => __('View Notification', KETCHUP_RR_TEXTDOMAIN),
                        'add_new_item' => __('Add New Notification', KETCHUP_RR_TEXTDOMAIN),
                        'add_new' => __('Add  New', KETCHUP_RR_TEXTDOMAIN),
                        'edit_item' => __('Edit Notification', KETCHUP_RR_TEXTDOMAIN),
                        'update_item' => __('Update Notification', KETCHUP_RR_TEXTDOMAIN),
                        'search_items' => __('Search Notification', KETCHUP_RR_TEXTDOMAIN),
                        'not_found' => __('Not found', KETCHUP_RR_TEXTDOMAIN),
                        'not_found_in_trash' => __('Not found in Trash', KETCHUP_RR_TEXTDOMAIN),
                );
                $args = array(
                        'label' => __('Notification', KETCHUP_RR_TEXTDOMAIN),
                        'description' => __('A notifications registry', KETCHUP_RR_TEXTDOMAIN),
                        'labels' => $labels,
                        'supports' => array('title', 'author'),
                        'taxonomies' => array(),
                        'hierarchical' => false,
                        'public' => false,
                        'show_ui' => true,
                        'show_in_menu' => true,
                        'show_in_nav_menus' => true,
                        'show_in_admin_bar' => true,
                        'menu_position' => 6,
                        'can_export' => true,
                        'has_archive' => true,
                        'exclude_from_search' => false,
                        'publicly_queryable' => true,
                        'capability_type' => 'page',
                );
                register_post_type('notification', $args);
        }

        //Add Actions Here!
        function ketchup_rr_register_post_types() {
                add_action('init', 'ketchup_rr_register_restaurant_post_type', 2);
                add_action('init', 'ketchup_rr_register_notification_post_type', 2);
        }

}
ketchup_rr_register_post_types();


//Modify Columns for Custom Post Types
if (!function_exists('ketchup_rr_custom_post_columns')) {

        function ketchup_rr_custom_post_columns($defaults) {
                //Here you can set custom columns
                if ($_GET['post_type'] === 'restaurant') {
                        //Unset Categories and Tags
                        unset($defaults['categories']);
                        unset($defaults['tags']);

                        //Set new!
                        $defaults['address'] = __('Address', KETCHUP_RR_TEXTDOMAIN);
                        $defaults['phone_number'] = __('Phone', KETCHUP_RR_TEXTDOMAIN);
                        $defaults['email'] = __('Email', KETCHUP_RR_TEXTDOMAIN);
                }
                if ($_GET['post_type'] === 'notification') {
                        $defaults['assigned_to'] = __('Assigned To', KETCHUP_RR_TEXTDOMAIN);
                }

                return $defaults;
        }

        add_filter('manage_posts_columns', 'ketchup_rr_custom_post_columns');
}

//Set Content for Custom Columns
if (!function_exists('ketchup_rr_custom_columns_content')) {

        function ketchup_rr_custom_columns_content($column_name, $id) {

                //You can set the names of the meta you want to insert as contet to its
                //existing custom column, add it above if not exists
                $columns = array(
                        'address',
                        'phone_number',
                        'email',
                );

                if (in_array($column_name, $columns)) {
                        echo get_post_meta($id, $column_name, true);
                }

                //Exception for Notification because we want post title
                if ($column_name == 'assigned_to') {
                        $restaurant_id = get_post_meta($id, 'assigned_to', true);
                        echo get_post_field('post_title', $restaurant_id);
                }
        }

        add_action('manage_posts_custom_column', 'ketchup_rr_custom_columns_content', 9, 2);
}


if (!function_exists('ketchup_rr_initialize_frontend_stuff')) {

        function ketchup_rr_initialize_frontend_stuff() {
                ketchup_rr_require_file('app/front/shortcodes/shortcodes');
        }

}
ketchup_rr_initialize_frontend_stuff();

//Conditional Inline CSS
if (!function_exists('kechup_rr_conditional_css')) {

        function kechup_rr_conditional_css() {
                $browser = new Browser();
                if ($browser->getBrowser() == Browser::BROWSER_FIREFOX) {

                        $css = '.restaurant-reservation-form label { display: block;} .restaurant-reservation-form fieldset { display: block; width: 100%;}';

                        wp_add_inline_style('restaurant_reservations_style', $css);
                }
        }

}

//Conditional Admin Inline CSS
if (!function_exists('kechup_rr_admin_conditional_css')) {

        function kechup_rr_admin_conditional_css() {
                $browser = new Browser();
//                if( $browser->getBrowser() == Browser::BROWSER_FIREFOX ){                        
//                        $css = ''; 
//                        wp_add_inline_style('restaurant_reservations_admin_style', $css);
//                }
        }

}


//Register Plugin Stylesheets
if (!function_exists('kechup_rr_register_all_styles')) {

        function kechup_rr_register_all_styles() {

                //Register Fonts
                wp_register_style('advent_pro_font', 'http://fonts.googleapis.com/css?family=Advent+Pro:100,700,400,300,200,600,500&subset=latin,greek', array(), '1.0', 'all');

                //jQuery UI
                wp_register_style('jquery_ui_css', ketchup_rr_url('/assets/stylesheets/jquery-ui.css'), array(), '1.0', 'all');

                //Icons
                wp_register_style('restaurant_icons', ketchup_rr_url('/assets/stylesheets/restaurant_icons.css'), array(), '1.0', 'all');
                wp_register_style('ketchup_rr_icons', ketchup_rr_url('/assets/stylesheets/ketchup_rr_icons.css'), array(), '1.0', 'all');


                //Rest
                wp_register_style('restaurant_reservations_admin_style', ketchup_rr_url('/assets/stylesheets/admin.css'), array('advent_pro_font', 'restaurant_icons', 'ketchup_rr_icons', 'jquery_ui_css'), '1.0', 'all');
                wp_register_style('restaurant_reservations_style', ketchup_rr_url('/assets/stylesheets/style.css'), array('jquery_ui_css'), '1.0', 'all');
        }

}

//Enqueue FrontEnd Stylesheets
if (!function_exists('ketchup_rr_register_stylesheets')) {

        function ketchup_rr_register_stylesheets() {

                //Register Plugin Styles if aren't registers
                kechup_rr_register_all_styles();

                //Add Conditional CSS
                kechup_rr_conditional_css();

                //Enqueue Stylesheets
                wp_enqueue_style('restaurant_reservations_style');
        }

        add_action('wp_enqueue_scripts', 'ketchup_rr_register_stylesheets', 1);
}

//Register Plugin Admin Stylesheets
if (!function_exists('ketchup_rr_register_admin_stylesheets')) {

        function ketchup_rr_register_admin_stylesheets() {

                //Register Plugin Styles if aren't registers
                kechup_rr_register_all_styles();

                //Conditional CSS
                kechup_rr_admin_conditional_css();

                //Enqueue Stylesheets
                wp_enqueue_style('restaurant_reservations_admin_style');
        }

        add_action('admin_enqueue_scripts', 'ketchup_rr_register_admin_stylesheets', 1);
}

//Days Translation for internal function use for clarity
if (!function_exists('kechup_rr_get_localized_days')) {

        function kechup_rr_get_localized_days() {
                return array(
                        __('Sunday', KETCHUP_RR_TEXTDOMAIN),
                        __('Monday', KETCHUP_RR_TEXTDOMAIN),
                        __('Tuesday', KETCHUP_RR_TEXTDOMAIN),
                        __('Wednesday', KETCHUP_RR_TEXTDOMAIN),
                        __('Thursday', KETCHUP_RR_TEXTDOMAIN),
                        __('Friday', KETCHUP_RR_TEXTDOMAIN),
                        __('Saturday', KETCHUP_RR_TEXTDOMAIN),
                );
        }

}

//Vars For Localization (use in wp_localize_script function)
if (!function_exists('kechup_rr_get_vars')) {

        function kechup_rr_get_vars() {
                return array(
                        'url' => admin_url('admin-ajax.php'),
                        'homeUrl' => home_url('/'),
                        'kechup_rr_cnonce' => wp_create_nonce('utilize_classes'), //this is the nonce classes use for interaction
                        'days' => kechup_rr_get_localized_days(),
                        'minimum_persons_per_table' => get_option('minimum_persons', 1),
                        'translations' => array(
                                'all' => __('All', KETCHUP_RR_TEXTDOMAIN)
                        )
                );
        }

}

//A function to clear the setup scripts for frontend and backend and reduce the dublicate code
if (!function_exists('kechup_rr_register_all_scripts')) {

        function kechup_rr_register_all_scripts() {
                //register scripts                
                wp_register_script('jquery-validate', ketchup_rr_url('/assets/javascript/jquery.validate.min.js'), array('jquery'), '1.13.1', false);
                wp_register_script('maskedinputjs', ketchup_rr_url('/assets/javascript/jquery.maskedinput.min.js'), array('jquery'), '1.4', true);
                wp_register_script('time_js', ketchup_rr_url('/assets/javascript/time.js'), array(), '1.0', true);
                wp_register_script('reservation_data_js', ketchup_rr_url('/assets/javascript/reservations-data.js'), array('jquery'), '1.0', true);
                wp_register_script('bookings_ui_js', ketchup_rr_url('/assets/javascript/bookings-ui.js'), array('jquery'), '1.0', true);
                wp_register_script('velocity_js', ketchup_rr_url('/assets/javascript/velocity.min.js'), array('jquery'), '1.0', false);

                //Feature detection, if needed
                //wp_register_script('modernizr', ketchup_rr_url('/assets/javascript/modernizr.js'), array(), '1.0', false);                
                //Plugin Main Scripts
                wp_register_script('restaurant_reservations_admin_js', ketchup_rr_url('/assets/javascript/restaurant_reservations_admin.js'), array('jquery', 'jquery-validate', 'maskedinputjs', 'bookings_ui_js', 'time_js', 'reservation_data_js'), '1.0', true);

                wp_register_script('restaurant_reservations_js', ketchup_rr_url('/assets/javascript/restaurant_reservations.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-validate', 'velocity_js', 'maskedinputjs', 'time_js', 'reservation_data_js'), '1.0', true);
        }

}

//Register Plugin Javascript
if (!function_exists('ketchup_rr_register_scripts')) {

        function ketchup_rr_register_scripts() {
                $localized_vars = kechup_rr_get_vars();

                //Register all posible scripts
                kechup_rr_register_all_scripts();

                //This function pass parameters to javascript objects
                wp_localize_script('restaurant_reservations_js', 'kechup_rr_admin_vars', $localized_vars);

                //Enqueue main script and all the others as dependencies
                wp_enqueue_script('restaurant_reservations_js');
        }

        add_action('wp_enqueue_scripts', 'ketchup_rr_register_scripts', 1);
}

if (!function_exists('kechup_rr_register_admin_scripts')) {

        function kechup_rr_register_admin_scripts() {
                $localized_vars = kechup_rr_get_vars();

                //Register all posible scripts
                kechup_rr_register_all_scripts();

                //This function pass parameters to javascript objects
                wp_localize_script('restaurant_reservations_admin_js', 'kechup_rr_admin_vars', $localized_vars);

                //Enqueue main script and all the others as dependencies
                wp_enqueue_script('restaurant_reservations_admin_js');
        }

        add_action('admin_enqueue_scripts', 'kechup_rr_register_admin_scripts', 2);
}


//Setup plugin wp-admin Pages
if (!function_exists('ketchup_rr_add_pages')) {

        function ketchup_rr_add_pages() {
                //include pages               
                ketchup_rr_include_file('/app/admin/bookings_page');

                //actions                
                add_action('admin_menu', 'ketchup_rr_bookings_page');
        }

}
ketchup_rr_add_pages();

if (!function_exists('ketchup_rr_initialize_meta')) {

        function ketchup_rr_initialize_meta() {
                new Ketchup\RestaurantMeta();
                new Ketchup\NotificationMeta();
                new Ketchup\GeneralMeta();
        }

}
ketchup_rr_initialize_meta();

if (!function_exists('kechup_rr_generate_interact_action_name')) {

        function kechup_rr_generate_interact_action_name($class_instance, $operation) {
                $prefix = 'kechup_rr_';
                $class_name = get_class($class_instance);
                $class_lower_name = strtolower(str_replace('Ketchup\\', '', $class_name));

                return $prefix . $class_lower_name . '_' . $operation;
        }

}

if (!function_exists('kechup_rr_set_booking_actions')) {

        function kechup_rr_set_booking_actions() {
                $mailer = new Ketchup\Mailer;

                add_action('kechup_rr_booking_create', array($mailer, 'send'), 1, 3);
                add_action('kechup_rr_booking_edit', array($mailer, 'send'), 1, 3);
                add_action('kechup_rr_booking_cancelBooking', array($mailer, 'send'), 1, 3);
        }

}
kechup_rr_set_booking_actions();

//This function basically a template for data interaction based on TableOperations iterface
//Is for eliminating dublicated code
if (!function_exists('kechup_rr_interact')) {

        function kechup_rr_data($class_instance) {
                //Initialize Filters to validate and sanitize data from requests
                $filter = new Ketchup\FilterData;

                $validation_key = $filter->getpost('validation_key');
                $result = NULL; // Set result element as NULL                

                if (!wp_verify_nonce($validation_key, 'utilize_classes')) {
                        die('Please... don\'t make requests you know are not going to get the response you hope for!');
                }

                //use filter to sanitize data
                $operation = $filter->getpost('operation');
                $data = $filter->getJSON('data');

                //Execute the request
                $result = json_encode($class_instance->{$operation}($data));


                //Set a dynamic do action, this will be tranlated as for example kechup_rr_table_edit, kechup_rr_booking_create
                $action_name = kechup_rr_generate_interact_action_name($class_instance, $operation);
                do_action($action_name, $operation, $data, $result);

                //output result
                return $result;
        }

}


//These two functions ( kechup_rr_working_pediods_interact, kechup_rr_bookings_interact ) 
//is a way to interact asynchronously with the classes their name refers to.

//Tables Ajax Handler
if (!function_exists('kechup_rr_tables_interact')) {

        function kechup_rr_tables_interact() {
                $filter = new Ketchup\FilterData;
                $operation = $filter->getpost('operation');

                if (current_user_can('edit_posts') || $operation === 'getAll') {
                        $tables = new Ketchup\Table;
                        die(kechup_rr_data($tables));
                } else {
                        die(__('Not allowed Action', KETCHUP_RR_TEXTDOMAIN));
                }
        }

        add_filter('wp_ajax_nopriv_kechup_rr_tables_interact', 'kechup_rr_tables_interact');
        add_filter('wp_ajax_kechup_rr_tables_interact', 'kechup_rr_tables_interact');
}

//Bookings Ajax Handler, Bookings aren't private, so there is no condition fo this one!
if (!function_exists('kechup_rr_bookings_interact')) {

        function kechup_rr_bookings_interact() {
                $filter = new Ketchup\FilterData;
                $operation = $filter->getpost('operation');

                $allowed_actions = array('getAll', 'getQueried', 'create', 'cancelBooking');

                if (current_user_can('edit_posts') || in_array($operation, $allowed_actions)) {  
                        $bookings = new Ketchup\Booking;
                        die(kechup_rr_data($bookings));
                } else {
                        die(__('Not allowed Action', KETCHUP_RR_TEXTDOMAIN));
                }
        }

        add_action('wp_ajax_nopriv_kechup_rr_bookings_interact', 'kechup_rr_bookings_interact');
        add_action('wp_ajax_kechup_rr_bookings_interact', 'kechup_rr_bookings_interact');
}


        