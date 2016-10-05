<?php

namespace Mamba\Controller;

use App\Application;
use Symfony\Component\HttpFoundation\Request;

class HomeController
{
    public function welcomeAction(Application $app, Request $request)
    {
        return $app['twig']->render('home/welcome.html.twig', []);
    }
}
