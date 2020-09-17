<?php

declare(strict_types=1);

namespace App\UI\Rest;

use Symfony\Component\HttpFoundation\Response;

class Landing
{
    /**
     * Starting point for APP.
     * @return Response
     */
    public function index(): Response
    {
        return new Response('ok');
    }
}