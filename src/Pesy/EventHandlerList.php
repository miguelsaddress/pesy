<?php
namespace Mamoreno\Pesy;

use Mamoreno\Pesy\Event;
use Mamoreno\Pesy\EventHandler;
use Mamoreno\Pesy\Exception\OverrideEventHandlerException;
use Mamoreno\Pesy\Exception\UnregisteredEventHandlerException;

class EventHandlerList {
    private $handlers = array();

    /**
     * @param $name
     * @param EventHandler $eh
     * @throws OverrideEventHandlerException
     */
    public function register($name, EventHandler $eh) {
        if ($this->has($name)) {
            throw new OverrideEventHandlerException("An EventHandler is already defined for [$name]");
        }
        $this->handlers[$name] = $eh;
    }

    /**
     * @param $name
     * @return mixed
     * @throws UnregisteredEventHandlerException
     */
    public function get($name) {
        if (!$this->has($name)) {
            throw new UnregisteredEventHandlerException("No EventHandler is defined for [$name]");
        }
        return $this->handlers[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name) {
        return array_key_exists($name, $this->handlers);
    }

    /**
     * @param $name
     * @throws UnregisteredEventHandlerException
     */
    public function remove($name) {
        if (!$this->has($name)) {
            throw new UnregisteredEventHandlerException("No EventHandler is defined for [$name]");
        }
        unset($this->handlers[$name]);
    }

    public function getHandlers() {
        return $this->handlers;
    }

    public function broadcast(Event $e) {
        $i = 0;
        foreach ($this->getHandlers() as $h) {
            $h->handle($e);
            $i++;
        }
        return $i;
    }

}