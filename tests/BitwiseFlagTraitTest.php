<?php

declare(strict_types=1);

namespace Fanmade\Bitwise\Tests;

use Fanmade\Bitwise\Tests\Classes\TestClass;
use PHPUnit\Framework\TestCase;

class BitwiseFlagTraitTest extends TestCase
{
    public function test_simple_switch(): void
    {
        $sut = new TestClass();

        $this->assertFalse($sut->getIsOne());

        $sut->setIsOne(true);

        $this->assertTrue($sut->getIsOne());

        $sut->setIsOne(true);

        $this->assertTrue($sut->getIsOne());
        $this->assertTrue($sut->getIsOne());

        $sut->setIsOne(false);

        $this->assertFalse($sut->getIsOne());
    }

    public function test_values_are_independent(): void
    {
        $sut = new TestClass();

        $this->assertFalse($sut->getIsOne());
        $this->assertFalse($sut->getIsTwo());
        $this->assertFalse($sut->getIsThree());

        $sut->setIsTwo(true);

        $this->assertFalse($sut->getIsOne());
        $this->assertTrue($sut->getIsTwo());
        $this->assertFalse($sut->getIsThree());

        $sut->toggleTwo();

        $this->assertFalse($sut->getIsOne());
        $this->assertFalse($sut->getIsTwo());
        $this->assertFalse($sut->getIsThree());

        $sut->toggleOne();
        $sut->toggleThree();

        $this->assertTrue($sut->getIsOne());
        $this->assertFalse($sut->getIsTwo());
        $this->assertTrue($sut->getIsThree());
    }
}
