<?php

namespace Ketchup;

class GeneralMeta implements PostMetaInterface {

        public function __construct() {
                
                //the markup is located in another file for clarity
                ketchup_rr_require_file('/app/admin/templates/GeneralMetaTemplates');
                
                add_action('add_meta_boxes', array($this, 'add_metaboxes'));


                return;
        }

        public function add_metaboxes() {
                $post_types = array(
                        'restaurant',
                        'notification'
                );
                foreach ($post_types as $post_type) {
                
                add_meta_box('upsell_btn', __('Go Pro!', KETCHUP_RR_TEXTDOMAIN), array($this, 'metabox_upsell_btn_callback'), $post_type, 'normal','high'); 

        
                }
        }
        
        //Upsell Btn       
        public function metabox_upsell_btn_callback($post) {
                //$content = get_post_meta($post->ID, 'working_periods', TRUE);              
                
                //printf('<meta id="current-restaurant" data-id="%d" />', $post->ID );
                
                echo    kechup_rr_insert_upsell_btn_html($post);                        
        }
}
