<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Board;
use AppBundle\Entity\User;
use AppBundle\Form\UserPostType;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends FOSRestController
{
    /**
     * @Route("/users", methods={"GET"})
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        $view = $this->view($users, 200);
        return $this->handleView($view);

        /* TODO: https://symfony.com/doc/current/bundles/FOSRestBundle/1-setting_up_the_bundle.html

            Serializer 를 이너블 하기위해 1~3번 항목 적용하기.
        */
    }


    /**
     * @Route("/user/{id}", methods={"GET"}, requirements={"id": "\d+"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id: ' . $id
            );
        }

        $view = $this->view($user, 200);
        return $this->handleView($view);
    }


    /**
     * @Route("/user", methods={"POST"})
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
            throw $this->createNotFoundException(
                'invalid form data'
            );
        }

        // UserPostType 으로 생성된 폼 default configure option 에 'data_class' = User Entity 로 되어있기 때문에
        // $form->getData 는 User 엔티티의 데이터가 리턴된다.
        $user = $this->get('app.service.user_service')->create($form->getData());
        $view = $this->view($user, 200);
        return $this->handleView($view);
    }


    /**
     * @Route("/user/{id}", methods={"PUT"}, requirements={"id": "\d+"})
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction(User $user, Request $request)
    {
        $form = $this->createForm(UserPostType::class, $user);
        $form->submit([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        if (!$form->isValid()) {
            throw $this->createNotFoundException(
                'invalid form data'
            );
        }

        // $user 에 id 가 존재하는경우 update 문이 실행된다.
        $user = $this->get('app.service.user_service')->update($form->getData());
        $view = $this->view($user, 200);
        return $this->handleView($view);
    }


    /**
     * @Route("/user/{id}", methods={"DELETE"}, requirements={"id": "\d+"})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(User $user)
    {
        $this->get('app.service.user_service')->delete($user);
        $view = $this->view($user, 200);
        return $this->handleView($view);
    }


    /**
     * @Route("/user/{id}/boards", methods={"GET"}, requirements={"id": "\d+"})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBoardsAction(User $user)
    {
        $boards = $this->getDoctrine()->getRepository(Board::class)
            ->findBy(
                ['user' => $user]
            );

        if (!$boards) {
            throw $this->createNotFoundException(
                'No boards found for user_id: ' . $user->getId()
            );
        }

        $view = $this->view($boards, 200);
        return $this->handleView($view);
    }
}
