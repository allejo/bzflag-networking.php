<?php


namespace allejo\bzflag\networking\World\Managers;


use allejo\bzflag\networking\JsonSerializePublicGetters;
use allejo\bzflag\networking\World\WorldDatabase;

abstract class BaseManager implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var WorldDatabase */
    protected $worldDatabase;

    public function __construct(WorldDatabase &$worldDatabase)
    {
        $this->worldDatabase = &$worldDatabase;
    }
}
