<?php
// Restaurant Upsell
if (!function_exists('kechup_rr_insert_upsell_btn_html')) {

        function kechup_rr_insert_upsell_btn_html($post) {
                $add_upsell_btn_label = __('Go Pro', KETCHUP_RR_TEXTDOMAIN);
                echo '<div id="go_pro">'.
                __('If you need to setup:',KETCHUP_RR_TEXTDOMAIN).'
                <ul>
                <li> - '.__('Table Positions',KETCHUP_RR_TEXTDOMAIN).'</li> 
                <li> - '.__('Working Periods & exceptions',KETCHUP_RR_TEXTDOMAIN).'</li>
                <li> - '.__('Admin notifications',KETCHUP_RR_TEXTDOMAIN).'</li> 
                <li> - '.__('Mailchimp integration and..',KETCHUP_RR_TEXTDOMAIN).'</li> 
                <li> - '.__('Premium Support',KETCHUP_RR_TEXTDOMAIN).'</li> 
                <li> - '.__('More cool features',KETCHUP_RR_TEXTDOMAIN).'</li> 
                '.__('you might want to take a look at our Pro version!',KETCHUP_RR_TEXTDOMAIN).'
                </ul>
                <a href="'.esc_url('http://ketchupthemes.com/restaurant-reservation-plugin/').'">'.__('Go Pro Now',KETCHUP_RR_TEXTDOMAIN).'</a>
                </div>';
        }

}