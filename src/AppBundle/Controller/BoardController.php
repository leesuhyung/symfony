<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Board;
use AppBundle\Form\BoardPostType;
use AppBundle\Pagination\PaginatedCollection;
use AppBundle\Security\OwnerVoter;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardController extends FOSRestController
{
    /**
     * @QueryParam(name="entity", requirements="\d+", nullable=true, description="게시글 유형")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Current page")
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit page")
     * @param ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');

        $boards = $this->getDoctrine()->getRepository(Board::class)
            ->getFindAll($paramFetcher->all());

        // TODO: Paginate 클래스를 만들어서 서비스에 등록하고 거기서 가져오게끔 변경하기.

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($boards));
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        $items = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $items[] = $result;
        }

        $route = 'get_boards';
        $routeParams = $paramFetcher->all();
        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            /**
             * generateUrl() : 지정된 파라미터로 url 생성.
             *                 e.g. /api/boards?page=[2 == $targetPage]&[params == $routeParams]
             */
            return $this->generateUrl($route, array_merge(
                $routeParams,
                ['page' => $targetPage]
            ));
        };

        $paginatedCollection = new PaginatedCollection($items, $pagerfanta->getNbResults());
        $paginatedCollection->addLink('self', $createLinkUrl($page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }

        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        $view = $this->view($paginatedCollection, 200);
        $context = new Context();
        $context->setGroups(['list', 'detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param Board $board
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Board $board)
    {
        $view = $this->view($board, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param Board $board
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction(Board $board)
    {
        $user = $board->getUser();

        $view = $this->view($user, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(BoardPostType::class);
        $form->submit([
//            'userId' => $this->getUser()->getId(),
            'entity' => $request->get('entity'),
            'title' => $request->get('title'),
            'contents' => $request->get('contents'),
        ]);

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        // TODO: BoardPostType 에 userId 를 넣는 것과, board 서비스 메소드 인자에 user 를 넣는 것의 차이
        $user = $this->getUser();
        $board = $this->get('app.service.board_service')->create($form->getData(), $user);
        $view = $this->view($board, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @param Board $board
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @return Response
     */
    public function putAction(Board $board, Request $request)
    {
        /*
         * 1번째 인자 - 로그인한 User / 2번째 인자 - DB User
         * OwnerVoter 에서 1, 2번째 인자가 동일한 경우에만 return true. (false 일 때 Access Denied.)
         */
        $this->denyAccessUnlessGranted(OwnerVoter::EDIT, $board->getUser());

        $form = $this->createForm(BoardPostType::class, $board);
        $form->submit([
            'entity' => $request->get('entity'),
            'title' => $request->get('title'),
            'contents' => $request->get('contents'),
        ]);

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        $board = $this->get('app.service.board_service')->update($form->getData());
        $view = $this->view($board, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @param Board $board
     * @return Response
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Board $board)
    {
        $this->get('app.service.board_service')->delete($board);
        $view = $this->view()->setStatusCode(Request::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}
