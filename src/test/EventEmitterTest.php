<?php
include_once "EventTests.php";

use Mamoreno\Pesy\Event;
use Mamoreno\Pesy\EventEmitter;
use Mamoreno\Pesy\EventHandlerList;

class EventEmitterTest extends EventTests {

    public function testAddNewHandler() {
        $e = $this->_getEventEmitter();
        $e->registerHandler("test", $this->_getEventHandlerMock());
        $this->assertNotNull($e->getHandler("test"));
    }

    public function testOverridingNameThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\OverrideEventHandlerException");

        $e = $this->_getEventEmitter();
        $handlerMock = $this->_getEventHandlerMock();
        $e->registerHandler("test", $handlerMock);
        $e->registerHandler("test", $handlerMock);
    }

    public function testGetHandler() {
        $handlerMock = $this->_getEventHandlerMock();
        $e = $this->_getEventEmitter();
        $e->registerHandler("test", $handlerMock);
        $this->assertEquals($handlerMock, $e->getHandler("test"));
    }

    public function testGetUnknownHandlerThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\UnregisteredEventHandlerException");

        $e = $this->_getEventEmitter();
        $e->getHandler("unknown");
    }

    public function testHasHandler() {
        $e = $this->_getEventEmitter();
        $e->registerHandler("test", $this->_getEventHandlerMock());
        $this->assertTrue($e->hasHandler("test"));
    }

    public function testRemoveHandler() {
        $e = $this->_getEventEmitter();
        $e->registerHandler("test", $this->_getEventHandlerMock());
        $this->assertTrue($e->hasHandler("test"));
        $e->removeHandler("test");
        $this->assertFalse($e->hasHandler("test"));
    }

    public function testRemoveUnregisteredHandlerThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\UnregisteredEventHandlerException");

        $e = $this->_getEventEmitter();
        $e->removeHandler("test");
    }

    public function testSendEventCallsHandlerCanHandle() {
        $e = $this->_getEventEmitter();
        $ev = new Event("whatever", array());

        $handlerMock = $this->_getEventHandlerMock();
        $handlerMock->expects($this->exactly(1))->method('handle');
        $e->registerHandler("myHandler", $handlerMock);
        $e->sendTo("myHandler", $ev);
    }


    public function testBroadCastEventCallsHandlerCanHandleNTimes() {
        $e = $this->_getEventEmitter();
        $ev = new Event("whatever", array());

        $handlerMock = $this->_getEventHandlerMock();
        $handlerMock->expects($this->exactly(3))->method('handle');
        $e->registerHandler("myHandler1", $handlerMock);
        $e->registerHandler("myHandler2", $handlerMock);
        $e->registerHandler("myHandler3", $handlerMock);
        $e->broadcast($ev);
    }

    public function testGlobalHandlersAreCalled() {
        $ev = new Event("whatever", array());
        $handlerMock = $this->_getEventHandlerMock();

        $eventHandlerList = new EventHandlerList();
        $eventHandlerList->register('EventHandlerMock', $handlerMock);

        $e = new EventEmitter($eventHandlerList);
        $e->registerHandler("myHandler1", $handlerMock);

        $called = $e->broadcast($ev);
        $this->assertEquals($called, 2);
    }

    public function testCanGetGlobalAndLocalHandlers() {
        $handlerMock = $this->_getEventHandlerMock();

        $eventHandlerList = new EventHandlerList();
        $eventHandlerList->register('EventHandlerMock', $handlerMock);

        $e = new EventEmitter($eventHandlerList);
        $e->registerHandler("myHandler1", $handlerMock);

        $this->assertTrue($e->hasHandler('EventHandlerMock'));
        $this->assertTrue($e->hasHandler('myHandler1'));
    }

}
