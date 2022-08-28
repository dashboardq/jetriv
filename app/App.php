<?php

namespace app;

use app\models\Cycle;

use app\services\TwitterService;

class App {
    public function init() {
        ao()->filter('ao_response_partial_args', [$this, 'cacheDate']);
        ao()->filter('ao_response_partial_args', [$this, 'cycles']);
    }

    public function cacheDate($vars, $args) {
        $view = $args[0];
        if($view == 'head' || $view == 'footer') {
            $vars['cache_date'] = '2022-08-15';
        }

        return $vars;
    }

    public function cycles($vars, $args) {
        $view = $args[0];
        if($view == 'cycles') {
            $req = $vars['req'];
            $cycles = Cycle::where('user_id', $req->user_id, 'data');

            // If there are no cycles, create the first batch of cycles.
            // Limit to 10.
            // Order by count descending.
            if(count($cycles) == 0) {
                $twitter = new TwitterService($req->user_id);
                $followings = $twitter->following(null, 10, 'followers_count', 'desc');

                foreach($followings as $following) {
                    $args = [];
                    $args['user_id'] = $req->user_id;
                    $args['name'] = '@' . $following['username'];
                    $args['link'] = '/' . $following['username'];
                    $args['type'] = 'user';
                    $args['twitter_id'] = $following['id'];
                    $args['twitter_name'] = $following['name'];
                    $args['twitter_username'] = $following['username'];
                    Cycle::create($args);
                }

                $cycles = Cycle::where('user_id', $req->user_id, 'data');
            }
            $vars['cycles'] = $cycles;
        }

        return $vars;
    }

}
