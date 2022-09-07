<?php

namespace App\Ship\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class OnlyGuest
{
    private ?string $redirectRoute;

    public function __construct(string $redirectRoute = null)
    {
        $this->redirectRoute = $redirectRoute;
    }

    public function getRedirectRoute(): ?string
    {
        return $this->redirectRoute;
    }
}