<?php

declare(strict_types=1);

namespace App\Container\AdminSection\User\Action;

use App\Container\User\Data\Repository\Doc\UserRepository;
use App\Container\User\Entity\Doc\User;
use App\Ship\Parent\Action;
use App\Ship\Trait\GetPageForPaginatorTrait;
use Knp\Component\Pager\Pagination\PaginationInterface;

class GetAllUsersWithPaginationAction extends Action
{
    use GetPageForPaginatorTrait;

    public const MAX_RESULTS = 4;

    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @return PaginationInterface<int, User>
     */
    public function run(string|int $page = null): PaginationInterface
    {
        $page = $this->getPage($page);

        return $this->userRepository->getAllWithPagination($page, self::MAX_RESULTS);
    }
}
