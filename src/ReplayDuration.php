<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

class ReplayDuration implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var int */
    private $asSeconds;

    /** @var int */
    private $days;

    /** @var int */
    private $hours;

    /** @var int */
    private $minutes;

    /** @var int */
    private $seconds;

    /** @var int */
    private $usecs;

    public function __construct(int $timestamp)
    {
        $secs = $timestamp / 1000000;

        $day_len = 24 * 60 * 60;
        $this->days = (int)($secs / $day_len);
        $secs = $secs % $day_len;

        $hour_len = 60 * 60;
        $this->hours = (int)($secs / $hour_len);
        $secs = $secs % $hour_len;

        $min_len = 60;
        $this->minutes = (int)($secs / $min_len);
        $secs = $secs % $min_len;

        $this->seconds = (int)$secs;
        $this->usecs = (int)($timestamp % 1000000);

        // Short cut for accessing this duration in seconds
        $this->asSeconds =
            ($this->days * $day_len) +
            ($this->hours * $hour_len) +
            ($this->minutes * $min_len) +
            $this->seconds
        ;
    }

    /**
     * @return int
     */
    public function getAsSeconds(): int
    {
        return (int)$this->asSeconds;
    }

    /**
     * @return int
     */
    public function getDays(): int
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * @return int
     */
    public function getUsecs(): int
    {
        return $this->usecs;
    }
}
