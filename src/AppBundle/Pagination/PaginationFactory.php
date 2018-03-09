<?php
namespace AppBundle\Pagination;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;

class PaginationFactory
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param $qb
     * @param $limit
     * @param $page
     * @param $route
     * @param array $routeParams
     * @return PaginatedCollection
     */
    public function createCollection($qb, $limit, $page, $route, array $routeParams)
    {
        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        $items = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $items[] = $result;
        }

        $paginatedCollection = new PaginatedCollection();
        $paginatedCollection->setItems($items);
        $paginatedCollection->setTotal($pagerfanta->getNbResults());

        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            /**
             * generate() : 지정된 파라미터로 url 생성.
             *                 e.g. /api/boards?page=[2 == $targetPage]&[params == $routeParams]
             */
            return $this->router->generate($route, array_merge(
                $routeParams,
                ['page' => $targetPage]
            ));
        };

        $paginatedCollection->addLink('self', $createLinkUrl($page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }

        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}