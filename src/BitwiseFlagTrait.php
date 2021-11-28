<?php

declare(strict_types=1);

namespace Fanmade\Bitwise;

/**
 * Class BitwiseFlagTrait
 * @package Fanmade\Bitwise
 */
trait BitwiseFlagTrait
{
    protected function getFlag(string $name, int $flag): bool
    {
        return ($this->$name & $flag) === $flag;
    }

    protected function setFlag(string $name, int $flag, bool $value): self
    {
        if ($value) {
            $this->$name |= $flag;
        } else {
            $this->$name &= ~$flag;
        }
        
        return $this;
    }

    protected function toggleFlag(string $name, int $flag): self
    {
        $this->$name ^= $flag;

        return $this;
    }
}
