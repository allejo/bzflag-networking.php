<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Packets\MsgSetVar;
use allejo\bzflag\world\WorldDatabase;
use MathParser\Interpreting\Evaluator;
use MathParser\StdMathParser;

class BZDBManager extends BaseManager
{
    private const BZDB_RE = '/(\b_[a-zA-Z]+\b)/m';

    /** @var array<string, mixed> $databaseCache */
    private $databaseCache;

    /** @var array<string, string> $databaseRaw */
    private $databaseRaw;

    /** @var array<string, bool> $calculatedFields */
    private $calculatedFields;

    public function __construct(WorldDatabase $worldDatabase)
    {
        parent::__construct($worldDatabase);

        $this->databaseCache = [];
        $this->databaseRaw = [];
        $this->calculatedFields = [];
    }

    /**
     * @return mixed
     */
    public function getBZDBVariable(string $variable)
    {
        if (isset($this->databaseCache[$variable]))
        {
            return $this->databaseCache[$variable];
        }

        if (isset($this->calculatedFields[$variable]))
        {
            $equation = $this->databaseRaw[$variable];

            // Expand out BZDB variables recursively
            while ($this->isCalculatedField($equation))
            {
                $equation = strtr($equation, $this->databaseRaw);
            }

            $ast = (new StdMathParser())->parse($equation);

            return $this->databaseCache[$variable] = $ast->accept(new Evaluator());
        }

        /** @var string $rawValue */
        $rawValue = $this->databaseRaw[$variable];

        if (is_numeric($rawValue))
        {
            // A nasty abuse of the PHP language to automatically convert this
            // string into a float or an int
            $this->databaseCache[$variable] = $rawValue + 0; // @phpstan-ignore-line
        }
        else
        {
            $this->databaseCache[$variable] = $rawValue;
        }

        return $this->databaseCache[$variable];
    }

    /**
     * @return array<string, mixed>
     */
    public function getBZDBVariables(bool $evaluate = false): array
    {
        if (!$evaluate)
        {
            return $this->databaseRaw;
        }

        $cacheKeyCount = count(array_keys($this->databaseCache));
        $rawKeyCount = count(array_keys($this->databaseRaw));

        if ($cacheKeyCount === $rawKeyCount)
        {
            return $this->databaseCache;
        }

        foreach ($this->databaseRaw as $key => $_)
        {
            $this->getBZDBVariable($key);
        }

        return $this->databaseCache;
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
