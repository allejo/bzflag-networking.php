<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

/**
 * A trait to automatically allow a class to be JSON serialized by exporting all
 * of its getter methods as JSON keys.
 */
trait JsonSerializePublicGetters
{
    public function jsonSerialize(): array
    {
        $output = [];
        $fxns = $this->getExports();

        foreach ($fxns as $fxn)
        {
            $key = lcfirst(substr($fxn, 3));

            if (in_array($key, $this->getJsonEncodeBlacklist()))
            {
                continue;
            }

            $output[$key] = $this->{$fxn}();
        }

        return $output;
    }

    /**
     * Get an array of getter class methods that should be called in the
     * `jsonSerialize()` process.
     *
     * @throws \ReflectionException
     *
     * @return string[]
     */
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

    /**
     * An array of keys not to include in the jsonSerialize() return value.
     *
     * @return array
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return [];
    }
}
