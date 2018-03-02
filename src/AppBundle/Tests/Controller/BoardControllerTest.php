<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardControllerTest extends WebTestCase
{
    public function testShowAction()
    {
        $client = self::createClient();

        $client->request('GET', '/api/boards/4/user');

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }
}
