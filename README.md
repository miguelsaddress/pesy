# Pesy: PHP Event SYstem


This is an event system that allows you to broadcast events that will be handled by different entities than the emitter.

# Paths
Paths can be unique, such as "myPath:extra:thing" or it can contain a wildcard `*` to show it handles whatever subevent path such as you will see in the case of `another:path:*` which will handle all subpaths like `another:path:like:this` but *IT WILL NOT HANDLE* paths like `another:path`


# Initialization:

Create a Handler's centre. It will be the main holder of the Handlers. Technically, though any EventHandlerList would do...



```php
$handlersCentre = HandlersCentre::getWithHandlers(array(
    'TextFileDump' => new TextFileDumpSampleHandler(array("sample:*")),
    'OtherTextFileDump' => new TextFileDumpSampleHandler(array("another:path:*"))
));

```

Once you have the handlers centre or your list, you can create your emitter, that will emit to the list of handlers registered in the handlers list passed by parameter

```php
$eventEmitter = new EventEmitter($handlersCentre->getList());
```

Now, whereever in your code, you can create an event and broadcast it. If th path of the event is handled by any of the handlers, it will be processed. Otherwise, it will be ignored

```php
$e = new TestEvent();
$e->setPath("sample:path");
$e->addData("key1", "value1");
$e->addData("key2", array(1,2,3));
$eventEmitter->broadcast($e);
```

This will not be handled, since no handler is listening for this path `wontBeHandled`

```php
$e = new TestEvent();
$e->setPath("wontBeHandled");
$e->addData("wontAppear", "in text file");
$eventEmitter->broadcast($e);
```

As told before, this will be handled by the `OtherTextFileDump` handler

```php
$e = new TestEvent();
$e->setPath("another:path:like:this");
$e->addData("will appear", "in text file");
$eventEmitter->broadcast($e);
```

# Contact

You can contact me at my twitter: [@miguelsaddress](twitter.com/miguelsaddress)