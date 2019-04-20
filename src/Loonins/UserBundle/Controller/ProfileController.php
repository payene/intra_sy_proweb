<?php

namespace Loonins\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Propel\UserManager as UmP;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
     public function listAction($page = null) {
        if (empty($page)) {
            $page = 1;
        }
        $nbrParPage = 10;
//        $entities = $this->get('fos_user.user_manager')->findUsers($nbrParPage, $page);
//        $entities = $this->get('fos_user.user_manager')
//                ->findUserBy(array('userName' => $this->canonicalizeEmail($email)));
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('LooninsUserBundle:User')->findBy(array(), array('username' => 'asc'));
        return $this->render('FOSUserBundle:Profile:list.html.twig', array(
                    'entities' => $entities,
                    'page' => $page,
                    'nombrePage' => ceil(count($entities) / $nbrParPage)
        ));
    }


      public function modifyAction($userName, Request $request = null) {
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($userName);
       // $user = $this->getUser();
       // dump($user);
       // die('');
       //  if (!is_object($user) || !$user instanceof UserInterface) {
       //      throw new AccessDeniedException('This user does not have access to this section.');
       //  }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);
        // dump($form->isValid());
        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);


            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_view', array('userName' => $user->getEmail()));
                $response = new RedirectResponse($url);
            }

         //    dump($response);
        	// die();

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:modify.html.twig', array(
                    'form' => $form->createView(),
                    'userName' => $userName
        ));
    }

    /**
     * Show the user
     */
    public function viewAction($userName) {
//        $user = $this->getUser();
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($userName);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('FOSUserBundle:Profile:view.html.twig', array(
                    'user' => $user,
                    'userName' => $user->getEmail()
        ));
    }

    /**
     * Show the user
     */
    public function reintAction($userName) {
//        $user = $this->getUser();
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($userName);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $user->setPlainPassword('123456');
        
        $this->get('fos_user.user_manager')->updateUser($user);
        return $this->render('FOSUserBundle:Profile:view.html.twig', array(
                    'user' => $user,
                    'userName' => $user->getEmail()
        ));
    }
}
