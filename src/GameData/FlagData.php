<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class FlagData implements \JsonSerializable
{
    public $index;
    public $abbv;
    public $status;
    public $endurance;
    public $owner;
    public $position;
    public $launchPos;
    public $landingPos;
    public $flightTime;
    public $flightEnd;
    public $initialVelocity;

    public function jsonSerialize()
    {
        return [
            'index' => $this->index,
            'abbv' => $this->abbv,
            'status' => $this->status,
            'endurance' => $this->endurance,
            'owner' => $this->owner,
            'position' => $this->position,
            'launchPos' => $this->launchPos,
            'landingPos' => $this->landingPos,
            'flightTime' => $this->flightTime,
            'flightEnd' => $this->flightEnd,
            'initialVelocity' => $this->initialVelocity,
        ];
    }
}
