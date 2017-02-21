<?php

namespace TastPHP\Tests\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class HomeController extends \Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('home/index.html.twig', [
        ]);
    }
}