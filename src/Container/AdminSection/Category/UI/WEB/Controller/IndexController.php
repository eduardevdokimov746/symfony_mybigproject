<?php

declare(strict_types=1);

namespace App\Container\AdminSection\Category\UI\WEB\Controller;

use App\Container\AdminSection\Category\Data\Repository\CategoryRepository;
use App\Container\AdminSection\Category\Entity\Book\Category;
use App\Container\AdminSection\Category\Task\GetAllCategoriesWithPaginationTask;
use App\Ship\Parent\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/',
    name: 'index',
    methods: 'GET'
)]
class IndexController extends Controller
{
    public function __construct(
        private GetAllCategoriesWithPaginationTask $getAllCategoriesWithPaginationTask
    )
    {
    }

    public function __invoke(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->get('p', 1);
//        $categories = $this->getAllCategoriesWithPaginationTask->run(1);
        $categories = $categoryRepository->findAll();

        $sql = 'SELECT c FROM '.Category::class.' c';

        $query = $entityManager->createQuery($sql);

        $p = $paginator->paginate($query, $page, 2);

//        $p = $categoryRepository->getAllWithPagination(0, 2);

        return $this->render('@admin_category/index.html.twig', ['categories' => $categories, 'p' => $p, 'page' => $page]);
    }
}