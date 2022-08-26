<?php

namespace app\models;

use mavoc\core\Model;

class Cycle extends Model {
    public static $table = 'cycles';
    public static $order = ['updated_at' => 'desc'];
    public static $limit = 20;

    public static function call($user_id, $username) {
        if($username) {
            $cycle = self::by(['user_id' => $user_id, 'twitter_username' => $username]); 
            if(!$cycle) {
                $args = [];
                $args['user_id'] = $user_id;
                $args['name'] = '@' . $username;
                $args['link'] = '/' . $username;
                $args['twitter_username'] = $username;
                Cycle::create($args);
            } else {
                $cycle->update();
            }
        }
    }

}
