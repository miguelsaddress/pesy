<?php
namespace Mamoreno\Pesy;

use Mamoreno\Pesy\EventHandlerList;

function with($a) {
    return $a;
}

class HandlersCentre {
    private $globalEventHanderList;
    static private $instance = null;

    private function __construct(array $handlers = null) {
        $this->globalEventHanderList = new EventHandlerList();
        foreach ($handlers as $n => $h) {
            $this->globalEventHanderList->register($n, $h);
        }
    }

    public static function getWithHandlers(array $handlers) {
        return new self($handlers);
    }

    public function get($name) {
        return $this->getList()->get($name);
    }

    public function getList() {
        return $this->globalEventHanderList;
    }

}
