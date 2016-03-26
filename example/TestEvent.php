<?php
namespace Mamoreno\Sample;

use Mamoreno\Pesy\Event;

class TestEvent extends Event {

    public function __construct($path = "", array $data = array()) {
        parent::__construct($path, $data);
    }

    /**
     * setter for the path
     * @param $path string
     */
    public function setPath($path) {
        $this->data["event:path"] = $path;
        $this->path = $path;
    }

    /**
     * setter of the data as a whole array
     * @param array $data data of the event
     */
    public function setData(array $data) {
        $this->data = $data;
    }

    /**
     * Sets and overrides if exists, data for a given key of the data
     * @param $key
     * @param $value
     */
    public function addData($key, $value) {
        $this->data[$key] = $value;
    }
}
