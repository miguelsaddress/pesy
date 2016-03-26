<?php
namespace Mamoreno\Sample;

use Mamoreno\Pesy\Event;
use Mamoreno\Pesy\EventHandler;
use Mamoreno\Sample\TestEvent;

class TextFileDumpSampleHandler extends EventHandler {
    const FILE_NAME = "handlerOutput.txt";
    static private $fileHandler = null;
    private $messages = array();

    public function __construct(array $handledPaths) {
        $this->createFilePath();
        parent::__construct($handledPaths);
    }

    /** ABSTRACT METHOD TO BE IMPLEMENTED **/

    protected function performHandling(Event $ev) {
        if (!($ev instanceof TestEvent)) {
            throw new InvalidArgumentException("arg must be instance of TestEvent");
        }
        $this->addEvent($ev);
    }

    /* HANDLERS FUNCTIONALITY */

    public function __destruct() {
        $this->flush();
        fclose($this->getFileHandler());
        self::$fileHandler = null;
    }

    public function flush() {
        foreach ($this->messages as $m) {
            $this->writeLine($m);
        }
        $this->messages = array();
    }

    public function addEvent(TestEvent $ev) {
        $this->messages[] = $this->buildLogMessage($ev);
    }

    private function buildLogMessage(TestEvent $ev) {
        $data = $ev->getData();
        $p = $ev->getPath();
        $df = $ev->getCreatedFormatted();
        $data['timestamp'] = $ev->getCreated();

        $d = print_r($data, true);
        $msg = "[$df]: Event [$p], data [$d]";
        return $msg;
    }

    private function getFilePath() {
        return __DIR__ . "/handler_output/";
    }

    private function getFileFullPath() {
        return __DIR__ . "/handler_output/" . self::FILE_NAME;
    }

    private function getFileHandler() {
        if (!self::$fileHandler) {
            $fp = $this->getFileFullPath();
            self::$fileHandler = fopen($fp, "a");
        }
        return self::$fileHandler;
    }

    private function createFilePath() {
        $fp = $this->getFilePath();
        if (!file_exists($fp)) {
            mkdir($fp, 0777, true);
        }
    }

    private function writeLine($line) {
        fwrite($this->getFileHandler(), $line . PHP_EOL);
    }
}
