# BZFlag Networking

[![Packagist](https://img.shields.io/packagist/v/allejo/bzflag-networking.php.svg)](https://packagist.org/packages/allejo/bzflag-networking.php)
[![License](https://img.shields.io/github/license/allejo/bzflag-networking.php.svg)](https://github.com/allejo/bzflag-networking.php/blob/master/LICENSE.md)

A PHP 7.1+ library for reading and handling BZFlag network packets.

BZFlag Replay files are simply the raw packets stored in a file together, so this library will let you read replay files and unpack them into PHP classes that can be serialized into JSON.

## Usage

The recommended use of this library is to "stream" the replays in your code with the `getPacketsIterable()` method, which reads and yields a packet at a time. This allows for you to iterate through a replay file once and not have to store the entire replay in memory as you're working with it.

```php
use allejo\bzflag\replays\Replay;

$replay = new Replay(__DIR__ . '/my-bz-replay.rec');

foreach ($replay->getPacketsIterable() as $packet) {
    // do something with $packet
}
```

If you'd like to get all of a replay file's packets as an array, use the `getPacketsAsArray()` method. Keep in mind, that this will incur high-memory usage as the entire replay will be stored in an expanded format in memory. Additionally, all of the packets are read and if your code would like to perform any type of analysis, you will need to iterate through **all** of the packets again.

```php
use allejo\bzflag\networking\Packets\GamePacket;

/** @var GamePacket[] $packets */
$packets = $replay->getPacketsAsArray();
```

### Exporting to JSON

> :warning: **High Memory Usage**
>
> The built-in `json_encode` function PHP will not be able to handle writing large JSON files from replays. At this point, it's necessary to stream your JSON using something like [`streaming-json-encoder`](https://github.com/violet-php/streaming-json-encoder). See the [PHP port of `rrlog`](https://github.com/allejo/rrlog/blob/master/src/allejo/rrlog/Writer/JsonWriter.php) for sample usage.

```php
use allejo\bzflag\replays\Replay;

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
