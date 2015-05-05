<?php

function kechup_rr_wptexturize_exceptions($shortcodes) {

        $excluded_shortcodes = array(
                'reservation_form'
        );
        array_merge($shortcodes, $excluded_shortcodes);

        return array_merge($shortcodes, $excluded_shortcodes);
}
