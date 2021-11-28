<?php

declare(strict_types=1);

namespace Fanmade\Bitwise\Tests\Classes;

use Fanmade\Bitwise\BitwiseFlagTrait;

class TestClass
{
    use BitwiseFlagTrait;

    public const FLAG_ONE = 1 << 0;
    public const FLAG_TWO = 1 << 1;
    public const FLAG_THREE = 1 << 2;

    private int $flag = 0;

    public function getIsOne(): bool
    {
        return $this->getFlag('flag', self::FLAG_ONE);
    }

    public function setIsOne(bool $value = true): self
    {
        $this->setFlag('flag', self::FLAG_ONE, $value);
        return $this;
    }

    public function toggleOne(): self
    {
        $this->toggleFlag('flag', self::FLAG_ONE);
        return $this;
    }

    public function getIsTwo(): bool
    {
        return $this->getFlag('flag', self::FLAG_TWO);
    }

    public function setIsTwo(bool $value = true): self
    {
        $this->setFlag('flag', self::FLAG_TWO, $value);
        return $this;
    }

    public function toggleTwo(): self
    {
        $this->toggleFlag('flag', self::FLAG_TWO);
        return $this;
    }

    public function getIsThree(): bool
    {
        return $this->getFlag('flag', self::FLAG_THREE);
    }

    public function setIsThree(bool $value = true): self
    {
        $this->setFlag('flag', self::FLAG_THREE, $value);
        return $this;
    }

    public function toggleThree(): self
    {
        $this->toggleFlag('flag', self::FLAG_THREE);
        return $this;
    }
}