# BZFlag Networking

[![Packagist](https://img.shields.io/packagist/v/allejo/bzflag-networking.php.svg)](https://packagist.org/packages/allejo/bzflag-networking.php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/allejo/bzflag-networking.php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/allejo/bzflag-networking.php/?branch=master)
[![License](https://img.shields.io/github/license/allejo/bzflag-networking.php.svg)](https://github.com/allejo/bzflag-networking.php/blob/master/LICENSE.md)

A PHP 7.1+ library for reading and handling BZFlag network packets.

BZFlag Replay files are simply the raw packets stored in a file together, so this library will let you read replay files and unpack them into PHP classes that can be serialized into JSON.

## Installation

This project is available via [Composer](https://getcomposer.org).

```bash
composer require allejo/bzflag-networking.php
```

### Related Libraries

- [`allejo/bzflag-rendering.php`](https://github.com/allejo/bzflag-rendering.php): a library for drawing radar images of BZFlag worlds from the classes provided in this package

## Usage

This library has all of the utilities for unpacking BZFlag network packets and world packets. However, the only convenience methods available in this library is reading those packets from replay files.

### Analyzing Replay Packets

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

### Embedded Worlds in Replays

As of version 1.1+, this library supports extracting the world data that's embedded inside of replay files. The BZFlag World Database is not an easy object to navigate and contains a lot of "managers" that have an inventory of textures, obstacles, world weapons, etc.

Take a look at the [`allejo/bzflag-rendering.php`](https://github.com/allejo/bzflag-rendering.php) library for example usage of how to navigate the World Database.

```php
use allejo\bzflag\replays\Replay;
use allejo\bzflag\world\WorldDatabase;

$replay = new Replay(__DIR__ . '/my-bz-replay.rec');

/** @var WorldDatabase $worldDB */
$worldDB = $replay->getHeader()->getWorldDatabase();

// or an convenience method exists too
$worldDB = $replay->getWorldDatabase();
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

This library was originally written a few times in other languages with less features. However, those other projects are now end-of-life and this project has taken their place.

- [Go](https://github.com/allejo/bzflag-networking.go)
- [Python](https://github.com/allejo/bzflag-networking.py)

## License

[MIT](./LICENSE.md)
