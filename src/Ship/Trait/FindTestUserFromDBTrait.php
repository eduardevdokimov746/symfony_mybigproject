<?php

declare(strict_types=1);

namespace App\Ship\Trait;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use Exception;

trait FindTestUserFromDBTrait
{
    /**
     * @param array<string, mixed> $credentials
     *
     * @throws Exception
     */
    protected function findUserFromDB(array $credentials = ['id' => 1]): ?User
    {
        return self::getContainer()->get(UserRepository::class)->findOneBy($credentials);
    }
}
