<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LooninsSuiviBundle:Default:index.html.twig', array('name' => $name));
    }
}
