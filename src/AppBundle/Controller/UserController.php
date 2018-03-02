<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Board;
use AppBundle\Entity\User;
use AppBundle\Form\UserPostType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        $view = $this->view($users, 200);
        $context = new Context();
        $context->setGroups(['list', 'detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(User $user)
    {
        $view = $this->view($user, 200);
        $context = new Context();
        $context->setGroups(['detail', 'User_Board']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(UserPostType::class);
        $form->submit([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        // UserPostType 으로 생성된 폼 default configure option 에 'data_class' = User Entity 로 되어있기 때문에
        // $form->getData 는 User 엔티티의 데이터가 리턴된다.
        $user = $this->get('app.service.user_service')->create($form->getData());
        $view = $this->view($user, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function putAction(User $user, Request $request)
    {
        $form = $this->createForm(UserPostType::class, $user);
        $form->submit([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        /*
         *  Form을 사용하는 경우    : isValid() 로 유효성검사를 간단히 할 수 있다.
         *  Form을 사용하지 않는경우 : validator 로 가능. 이 때는 entity 에 명시된 유효성 검사가 실행된다.
         */
//        $errors = $this->get('validator')->validate($form->getData());
//        if (count($errors) > 0) {
//            $view = $this->view(['errors' => $errors])->setStatusCode(Response::HTTP_BAD_REQUEST);
//            return $this->handleView($view);
//        }

        if (!$form->isValid()) {
            return $this->handleView($this->view($form)->setStatusCode(Response::HTTP_BAD_REQUEST));
        }

        // $user 에 id 가 존재하는경우 update 문이 실행된다.
        $user = $this->get('app.service.user_service')->update($form->getData());
        $view = $this->view($user, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }


    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(User $user)
    {
        $this->get('app.service.user_service')->delete($user);
        $view = $this->view()->setStatusCode(Request::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }


    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBoardsAction(User $user)
    {
        $boards = $this->getDoctrine()->getRepository(Board::class)
            ->findBy(
                ['user' => $user]
            );

        $view = $this->view($boards, 200);
        $context = new Context();
        $context->setGroups(['detail']);
        $view->setContext($context);

        return $this->handleView($view);
    }
}
