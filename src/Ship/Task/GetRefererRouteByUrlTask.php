<?php

namespace App\Ship\Task;

use App\Ship\Parent\Task;
use RuntimeException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class GetRefererRouteByUrlTask extends Task
{
    public const ROURE = '_route';
    public const CONTROLLER = '_controller';

    private string $exceptionMessage = 'Invalid key parameter given. Expected "%s", "%s", given "%s"';

    public function __construct(
        private RouterInterface $router
    )
    {
    }

    /**
     * @param string $refererUrl For example from $_SERVER['HTTP_REFERER']
     *
     * @return array|null Null is returned if the route is not found
     */
    public function run(string $refererUrl): ?array
    {
        $parseUrl = parse_url($refererUrl);

        if (!empty($parseUrl['query'])) parse_str($parseUrl['query'], $queryString);

        try {
            return $this->router->match($parseUrl['path']);
        } catch (ResourceNotFoundException) {
            return null;
        }
    }

    /**
     * @param string $refererUrl For example from $_SERVER['HTTP_REFERER']
     * @param string $key
     *
     * @throws RuntimeException If the value $key is not valid. {@see GetRefererRouteByUrlTask::getAvailableKeys() Avaliable keys}
     *
     * @return string|null Null is returned if the route is not found
     */
    public function getOnly(string $refererUrl, string $key): ?string
    {
        if (!in_array($key, $this->getAvailableKeys(), true))
            throw new RuntimeException(sprintf($this->exceptionMessage, ...[...$this->getAvailableKeys(), $key]));

        if ($refererRoute = $this->run($refererUrl))
            return $refererRoute[$key];

        return null;
    }

    public function getAvailableKeys(): array
    {
        return [self::ROURE, self::CONTROLLER];
    }
}