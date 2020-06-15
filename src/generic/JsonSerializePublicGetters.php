<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\generic;

/**
 * A trait to automatically allow a class to be JSON serialized by exporting all
 * of its getter methods as JSON keys.
 *
 * @internal
 */
trait JsonSerializePublicGetters
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $output = [];

        try
        {
            $fxns = $this->getExports();
        }
        catch (\ReflectionException $e)
        {
            return [
                '_error' => [
                    sprintf('JSON Serialization Error: Could not perform reflection of the %s class', __CLASS__),
                ],
            ];
        }

        foreach ($fxns as $fxn)
        {
            $key = lcfirst(substr($fxn, 3));

            if (in_array($key, $this->getJsonEncodeBlacklist(), true))
            {
                continue;
            }

            $output[$key] = $this->{$fxn}();
        }

        return $output;
    }

    /**
     * An array of keys not to include in the jsonSerialize() return value.
     *
     * @return array<int, string>
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return [];
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
            $isGetter = strpos($fxn->getName(), 'get') === 0;
            $hasNoArgs = count($fxn->getParameters()) === 0;

            if ($isGetter && $hasNoArgs)
            {
                $keys[] = $fxn->getName();
            }
        }

        return $keys;
    }
}
