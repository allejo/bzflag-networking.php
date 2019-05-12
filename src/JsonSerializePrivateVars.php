<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

trait JsonSerializePrivateVars
{
    public function jsonSerialize(): array
    {
        $output = [];
        $keys = $this->getPrivateVariables();

        foreach ($keys as $key)
        {
            $output[$key] = $this->{$key};
        }

        return $output;
    }

    private function getPrivateVariables(): array
    {
        $keys = [];
        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop)
        {
            $keys[] = $prop->getName();
        }

        return $keys;
    }
}
