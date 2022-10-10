<?php

declare(strict_types=1);

namespace App\Ship\Test\Unit\Attribute;

use App\Ship\Attribute\OnlyGuest;
use PHPUnit\Framework\TestCase;

class OnlyGuestTest extends TestCase
{
    public function testRedirectRouteReturnNull(): void
    {
        $onlyGuest = new OnlyGuest();

        $this->assertNull($onlyGuest->getRedirectRoute());
    }

    public function testRedirectRouteReturnCorrect(): void
    {
        $onlyGuest = new OnlyGuest('app.home');

        $this->assertSame('app.home', $onlyGuest->getRedirectRoute());
    }
}
