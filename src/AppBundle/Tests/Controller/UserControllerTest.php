<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = self::createClient();

        $client->request('GET', '/users');

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }

    public function testShowAction()
    {
        $client = self::createClient();

        $client->request('GET', '/user/1');

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }

    public function testPostAction()
    {
        $params = array(
            'name' => 'symfony',
            'email' => 'symfony@test.com',
            'password' => '123'
        );

        $client = self::createClient();
        $client->request('POST', '/user', $params);

        /*$client->request('POST', '/user', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ), json_encode($params));*/

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }

    public function testPutAction()
    {
        $params = array(
            'name' => 'shlee',
        );

        $client = self::createClient();
        $client->request('PUT', '/user/22', $params);
//        $client->request('PUT', '/user/22', array(), array(), array(), json_encode($params));

        $response = $client->getResponse();

        var_dump(json_decode($response->getContent()));
    }
}
