<?php

declare(strict_types=1);

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
    ) {
    }

    /**
     * @param string $refererUrl For example from $_SERVER['HTTP_REFERER']
     *
     * @return null|string Null is returned if the route is not found
     *
     * @throws RuntimeException If the value $key is not valid. {@see GetRefererRouteByUrlTask::getAvailableKeys() Avaliable keys}
     */
    public function getOnly(string $refererUrl, string $key): ?string
    {
        if (!in_array($key, $this->getAvailableKeys(), true)) {
            throw new RuntimeException(sprintf($this->exceptionMessage, ...[...$this->getAvailableKeys(), $key]));
        }

        if (null !== $refererRoute = $this->run($refererUrl)) {
            return $refererRoute['route'][$key];
        }

        return null;
    }

    /**
     * @return array<self::*>
     */
    public function getAvailableKeys(): array
    {
        return [self::ROURE, self::CONTROLLER];
    }

    /**
     * @param string $refererUrl For example from $_SERVER['HTTP_REFERER']
     *
     * @return null|array{
     *     route: array<string, string>,
     *     parameters: array<int|string, list<mixed>|mixed>
     * } Null is returned if the route is not found
     */
    public function run(string $refererUrl): ?array
    {
        $parseUrl = parse_url($refererUrl);

        if (!isset($parseUrl['path'])) {
            return null;
        }

        if (isset($parseUrl['query']) && '' !== $parseUrl['query']) {
            parse_str($parseUrl['query'], $queryString);
        }

        try {
            $mapRoute = $this->mapRoute($this->router->match($parseUrl['path']));

            $mapRoute['parameters'] = array_merge($this->mapQueryString($refererUrl), $mapRoute['parameters']);

            return $mapRoute;
        } catch (ResourceNotFoundException) {
            return null;
        }
    }

    /**
     * @param array<string, mixed> $match
     *
     * @return array{
     *     route: array<string, string>,
     *     parameters: array<int|string, list<mixed>|mixed>
     * }
     */
    private function mapRoute(array $match): array
    {
        $callback = static fn (string $key): bool => 1 === preg_match('#^_.*#', $key);

        /** @var array<string, string> $route */
        $route = array_filter($match, $callback, ARRAY_FILTER_USE_KEY);

        $parameters = array_diff_key($match, $route);

        return [
            'route' => $route,
            'parameters' => $parameters,
        ];
    }

    /**
     * @return array<array<int|string>|string>
     */
    private function mapQueryString(string $refererUrl): array
    {
        $queryString = [];

        parse_str(parse_url($refererUrl)['query'] ?? '', $queryString);

        return $queryString;
    }
}
