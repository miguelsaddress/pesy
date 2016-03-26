<?php
namespace Mamoreno\Pesy;

use Mamoreno\Pesy\EventHandler;
use Mamoreno\Pesy\EventHandlerList;
use Mamoreno\Pesy\HandlersCentre;

class EventEmitter {
    /**
     * @var EventHandlerList is the list of handlers registered to this particular emitter only
     */
    protected $handlerList;
    /**
     * @var EventHandlerList is the global list of handlers registered for the whole application
     */
    protected $handlerCentre;

    /**
     * EventEmitter constructor.
     */
    public function __construct(EventHandlerList $handlersCentreList) {

        $this->handlerList = new EventHandlerList();
        $this->handlerCentre = $handlersCentreList;
    }

    /**
     * Registers a handler onlu for this emitter
     * @param $name string with the name
     * @param EventHandler $eh
     * @throws OverrideEventHandlerException
     */
    public function registerHandler($name, EventHandler $eh) {
        $this->handlerList->register($name, $eh);
    }


    /**
     * Removes a hander only for this emitter
     * @param $name
     * @throws UnregisteredEventHandlerException
     */
    public function removeHandler($name) {
        $this->handlerList->remove($name);
    }

    /**
     * Gets a handler registered for this emitter or in handlerCentre. In that order
     * @param $name string with the name
     * @return mixed
     * @throws UnregisteredEventHandlerException
     */
    public function getHandler($name) {
        $h = $this->handlerList->get($name);
        $h = $h ? $h : $this->handlerCentre->get($name);

        return $h;
    }

    /**
     * Returns true if the emitter has a handler registered under the given name
     * in its own list or in the handlersCentre, in that order
     * @param $name string with the name
     * @return bool
     */
    public function hasHandler($name) {
        return !!($this->handlerList->has($name) || $this->handlerCentre->has($name));
    }

    /**
     * Sends a directed event to a certain handler only
     * @see getHandler()
     * @param Event $e
     * @param $handlerName string with the name
     * @throws UnregisteredEventHandlerException
     */
    public function sendTo($handlerName, Event $e) {
        return $this->getHandler($handlerName)->handle($e);
    }

    /**
     * Sends the given event to every register handler, starting by the handlers
     * registered only for this emitter and later to the handlers registered in
     * the handlers centre
     * @param Event $e
     * @return int the amount of handlers notified
     */
    public function broadcast(Event $e) {
        $i = 0;
        foreach ($this->handlerList->getHandlers() as $h) {
            $h->handle($e);
            $i ++;
        }

        $i = $i + $this->handlerCentre->broadcast($e);
        return $i;
    }

}