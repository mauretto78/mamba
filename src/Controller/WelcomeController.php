<?php

namespace Mamba\Controller;

use App\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{
    /**
     * @param Request $request
     * @param Response $response
     */
    public function welcomeAction(Application $app, Request $request, Response $response)
    {
        return $app['twig']->render('home/welcome.html.twig', []);
    }
}
