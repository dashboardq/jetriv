<?php

namespace app;

class App {
    public function init() {
        ao()->filter('ao_response_partial_args', [$this, 'cacheDate']);
    }

    public function cacheDate($vars, $args) {
        $view = $args[0];
        if($view == 'head' || $view == 'footer') {
            $vars['cache_date'] = '2022-07-15';
        }

        return $vars;
    }

}
