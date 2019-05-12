<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

trait JsonSerializePublicGetters
{
    public function jsonSerialize(): array
    {
        $output = [];
        $fxns = $this->getExports();

        foreach ($fxns as $fxn)
        {
            $key = lcfirst(substr($fxn, 3));

            $output[$key] = $this->{$fxn}();
        }

        return $output;
    }

    private function getExports(): array
    {
        $keys = [];
        $reflect = new \ReflectionClass($this);
        $fxns = $reflect->getMethods(\ReflectionProperty::IS_PUBLIC);

        foreach ($fxns as $fxn)
        {
            if (substr($fxn->getName(), 0, 3) === 'get')
            {
                $keys[] = $fxn->getName();
            }
        }

        return $keys;
    }
}
