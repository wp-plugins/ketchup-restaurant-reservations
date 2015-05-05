<?php

include_once 'reservations_filters.php';

//This filter excludes reservation app from wptexturize
add_filter('no_texturize_shortcodes', 'shortcodes_to_exempt_from_wptexturize');
