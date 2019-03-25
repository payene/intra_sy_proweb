<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhContrats;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Loonins\GrhBundle\Form\GrhContratsType;

/**
 * GrhContrats controller.
 *
 * @Route("/grhcontrats")
 */
class GrhContratsController extends Controller {

    /**
     * Lists all GrhContrats entities.
     *
     * @Route("/", name="grhcontrats")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page = null) {

        $em = $this->getDoctrine()->getManager();

//        $entities = $em->getRepository('LooninsGrhBundle:GrhContrats')->findBy(['status' => '3']);
        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->join('c.employe', 'e')
                ->where('c.status = 1')
//                ->orwhere('c.status = 2')
//                ->orwhere('c.status = 3')
                ->orwhere('c.status = 4')
                ->orderBy('e.prenoms', 'ASC')
                ->orderBy('e.nom', 'ASC');
        $entities = $query->getQuery()->getResult();


//
//        foreach ($entities as $contrat) {
//            $coll = new \ArrayObject($contrat);
//        }
        //$coll->natsort();
//        $entities = array_merge($entities,$entities);
        return ['entities' => $entities,];

        /*
          if (empty($page)) {
          $page = 1;
          }
          $nbrParPage = 10;

          $entities = $this->getContrats($nbrParPage, $page);

          return $this->render('LooninsGrhBundle:GrhContrats:index.html.twig', array(
          'entities'
          => $entities,
          'page'
          => $page,
          'nombrePage' => ceil(count($entities) / $nbrParPage)
          ));
         */
    }

    public function getContrats($nombreParPage, $page) {
        $em = $this->getDoctrine()->getManager();
        if ($page < 1) {
            //throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "' . $page . '").');
            $page = 1;
        }
        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->getQuery();
        $query->setFirstResult(($page - 1) * $nombreParPage)
                ->setMaxResults($nombreParPage);
        return new Paginator($query);
    }

    /**
     * Creates a new GrhContrats entity.
     *
     * @Route("/", name="grhcontrats_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhContrats:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new GrhContrats();
        $entity->setDate(new \DateTime());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
//        $fin = $entity->getFinReel();
        $debut = $entity->getDebut();
        $type = $entity->getType();
        $duree = $type->getDuree();
//        var_dump($debut < $fin);
        $db = $debut->format("Y-m-d");
//        $fn = $fin->format("Y-m-d");

        $fn = date("Y-m-d", strtotime("+$duree months", strtotime($db)));
        $tab = explode("-", $fn);
        $dfn = new \DateTime;
        $dfn->setDate($tab[0], $tab[1], $tab[2]);
        $dfn->setTime("00", "00", "00");
        $entity->setFinReel($dfn);
        $entity->setStatus(1);
//        if($fn2 != $fn){
//            $badDateFin = "La date de fin de contrat est incompatible avec la du"
//        }
//        var_dump($db);
//        var_dump($fn);
//        var_dump($entity->getType());
//        var_dump($entity);
//        die('ff');

        $employe = $entity->getEmploye();

        $today = date("Y-m-d", time());

        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->where('c.status != 4')
                ->andwhere('c.status != 5')
                ->andwhere('c.employe = :id')
                ->setParameter('id', $employe->getId());
        $encours = $query->getQuery()->getResult();
        $no_doublon = 0;
        foreach ($encours as $contrat) {
            if ($contrat->getFinReel()->format("Y-m-d") >= $today) {
                $no_doublon++;
            }
        }
        if ($form->isValid() && $no_doublon == 0) {

            $em->persist($entity);
            $query = $em->createQueryBuilder()
                    ->select('c')
                    ->from('LooninsGrhBundle:GrhContrats', 'c')
                    ->where('c.status = 4')
                    ->andwhere('c.employe = :id')
                    ->setParameter('id', $employe->getId());
            $expired = $query->getQuery()->getResult();

//            $expired = $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->findBy(['status' => 4, 'employe' => $entity->getEmploye()->getId()]);
//            var_dump($expired);
//            die('');
            foreach ($expired as $expire) {
                if (!empty($expire)) {
                    $expire->setStatus(5);
                }

                $em->merge($expire);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $entity->getId())));
        }
        $msg_doublon = ($no_doublon > 0) ? "Vous ne pouvez pas assigner un double contrat à cet employé." : "";
//die('No valid');
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'err_doublon' => $msg_doublon,
        );
    }

    /**
     * Creates a form to create a GrhContrats entity.
     *
     * @param GrhContrats $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrhContrats $entity) {
        $form = $this->createForm(new GrhContratsType(), $entity, array(
            'action' => $this->generateUrl('grhcontrats_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhContrats entity.
     *
     * @Route("/new", name="grhcontrats_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new GrhContrats();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'err_doublon' => "",
        );
    }

    /**
     * Displays a form to create a new GrhContrats entity.
     *
     * @Route("/renew/{employee}", name="grh_renouvellement")
     * @Method("GET")
     * @Template()
     */
    public function renewAction($employee) {
        $em = $this->getDoctrine()->getManager();
        $entity = new GrhContrats();
        $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($employee);
        $entity->setEmploye($employe);
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

        $debut = $entity->getDebut();
        $type = $entity->getType();
//        $duree = $type->getDuree();
////        var_dump($debut < $fin);
//        $db = $debut->format("d-m-Y");
//        $fn = $fin->format("Y-m-d");
//        $fn = date("d-m-Y", strtotime("+$duree months", strtotime($db)));
        $fn = $entity->getFinReel()->format("d-m-Y");

        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'finPrevu' => $fn,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhContrats entity.
     *
     * @Route("/{id}/edit", name="grhcontrats_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a GrhContrats entity.
     *
     * @param GrhContrats $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrhContrats $entity) {
        $form = $this->createForm(new GrhContratsType(), $entity, array(
            'action' => $this->generateUrl('grhcontrats_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhContrats:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        //var_dump($entity);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $debut = $entity->getDebut();
            $type = $entity->getType();
            $duree = $type->getDuree();
            $db = $debut->format("Y-m-d");
            if ($duree > 0) {
                $fn = date("Y-m-d", strtotime("+$duree months", strtotime($db)));

                $tab = explode("-", $fn);
                $dfn = new \DateTime;
                $dfn->setDate($tab[0], $tab[1], $tab[2]);
                $dfn->setTime("00", "00", "00");
                $entity->setFinReel($dfn);
                $today = new \DateTime;
                $today->setTime("00", "00", "00");
                if ($dfn < $today) {
                    $entity->setStatus(4);
                } else {
//                    die('WAWAWAW ');
                }
            } else {
                $entity->setFinReel(null);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
        }
//        var_dump($entity);
//        die('');
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
            }

//            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhcontrats'));
    }

    /**
     * Creates a form to delete a GrhContrats entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('grhcontrats_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Supprimer'))
                        ->getForm()
        ;
    }

    /**
     *
     * @Route("/", name="grhcontrats_dispo")
     * @Method("GET")
     * @Template()
     */
    public function dispoAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $status = $entity->getStatus();
        if ($status == 2) {
            $entity->setStatus(1);
        }
        if ($status == 1) {
            $entity->setStatus(2);
        }

        $em->flush($entity);
        return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
    }

    /**
     *
     * @Route("/", name="grhcontrats_rupture")
     * @Method("GET")
     * @Template()
     */
    public function ruptureAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $entity->setStatus(3);
        $entity->setFinReel(new \DateTime); //(3);
        $em->flush($entity);
        return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
    }

    /**
     *
     * @Route("/", name="grhparams")
     * @Method("GET")
     * @Template()
     */
    public function paramsAction() {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find(2);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $entity->setStatus(3);
        $em->flush($entity);
//        return $this->redirect($this->generateUrl('grhparams'));
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Lists all GrhContrats entities.
     *
     * @Route("/", name="grhalert")
     * @Method("GET")
     * @Template()
     */
    public function alertAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $qr1 = $em->createQueryBuilder();
        $qr2 = $em->createQueryBuilder();
        $qr3 = $em->createQueryBuilder();
        $qr0 = $em->createQueryBuilder();
        $today = "2015-12-01";//date("Y-m-d", time());
        /*
          //fin contrat dans un mois jour pour jour
          //fin contrat dans deux mois jour pour jour
          //fin contrat dans trois mois jour pour jour
          //fin contrat dans aujourd'hui jour pour jour
         *          
         */

        $intv_1 = date("Y-m-d", strtotime("+1 months", strtotime($today)));
        $intv_2 = date("Y-m-d", strtotime("+2 months", strtotime($today)));
        $intv_3 = date("Y-m-d", strtotime("+3 months", strtotime($today)));
        $intv_0 = date("Y-m-d", strtotime("+0 months", strtotime($today)));

        $query1 = $this->getAlertes($qr1, $intv_1);
        $query2 = $this->getAlertes($qr2, $intv_2);
        $query3 = $this->getAlertes($qr3, $intv_3);
        $query0 = $this->getAlertes($qr0, $intv_0);

        $alertes[] = $query1->getQuery()->getResult();
        $alertes[] = $query2->getQuery()->getResult();
        $alertes[] = $query3->getQuery()->getResult();
        $alert0 = $query0->getQuery()->getResult();
        $senderMail = "no-reply@prowebgroupe.com";
        $receivers = array("denis.kombate@gmail.com", "payene@payene.name");
        $serveur = $_SERVER['SERVER_ADDR'];
        foreach ($alert0 as $contrat) {
            $subject = "ALERTE CONTRAT EXPIRE";
            $message = "Le contrat " . $contrat->getType() . " de l'employé " . $contrat->getEmploye() . " expire ";

            echo $message .= " aujourd'hui. Cliquez <a href='http://$serveur/looninsfos/web/app.php/grh/grhcontrats/show/" . $contrat->getId() . "' >"
                    . "ici pour afficher les détails du dit contrat </a>";
            $this->payeneSfMailer($receivers, $senderMail, $subject, $message);
        }
        foreach ($alertes as $alerte) {
            foreach ($alerte as $contrat) {
                $contrat->setStatus(4);
                $em->flush($contrat);
                $subject = "ALERTE CONTRAT BIENTOT EXPIRE";
                $message = "Le contrat " . $contrat->getType() . " de l'employé " . $contrat->getEmploye() . " expire ";

                echo $message .= "prend fin le  " . $contrat->getFinReel()->format("d-m-Y") . ". Cliquez <a href='http://$serveur/looninsfos/web/app.php/grh/grhcontrats/show/" . $contrat->getId() . "' >"
                        . "ici pour afficher les détails du dit contrat </a>";
                $this->payeneSfMailer($receivers, $senderMail, $subject, $message);
            }
        }
        $this->regularisationDateFin($query);
        $grh_alerte = array_merge($alertes[0], $alertes[1], $alertes[2], $alert0);
//        return array(
//            'entities' => $grh_alerte 
//        );
//        var_dump($grh_alerte);
        die('');
    }

    public function getAlertes($query, $intv) {
        return $query
                        ->select("c")
                        ->from('LooninsGrhBundle:GrhContrats', 'c')
                        ->where('c.finReel = :intv_3')
                        ->setParameter('intv_3', "$intv 00:00:00")
        ;
    }

    public function regularisationDateFin($query) {
        $today = date('Y-m-d', time());
        $query
                ->select("c")
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->where('c.finReel < :today')
                ->andwhere('c.type < :today')
                ->setParameter('today', "$today 00:00:00")
        ;
        $finSup = $query->getQuery()->getResult();

        foreach ($finSup as $fin) {
            $fin->setStatus(4);
            $em = $this->getDoctrine()->getManager();
            $em->merge($fin);
        }
        $em->flush($finSup);
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
                ->setFrom($from)->setTo($to)
                ->setBody($messageBody);
        $this->get('mailer')->send($message);
    }

}
