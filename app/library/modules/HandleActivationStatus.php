<?php

namespace Ketchup;

class HandleActivationStatus implements PluginActivationsStatusHandlerInterface {
        
        public function install(){
                $datahandler = new PluginDataHandler();
                $datahandler->createPluginTables();
        }
        
        public function activate(){
                
        }
        
        public function deactivate(){
                
        }
        
}
