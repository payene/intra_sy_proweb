<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsIncidentBundle:Default:index.html.twig');
    }
}
