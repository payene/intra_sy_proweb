<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\IncidentBundle\Entity\GdpIncident;
use Loonins\IncidentBundle\Form\GdpIncidentType;
use Symfony\Component\HttpFoundation\StreamedResponse;
use FOS\UserBundle\Util\LegacyFormHelper;
/**
 * GdpIncident controller.
 *
 * @Route("/gdpincident")
 */
class GdpIncidentController extends Controller {

    /**
     * Lists all GdpIncident entities.
     *
     * @Route("/", name="gdpincident")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
            ->add('debut',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('widget' => 'single_text'))
            ->add('fin', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('widget' => 'single_text'))
            ->add('status',LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsIncidentBundle:GdpStatus', 'multiple' => false))
        ;

        $formRech = $formBuilder->getForm();
        // $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $data = $request->get('form');
            $debut = $data['debut'] . ' 00:00:00';
            $fin = $data['fin'] . ' 23:59:59';
            ;
            $st = $data['status'];
            $query = $em->createQueryBuilder();
            if ($st == 4):
                $query
                        ->select('i')
                        ->from('LooninsIncidentBundle:GdpIncident', 'i')
                        ->where("i.date >= :debut")
                        ->andwhere('i.date <= :fin')
                        ->andwhere('i.status != 4')
                        ->setParameter('debut', $debut)
                        ->orderBy('i.date', 'DESC')
                        ->setParameter('fin', $fin);
            else:
                $query
                        ->select('i')
                        ->from('LooninsIncidentBundle:GdpIncident', 'i')
                        ->where("i.date >= :debut")
                        ->andwhere('i.date <= :fin')
                        ->andwhere('i.status = :status')
                        ->orderBy('i.date', 'DESC')
                        ->setParameter('debut', $debut)
                        ->setParameter('fin', $fin)
                        ->setParameter('status', $st);
            endif;

            $entities = $query->getQuery()->getResult();
        } else {
            $entities = $em->getRepository('LooninsIncidentBundle:GdpIncident')->findBy(array(), array('date' => 'DESC'));
        }

        $categ = $em->getRepository('LooninsIncidentBundle:TypeIncident')->findAll();
        return array(
            'entities' => $entities,
            'categ' => $categ,
            'form' => $formRech->createView(),
        );
    }

    /**
     * Lists all GdpIncident entities by category.
     *
     * @Route("/classeur/classeur", name="gdp_classeur")
     * @Method("GET")
     * @Template()
     */
    public function classeurAction(Request $request, $classeur) {

        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
// On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
                ->add('debut', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('widget' => 'single_text'))
                ->add('fin', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('widget' => 'single_text'))
                ->add('status', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsIncidentBundle:GdpStatus', 'multiple' => false));
        $formRech = $formBuilder->getForm();
        // $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $data = $request->get('form');
            $debut = $data['debut'] . ' 00:00:00';
            $fin = $data['fin'] . ' 23:59:59';
            ;
            $st = $data['status'];
            $query = $em->createQueryBuilder();
            if ($st == 4):
                $query
                        ->select('i')
                        ->from('LooninsIncidentBundle:GdpIncident', 'i')
                        ->where("i.date >= :debut")
                        ->andwhere('i.date <= :fin')
                        ->andwhere('i.categorie = :classeur')
                        ->andwhere('i.status != 4')
                        ->setParameter('debut', $debut)
                        ->orderBy('i.date', 'DESC')
                        ->setParameter('fin', $fin)
                        ->setParameter('classeur', $classeur);
            else:
                $query
                        ->select('i')
                        ->from('LooninsIncidentBundle:GdpIncident', 'i')
                        ->where("i.date >= :debut")
                        ->andwhere('i.date <= :fin')
                        ->andwhere('i.categorie = :classeur')
                        ->andwhere('i.status = :status')
                        ->orderBy('i.date', 'DESC')
                        ->setParameter('debut', $debut)
                        ->setParameter('fin', $fin)
                        ->setParameter('status', $st)
                        ->setParameter('classeur', $classeur);
            endif;

            $entities = $query->getQuery()->getResult();
        } else {
            $entities = $em->getRepository('LooninsIncidentBundle:GdpIncident')->findBy(array('categorie' => $classeur), array('date' => 'DESC'));
        }
        $categ = $em->getRepository('LooninsIncidentBundle:TypeIncident')->findAll();
        $classeur = $em->getRepository('LooninsIncidentBundle:TypeIncident')->find($classeur);
        return array(
            'entities' => $entities,
            'categ' => $categ,
            'classeur' => $classeur,
            'form' => $formRech->createView(),
        );
    }

    /**
     * Creates a new GdpIncident entity.
     *
     * @Route("/", name="gdpincident_create")
     * @Method("POST")
     * @Template("LooninsIncidentBundle:GdpIncident:new.html.twig")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new GdpIncident();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $user = $this->getUser();
        $entity->setProprio($user);
        $status = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find(1);
        $entity->setStatus($status);

        $reqEmp = $entity->getCategorie()->getRequireEmployee();
        $reqTime = $entity->getCategorie()->getRequireTime();
        
        $emp = $entity->getEmploye();
        $duree = $entity->getDuree();

        $validated = true;
        // var_dump($req);
        // dump(is_null($emp));
        // die('');
//        $session = $this->getSession();
//        $session->get('event');
        $reqEmpError = 0;
        if ($reqEmp && is_null($emp)) {
            $validated = false;
            $reqEmpError = 1;
            //echo "Pas possible";
        }
        
        $reqTmError = 0;
        if ($reqTime && is_null($duree)){
            $validated = false;
            $reqTmError = 1;
        }

        $subject = $entity->getCategorie() . "  - " . $entity->getEmploye() . "  ( ". $entity->getTitre() ." )";
        if($validated) {
            //die('');
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity->setDateEnreg(new \DateTime());
                $em->persist($entity);
                $em->flush();
                $em->refresh($entity);
                $emails = $em->getRepository('LooninsIncidentBundle:GdpMails')->findAll();
                //$sendto = 'denis.kombate@gmail.com';

                $destinataires = array();
                $from = array("no-reply@prowebgroupe.com" => "Nouvel Incident PROWEB ");
                foreach ($emails as $mail) {
                    $email = $mail->getEmail();
                    if (!in_array($email, $destinataires)) {
                        $destinataires[] = $email;
                    }
                }
                if (in_array($user->getEmail(), $destinataires)) {
                    $destinataires[] = $email;
                }
                $user = $this->getUser();
                $destinataires[] = $user->getEmail();

                
//$destinataires = "denis.kombate@gmail.com";
                $message = \Swift_Message::newInstance()
                        ->setSubject( $subject )
                        ->setFrom($from)
                        ->setTo($destinataires)
                        ->setBody("" . $entity->getDescription());
//                    ->setBody('Your Mail');
//                    User a ajouté un nouvel incident à proweb le
//->setPart($this->renderView('LooninsIncidentBundle:GdpIncident:email.html.twig', array('type' => 'Nouvel incident', 'entity' => $entity), 'text/html'));
//            $done = 
                $this->get('mailer')->send($message);
//		var_dump($done);
                return $this->redirect($this->generateUrl('gdpincident_show', array('id' => $entity->getId())));
            }
        }
//die('');
//        $entity->setEmploye($emp);


        return array(
            'reqEmpError' => $reqEmpError,
            'reqTmError' => $reqTmError,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GdpIncident entity.
     *
     * @param GdpIncident $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GdpIncident $entity) {
        $form = $this->createForm('Loonins\IncidentBundle\Form\GdpIncidentType', $entity, array(
            'action' => $this->generateUrl('gdpincident_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer un incident'));

        return $form;
    }

    /**
     * Displays a form to create a new GdpIncident entity.
     *
     * @Route("/new", name="gdpincident_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new GdpIncident();
        $form = $this->createCreateForm($entity);

        return array(
            'reqEmpError' => null,
            'reqTmError' => null,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GdpIncident entity.
     *
     * @Route("/{id}", name="gdpincident_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
        $evolution = $em->getRepository('LooninsIncidentBundle:GdpVersions')->findBy(array('incident' => $entity)/* , array('updateDate' => 'DESC') */);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $user = $this->getUser();
        $updateDate = $entity->getUpdateDate();
//        die('');
        return array(
            'entity' => $entity,
            'evolution' => $evolution,
            'user' => $user,
            'updateDate' => $updateDate,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GdpIncident entity.
     *
     * @Route("/{id}/edit", name="gdpincident_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
        $user = $this->getUser();

        if (!$entity) {
//            var_dump($entity->getProprio());
//            var_dump($user);
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reqEmpError' => null,
            'reqTmError' => null,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a GdpIncident entity.
     *
     * @param GdpIncident $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GdpIncident $entity) {
        $form = $this->createForm('Loonins\IncidentBundle\Form\GdpIncidentType', $entity, array(
            'action' => $this->generateUrl('gdpincident_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GdpIncident entity.
     *
     * @Route("/{id}", name="gdpincident_update")
     * @Method("PUT")
     * @Template("LooninsIncidentBundle:GdpIncident:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
        $oldCategory = $entity->getCategorie();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $entity->setCategorie($oldCategory);
        // $req = $entity->getCategorie()->getRequireEmployee();
        // $emp = $entity->getEmploye();
//        var_dump($req);
//        
//        $session = $this->getSession();
//        $session->get('event');

        $reqEmp = $entity->getCategorie()->getRequireEmployee();
        $reqTime = $entity->getCategorie()->getRequireTime();
        
        $emp = $entity->getEmploye();
        $duree = $entity->getDuree();

        $validated = true;
        // var_dump($req);
        // dump(is_null($emp));
        // die('');
//        $session = $this->getSession();
//        $session->get('event');
        $reqEmpError = 0;
        if ($reqEmp && is_null($emp)) {
            $validated = false;
            $reqEmpError = 1;
            //echo "Pas possible";
        }
        
        $reqTmError = 0;
        if ($reqTime && is_null($duree)){
            $validated = false;
            $reqTmError = 1;
        }




        // $reqEmpError = 0;
        // if ($req && is_null($emp)) {
        //     $reqEmpError = 1;
        // } 
        if($validated) {
            if ($editForm->isValid()) {

                $em->flush();
                $em->refresh($entity);
                return $this->redirect($this->generateUrl('gdpincident_show', array('id' => $id)));
            }
        }
//        var_dump($editForm->getErrors());

        return array(
            'reqEmpError' => $reqEmpError,
            'reqTmError' => $reqTmError,
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GdpIncident entity.
     *
     * @Route("/{id}", name="gdpincident_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GdpIncident entity.');
            }
            $versions = $em->getRepository('LooninsIncidentBundle:GdpVersions')->findBy(array("incident" => $entity));
//            var_dump($versions);
            foreach ($versions as $evolution) {
                $em->remove($evolution);
                $em->flush();
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('gdpincident'));
    }

//    public function deleteVersionsAction($id) {
//        $em = $this->getDoctrine()->getManager();
//
//        $incident = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
//
//        $versions = $em->getRepository('LooninsIncidentBundle:GdpVersions')->findBy(array("incident" => $incident));
//
//        var_dump($versions);
//        
//        if (count($versions) != null) {
////            foreach ()
//        }
////        $incident = $entity->getIncident();
////        $em->remove($entity);
//
//
//        $form = $this->createDeleteForm($id);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $incident = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
//
//            if (!$entity) {
//                throw $this->createNotFoundException('Unable to find GdpIncident entity.');
//            }
//
//            $em->remove($entity);
//            $em->flush();
//        }
////        die('dddoodo');
//        return $this->redirect($this->generateUrl('gdpincident'));
//    }

    /**
     * Creates a form to delete a GdpIncident entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('gdpincident_delete', array('id' => $id)))
//                        ->setMethod('DELETE')
                        ->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer l\'incident courant'))
                        ->getForm()
        ;
    }

    /**
     * Cloture an existing GdpIncident entity.
     *
     * @Route("/{id}", name="gdpincident_cloture")
     * @Method("PUT")
     * @Template()
     */
    public function clotureAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpIncident')->find($id);
        $statuss = $em->getRepository('LooninsIncidentBundle:GdpStatus')->findBy(array('num' => 3));
        $status = isset($statuss[0]) ? $statuss[0] : null;
        if (!$entity || !$status instanceof \Loonins\IncidentBundle\Entity\GdpStatus) {
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }
        $entity->setStatus($status);
        $entity->setClotureDate(new \DateTime());
        $user = $this->getUser();
        $entity->setCLoser($user);



                $emails = $em->getRepository('LooninsIncidentBundle:GdpMails')->findAll();
                //$sendto = 'denis.kombate@gmail.com';

                $destinataires = array();
                $from = array("no-reply@prowebgroupe.com" => "Nouvel Incident PROWEB ");
                foreach ($emails as $mail) {
                    $email = $mail->getEmail();
                    if (!in_array($email, $destinataires)) {
                        $destinataires[] = $email;
                    }
                }
                if (in_array($user->getEmail(), $destinataires)) {
                    $destinataires[] = $email;
                }
                $user = $this->getUser();
                $destinataires[] = $user->getEmail();
//$destinataires = "denis.kombate@gmail.com";
                $message = \Swift_Message::newInstance()
                        ->setSubject('Cloture Incident '. $entity->getTitre())
                        ->setFrom($from)
                        ->setTo($destinataires)
                        ->setBody("" . $entity->getDescription());
//                    ->setBody('Your Mail');
//                    User a ajouté un nouvel incident à proweb le
//->setPart($this->renderView('LooninsIncidentBundle:GdpIncident:email.html.twig', array('type' => 'Nouvel incident', 'entity' => $entity), 'text/html'));
//            $done = 
                $this->get('mailer')->send($message);










//        $deleteForm = $this->createDeleteForm($id);
//        $editForm = $this->createEditForm($entity);
//        $editForm->handleRequest($request);
//
//        if ($editForm->isValid()) {
        $em->flush();
//            echo "IN VAL";
        return $this->redirect($this->generateUrl('gdpincident', array('id' => $id)));
//        }
//        var_dump($editForm->getErrors());
//        return array(
//            'entity' => $entity,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        );
    }

    /**
     * @Route("/", name="gdpexport")
     * @Template()
     */
    public function exportAction() {
// get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {
            $em = $container->get('doctrine')->getManager();

// The getExportQuery method returns a query that is used to retrieve
// all the objects (lines of your csv file) you need. The iterate method
// is used to limit the memory consumption
            $results = $em->getRepository('LooninsWikiBundle:GdpIncident')->getExportQuery()->iterate();
            $handle = fopen('incident', 'a+');

            while (false !== ($row = $results->next())) {
// add a line in the csv file. You need to implement a toArray() method
// to transform your object into an array
                fputcsv($handle, $row[0]->toArray());
// used to limit the memory consumption
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
        var_dump($response);
        return $response;
    }

    /**
     * @Route("/", name="gdpexport")
     * @Template()
     */
    public function rechercheAction() {
        
    }

}
