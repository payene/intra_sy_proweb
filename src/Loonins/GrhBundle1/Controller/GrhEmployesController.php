<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhEmployes;
use Loonins\GrhBundle\Form\GrhEmployesType;
use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * GrhEmployes controller.
 *
 * @Route("/grhemployes")
 */
class GrhEmployesController extends Controller {

    /**
     * Lists all GrhEmployes entities.
     *
     * @Route("/", name="grhemployes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();

        // Les employés archivés
        $query2 = $em->createQueryBuilder()
                ->select('e')
                ->from('LooninsGrhBundle:GrhEmployes', 'e')
                ->where('e.trashed = 1')
                ->orderBy('e.prenoms', 'ASC')
                ->orderBy('e.nom', 'ASC');
        $empT = $query2->getQuery()->getResult();

        //  Les employés sus contrat
        $query = $em->createQuery('select e'
                . ' from LooninsGrhBundle:GrhEmployes e'
                . ' where e.trashed = 0'
                . ' and e.id in ('
                . ' select m.id'
                . ' from LooninsGrhBundle:GrhContrats c'
                . ' join c.employe m'
                . ')'
                . ' order by e.prenoms, e.nom ASC'
                );
        $entities = $query->getResult();

//        
//        
//        $entities = $em
//                ->getRepository('LooninsGrhBundle:GrhEmployes')
//                ->findAll()
//                ->orderBy('');
//        var_dump($entities)
        
        //les employés n'ayant jamais eu de contrat
        $empN = $em->createQuery('select e'
                . ' from LooninsGrhBundle:GrhEmployes e'
                . ' where e.trashed = 0'
                . ' and e.id not in ('
                . ' select m.id'
                . ' from LooninsGrhBundle:GrhContrats c'
                . ' join c.employe m'
                . ')')->getResult();
        
        //les employés stagiaires
        $empS = $em->createQuery('select e'
                . ' from LooninsGrhBundle:GrhEmployes e'
                . ' where e.trashed = 0'
                . ' and e.id in ('
                . ' select m.id'
                . ' from LooninsGrhBundle:GrhContrats c'
                . ' join c.employe m'
                . ' join c.type t'
                . ' where t.id = 7'
                . ' and c.finReel >= :today'
                . ')')
        ->setParameter('today', new \DateTime( date('Y-m-d') ))
        ->getResult();
        
        
        return array(
            'entities' => $entities,
            'empN' => $empN,
            'empS' => $empS,
            'empT' => $empT,
        );

    }


    /**
     * Lists all GrhEmployes entities.
     *
     * @Route("/grh/repertoire", name="Looninsgrh_repertoire")
     * @Method("GET")
     * @Template()
     */
    public function repertoireAction() {

        $em = $this->getDoctrine()->getManager();

        // Les employés archivés
        $query2 = $em->createQueryBuilder()
                ->select('e')
                ->from('LooninsGrhBundle:GrhEmployes', 'e')
                ->where('e.trashed = 1')
                ->orderBy('e.prenoms', 'ASC')
                ->orderBy('e.nom', 'ASC');
        $empT = $query2->getQuery()->getResult();

        //  Les employés non archivés
        $query = $em->createQuery('select e'
                . ' from LooninsGrhBundle:GrhEmployes e'
                . ' where e.trashed = 0'
                . ' order by e.nom ASC, e.prenoms ASC'
                );
        $empNT = $query->getResult();
        
        
        return array(
            'empNT' => $empNT,
            'empT' => $empT,
        );

    }

    public function annivAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $respmails = $em->getRepository("LooninsSuiviBundle:RespMail")->findAll();
        $employes = $em->getRepository("LooninsGrhBundle:GrhEmployes")->findBy( ['trashed' => FALSE] );
        
        $resp_array = [];
        //$anniv_array = [];
        
//        foreach ($respmails as $respmail) {
//            $resp_array[] = $respmail->getEmail();
//        }
        
        $grhmails = $em->getRepository("LooninsGrhBundle:GrhMail")->findAll();
        foreach ($grhmails as $grhmail) {
            
            $resp_array[] = $grhmail->getEmail();
            
        }
        
        $rep = "Aucun anniversaire aujourd'hui";        
        $today = new \DateTime();
        $existe = FALSE;
        $prochains = "";
        $prochains_array = [];
        $anniversaireux = "";
        foreach ($employes as $employe) {
            
            if( $employe->getDatenaiss()->format('m') == $today->format('m') && $employe->getDatenaiss()->format('d') > $today->format('d') )
            {
                $prochains_array[] = $employe->getNom() . " ". $employe->getPrenoms();
                $prochains .= $employe->getNom() . " ". $employe->getPrenoms() . " le ". $employe->getDatenaiss()->format('d') .", ";
            } 
            
            if( $employe->getDatenaiss()->format('d/m') == $today->format('d/m') ){
                $email = [];
                $email[] = $employe->getEmail();
                //$email[] = 'denis.kombate@gmail.com';
                //$email[] = 'manouauguste@yahoo.com';
                $html = $this->renderView("LooninsGrhBundle:GrhEmployes:anniv.html.twig", array('prenom'=> $employe->getPrenoms()));
                
                //dump($html);
                
                GrhContratsController::payeneSfMailerStatic($email, "Pro Web", "Joyeux anniversaire ". $employe->getNom() . " ". $employe->getPrenoms(), $html , $this  );
                $rep = "Au moins un employé fête son anniversaire aujourd'hui";
                $anniversaireux .= $employe->getNom() . " ". $employe->getPrenoms() . ", ";
                $existe = TRUE;
            }
            
        
        }
        
        if( $existe == TRUE ){
                if( count($prochains_array) == 0 ){
                    $prochains = "Aucun employé";
                }
                //$resp_array = ['manouauguste@yahoo.com'];
                GrhContratsController::payeneSfMailerStatic($resp_array, "Pro Web","Info anniversaire" ,$anniversaireux ." fête/fêtent son/leurs anniversaire(s) aujourd'hui<br/>Anniversaires dans les prochains jours: " . $prochains, $this, 'denis.kombate@gmail.com' );
        }
        
//        dump($anniversaireux);
//        dump($prochains_array);
        
        return new Response( $rep );
    }
    
    public function annivTestAction(Request $request) {
        
        return $this->render("LooninsGrhBundle:GrhEmployes:anniv.html.twig", array('prenom'=> 'prenom'));
    }
    
    public function annivDuMoisAction( $month ){

        if($month == 'null')
            $month = 1;

        $em = $this->getDoctrine()->getManager();
        $employes = $em->getRepository("LooninsGrhBundle:GrhEmployes")->findBy(['trashed' => FALSE]);
        $mois = (\DateTime::createFromFormat('!m', $month))->format('F');
        $annivMois = [];
        foreach ($employes as $employe) {
            
            if( $employe->getDatenaiss()->format('m') == $month )
            {
                $annivMois[] = $employe;
            }
        }

        //dump($annivMois); exit();

        return $this->render("LooninsGrhBundle:GrhEmployes:annivMois.html.twig", array('annivMois'=> $annivMois,
            'mois' => $mois,
            'num' => $month ));
    }

    /**
     * Creates a new GrhEmployes entity.
     *
     * @Route("/", name="grhemployes_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhEmployes:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new GrhEmployes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grhemployes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GrhEmployes entity.
     *
     * @param GrhEmployes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrhEmployes $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhEmployesType', $entity, array(
            'action' => $this->generateUrl('grhemployes_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhEmployes entity.
     *
     * @Route("/new", name="grhemployes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new GrhEmployes();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhEmployes entity.
     *
     * @Route("/{id}", name="grhemployes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhEmployes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $session = $this->get('session');
        $session->set('employe_id',$entity->getId());
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    public function trashAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);
        $entity->setTrashed(TRUE);

        $contrats = $em->getRepository('LooninsGrhBundle:GrhContrats')->findByEmploye($entity);

        foreach ($contrats as $contrat) {
            $contrat->setStatus(5);
            $em->persist( $contrat );
        }

        $em->persist( $entity );
        $em->flush();
        
        return $this->redirectToRoute('grhemployes');
    }

    /**
     * Displays a form to edit an existing GrhEmployes entity.
     *
     * @Route("/{id}/edit", name="grhemployes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhEmployes entity.');
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
     * Creates a form to edit a GrhEmployes entity.
     *
     * @param GrhEmployes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrhEmployes $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhEmployesType', $entity, array(
            'action' => $this->generateUrl('grhemployes_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GrhEmployes entity.
     *
     * @Route("/{id}", name="grhemployes_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhEmployes:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhEmployes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			
			
			$em->persist( $entity );
            $em->flush();

            return $this->redirect($this->generateUrl('grhemployes_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GrhEmployes entity.
     *
     * @Route("/{id}", name="grhemployes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GrhEmployes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhemployes'));
    }

    /**
     * Creates a form to delete a GrhEmployes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('grhemployes_delete', array('id' => $id)))
//            ->setMethod('DELETE')
                        ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer'))
                        ->getForm()
        ;
    }

}
