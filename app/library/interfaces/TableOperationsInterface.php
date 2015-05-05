<?php

namespace Ketchup;

interface TableOperationsInterface {
        public function create(array $data);
        public function edit($data);
        public function delete($id);
        public function get($id);
        public function getAll( $restaurant_id );
}
