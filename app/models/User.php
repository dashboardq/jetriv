<?php

namespace app\models;

use mavoc\core\Model;

class User extends Model {
    public static $table = 'users';

    public $con;
    public $pro;
    public $set;

    public static function create($args) {
        $login_type = ao()->env('APP_LOGIN_TYPE');
        if($login_type == 'db') {
            // Bcrypt the password
            $args['password'] = password_hash($args['password'], PASSWORD_DEFAULT);
        } else {
            $args['password'] = '';
        }

        $item = new User($args);
        $item->save();
        return $item;
    }

    public static function local($user_id) {
        $item = null;
        if(ao()->env('APP_LOGIN_TYPE') == 'list') {
            $users = ao()->env('APP_LOGIN_USERS');
            if(isset($users[$user_id])) {
                $args = [];
                $args['id'] = $user_id;
                $args['email'] = $users[$user_id]['email'];
                $item = new User($args);
            }
        }
        return $item;
    }

    public static function login($email, $password) {
        if(ao()->env('APP_LOGIN_TYPE') == 'list') {
            $users = ao()->env('APP_LOGIN_USERS');
            foreach($users as $id => $user) {
                if(
                    isset($user['email'])
                    && isset($user['password']) 
                    && $user['email'] == $email
                    && $user['password'] == $password
                ) {
                    $user = User::local($id);

                    $user->session();
                    return true;
                }
            }
        } elseif(ao()->env('APP_LOGIN_TYPE') == 'db' && ao()->env('DB_USE')) {
            $user = User::by('email', $email);

            if($user) {
                if(password_verify($password, $user->data['password'])) {

                    // TODO: Need to make this more robust.
                    unset($user->data['password']);

                    $user->session();
                    return true;
                }
            }
        }

        return false;
    }

    public function logout() {
        ao()->session->logout();
    }

    public function session() {
        ao()->session->user = $this;
        ao()->session->user_id = $this->data['id'];
    }

    // Other methods
    // Need to add error checking when doesn't exist.

    public function connection($refresh = false) {
        if($refresh || !$this->con || $this->con->id == 0) {
            $this->con = Connection::find($this->profile($refresh)->data['connection_id'] ?? 0);
        }

        return $this->con;
    }

    public function profile($refresh = false) {
        if($refresh || !$this->pro || $this->pro->id == 0) {
            $this->pro = Profile::find($this->userSetting($refresh)->data['profile_id'] ?? 0);
        }

        return $this->pro;
    }

    public function userSetting($refresh = false) {
        if($refresh || !$this->set || $this->set->id == 0) {
            $this->set = UserSetting::by('user_id', $this->id);
        }

        return $this->set;
    }
}
