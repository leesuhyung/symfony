<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BoardController extends Controller
{
    /**
     * @Route("/boards", methods={"GET"})
     */
    public function listAction()
    {
        $boards = $this->getDoctrine()->getRepository(Board::class)
            ->findAll();

        return $this->serialize($boards);
    }

    /**
     * @Route("/board/{id}", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function showAction(Board $board)
    {
        $query = $this->getDoctrine()->getRepository(Board::class)
            ->find($board);

        if (!$query) {
            throw $this->createNotFoundException(
                'No board found for id: ' . $board->getId()
            );
        }

        return $this->serialize($query);
    }

    /**
     * @Route("board/{id}/user", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function userAction($id)
    {
        $board = $this->getDoctrine()->getRepository(Board::class)
            ->find($id);

        $user = $board->getUser();

        return $this->serialize($user);
    }

    /**
     * @param $entity
     * @return JsonResponse
     */
    public function serialize($entity)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($entity, 'json');

        return new JsonResponse(
            json_decode($jsonContent)
        );
    }
}
