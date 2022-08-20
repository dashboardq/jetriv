<?php

namespace mavoc\core;

// Currently recommend the order from less specific to more specific
// ao()->hook('some_event', $event);
// ao()->hook('some_event_' . $details, $event);
class Hooks {
    public $filters = [];
    public $id = -1;
    public $keys = [];

    public function __construct() {
    }

    public function init() {
    }

    public function filter($key, $callback, $priority = 10) {
        if(!isset($this->filters[$key])) {
            $this->filters[$key] = [];
        }

        if(!isset($this->filters[$key][$priority])) {
            $this->filters[$key][$priority] = [];
        }

        $this->id++;
        $this->filters[$key][$priority][$this->id] = $callback;
        $this->keys[$this->id] = [
            'key' => $key,
            'priority' => $priority,
            'id' => $this->id,
        ];
        ksort($this->filters[$key], SORT_NUMERIC);

        return $this->id;
    }

    public function hook($key, $item = null, $args = []) {
        if(ao()->env('AO_OUTPUT_HOOKS')) {
            echo $key;
            echo '<br>';
        }
        if(isset($this->filters[$key])) {
            foreach($this->filters[$key] as $group) {
                foreach($group as $filter) {
                    $item = call_user_func($filter, $item, $args);
                }
            }
        }

        return $item;
    }

    public function unfilter($key, $callback = null, $priority = 10) {
        // If no callback, then an id was passed in.
        if(!$callback) {
            $id = $key;
            if(isset($this->keys[$id])) {
                $keys = $this->keys[$id];
                unset($this->filters[$keys['key']][$keys['priority']][$id]);
            }
        } else {
            $id = false;
            foreach($this->filters[$key] as $tmp_priority => $group) {
                foreach($group as $tmp_id => $filter) {
                    if($priority == $tmp_priority && $callback == $filter) {
                        $id = $tmp_id;
                        break 2;
                    }
                }
            }

            if($id !== false) {
                unset($this->filters[$key][$priority][$id]);
            }
        }
    }
}
