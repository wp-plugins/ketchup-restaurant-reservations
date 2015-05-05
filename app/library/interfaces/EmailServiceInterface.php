<?php

namespace Ketchup;

interface EmailServiceInterface {        
        public function getLists(array $lists);
        public function subscribe(array $user);
        public function unsubscribe(array $user);        
}
