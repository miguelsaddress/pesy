<?php

ini_set('display_errors', 1);
ini_set('session.auto_start', 0);
error_reporting(E_ALL^E_NOTICE^E_DEPRECATED);
include_once dirname(dirname(__DIR__)) . '/bootstrap.php';

use Mamoreno\Pesy\EventHandler;
use Mamoreno\Pesy\EventEmitter;
use Mamoreno\Pesy\HandlersCentre;

class EventTests extends PHPUnit_Framework_TestCase {

    protected function _getEventHandlerMock(array $paths = array("*")) {
        $handlerMock = 
            $this->getMockBuilder('Mamoreno\Pesy\EventHandler')
            ->setConstructorArgs(array($paths))
            ->setMethods(array('handle'))
            ->getMockForAbstractClass();
        return $handlerMock;
    }

    protected function _getHandlerCentreList() {
        $handlers = array('EventHandlerMock' => $this->_getEventHandlerMock());
        $centre = HandlersCentre::getWithHandlers($handlers);
        return $centre->getList();
    }

    protected function _getEventEmitter() {
        return new EventEmitter($this->_getHandlerCentreList());
    }
    
}