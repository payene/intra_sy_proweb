<?php

namespace Loonins\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExceptionControllerControllerTest extends WebTestCase
{
    public function testShowexception()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showException');
    }

}
