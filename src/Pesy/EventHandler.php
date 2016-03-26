<?php
namespace Mamoreno\Pesy;

abstract class EventHandler {

    protected $handledPaths = array("*");

    public function __construct(array $handledPaths = array("*")) {
        $this->handledPaths = $handledPaths;
    }

    public function canHandle($eventPath) {
        return $this->isHandled($eventPath);
    }

    private function _startsWith($str, $piece) {
        // search backwards starting from haystack length characters from the end
        return !!($piece === "" || strrpos($str, $piece, -strlen($str)) !== false);
    }

    private function isHandled($path) {
        $i = 0; $len = count($this->handledPaths);

        $isHandled = in_array($path, $this->handledPaths);
        while (!$isHandled && $i < $len) {
            $h = $this->handledPaths[$i++];
            if ($this->_isLastElemAWildcard($h)) {
                $handledNoWildcard = preg_replace("/(\*)$/", "", $h);
                $isHandled = !!$this->_startsWith($path, $handledNoWildcard);
            }
        }

        return $isHandled;
    }


    private function _isLastElemAWildcard($path) {
        $lastElem = substr($path, strlen($path) - 1);
        return $lastElem === "*";
    }

    public function handle(Event $ev) {
        if (!$this->canHandle($ev->getPath())) return;

        $this->preHandle($ev);
        $this->performHandling($ev);
        $this->postHandle($ev);
    }

    protected function preHandle(Event $ev) { /* do nothing, just to be extended */ return; }
    protected function postHandle(Event $ev) { /* do nothing, just to be extended */ return; }
    abstract protected function performHandling(Event $ev);
}
