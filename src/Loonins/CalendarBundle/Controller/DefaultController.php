<?php

namespace Loonins\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsCalendarBundle:Default:index.html.twig');
    }
}
