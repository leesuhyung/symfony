<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller
{
    /**
     * @Route("/products")
     */
    public function newAction()
    {
        $messageGenerator = $this->container->get('app.message_generator');

        $message = $messageGenerator->getHappyMessage();

        return $this->json($message);
    }
}
