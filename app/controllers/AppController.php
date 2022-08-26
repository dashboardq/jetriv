<?php

namespace app\controllers;

use app\models\Bookmark;
use app\models\Connection;
use app\models\Cycle;
use app\models\Profile;
use app\models\Todo;
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
            return compact('list', 'show_connect', 'title');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $twitter = new TwitterService($req->user_id);
            $list = $twitter->home($query['pagination_token']);
        } else {
            $twitter = new TwitterService($req->user_id);
            $list = $twitter->home($query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function bookmarks($req, $res) {
        $title = 'Bookmarks';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
        } else {
            $list = $twitter->bookmarks($query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function notifications($req, $res) {
        $title = 'Notifications';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->notifications($query['pagination_token']);
        } else {
            $list = $twitter->notifications($query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function todo($req, $res) {
        $title = 'Todos';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $query = $req->val('query', [
            'q' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            $db = ao()->db;
            $results = $db->query('SELECT tweet_id FROM todos WHERE user_id = ? AND MATCH(search) AGAINST(?) ORDER BY id DESC LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
        } else {
            $db = ao()->db;
            $results = $db->query('SELECT tweet_id FROM todos WHERE user_id = ? ORDER BY id DESC LIMIT ?', $req->user_id, 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function tweet($req, $res) {
        $title = 'Tweet Timeline';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $params = $req->val('params', [
            'username' => ['required', ['match' => '/[a-zA-Z0-9_]+/']],
            'tweet_id' => ['required', 'int'],
        ], '/home');

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->tweetTimeline($params['tweet_id'], $query['pagination_token']);
        } else {
            $list = $twitter->tweetTimeline($params['tweet_id'], $query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        return compact('list', 'query', 'show_connect', 'title');
    }

    public function username($req, $res) {
        $title = 'User Timeline';

        $connection = $req->user->connection();
        $list = [];
        $show_connect = false;
        if(!$connection) {
            $res->error('Please connect your Twitter account to continue.', '/home');
		}

        $params = $req->val('params', [
            'username' => ['required', ['match' => '/[a-zA-Z0-9_]+/']],
        ], '/home');

        $query = $req->val('query', [
            'q' => ['sometimes'],
            'pagination_token' => ['sometimes'],
        ]);

        $twitter = new TwitterService($req->user_id);
        if($query['q']) {
            /*
            $db = ao()->db;
            $results = $db->query('SELECT DISTINCT tweet_id FROM bookmarks WHERE user_id = ? AND MATCH(search) AGAINST(?) LIMIT ?', $req->user_id, $query['q'], 20);
            $ids = $db->array($results);
            $list = $twitter->tweets($ids);
             */
            $list = $twitter->userTimeline($params['username'], $query['pagination_token']);
        } else {
            $list = $twitter->userTimeline($params['username'], $query['pagination_token']);
        }

        $tweet_ids = [];
        foreach($list as $item) {
            $tweet_ids[] = $item->id;
        }

        $bookmarks = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Bookmark::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $bookmarks[$item->data['tweet_id']] = $item;
        }

        $todos = [];
        // Need to pull this by user_id too
        // Right now I'm manually stripping below but as the database grows that is not going to be optimal
        // whereIn does not currently support multiple where options which is why I'm not doing it right now.
        $temp = Todo::whereIn('tweet_id', $tweet_ids, ['user_id' => $req->user_id]);
        foreach($temp as $item) {
            $todos[$item->data['tweet_id']] = $item;
        }


        foreach($list as $i => $item) {
            if(isset($bookmarks[$item->id])) {
                $list[$i]->bookmark = $bookmarks[$item->id]->data['note'];
                $list[$i]->show_bookmark = true;
            } else {
                $list[$i]->bookmark = '';
                $list[$i]->show_bookmark = false;
            }

            if(isset($todos[$item->id])) {
                $list[$i]->todo = $todos[$item->id]->data['note'];
                $list[$i]->show_todo = true;
            } else {
                $list[$i]->todo = '';
                $list[$i]->show_todo = false;
            }
        }

        // Update the cycle list

        Cycle::call($req->user_id, $params['username']);

        return compact('list', 'query', 'show_connect', 'title');
    }

}
