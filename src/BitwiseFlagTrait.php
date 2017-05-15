<?php

namespace App\Traits;

/**
 * Class BitwiseFlagTrait
 * @package App\Traits
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
