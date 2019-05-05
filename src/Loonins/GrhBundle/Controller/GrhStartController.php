<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class GrhStartController extends Controller {


     /*
     * @Route("/recherche/avancee", name="grhsearch_advanced")
     * @Method("GET")
     * @Template()
     */

    public function advancedSearchAction(Request $request) {
        $advancedformRech = $this->createAdvancedSearchForm();
        $em = $this->getDoctrine()->getManager();
        $advancedformRech->handleRequest($request);
        $data = $request->get('form');
        $debut1 = $data['dateEntreeD'];
        $debut2 = $data['dateEntreeF'];

        $naissD = $data['naissD'];
        $naissF = $data['naissF'];

        $employe = $data['employee'];
        $lieuNaiss = $data['lieuNaiss'];
        $departement = $data['departement'];
        $sitMat = $data['sitMat'];
        $assurance = intval($data['assurance']);
        // dump($data);
        $query = $em->createQueryBuilder()
            ->select('e')
            ->from('LooninsGrhBundle:GrhEmployes', 'e')
            ->leftJoin('e.departement', 'd')
            ->where('1 = 1');

            if(!empty($debut1)){
                $debut1 .= " 00:00:00";
                $query->andwhere("e.dateentree >= :debut1");
                $query->setParameter('debut1', $debut1);       
            }
            
            if($assurance  == 0 || $assurance == 1){
                $query->andwhere("e.assurance = $assurance");
            }

            if(!empty($naissD)){
                $naissD;
                $query->andwhere("e.datenaiss >= '$naissD'");
            }

            if(!empty($naissF)){
                $naissF;
                $query->andwhere("e.datenaiss <= '$naissF'");
            }

            if(!empty($lieuNaiss)){
                $query->andwhere("e.lieuNaiss LIKE :lieuNaiss");
                $query->setParameter('lieuNaiss', "%$lieuNaiss%" );       
            }

            if(!empty($sitMat)){
                $query->andwhere("e.sitMat = :mat");
                $query->setParameter('mat', $sitMat);
            }

            if(!empty($debut2)){
                $debut2 .= " 23:59:59";
                $query->andwhere("e.dateentree <= :debut2");
                $query->setParameter('debut2', $debut2);
            }

            if(!empty($employe) ){
                $query->andwhere("e.phone LIKE :phone OR e.nom LIKE :nom OR e.prenoms LIKE :prenom");
                $query->setParameter('prenom', "%$employe%");
                $query->setParameter('nom', "%$employe%");
                $query->setParameter('phone', "%$employe%");
            }

            if(!empty($departement)){
                $query->andwhere("e.departement = :dep");
                $query->setParameter('dep', $departement);
            }
            $query->orderBy('e.nom', 'ASC');
        // dump($query->getQuery());

        $entities = $query->getQuery()->getResult();

        // dump($entities);

        return $this->render('LooninsGrhBundle:GrhStart:advanced.search.html.twig', array(
            'entities' => $entities,
            "form" => $advancedformRech->createView()
        ));
    }


    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $query1 = $em->createQueryBuilder();
        $query2 = $em->createQueryBuilder();
        $query3 = $em->createQueryBuilder();
        $query5 = $em->createQueryBuilder();
        $query7 = $em->createQueryBuilder();
        $query8 = $em->createQueryBuilder();

        $tab1 = $this->contratEnCours($query1);
        $tab2 = $this->contratSuspendu($query2);
        $tab3 = $this->contratResilie($query3);
        $tab5 = $this->contratATerme($query5);
        $tab7 = $this->contratStage($query7);
        $tabOnTop = $this->contratAttenteArchive($query8);
                
        return $this->render('LooninsGrhBundle:GrhStart:index.html.twig', array(
            'tab1' => $tab1,
            'tab2' => $tab2,
            'tab3' => $tab3,
            'tab4' => $tab5,
            'tab7' => $tab7,
            'tabOnTop' => $tabOnTop,
        ));
    }

    /*
     * @Route("/recherche", name="grhsearch")
     * @Method("GET")
     * @Template()
     */

    public function searchAction(Request $request) {
        $formRech = $this->createSearchForm();

        $formRech = $this->createSearchForm();
        $em = $this->getDoctrine()->getManager();
        $formRech->handleRequest($request);

        return $this->render('LooninsGrhBundle:GrhStart:search.html.twig', array(
                    'resultats' => [] ,
                    "form" => $formRech->createView()
        ));
    }

    public function resultAction(Request $request) {
        $data = $request->get('form');

        $form = $this->createSearchForm();
        $form->handleRequest($request);
        $entities = array();
        // die('');
        // dump($data);
        if ($form->isValid()) {
            $debutA = $data ["debutD"];
            $debutB = $data ["finD"];

            $finA = $data ["debutF"];
            $finB = $data ["finF"];

            $type = $data ["type"];

            $status = intval($data["status"]);

            $tri = $data ["tri"];

            $em = $this->getDoctrine()->getManager();
            $query = $em->createQueryBuilder()
                    ->select('c')
                    ->from('LooninsGrhBundle:GrhContrats', 'c')
                    ->join('c.employe', 'e')
                    ->join('c.type', 't')
                    ->where('1= 1');

            if (!empty($debutA)) {
                $query->andwhere("c.debut >= '$debutA'");
            }

            if (!empty($debutB)) {
                $query->andwhere("c.debut <= '$debutB'");
            }

            if (!empty($finA)) {
                $query->andwhere("c.finReel >= '$finA'");
            }

            if (!empty($finB)) {
                $query->andwhere("c.finReel <= '$finB'");
            }

            if (!empty($type)) {
                $query->andwhere("c.type = $type");
            }


            if (!empty($status)) {
                $query->andwhere("c.status = $status");
            }

            if ($tri != "") {
                switch ($tri) {
                    case 1:
                        $field = "c.debut";
                        $order = "ASC";
                        break;
                    case 2:
                        $field = "c.debut";
                        $order = "DESC";
                        break;
                    case 3:
                        $field = "c.finReel";
                        $order = "ASC";
                        break;
                    case 4:
                        $field = "c.finReel";
                        $order = "DESC";
                        break;
                    case 5:
                        $field = "c.date";
                        $order = "ASC";
                        break;
                    case 6:
                        $field = "c.date";
                        $order = "DESC";
                        break;
                }

                $query->orderBy("$field", "$order");
            } else {
                $query->orderBy('e.prenoms', 'ASC')
                        ->orderBy('e.nom', 'ASC');
            }
            // dump($query->getQuery());
            $entities = $query->getQuery()->getResult();
        }
        return $this->render('LooninsGrhBundle:GrhStart:search.html.twig', array(
                    'resultats' => $entities,
                    'form' => $form->createView()
        ));
    }

    public function resultatsAction($entities = array()) {
        return array(
            'resultats' => $entities,
        );
    }

    public function createAdvancedSearchForm(){
        $formBuilder = $this->createFormBuilder();
        $action = $this->generateUrl('grhsearch_advanced');

        $formBuilder
            ->add('employee', TextType::class, array('required' => false))
            ->add('naissD', TextType::class, array('required' => false))
            ->add('naissF', TextType::class, array('required' => false))
            ->add('lieuNaiss', TextType::class, array('required' => false))
            ->add('sitMat', EntityType::class, array('required' => false,'class' => 'LooninsGrhBundle:Matrimonial', 'multiple' => false))
            ->add('departement', EntityType::class,  array('required' => false,'class' => 'LooninsGrhBundle:GrhDepartement', 'multiple' => false))
            ->add('dateEntreeD', TextType::class, array('required' => false))
            ->add('dateEntreeF', TextType::class, array('required' => false))
            ->add('assurance', ChoiceType::class, array('required' => false,'choices' => ["" => 2,"NON" => 0 ,"OUI" => 1],'required' => true,'preferred_choices' => array(""),'multiple' => false))
             ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Rechercher'));
        ;

        $formBuilder->setAction($action);
        $formBuilder->setMethod("POST");
        return $formRech = $formBuilder->getForm();
    }



    public function createSearchForm() {
        $formBuilder = $this->createFormBuilder();
        $action = $this->generateUrl('grhresult');
        $formBuilder
                ->add('debutD', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('required' => false, 'widget' => 'single_text'))
                ->add('finD', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('required' => false, 'widget' => 'single_text'))
                ->add('debutF', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('required' => false, 'widget' => 'single_text'))
                ->add('finF', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('required' => false, 'widget' => 'single_text'))
                ->add('type', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('required' => false, 'label' => 'Type de contrat', 'class' => 'LooninsGrhBundle:GrhType', 'multiple' => false))
                ->add('status', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\ChoiceType'), array('label' => 'Status', 'choices' =>
                    array(
                        "En cours" => 1,
                        "Suspendu" => 2,
                        "Resilié"  => 3,
                        "A terme"  => 4,
                        "Archives" => 5,
                    ),
                    'required' => false,
                    'preferred_choices' => array("En cours"),
                    'multiple' => false))
                    ->add('tri', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\ChoiceType'), array('label' => 'Trier par', 'choices' =>
                        array(
                            "Date debut Croissant" =>1,
                            "Date debut Decroissant" => 2,
                            "Date fin Croissant" => 3,
                            "Date fin Decroissant" => 4,
                            "Date saisie Croissant" => 5,
                            "Date saisie Decroissant" => 6,
                        ),
                        'required' => false,
                        'multiple' => false)
                    )
                ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Rechercher'));
        $formBuilder->setAction($action);
        $formBuilder->setMethod("POST");
        return $formRech = $formBuilder->getForm();
    }


    public function contratEnCours($query) {
        $query
            ->select('c1')
            ->from('LooninsGrhBundle:GrhContrats', 'c1')
            ->join('c1.employe', 'e1')
            ->where('c1.status = 1')
            ->andWhere('e1.trashed = 0')
            ->orderBy('e1.prenoms', 'ASC')
            ->orderBy('e1.nom', 'ASC');
        return $liste = $query->getQuery()->getResult();
    }

    public function contratSuspendu($query) {
        $query
            ->select('c2')
            ->from('LooninsGrhBundle:GrhContrats', 'c2')
            ->join('c2.employe', 'e2')
            ->where('c2.status = 2')
            ->orderBy('e2.prenoms', 'ASC')
            ->orderBy('e2.nom', 'ASC');

        return $liste = $query->getQuery()->getResult();
    }

    public function contratResilie($query) {
        $query
            ->select('c3')
            ->from('LooninsGrhBundle:GrhContrats', 'c3')
            ->join('c3.employe', 'e3')
            ->where('c3.status = 3')
            ->orderBy('e3.prenoms', 'ASC')
            ->orderBy('e3.nom', 'ASC');
        return $liste = $query->getQuery()->getResult();
    }

    public function contratATerme($query) {
        $query
            ->select('c5')
            ->from('LooninsGrhBundle:GrhContrats', 'c5')
            ->join('c5.employe', 'e5')
            ->where('c5.status = 5')
            ->orderBy('e5.prenoms', 'ASC')
            ->orderBy('e5.nom', 'ASC');
        return $liste = $query->getQuery()->getResult();
    }

    public function contratStage($query) {
        $query
            ->select('c7')
            ->from('LooninsGrhBundle:GrhContrats', 'c7')
            ->join('c7.employe', 'e7')
            ->where('c7.status = 1')
            ->andWhere('c7.type = 7')
            ->andWhere('e7.trashed = 0')
            ->orderBy('e7.prenoms', 'ASC')
            ->orderBy('e7.nom', 'ASC');
        return $liste = $query->getQuery()->getResult();
    }

    public function contratAttenteArchive($query) {
        $query
            ->select('c8')
            ->from('LooninsGrhBundle:GrhContrats', 'c8')
            ->join('c8.employe', 'e8')
            ->where('c8.status = 4')
            ->andWhere('e8.trashed = 0')
            ->orderBy('e8.prenoms', 'ASC')
            ->orderBy('e8.nom', 'ASC');
        return $liste = $query->getQuery()->getResult();
    }

     /*
     * @Route("/wishes/christams", name="wishes_christmas")
     * @Method("GET")
     * @Template()
     */
    public function merryChristmsAction(Request $request){

        $messageBody = "<br/><img src='http://www.prowebgroupe.com/intra_sy/web/uploads/proweb-merry-christmas.jpg' >";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query
            ->select('e.email')
            ->from('LooninsGrhBundle:GrhEmployes', 'e')
            ->where('e.trashed = 0')
            ->orderBy('e.email', 'ASC')
        ;
        $liste = $query->getQuery()->getScalarResult();
        foreach ($liste as $key => $email) {
            # code...
            $receivers[] = $email['email'];
        }
        $receivers[] = "denis.kombate@gmail.com";
        $receivers[] = "krakitan@prowebgroupe.com";
        // var_dump($receivers);
        // $liste[] = "krakitan@gmail.com";
        GrhContratsController::payeneSfMailerStatic($receivers, "no-reply@prowebgroupe.com", "Joyeux Noel ". date('Y'), $messageBody, $this);

        $response = new Response(json_encode( ['status' => 1] ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

     /*
     * @Route("/wishes/newyear", name="wishes_newyear")
     * @Method("GET")
     * @Template()
     */
    public function newyearAction(Request $request){

        $messageBody = "<br/><img src='http://www.prowebgroupe.com/intra_sy/web/uploads/proweb-happy-newyear.jpg' >";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query
            ->select('e.email')
            ->from('LooninsGrhBundle:GrhEmployes', 'e')
            ->where('e.trashed = 0')
            ->orderBy('e.email', 'ASC')
        ;
        $liste = $query->getQuery()->getScalarResult();
        foreach ($liste as $key => $email) {
            # code...
            $receivers[] = $email['email'];
        }
        $receivers[] = "denis.kombate@gmail.com";
        $receivers[] = "krakitan@gmail.com";
        // var_dump($receivers);
        // $liste[] = "krakitan@gmail.com";
        GrhContratsController::payeneSfMailerStatic($receivers, "no-reply@prowebgroupe.com", "Bonne et Heureuse année ". date('Y') , $messageBody, $this);

        $response = new Response(json_encode( ['status' => 1] ));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
