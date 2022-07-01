<?php

namespace App\DevOps\Test\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/status');

        $this->assertResponseIsSuccessful();
    }
}
