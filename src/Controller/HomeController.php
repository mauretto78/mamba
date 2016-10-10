<?php

namespace Mamba\Controller;

use Mamba\Base\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    public function welcomeAction(Request $request)
    {
        return $this->render('home/welcome.html.twig');
    }
}
