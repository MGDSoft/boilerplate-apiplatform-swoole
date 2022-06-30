<?php

namespace App\Shared\Test\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractIntegrationTest extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }
}
