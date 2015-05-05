<?php

if (!function_exists('kechup_rr_get_confirmation_html')) {

        function kechup_rr_get_confirmation_html() {
                $empty = '';
                return sprintf('                      
Dear {{name}},

This is a reminder for your reservation at {{restaurant_name}}.

RESERVATION DETAILS
Your Name: {{name}}
Your Email: {{email}}
No of Persons: {{person_num}}
Your Phone Number: {{phone_number}}
Table: {{table_name}}
Date: {{date}}, {{start_time}} to {{end_time}}

Kind Regards,
{{restaurant_name}}%s', $empty);
        }

}

if (!function_exists('kechup_rr_get_rejection_html')) {

        function kechup_rr_get_rejection_html() {
                $empty = '';
                return sprintf('                       
Dear {{name}},

Your reservation {{booking_id}} for {{table_name}} was rejected.

Kind Regards,
{{restaurant_name}}%s', $empty);
        }

}

if (!function_exists('kechup_rr_get_cancelation_html')) {

        function kechup_rr_get_cancelation_html() {
                $empty = '';
                return sprintf('                        
Dear {{name}},

Your reservation {{booking_id}} for {{table_name}} was cancelled at your request.

Kind Regards,
{{restaurant_name}}%s', $empty);
        }

}


if (!function_exists('kechup_rr_get_pending_html')) {

        function kechup_rr_get_pending_html() {
                $empty = '';
                return sprintf('                       
Dear {{name}},

Your reservation {{booking_id}} for {{table_name}} is pending approval by {{restaurant_name}}. Soon a stuff member will confirm your reservation.

Kind Regards,
{{restaurant_name}}%s', $empty);
        }

}

if (!function_exists('kechup_rr_get_reminder_html')) {

        function kechup_rr_get_reminder_html() {
                $empty = '';
                return sprintf('                        
Dear {{name}},

This is a reminder for your reservation at {{restaurant_name}}.

RESERVATION DETAILS
Your Name: {{name}}
Your Email: {{email}}
No of Persons: {{person_num}}
Your Phone Number: {{phone_number}}
Table: {{table_name}}
Date: {{date}}, {{start_time}} to {{end_time}}

Kind Regards,
{{restaurant_name}}%s', $empty);
        }
}

