<?php

namespace Ketchup;

/**
 *
 * @author Konstantinos Tsatsarounos  <konstantinos.tsatsarounos@gmail.com>
 */
interface PluginActivationsStatusHandlerInterface {       
        
        public function install();        
        public function activate();        
        public function deactivate();
}
