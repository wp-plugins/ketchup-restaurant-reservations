<?php

namespace Ketchup;

class TemplateParser {

        public $site = NULL;        
        private $properties = NULL;

        public function __construct($properties = NULL) {
                $this->site = array(
                        'title' => get_bloginfo('name'),
                        'description' => get_bloginfo('description'),
                        'url' => get_bloginfo('url'),
                        'admin_email' => get_bloginfo('admin_email')
                );
               $this->properties = $properties;
               if(is_object($properties)){
                       $this->properties = $this->parseObject($properties, array());
               }
        }
        
        public function parseObject($object, $default_values){
                $parsed = get_object_vars($object);                
                return array_merge( $default_values, $parsed );
        }

        public function parseTemplate($template) {
                foreach ($this->properties as $property => $value) {
                        $template = str_replace('{{' . $property . '}}', $value, $template);
                }
                return $template;
        }

        public function parseNotification($id) {                
                $notification = get_post_meta($id, 'notification_template', TRUE);
                return $this->parseTemplate($notification);
        }

}
