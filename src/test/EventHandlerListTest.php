<?php
include_once "EventTests.php";
use Mamoreno\Pesy\EventHandlerList;

class EventHandlerListTest extends EventTests {
    public function testAddNewHandler() {
        $l = new EventHandlerList();
        $l->register("test", $this->_getEventHandlerMock());
        $this->assertNotNull($l->get("test"));
    }

    public function testOverridingNameThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\OverrideEventHandlerException");

        $l = new EventHandlerList();
        $l->register("test", $this->_getEventHandlerMock());
        $l->register("test", $this->_getEventHandlerMock());
    }

    public function testGetHandler() {
        $h = $this->_getEventHandlerMock();
        $l = new EventHandlerList();
        $l->register("test", $h);
        $this->assertEquals($h, $l->get("test"));
    }

    public function testGetUnknownHandlerThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\UnregisteredEventHandlerException");

        $l = new EventHandlerList();
        $l->get("unknown");
    }

    public function testHasHandler() {
        $l = new EventHandlerList();
        $l->register("test", $this->_getEventHandlerMock());
        $this->assertTrue($l->has("test"));
    }

    public function testRemoveHandler() {
        $l = new EventHandlerList();
        $l->register("test", $this->_getEventHandlerMock());
        $this->assertTrue($l->has("test"));
        $l->remove("test");
        $this->assertFalse($l->has("test"));
    }

    public function testRemoveUnregisteredHandlerThrowsException() {
        $this->setExpectedException("Mamoreno\Pesy\Exception\UnregisteredEventHandlerException");

        $l = new EventHandlerList();
        $l->remove("test");
    }

}
