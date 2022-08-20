<?php

namespace mavoc\console;

use app\settings\Overrides;

//use mavoc\console\Args;
use mavoc\console\In;
use mavoc\console\Out;
use mavoc\console\Route;
use mavoc\console\Router;

use mavoc\core\Confs;
use mavoc\core\DB;
use mavoc\core\Email;
use mavoc\core\Hooks;
use mavoc\core\Plugins;
use mavoc\console\Route as MainRoute;
use mavoc\console\Router as MainRouter;

require_once 'mavoc/other/helpers.php';

//require_once 'Args.php';
require_once 'In.php';
require_once 'Out.php';
require_once 'Route.php';
require_once 'Router.php';

require_once 'mavoc/core/Clean.php';
require_once 'mavoc/core/Cleaners.php';
require_once 'mavoc/core/Confs.php';
require_once 'mavoc/core/DB.php';
require_once 'mavoc/core/GenericController.php';
require_once 'mavoc/core/Email.php';
require_once 'mavoc/core/Hooks.php';
require_once 'mavoc/core/HTML.php';
require_once 'mavoc/core/InternalREST.php';
require_once 'mavoc/core/Model.php';
require_once 'mavoc/core/Plugins.php';
require_once 'mavoc/core/Route.php';
require_once 'mavoc/core/Router.php';
require_once 'mavoc/core/Request.php';
require_once 'mavoc/core/Response.php';
require_once 'mavoc/core/REST.php';
require_once 'mavoc/core/Secret.php';
require_once 'mavoc/core/Session.php';
require_once 'mavoc/core/Validate.php';
require_once 'mavoc/core/Validators.php';

// Handle autoloading any other files.
// TODO: Eventually need to move this to where it can hooked.
spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    //$file = '..' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    $file = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    if(is_file($file)) {
        include $file;
    }
});

class Main {
    public $confs;
    public $db;
    public $email;
    public $envs = [];
    public $in;
    public $hooks;
    public $out;
    public $overrides;
    public $plugs;
    public $router;

    public function __construct() {
        // Load environment variables.
        $this->envs = require '.env.php';
    }

    public function conf($key, $value = null) {
        $output = $this->confs->conf($key, $value);
        $output = $this->hook('ao_conf', $output);
        $output = $this->hook('ao_conf_' . $key, $output);
        return $output;
    }

    public function dir($input) {
        $subdir = preg_replace('|[\\\/]|', DIRECTORY_SEPARATOR, $input);
        $output = ao()->env('AO_BASE_DIR') . DIRECTORY_SEPARATOR . $subdir;
        $output = $this->hook('ao_dir', $output);
        return $output;
    }

    public function env($key, $value = null) {
        // Don't try to hook if it is not available yet.
        if(!$this->confs || $key == 'AO_OUTPUT_HOOKS') {
            $output = $this->envs[$key] ?? null;
            return $output;
        } else {
            $output = $this->envs[$key];
            $output = $this->hook('ao_env', $output);
            $output = $this->hook('ao_env_' . $key, $output);
            return $output;
        }
    }

    public function file($input) {
        $subdir = preg_replace('|[\\\/]|', DIRECTORY_SEPARATOR, $input);
        $output = ao()->env('AO_BASE_DIR') . DIRECTORY_SEPARATOR . $subdir;
        $output = $this->hook('ao_file', $output);
        return $output;
    }  

    public function filter($key, $args = [], $priority = 10) {
        return $this->hooks->filter($key, $args, $priority);
    }

    public function hook($key, $item = null, $args = []) {
        return $this->hooks->hook($key, $item, $args);
    }

    public function init() {
        // These are set up here so that ao() can be used.
        // Create the hook and configuration system and then override it later like all the other classes.
        $this->hooks = new Hooks();
        $this->confs = new Confs();

        // Use this to override Plugins.
        // You will probably never need this but added just in case.
        if(is_file('..' . DIRECTORY_SEPARATOR . '.boot.php')) {
            require '..' . DIRECTORY_SEPARATOR . '.boot.php';
        }
        $this->hook('ao_start');

        // Have a separate creation and then init for hook purposes.
        // Allows setting things up in the constructor and then making 
        // additional overrides in the init() method. Specifically useful
        // for loading and activating (like plugins).
        $this->plugins = new Plugins();
        $this->plugins = $this->hook('ao_plugins', $this->plugins);
        $func = [$this->plugins, 'init'];
        $func = $this->hook('ao_plugins_init', $func);
        call_user_func($func);


        $this->hooks = $this->hook('ao_hooks', $this->hooks);
        $func = [$this->hooks, 'init'];
        $func = $this->hook('ao_hooks_init', $func);
        call_user_func($func);


        $this->confs = $this->hook('ao_confs', $this->confs);
        $func = [$this->confs, 'init'];
        $func = $this->hook('ao_confs_init', $func);
        call_user_func($func);


        // Maybe have this fixed with autoloading
        $overrides_file = ao()->env('AO_SETTINGS_DIR') . DIRECTORY_SEPARATOR . 'Overrides.php';
        $overrides_file = $this->hook('ao_overrides_file', $overrides_file);
        if(is_file($overrides_file)) {
            require_once $overrides_file;
            $this->overrides = new Overrides();
            $this->overrides = $this->hook('ao_overrides', $this->overrides);
            $func = [$this->overrides, 'init'];
            $func = $this->hook('ao_overrides_init', $func);
            call_user_func($func);
        }

        $this->hook('ao_console_ready');


        $db_use = ao()->env('DB_USE');
        $db_use = ao()->hook('ao_db_use', $db_use);

        if($db_use) {
            $this->db = new DB();
            $this->db = $this->hook('ao_db', $this->db);
            $func = [$this->db, 'init'];
            $func = $this->hook('ao_db_init', $func);
            call_user_func($func);
        }


        $this->router = new Router();
        $this->router = $this->hook('ao_router', $this->router);
        $func = [$this->router, 'init'];
        $func = $this->hook('ao_console_router_init', $func);
        call_user_func($func);


        $this->in = new In();
        $this->in = $this->hook('ao_console_in', $this->in);
        $func = [$this->in, 'init'];
        $func = $this->hook('ao_console_in_init', $func);
        call_user_func($func);


        $this->out = new Out();
        $this->out = $this->hook('ao_console_out', $this->out);
        $func = [$this->out, 'init'];
        $func = $this->hook('ao_console_out_init', $func);
        call_user_func($func);

		$this->router->route($this->in, $this->out);

        $this->hook('ao_console_end');
    }

    public function unfilter($key, $args = [], $priority = 10) {
        return $this->hooks->unfilter($key, $args, $priority);
    }

}
