<?php

declare(strict_types=1);

namespace App\Ship\Task;

use App\Ship\Parent\Task;
use RuntimeException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class GetRefererRouteByUrlTask extends Task
{
    public const ROURE      = '_route';
    public const CONTROLLER = '_controller';

    private string $exceptionMessage = 'Invalid key parameter given. Expected "%s", "%s", given "%s"';

    public function __construct(
        private RouterInterface $router
    )
    {
    }

    /**
     * @param string $refererUrl For example from $_SERVER['HTTP_REFERER']
     * @param string $key
     *
     * @return string|null Null is returned if the route is not found
     * @throws RuntimeException If the value $key is not valid. {@see GetRefererRouteByUrlTask::getAvailableKeys() Avaliable keys}
     *
     */
    public function getOnly(string $refererUrl, string $key): ?string
    {
        if (!in_array($key, $this->getAvailableKeys(), true))
            throw new RuntimeException(sprintf($this->exceptionMessage, ...[...$this->getAvailableKeys(), $key]));

        if ($refererRoute = $this->run($refererUrl))
            return $refererRoute['route'][$key];

        return null;
    }

    public function getAvailableKeys(): array
    {
        return [self::ROURE, self::CONTROLLER];
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
            $mapRoute = $this->mapRoute($this->router->match($parseUrl['path']));

            $mapRoute['parameters'] = array_merge($this->mapQueryString($refererUrl), $mapRoute['parameters']);

            return $mapRoute;
        } catch (ResourceNotFoundException) {
            return null;
        }
    }

    private function mapRoute(array $match): array
    {
        $route = array_filter($match, fn($key) => preg_match('#^_.*#', $key) === 1, ARRAY_FILTER_USE_KEY);

        $parameters = array_diff_key($match, $route);

        return [
            'route'      => $route,
            'parameters' => $parameters
        ];
    }

    private function mapQueryString(string $refererUrl): array
    {
        $queryString = [];

        parse_str(parse_url($refererUrl)['query'] ?? '', $queryString);

        return $queryString;
    }
}