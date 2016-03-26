<?php
include_once 'vendor/autoload.php';
include_once 'TestEvent.php';
include_once 'TextFileDumpSampleHandler.php';

use Mamoreno\Sample\TestEvent;
use Mamoreno\Sample\TextFileDumpSampleHandler;
use Mamoreno\Pesy\HandlersCentre;
use Mamoreno\Pesy\EventEmitter;

$handlersCentre = HandlersCentre::getWithHandlers(array(
    'TextFileDump' => new TextFileDumpSampleHandler(array("sample:*")),
    'OtherTextFileDump' => new TextFileDumpSampleHandler(array("another:path:*"))
));

$eventEmitter = new EventEmitter($handlersCentre->getList());

$e = new TestEvent();
$e->setPath("sample:path");
$e->addData("key1", "value1");
$e->addData("key2", array(1,2,3));
$eventEmitter->broadcast($e);

$e = new TestEvent();
$e->setPath("wontBeHandled");
$e->addData("wontAppear", "in text file");
$eventEmitter->broadcast($e);

$e = new TestEvent();
$e->setPath("another:path:like:this");
$e->addData("will appear", "in text file");
$eventEmitter->broadcast($e);
