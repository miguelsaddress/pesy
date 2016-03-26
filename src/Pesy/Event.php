<?php
namespace Mamoreno\Pesy;

class Event {

    protected $path;
    protected $data;
    protected $created;

    function __construct($path, array $data) {
        $this->created = new \DateTime();
        $this->path = $path;
        $this->data = $data;
        $this->data["event:path"] = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function getData() {
        return $this->data;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCreatedFormatted() {
        return $this->created->format("d-m-Y H:i:s");
    }
}