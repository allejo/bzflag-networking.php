# BZFlag Networking

[![Packagist](https://img.shields.io/packagist/v/allejo/bzflag-networking.php.svg)](https://packagist.org/packages/allejo/bzflag-networking.php)
[![License](https://img.shields.io/github/license/allejo/bzflag-networking.php.svg)](https://github.com/allejo/bzflag-networking.php/blob/master/LICENSE.md)

A PHP 7.1+ library for reading and handling BZFlag network packets.

BZFlag Replay files are simply the raw packets stored in a file together, so this library will let you read replay files and unpack them into PHP classes that can be serialized into JSON.

> :warning: Memory Usage
>  
> The built-in `json_encode` function PHP will not be able to handle writing large JSON files from replays. At this point, it's necessary to stream your JSON using something like [`streaming-json-encoder`](https://github.com/violet-php/streaming-json-encoder). See our built-in [`bin/rrlog`](./bin/rrlog) script for sample usage.

```php
use allejo\bzflag\networking\Replay;

$replay = new Replay(__DIR__ . '/my-bz-replay.rec');

$json = json_encode($replay, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/my-bz-replay.rec.json', $json);
```

## In Other Languages

This is the third iteration of this library. It's available in other languages:

- [Go](https://github.com/allejo/bzflag-networking.go)
- [Python](https://github.com/allejo/bzflag-networking.py)

## License

[MIT](./LICENSE.md)
