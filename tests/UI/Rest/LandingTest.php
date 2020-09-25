<?php

namespace App\Tests\UI\Rest;

use App\UI\Rest\Landing;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class LandingTest extends TestCase
{

    private const HTML = '<h1>Some HTML</h1>';

    public function testIndex()
    {
        $env = $this->createStub(Environment::class);
        $env->method('render')->willReturn(self::HTML);
        $landing = new Landing($env);
        $response = $landing->index();

        self::assertSame($response->getStatusCode(), 200);
        self::assertSame($response->getContent(), self::HTML);
    }
}
