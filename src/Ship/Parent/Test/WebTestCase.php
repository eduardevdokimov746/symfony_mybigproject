<?php

declare(strict_types=1);

namespace App\Ship\Parent\Test;

use App\Ship\Trait\FindTestUserFromDBTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;

abstract class WebTestCase extends SymfonyWebTestCase
{
    use FindTestUserFromDBTrait;

    /**
     * @param array{environment?: string, debug?: bool} $options
     * @param array<string, string>                     $server
     *
     * @phpstan-ignore-next-line
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        $additionalHeaders = [
            'HTTP_ACCEPT_LANGUAGE' => 'ru',
        ];

        return parent::createClient($options, array_merge($additionalHeaders, $server));
    }
}
