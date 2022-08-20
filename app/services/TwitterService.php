<?php

namespace app\services;

use app\models\Connection;
use app\models\Profile;
use app\models\User;
use app\models\UserSetting;

use mavoc\core\Exception;
use mavoc\core\REST;

use DateTime;

class TwitterService {
    public $rest;
    public $twitter_id = '';
    public $url_base;
    public $refresh_count = 0;

    public $user;
    public $user_id;
    public $user_settings;
    public $profile;
    public $connection;

    public $timeline_args = [
        'expansions' => 'author_id,attachments.media_keys',
        'media.fields' => 'url',
        'tweet.fields' => 'attachments,created_at,public_metrics',
        'user.fields' => 'verified,profile_image_url',

        // Only load 20 to keep the response times quick.
        'max_results' => 20,
    ];


    public function __construct($user_id) {
        $this->user_id = $user_id;
        $this->user = User::find($this->user_id);

        $this->user_settings = UserSetting::by('user_id', $user_id);
        $this->profile = Profile::find($this->user_settings->data['profile_id']);
        $this->connection = Connection::find($this->profile->data['connection_id']);
        $this->rest = new REST($this->connection->data['values']['access_token']);
        $this->twitter_id = $this->profile->data['twitter_id'];

        $this->url_base = ao()->env('TWITTER_URL');
    }

    public function bookmarks() {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/bookmarks';
        $url .= '?' . http_build_query($this->timeline_args);

        $data = self::get($url);

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($data);
        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }


    public function home() {
        $twitter_id = $this->twitter_id;

        $url = '/2/users/' . $twitter_id . '/timelines/reverse_chronological';
        $url .= '?' . http_build_query($this->timeline_args);

        $data = self::get($url);

        $list = $this->processTimeline($data);

        //echo '<pre>'; print_r($data);
        //echo '<pre>'; print_r($list);
        //die;
        
        return $list;
    }

    public function me() {
    }

    public function get($url) {
        $url = $this->url_base . $url;
        $output = $this->rest->get($url);

        if(isset($output->status) && $output->status == 401) {
            $this->refresh();
            $output = $this->get($url);
        }
        return $output;
    }

    public function processTimeline($data) {
        $list = [];

        foreach($data->data ?? [] as $i => $item) {
            $list[] = $item;
            $list[$i]->created = new DateTime($item->created_at);
        }

        $users = [];
        foreach($data->includes->users ?? [] as $user) {
            $users[$user->id] = $user;
        }

        $media = [];
        foreach($data->includes->media ?? [] as $item) {
            $media[$item->media_key] = $item;
        }


        foreach($list as $i => $item) {
            $list[$i]->author = $users[$item->author_id];
            $list[$i]->media = [];
            if(isset($item->attachments->media_keys)) {
                foreach($item->attachments->media_keys as $j => $key) {
                    $list[$i]->media[] = $media[$key];
                }
            }
        }

        return $list;
    }

    public function refresh() {
        $this->refresh_count++;
        if($this->refresh_count >= 3) {
            $this->connection->delete($this->connection->id);
            throw new Exception('There was a problem connecting with Twitter. Please try connecting to Twitter again. If the issue continues, please contact support.', '/home');
        }

        $authentication = base64_encode(ao()->env('TWITTER_CLIENT_ID') . ':' . ao()->env('TWITTER_CLIENT_SECRET'));
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $authentication,
        ];
        $rest = new REST($headers);    
        // Make a curl call.   
        $url = ao()->env('TWITTER_URL_TOKEN');
        $post = [
            'refresh_token' => $this->connection->data['values']['refresh_token'],
            'grant_type' => 'refresh_token',
            'client_id' => ao()->env('TWITTER_CLIENT_ID'),
        ];
        // For some reason, it doesn't work when you passed in as an array so converting it to query args.
        // (I saw someone else on the Twitter forums describe this same issue.)
        // I don't have time right now to see what the difference is with the way curl makes the call.
        // I'm guessing it has something to do with explicitly calling the Content-Type header above.
        $values = http_build_query($post);
        $access = $rest->post($url, $values, [], true);
        //echo '<pre>'; print_r($access);die;
        //$access = $rest->post($url, $post, [], true);
        if(!isset($access['access_token'])) {
            $this->connection->delete($this->connection->id);
            throw new Exception('There was a problem connecting with Twitter. Try connecting again and if the problem continues, please contact support.', '/home');
        }

        $this->rest = new REST($access['access_token']);
        $response = $this->rest->get(ao()->env('TWITTER_URL') . '/2/users/me');
        if(!isset($response->data->id)) {
            throw new Exception('There was a problem accessing user info. Please try again and if the problem happens again, please contact support.', '/home');
        }

        $this->profile->data['twitter_name'] = $response->data->name;
        $this->profile->data['twitter_username'] = $response->data->username;
        $this->profile->save();

        $this->connection->data['user_id'] = $req->user_id;
        $this->connection->data['twitter_id'] = $response->data->id;
        $this->connection->data['data'] = $access;
        $this->connection->data['encrypted'] = 0;
        $this->connection->save();
    }
}
