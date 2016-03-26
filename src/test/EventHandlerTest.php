<?php
include_once "EventTests.php";

class EventHandlerTest extends EventTests {
    
    public function testAllPathsValid() {
        $h = $this->_getEventHandlerMock();
        $this->assertTrue($h->canHandle("whatever"));
    }

    public function testJustSomePathsValid() {
        $h = $this->_getEventHandlerMock((array("a:b", "a:c", "a:b:c")));
        $this->assertFalse($h->canHandle("whatever"));
        $this->assertFalse($h->canHandle("a:b:d"));
        $this->assertTrue($h->canHandle("a:b"));
        $this->assertTrue($h->canHandle("a:c"));
        $this->assertTrue($h->canHandle("a:b:c"));
    }

    public function testWildcardPaths() {
        $h = $this->_getEventHandlerMock((array("a:b:*", "a:c:*", "a:b:c:d:*")));
        // a:b:c.d.* is redundant with a:b:*
        $this->assertFalse($h->canHandle("a"));
        $this->assertFalse($h->canHandle("a:b"));
        $this->assertFalse($h->canHandle("a:c"));
        $this->assertTrue($h->canHandle("a:b:c"));
        $this->assertTrue($h->canHandle("a:b:c:d"));
        $this->assertTrue($h->canHandle("a:b:w:z"));
        $this->assertTrue($h->canHandle("a:b:c:w:z"));
    }

}
