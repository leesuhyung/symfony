<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * Matches /blog exactly
     *
     * @Route("/blog/{page}", name="blog_list", requirements={"page": "\d+"})
     */
    public function listAction($page = 1)
    {
        return new Response(
            "blog_list<br>page=".$page
        );
    }

    /**
     * Matches /blog/*
     *
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function showAction($slug)
    {
        return new Response(
            "blog_list<br>slug=".$slug
        );
    }
}
