<?php

namespace app\controllers;

use app\models\Connection;
use app\models\Profile;
use app\models\UserSetting;

use app\services\TwitterService;

class AppController {
    public function home($req, $res) {
        $title = 'Home';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $show_connect = true;
        } else {
            $twitter = new TwitterService($req->user_id);
            $list = $twitter->home();
		}

        return compact('list', 'show_connect', 'title');
    }

    public function bookmarks($req, $res) {
        $title = 'Bookmarks';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.');
		}

        $twitter = new TwitterService($req->user_id);
        $list = $twitter->bookmarks();

        return compact('list', 'show_connect', 'title');
    }
}
