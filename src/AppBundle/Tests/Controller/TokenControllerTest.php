<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest extends WebTestCase
{
    public function testPostAction()
    {
        $client = self::createClient();

        $params = array(
            'username' => 'shlee1129@yellostory.co.kr',
            'password' => 'new1526615!'
        );

        $client->request('POST', '/api/tokens', $params);

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }
}
