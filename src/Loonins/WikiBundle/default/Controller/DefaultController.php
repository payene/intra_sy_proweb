<?php

namespace Loonins\WikiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsWikiBundle:Default:index.html.twig');
    }
}
