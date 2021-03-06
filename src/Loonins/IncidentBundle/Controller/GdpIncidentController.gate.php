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
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
                ->add('debut', 'date', array('widget' => 'single_text'))
                ->add('fin', 'date', array('widget' => 'single_text'))
                ->add('status', 'entity', array('class' => 'LooninsIncidentBundle:GdpStatus', 'property' => 'status', 'multiple' => false));
        $formRech = $formBuilder->getForm();
        $request = $this->get('request');
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
    public function classeurAction($classeur) {

        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
                ->add('debut', 'date', array('widget' => 'single_text'))
                ->add('fin', 'date', array('widget' => 'single_text'))
                ->add('status', 'entity', array('class' => 'LooninsIncidentBundle:GdpStatus', 'property' => 'status', 'multiple' => false));
        $formRech = $formBuilder->getForm();
        $request = $this->get('request');
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity->setProprio($user);
        $status = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find(1);
        $entity->setStatus($status);

        $req = $entity->getCategorie()->getRequireEmployee();
        $emp = $entity->getEmploye();
            //        var_dump($emp);
            //        
            //        $session = $this->getSession();
            //        $session->get('event');
        $reqEmpError = 0;
        if ($req && is_null($emp)) {
            $reqEmpError = 1;
            //echo "Pas possible";
        } else {
            //die('');
            if ($form->isValid()) {
                $senderMail = "no-reply@prowebgroupe.com";
                $receivers = array("denis.kombate@gmail.com", "blog@payene.name");
                $this->payeneSfMailer($receivers, $senderMail, "MAILER INCIDENT", '$messag' . date('d-m-Y H:i;s', time()) . '');

                
                die(' sf ');

            }
        }
        //die('');
        //        $entity->setEmploye($emp);


        return array(
            'reqEmpError' => $reqEmpError,
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
        $form = $this->createForm(new GdpIncidentType(), $entity, array(
            'action' => $this->generateUrl('gdpincident_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enrégistrer un icident'));

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
        $user = $this->container->get('security.context')->getToken()->getUser();
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
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$entity) {
//            var_dump($entity->getProprio());
//            var_dump($user);
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reqEmpError' => null,
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
        $form = $this->createForm(new GdpIncidentType(), $entity, array(
            'action' => $this->generateUrl('gdpincident_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enrégistrer les modifications'));

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

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpIncident entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $req = $entity->getCategorie()->getRequireEmployee();
        $emp = $entity->getEmploye();
//        var_dump($req);
//        
//        $session = $this->getSession();
//        $session->get('event');
        $reqEmpError = 0;
        if ($req && is_null($emp)) {
            $reqEmpError = 1;
        } else {
            if ($editForm->isValid()) {
                $em->flush();
                $em->refresh($entity);
                return $this->redirect($this->generateUrl('gdpincident_show', array('id' => $id)));
            }
        }
//        var_dump($editForm->getErrors());

        return array(
            'reqEmpError' => $reqEmpError,
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
                        ->add('submit', 'submit', array('label' => 'Supprimer l\'incident courant'))
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity->setCLoser($user);

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

    public function payeneSfMailer(array $receivers, $senderMail, $subject, $messageBody) {
        $from = array("$senderMail" => $subject);
        $to = array();
        foreach ($receivers as $receiver) {
            if (!in_array($receiver, $to)) {
                $to[] = $receiver;
            }
        }
        $message = \Swift_Message::newInstance()
                ->setSubject('')
                ->setFrom($from)->setTo('')
                ->setBody($messageBody);
        $this->get('mailer')->send($message);
    }
}
