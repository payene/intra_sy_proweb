<?php

namespace Loonins\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsUserBundle:Default:index.html.twig');
    }
}
