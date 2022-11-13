<?php

declare(strict_types=1);

namespace App\Container\News\Data\Repository;

use App\Container\News\Entity\Doc\News;
use App\Ship\Parent\Repository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends Repository<News>
 */
class NewsRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }
}
