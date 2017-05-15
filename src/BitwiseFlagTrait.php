<?php

namespace Fanmade\Bitwise;

/**
 * Class BitwiseFlagTrait
 * @package Fanmade\Bitwise
 */
trait BitwiseFlagTrait
{

    /**
     *
     */
    protected static function bootBitwiseFlagTrait()
    {
        //
    }

    /**
     * @param $name
     * @param $flag
     * @return bool
     */
    protected function getFlag($name, $flag)
    {
        return (($this->$name & $flag) == $flag);
    }

    /**
     * @param string $name
     * @param int $flag
     * @param $value
     * @return bool
     */
    protected function setFlag($name, $flag, $value)
    {
        if ($value) {
            $this->$name |= $flag;
        } else {
            $this->$name &= ~$flag;
        }
    }
}
