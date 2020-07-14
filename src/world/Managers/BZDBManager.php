<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Packets\MsgSetVar;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;

class BZDBManager extends BaseManager
{
    private const BZDB_RE = '/(\b_[a-zA-Z]+\b)/m';

    /** @var array<string, mixed> $databaseCache */
    private $databaseCache;

    /** @var array<string, mixed> $databaseRaw */
    private $databaseRaw;

    /** @var array<string, bool> $calculatedFields */
    private $calculatedFields;

    /**
     * @return mixed
     */
    public function getBZDBVariable(string $variable)
    {
        if (array_key_exists($variable, $this->calculatedFields))
        {
            if (isset($this->databaseCache[$variable]))
            {
                return $this->databaseCache[$variable];
            }

            $equation = $this->databaseRaw[$variable];

            // Expand out BZDB variables recursively
            while ($this->isCalculatedField($equation))
            {
                $equation = strtr($equation, $this->databaseRaw);
            }

            $ast = (new StdMathParser())->parse($equation);

            return $this->databaseCache[$variable] = $ast->accept(new Evaluator());
        }

        return $this->databaseRaw[$variable];
    }

    public function unpackFromMsgSetVar(MsgSetVar $message): void
    {
        foreach ($message->getSettings() as $setting)
        {
            $this->databaseRaw[$setting->name] = $setting->value;

            if ($this->isCalculatedField($setting->value))
            {
                $this->calculatedFields[$setting->name] = true;
            }
        }
    }

    private function isCalculatedField(string $value): bool
    {
        return preg_match(self::BZDB_RE, $value) === 1;
    }
}
