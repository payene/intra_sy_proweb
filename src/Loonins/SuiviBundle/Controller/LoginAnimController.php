<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\SuiviBundle\Entity\LoginAnim;
use Loonins\SuiviBundle\Form\LoginAnimType;

/**
 * LoginAnim controller.
 *
 * @Route("/loginanim")
 */
class LoginAnimController extends Controller
{
    /**
     * Lists all LoginAnim entities.
     *
     * @Route("/login/all", name="loginanim_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loginAnims = $em->getRepository('LooninsSuiviBundle:LoginAnim')->findAll( ['login' => 'ASC']);

        return $this->render('LooninsSuiviBundle:LoginAnim:index.html.twig', array(
            'loginAnims' => $loginAnims,
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
        ));
    }

    /**
     * Creates a new LoginAnim entity.
     *
     * @Route("/new/login", name="loginanim_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $loginAnim = new LoginAnim();
        $form = $this->createForm('Loonins\SuiviBundle\Form\LoginAnimType', $loginAnim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $loginAnim->setDateCreation( new \DateTime());
            $em->persist($loginAnim);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Enregistrement du login effectue avec succes!');

            return $this->redirectToRoute('loginanim_index');
        }

        return $this->render('LooninsSuiviBundle:LoginAnim:new.html.twig', array(
            'loginAnim' => $loginAnim,
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LoginAnim entity.
     *
     * @Route("/show/login/{id}", name="loginanim_show")
     * @Method("GET")
     */
    public function showAction(LoginAnim $loginAnim)
    {
        $deleteForm = $this->createDeleteForm($loginAnim);

        return $this->render('LooninsSuiviBundle:LoginAnim:show.html.twig', array(
            'loginAnim' => $loginAnim,
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LoginAnim entity.
     *
     * @Route("/edit/login/{id}", name="loginanim_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LoginAnim $loginAnim)
    {
        $deleteForm = $this->createDeleteForm($loginAnim);
        $editForm = $this->createForm('Loonins\SuiviBundle\Form\LoginAnimType', $loginAnim);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loginAnim);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Le titre du login a été modifié avec succes');
            return $this->redirectToRoute('loginanim_edit', array('id' => $loginAnim->getId()));
        }

        return $this->render('LooninsSuiviBundle:LoginAnim:edit.html.twig', array(
            'loginAnim' => $loginAnim,
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LoginAnim entity.
     *
     * @Route("/delete/login/{id}", name="loginanim_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LoginAnim $loginAnim)
    {
        $form = $this->createDeleteForm($loginAnim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $constraint = $em->getRepository('LooninsSuiviBundle:Animatrice')->findBy(['login' => $loginAnim->getId()]);
            if(empty($constraint)){
                $em->remove($loginAnim);
                $em->flush();                
                $this->get('session')->getFlashBag()->add('info', 'Login supprimé avec succes');
            }
            else{
                $this->get('session')->getFlashBag()->add('error', 'Impossible de supprimer un login qui est liés a des stats en existantes');
                return $this->redirectToRoute('loginanim_show', array('id' => $loginAnim->getId()));
            }

        }
        return $this->redirectToRoute('loginanim_index');
    }

    /**
     * Creates a form to delete a LoginAnim entity.
     *
     * @param LoginAnim $loginAnim The LoginAnim entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoginAnim $loginAnim)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loginanim_delete', array('id' => $loginAnim->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
