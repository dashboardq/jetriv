<?php

namespace mavoc\core;

class Cleaners {
    public function __construct() {
    }

    // Dynamic rules: 
    // https://stackoverflow.com/questions/7026487/how-to-add-methods-dynamically
    public function __call($name, $arguments) {
        return call_user_func_array($this->{$name}, $arguments);
    }

    public function _add($name, $method) {
        $this->{$name} = $method;
    }

    public function exclaim($value) {
        // If it doesn't end with an exclamation mark, add it.
        if(!preg_match('/.*!$/', $value)) {
            $value .= '!';
        }

        return $value;
    }   
}
