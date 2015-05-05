<?php


namespace Ketchup;

/**
 * Description of FilterData
 * Provides methods for getting and sanitize data from $_POST $_REQUEST and $_GET superglobals
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
class FilterData {

        public function getpost($name_of_data, $filter = FILTER_SANITIZE_MAGIC_QUOTES) {
                return filter_input(INPUT_POST, $name_of_data, $filter);
        }
        
        public function get($name_of_data, $filter = FILTER_SANITIZE_MAGIC_QUOTES ){                
                return filter_input(INPUT_GET, $name_of_data, $filter);
        }

        public function getJSON($name_of_data, $strip_entities = false) {

                //Sometimes jquery doesn't encode right, so we use the stripslashes and convert encoding function
                //to normalize the json string before conversion.
                //Alse we should use the filter_input function but for some reason doesn't give correct result for now
                
                $result = NULL;
                if ($strip_entities) {
                        $result = htmlspecialchars_decode(stripslashes_deep($_POST[$name_of_data]));
                } else {
                        $result = mb_convert_encoding(stripslashes_deep($_POST[$name_of_data]), 'UTF-8', 'UTF-8');
                }

                return json_decode($result, true);
        }

        public function jsontoarray($json) { 
                $result = mb_convert_encoding(stripslashes_deep($json), 'UTF-8', 'UTF-8');
                return json_decode($result, true);
        }

}
