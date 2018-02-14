<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{max}", name="lucky_number")
     */
    public function numberAction($max)
    {
        /*$number = mt_rand(0, $max);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );*/

        $from = $this->getParameter('mailer_transport');
        $this->addFlash('notice', 'HAHAHA!!');

        return new Response(
            $from
        );
    }
}
