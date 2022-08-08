<?php

namespace App\Container\Locale\Task;

use App\Ship\Parent\Task;
use RuntimeException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\LocaleSwitcher;

class SwitchTask extends Task
{
    public function __construct(
        private RouterInterface $router,
        private LocaleSwitcher  $localeSwitcher,
        private array           $enabledLocales = []
    )
    {
        if (count($this->enabledLocales) > 2)
            throw new RuntimeException('Too many enabled locales. Should be maximum 2');

        if (count($this->enabledLocales) === 0)
            throw new RuntimeException('$enabledLocales variable must not be empty');
    }

    public function run(): string
    {
        $enabledLocales = $this->enabledLocales;

        $currentLocale = $this->router->getContext()->getParameter('_locale');

        $currentLocaleKey = array_search($currentLocale, $this->enabledLocales, true);

        unset($enabledLocales[$currentLocaleKey]);

        $newLocale = array_pop($enabledLocales);

        $this->localeSwitcher->setLocale($newLocale);

        return $newLocale;
    }
}