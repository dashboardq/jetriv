<?php

namespace plugins\Example;

class Example {
    public function __construct() {
        ao()->filter('ao_test', [$this, 'test']);
    }

    public function test($input) {
        $output = $input . '!!!';
        return $output;
    }
}
