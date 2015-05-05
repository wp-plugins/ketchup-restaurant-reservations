<?php


namespace Ketchup;

/**
 * Description of Reservation
 * Is a Joined Tables Class without ajax access
 * 
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
class Reservation extends BasicHandler {
        
        public function __construct() {
                parent::__construct();
        }
        
        public function getRestaurantMeta($restaurant_id, array $metaStrings){
                $meta = array();
                if(!empty($metaStrings)){
                        foreach ($metaStrings as $metaString) {
                                $meta[$metaString] = get_post_meta($restaurant_id, $metaString, TRUE);
                        }   
                }
                
                return $meta;
        }
       
}
