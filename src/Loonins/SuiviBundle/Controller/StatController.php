<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\Stat;
use Loonins\SuiviBundle\Entity\Journal;
use Loonins\SuiviBundle\Entity\TypeTable;
use Loonins\SuiviBundle\Entity\DemandeExplication;
use Loonins\SuiviBundle\Entity\Animatrice;
use Loonins\SuiviBundle\Entity\EnvoiDE;
use Loonins\SuiviBundle\Entity\LoginAnim;
use Loonins\GrhBundle\Controller\GrhContratsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Loonins\ExcelBundle\Spreadsheet\Excel\Spreadsheet_Excel_Reader;

/**
 * Stat controller.
 *
 * @Route("/stat")
 */
class StatController extends Controller
{

    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route(name="stat_cumul_by_anim")
     * @Method("GET")
     * @Template()
     */
    public function cumulbyanimAction($busdt1, $busdt2, $type, Animatrice $animatrice)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();
        // dump($login);
        $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($type));
        
        $query
            ->select('s')
            ->from('LooninsSuiviBundle:Stat', 's')
            ->leftJoin('s.animatrice', 'a')
            ->leftJoin('a.login', 'l')
            ->leftJoin('a.employe', 'e')
            ->where("s.dateStat BETWEEN :debut AND :fin")
            ->andwhere("s.del = 0")
            ->andwhere("s.animatrice = :animatrice")
            ->andwhere("e.trashed = false")
            ->setParameter('animatrice', $animatrice->getId())
            ->setParameter('debut', $busdt1)
            ->setParameter('fin', $busdt2)
            ->orderBy('s.dateStat', 'DESC')
        ;
        if(!empty($typeTable)){
            $query->andwhere("s.type = :type");
            $query->setParameter('type', $typeTable->getId());
        }
        else{
            $cumul_array['type'] = 0;
        }

        $stat = $query->getQuery()->getResult();

        $formStat = $this->createSearchForm(new \DateTime($busdt1),new \DateTime($busdt2), $animatrice, null,$typeTable);
        $newFormBuilder = $this->createFormBuilder();

        $newFormBuilder
                ->add('dateStat', DateType::class, array('html5' => false,'widget' => 'choice','format' => 'dd MM yyyy'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $newForm = $newFormBuilder->getForm();

        return $this->render('LooninsSuiviBundle:Stat:cumulone.html.twig',  array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'notif' => "",
            'form'   => $newForm->createView(),
            'formRech'   => $formStat->createView(),    
            'table'   => $typeTable,
            'busDtDebut'   => $busdt1,
            'busDtFin'   => $busdt2,
            'stats'   => $stat,
            'login'  => null,
            'animatrice' => $animatrice
        ));
    } 



    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route(name="stat_cumul_by_login")
     * @Method("GET")
     * @Template()
     */
    public function cumulbyloginAction($busdt1, $busdt2, $type, LoginAnim $login)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();
        // dump($login);
        $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($type));
        
        $query
            ->select('s')
            ->from('LooninsSuiviBundle:Stat', 's')
            ->leftJoin('s.animatrice', 'a')
            ->leftJoin('a.employe', 'e')
            ->leftJoin('a.login', 'l')
            ->where("s.dateStat BETWEEN :debut AND :fin")
            ->andwhere("s.del = 0")
            ->andwhere("e.trashed = 0")
            ->andwhere("a.login = :login")
            ->setParameter('login', $login->getId())
            ->setParameter('debut', $busdt1)
            ->setParameter('fin', $busdt2)
            ->orderBy('s.dateStat', 'DESC')
            ;
        if(!empty($typeTable)){
            $query->andwhere("s.type = :type");
            $query->setParameter('type', $typeTable->getId());
        }
        else{
            $cumul_array['type'] = 0;
        }

        $stat = $query->getQuery()->getResult();

        $formStat = $this->createSearchForm(new \DateTime($busdt1),new \DateTime($busdt2), null, $login,$typeTable);
        $newFormBuilder = $this->createFormBuilder();

        $newFormBuilder
                ->add('dateStat', DateType::class, array('html5' => false,'widget' => 'choice','format' => 'dd MM yyyy'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $newForm = $newFormBuilder->getForm();

        return $this->render('LooninsSuiviBundle:Stat:cumulone.html.twig',  array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'notif' => "",
            'form'   => $newForm->createView(),
            'formRech'   => $formStat->createView(),    
            'table'   => $typeTable,
            'busDtDebut'   => $busdt1,
            'busDtFin'   => $busdt2,
            'stats'   => $stat,
            'animatrice'   => null,
        ));
    }

    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route("/cumul/one/{busdt1}/{busdt2}/{type}/{animatrice}/{array}", name="stat_cumul_anim_login")
     * @Method("GET")
     * @Template()
     */
    public function cumulbothAction($busdt1, $busdt2, $type, Animatrice $animatrice = null, LoginAnim $login = null)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();
        // dump($login);
        $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($type));
        
        $query
            ->select('s')
            ->from('LooninsSuiviBundle:Stat', 's')
            ->leftJoin('s.animatrice', 'a')
            ->leftJoin('a.login', 'l')
            ->where("s.dateStat BETWEEN :debut AND :fin")
            ->andwhere("s.del = 0")
            ;
        if(!empty($login)){
            $query
                ->andwhere("a.login = :login")
                ->setParameter('login', $login->getId());
        }
        if(!empty($animatrice)){
            $query
                ->andwhere("s.animatrice = :animatrice")
                ->setParameter('animatrice', $animatrice->getId());
        }
        $query
            ->setParameter('debut', $busdt1)
            ->setParameter('fin', $busdt2)
            ->orderBy('s.dateStat', 'DESC')
            ;
        if(!empty($typeTable)){
            $query->andwhere("s.type = :type");
            $query->setParameter('type', $typeTable->getId());
        }
        else{
            $cumul_array['type'] = 0;
        }

        $stat = $query->getQuery()->getResult();

        // dump($stat);

        $formStat = $this->createSearchForm(new \DateTime($busdt1),new \DateTime($busdt2), $animatrice, $login,$typeTable);
        $newFormBuilder = $this->createFormBuilder();

        $newFormBuilder
                ->add('dateStat', DateType::class, array('html5' => false,'widget' => 'choice','format' => 'dd MM yyyy'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $newForm = $newFormBuilder->getForm();

        return $this->render('LooninsSuiviBundle:Stat:cumulone.html.twig',  array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'notif' => "",
            'form'   => $newForm->createView(),
            'formRech'   => $formStat->createView(),    
            'table'   => $typeTable,
            'busDtDebut'   => $busdt1,
            'busDtFin'   => $busdt2,
            'stats'   => $stat,
            'animatrice'   => $animatrice,
        ));
    }


    /**
     * Lists all Stat entities.
     *
     * @Route("/export", name="stat_export")
     * @Method("GET")
     * @Template()
    **/
    public function exportAction()
    {
        $session = $this->get('session');
        $str = $session->get('export_str');


        $response = new Response($str);
        $response->headers->set('Content-Type', 'application/xls');
        return $response;
    }


    public function createSearchForm($busDtDebut=null, $busDtFin = null,$animatrice=null, $login =null, $table=null){
        $formBuilder = $this->createFormBuilder();

        if($busDtDebut == null){
            $monthFirstDay = new \DateTime(date('Y-m').'-01');
        }
        else{
            $monthFirstDay = $busDtDebut;
        }
        
        if($busDtFin == null){
            $today = new \DateTime();
        }
        else{
            $today = $busDtFin;
        }
        $formBuilder
                ->add('debut',  DateType::class, array( 'data' =>  $monthFirstDay ,'html5' => false,'widget' => 'choice','format' => 'dd MM yyyy'))
                ->add('fin', DateType::class, array( 'data' => $today,'html5' => false,'widget' => 'choice','format' => 'dd MM yyyy') )
                ->add('submit',SubmitType::class, array('label' => 'Filtrer'))
                ;
        if($table == null){
            $formBuilder->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false, 'required'=>false));
        }
        else{
            $formBuilder->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false, 'required'=>false,'data'=> $table));
        }
        if($animatrice == null){
            $formBuilder->add('animatrice', EntityType::class, array('class' => 'LooninsSuiviBundle:Animatrice',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                            ->join('a.employe', 'e')
                            ->where('a.finAffectation IS NULL or a.finAffectation = :nullDateTime')
                            ->orderBy('e.prenoms', 'ASC')
                            ->orderBy('e.nom', 'ASC')
                            // ->groupBy('a.employe')
                            // ->where('a.del = :del')
                            ->setParameter('nullDateTime', "0000-00-00 00:00:00")
                            ;
                }
                ,
            ));
        }
        else{
            $formBuilder->add('animatrice', EntityType::class, array('class' => 'LooninsSuiviBundle:Animatrice',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                            ->join('a.employe', 'e')
                            ->orderBy('e.prenoms', 'ASC')
                            ->orderBy('e.nom', 'ASC')
                            ->groupBy('a.employe')
                            ;
                }
                ,
                'data' => $animatrice
            ));
        }

        if($login == null ){
            $formBuilder->add('login', EntityType::class, array('class' => 'LooninsSuiviBundle:LoginAnim',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('l')
                            ->orderBy('l.login', 'ASC')
                            // ->where('a.del = :del')
                            // ->setParameter('del', 0)
                            ;
                }
                ,
            ));
        }
        else{
            $formBuilder->add('login', EntityType::class, array('class' => 'LooninsSuiviBundle:LoginAnim',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('l')
                            ->orderBy('l.login', 'ASC')
                            // ->where('a.del = :del')
                            // ->setParameter('del', 0)
                            ;
                }
                ,
                'data' => $login
            ));
        }
        // dump($login);
        return $formStat = $formBuilder->getForm();
    }

    public static function newStatForm($newFormBuilder){
        // $newFormBuilder = $this->createFormBuilder();
        $newFormBuilder
            ->add('dateStat', DateType::class, array('widget' => 'single_text'))
            ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
        ;
        return $newFormBuilder->getForm();
    }
    /**
     * Lists all Stat entities.
     *
     * @Route("/rech/", name="stat_search")
     * @Method({"GET","POST"})
     * @Template()
    **/
    public function searchAction(Request $request)
    {
        // die(' - ');
        $session = $request->getSession();
        //dump($session->get('origine'));

        // dump($session->get('cumul'));
        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        
            // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        // $today = new \DateTime();
        // $tomorrow = (new \DateTime())->add( new \DateInterval('P1D'));
       
        $formStat = $this->createSearchForm();
        $newForm = $this->newStatForm($this->createFormBuilder());        
        

        if ($request->getMethod() == 'POST') {

            $data = $request->get('form');
            $busDtDebut = $data['debut'];
            $busDtFin = $data['fin'];
            
            $busDtDebut = $busDtDebut['year'].'-'. $busDtDebut['month'] .'-'. $busDtDebut['day'];

            $busDtFin = $busDtFin['year'].'-'. $busDtFin['month'] .'-'. $busDtFin['day'];

            $login = $data['login'];
            $animatrice = $data['animatrice'];

            $table = $data['type'];
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            $animatrice = $em->getRepository('LooninsSuiviBundle:Animatrice')->find(intval($animatrice));
            $loginAnim =  $em->getRepository('LooninsSuiviBundle:LoginAnim')->find(intval($login));
            $formStat = $this->createSearchForm(new \DateTime($busDtDebut),new \DateTime($busDtFin), $animatrice,$loginAnim,$typeTable);
            
            $query = $em->createQueryBuilder();
            //Recherche pour un seul LOGIN  avec cumul
            if(!empty($login) || !empty($animatrice)){

                $cumul_array = array(
                    'busdt1'   => $busDtDebut,
                    'busdt2'   => $busDtFin,
                );

                if(!empty($table)){
                    $cumul_array['type'] = $typeTable->getId();
                    $query->setParameter('type', $typeTable->getId());
                }
                else{
                    $cumul_array['type'] = 0;
                }

                if(!empty($animatrice) && empty($loginAnim)){
                    $cumul_array['animatrice'] =  $animatrice->getId();
                    return $this->redirect($this->generateUrl('stat_cumul_by_anim', $cumul_array));
                }

                if(!empty($loginAnim) && empty($animatrice)){
                    $cumul_array['login'] =  $loginAnim->getId();
                    return $this->redirect($this->generateUrl('stat_cumul_by_login', $cumul_array));
                }
                
                if(!empty($loginAnim) && !empty($animatrice)){
                    $cumul_array['login'] =  $loginAnim->getId();
                    $cumul_array['animatrice'] =  $animatrice->getId();
                    return $this->redirect($this->generateUrl('stat_cumul_anim_login', $cumul_array));                    
                }
                //cumuloneAction($busdt1, $busdt2, $type,$animatrice)
                
            }
            else{
                //Recherche pour toutes les animatrices avec cumul seule animatrice
                $query
                    ->select('s as SS,AVG(s.msgParHeure) as AVG_MPH , SUM(s.programmedSeconds) as PRG , SUM(s.total) as TT, SUM(s.reelSeconds) as RL , SUM(s.msgParConv) as MPC, SUM(s.prime) as SUM_PRM')
                    ->from('LooninsSuiviBundle:Stat', 's')
                    ->leftJoin('s.animatrice', 'a')
                    ->leftJoin('a.login', 'l')
                    ->where("s.dateStat BETWEEN :debut AND :fin")
                    ->andwhere("s.del = 0")
                    ->andwhere("a.del = 0")
                    ->setParameter('debut', $busDtDebut. ' 00:00:00')
                    ->setParameter('fin', $busDtFin .' 23:59:59')
                    ->groupBy('a.id')
                    ->orderBy('l.login', 'ASC')
                ;

                    if(!empty($typeTable)){
                        $query->andwhere("s.type = :type");
                        $query->setParameter('type', $typeTable->getId());
                    }
                    else{
                        $cumul_array['type'] = 0;
                    }

                $stat = null;
                $session = $request->getSession();

                // dump($query->getQuery());

                $cumul = $query->getQuery()->getResult();
                $session->set('cumul', $cumul);
                $session->set('busDtFin', $busDtFin);
                $session->set('busDtDebut', $busDtDebut);
            }
            $params = array(
                'notif' => "",
                'addStatForm'   => $newForm->createView(),
                'formRech'   => $formStat->createView(),    
                'table'   => $typeTable,
                'busDtDebut'   => $busDtDebut,
                'busDtFin'   => $busDtFin,
                'stats'   => $stat,
                'cumul'   => $cumul,
                'animatrice'   => $animatrice,
            );
            $params['typeTable'] = $typeTable;

            //dump($params['formRech']);
            return $this->render('LooninsSuiviBundle:Stat:search.html.twig',  $params);
           
        }
        //return $this->redirect($this->generateUrl('stat'));   
        $params =array();
        $params['formRech'] = $formStat->createView();
        $params['addStatForm'] = $newForm->createView();
        $cumul = $session->get('cumul');
        $stat = $session->get('stat');
        $animatrice = $session->get('animatrice');
        $busDtDebut = $session->get('busDtDebut');
        $busDtFin = $session->get('busDtFin');
        
        //die();
        // dump($cumul);
        //if( )
        $uri = $request;
        $edit = strpos($uri, '/edit');
        if($edit == false){
        }
        else{
            if(!empty($stat) ){
                $params['stats'] = $stat;
                $params['animatrice'] = $animatrice;

            }
            if(!empty($cumul)){
                $params['cumul'] = $cumul;
                $params['table'] = $cumul[0]['SS']->getType()->getTypeTable();
                $params['typeTable'] = $cumul[0]['SS']->getType();
                $params['animatrice'] = null;
            }

            if(!empty($busDtFin)){
                $params['busDtFin'] = $busDtFin;
            }

            if(!empty($busDtDebut)){
                $params['busDtDebut'] = $busDtDebut;
            }
        }
        //dump($edit);
        // die();


        return $params;
    }
    /**
     * Lists all Stat entities.
     *
     * @Route("/stat", name="stat")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = $this->createFormBuilder();
        // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
                ->add('dateStat', DateType::class, array('widget' => 'single_text'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $formStat = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $data = $request->get('form');
            $dateStat = $data['dateStat'];
            $table = $data['type'];
            $session = $this->get('session');
            $session->set('type',$table);
            $session->set('date',$dateStat);
            
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            $user = $this->getUser();


            return $this->redirect($this->generateUrl('stat_new'));
        }

        $results = array();
        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'form'   => $formStat->createView(),
            'formRech'   => $formStat->createView(),
            'stats'   => array()
        );
    }


    function treatImportCsv($import,$dateStat,$type) {
        $arrayImported = [];
        $arrayFailed = [];
        $handle = fopen($import, "r");
        fgetcsv($handle); // on passse les entetes du fichier
        $strDateStat = $dateStat->format('Y-m-d');
        $em = $this->getDoctrine()->getManager();
        
        while ($ligne = fgetcsv($handle)) {
            $l = $ligne;
            
            $msgph = isset($l[0]) ? $l[0] : "";
            $total = isset($l[1]) ? $l[1] : "";
            $prog = isset($l[2]) ? $l[2] : "";
            $login = isset($l[3]) ? $l[3] : "";
            // $login =  $em->getRepository('LooninsSuiviBundle:LoginAnim')->findOneBy(["login" => $login]);
            $query = $em->createQueryBuilder();
            $query
                ->select("a")
                ->from('LooninsSuiviBundle:Animatrice', 'a')
                ->join('a.login',  'l')
                ->join('a.employe',  'e')
                ->where('a.debutAffectation <= :busdt')
                ->andwhere('a.finAffectation IS NULL')
                ->andwhere('l.login = :login')
                ->setParameter('busdt', "$strDateStat")
                ->setParameter('login', $login)
            ;
            $animatrices = $query->getQuery()->getResult();
            // dump($animatrices);
            if(count($animatrices) > 1){
                $animatrice = null;
            }
            $animatrice = isset($animatrices[0]) ? $animatrices[0] : null;  

            if(!is_null($animatrice)){
                //erreur de login et/ou animatrice inexistants
                // dump($animatrice);
                if(!empty($msgph) && !empty($total) && !empty($prog)){
                    $stat = new Stat();
                    $stat->setImported(1);
                    $stat->setMsgParHeure($msgph);
                    $stat->setTotal($total);
                    $stat->setProgrammed($prog);
                    $stat->setAnimatrice($animatrice);
                    $stat->setDateStat($dateStat);
                    $stat->setType($type);

                    $stat->setDel(0);
                    $stat->setDateSaisie(new \DateTime());
                    $progs = explode(":", $prog);
                    $h = isset($progs[0]) ? (intval($prog[0])) : 0;
                    $m = isset($progs[1]) ? (intval($prog[1])) : 0;

                    $progSecondes = ($h*3600)+ ($m*60);
                    $reel = ($total/$msgph);
                    $reelSecondes = $reel * 3600;
                    $retard = !(($reelSecondes) > ($progSecondes * (7/8)));

                    //calcul reel time
                    $decimalHour =  substr($reel, strpos($reel, '.')+1);
                    $h = intval(substr($reel, 0, (strlen($reel) - strlen($decimalHour))));
                    $reelSecondes -= $h*3600;
                    
                    $decimalMinutes =  substr($reelSecondes, strpos($reelSecondes, '.')+1);
                    $reelSecondes = $reelSecondes/60;
                    $m = intval(substr($reelSecondes, 0, (strlen($reelSecondes) - strlen($decimalMinutes))));
                    
                    if($reelSecondes > 0){
                        $reelSecondes -= $m;
                        $reelSecondes = $reelSecondes / 60;
                        $decimalSecondes =  substr($reelSecondes, strpos($reelSecondes, '.')+1);
                        $s = intval(substr($reelSecondes, 0, (strlen($reelSecondes) - strlen($decimalSecondes))));
                    }
                    else{
                        $s = 00;
                    }
                    
                    $h = ($h > 10) ? $h : "0".$h;
                    $m = ($m > 10) ? $m : "0".$m;
                    $s = ($s > 10) ? $s : "0".$s;

                    $timeReel = $h.":". $m .":".$s;
                    $stat->setReel($timeReel);
                    $stat->setReelSeconds((intval($h) * 3600) + (intval($m) * 60) +  intval($s));

                    $stat->setRetard($retard);
                    $em->persist($stat);
                    $em->flush();
                    // dump($stat);
                    $arrayImported[] = $em->getRepository('LooninsSuiviBundle:Stat')->find($stat->getId());
                }
                else{
                    $arrayFailed[] = array('ligne' => $ligne, 'motif' => "Satat incorectes");
                }
            }
            else{
                $arrayFailed[] = array('ligne' => $ligne, 'motif' => "Login ou animatrice inexistant");
            }
        }
        // die('');
        return array('imported' => $arrayImported, 'failled' => $arrayFailed);
    }

    function treatImportCsvNew($import,$dateStat,$type) {
        $arrayImported = [];
        $arrayFailed = [];
        $handle = fopen($import, "r");
        fgetcsv($handle); // on passse les entetes du fichier
        $strDateStat = $dateStat->format('Y-m-d');
        $em = $this->getDoctrine()->getManager();
        
        while ($ligne = fgetcsv($handle)) {
            $l = $ligne;
            
            $msgph = isset($l[0]) ? $l[0] : "";
            $total = isset($l[1]) ? $l[1] : "";
            $login = isset($l[2]) ? $l[2] : "";
            $prog = $this->getDuree($dateStat, $login, $type);
            //dump($prog); exit;
            // $login =  $em->getRepository('LooninsSuiviBundle:LoginAnim')->findOneBy(["login" => $login]);
            if ($prog != "00:0") {
                $query = $em->createQueryBuilder();
                $query
                    ->select("a")
                    ->from('LooninsSuiviBundle:Animatrice', 'a')
                    ->join('a.login',  'l')
                    ->join('a.employe',  'e')
                    ->where('a.debutAffectation <= :busdt')
                    ->andwhere('a.finAffectation IS NULL')
                    ->andwhere('l.login = :login')
                    ->setParameter('busdt', "$strDateStat")
                    ->setParameter('login', $login)
                ;
                $animatrices = $query->getQuery()->getResult();
                // dump($animatrices);
                if(count($animatrices) > 1){
                    $animatrice = null;
                }
                $animatrice = isset($animatrices[0]) ? $animatrices[0] : null;  

                if(!is_null($animatrice)){
                    //erreur de login et/ou animatrice inexistants
                    // dump($animatrice);
                    if(!empty($msgph) && !empty($total) && !empty($prog)){
                        $stat = new Stat();
                        $stat->setImported(1);
                        $stat->setMsgParHeure($msgph);
                        $stat->setTotal($total);


                        $stat->setProgrammed($prog);
                        
                        $stat->setAnimatrice($animatrice);
                        $stat->setDateStat($dateStat);
                        $stat->setType($type);

                        $stat->setDel(0);
                        $stat->setDateSaisie(new \DateTime());
                        $progs = explode(":", $prog);
                        $h = isset($progs[0]) ? (intval($prog[0])) : 0;
                        $m = isset($progs[1]) ? (intval($prog[1])) : 0;

                        $progSecondes = ($h*3600)+ ($m*60);
                        $reel = ($total/$msgph);
                        $reelSecondes = $reel * 3600;
                        $retard = !(($reelSecondes) > ($progSecondes * (7/8)));

                        //calcul reel time
                        $decimalHour =  substr($reel, strpos($reel, '.')+1);
                        $h = intval(substr($reel, 0, (strlen($reel) - strlen($decimalHour))));
                        $reelSecondes -= $h*3600;
                        
                        $decimalMinutes =  substr($reelSecondes, strpos($reelSecondes, '.')+1);
                        $reelSecondes = $reelSecondes/60;
                        $m = intval(substr($reelSecondes, 0, (strlen($reelSecondes) - strlen($decimalMinutes))));
                        
                        if($reelSecondes > 0){
                            $reelSecondes -= $m;
                            $reelSecondes = $reelSecondes / 60;
                            $decimalSecondes =  substr($reelSecondes, strpos($reelSecondes, '.')+1);
                            $s = intval(substr($reelSecondes, 0, (strlen($reelSecondes) - strlen($decimalSecondes))));
                        }
                        else{
                            $s = 00;
                        }
                        
                        $h = ($h > 10) ? $h : "0".$h;
                        $m = ($m > 10) ? $m : "0".$m;
                        $s = ($s > 10) ? $s : "0".$s;

                        $timeReel = $h.":". $m .":".$s;
                        $stat->setReel($timeReel);
                        $stat->setReelSeconds((intval($h) * 3600) + (intval($m) * 60) +  intval($s));

                        $stat->setRetard($retard);
                        $em->persist($stat);
                        $em->flush();
                        // dump($stat);
                        $arrayImported[] = $em->getRepository('LooninsSuiviBundle:Stat')->find($stat->getId());
                    }
                    else{
                        $arrayFailed[] = array('ligne' => $ligne, 'motif' => "Satat incorectes", "prog" => $prog);
                    }
                }
                else{
                    $arrayFailed[] = array('ligne' => $ligne, 'motif' => "Login ou animatrice inexistant", "prog" => $prog);
                }
            }
        }
        // die('');
        return array('imported' => $arrayImported, 'failled' => $arrayFailed);
    }

    function getDuree($dateStat, $login, $type){

        $em = $this->getDoctrine()->getManager();

        $week = $dateStat->format('W');
        $year = $dateStat->format('Y');

        $jour = $dateStat->format('w');

        $weekPlans = $em->createQuery('select e ' .
                                     ' from LooninsSuiviBundle:ExcelPlanning e' .
                                     ' where e.date = :date' .
                                     ' and e.duree is not null' .
                                     ' and e.login is not null' .
                                     ' and upper(e.login) = :login'
                                    )
                                    ->setParameter('date', $dateStat->format('Y-m-d'))
                                    ->setParameter('login', strtoupper($login))
                                    ->getResult();

            $duree = 0;

            foreach ($weekPlans as $weekPlan) {

                $aliasA = $em->getRepository('LooninsSuiviBundle:AliasActivite')->findByType($type);
                $existe = false;
                foreach ($aliasA as $alias) {
                    if( preg_match("#" . $alias->getAlias() ."#", $weekPlan->getActivites()) ){
                        $existe = true;
                    }
                }
                
                if($existe == true){
                    $duree += $weekPlan->getDuree();
                }    

            }

            if( $duree != 0 ){
                

                $duree_minutes = $duree;

                if( $duree_minutes < 60 ) {
                    $duree = "00:" . $duree_minutes;
                }
                else{
                    $nbMin = $duree_minutes % 60;
                    $nbH = ($duree_minutes - $nbMin)/60;
                    $duree = $nbH . ":" . $nbMin;
                }

            }

        return $duree===0?"00:0":$duree;

    }

    /**
     * Lists all Stat entities.
     *
     * @Route("/stat/import/csv/status", name="stat_import_status")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function importStatusAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');
        $imported = $session->get('imported');
        $failled = $session->get('failled');
        $typeTable = $session->get('typeTable');
        $busdt = $session->get('busdt');
        $arrayImported = [];
        foreach ($imported as $key => $stat) {
            $arrayImported[] = $em->getRepository('LooninsSuiviBundle:Stat')->find($stat->getId());
        }

        $formBuilder = $this->createFormBuilder();
        $formBuilder
            ->add('file', FileType::class, array('required' => true))
            ->add('dateStat', DateType::class, array('widget' => 'single_text'))
            ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
        ;
        $formImport = $formBuilder->getForm();

        $formImport->handleRequest( $request );

        // dump($imported);
        // dump($failled);

        return $this->render('LooninsSuiviBundle:Stat:import.html.twig',  array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'importForm'   => $formImport->createView(),
            'imported'   => $arrayImported,
            'failled'   => $failled,
            'busdt'   => $busdt,
            'typeTable'   => $typeTable
        ));

    }




    /**
     * Lists all Stat entities.
     *
     * @Route("/import/csv", name="stat_import")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = $this->createFormBuilder();
        // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
            ->add('file', FileType::class, array('required' => true))
            ->add('dateStat', DateType::class, array('widget' => 'single_text'))
            ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
        ;
        $formImport = $formBuilder->getForm();

        $formImport->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            $fimport = $formImport['file']->getData();
            $dateStat = $formImport['dateStat']->getData();
            $type = $formImport['type']->getData();
            $busdt = $dateStat->format('Ymd');
            $strDateStat = $dateStat->format('Y/m/d');
            // dump($busdt);
            $uploadDir = "uploads/imports";
            $extension = "";
            if ($fimport->getClientSize() > 0) {
                $ext = $fimport->guessExtension();
                // var_dump($ext);
                //       die('ext ');
                //                if (!$extension) {
                // l'extension n'a pas été trouvée
                $extension = $ext;
                //                }
                $newFilename = rand(1, 99999) . '.' . $extension;
                $fimport->move($uploadDir, $newFilename);
                //                $flogo->move($uploadDir, $flogo->getClientOriginalName());
                $import =  $uploadDir . '/'. $newFilename;
                // $entity->setLogo($logo);
            } else {
                $error = "Fichier illisible.";
                $f_error = new \Symfony\Component\Form\FormError($error);
                $form->addError($f_error);
                //                die('bio');
            }
            
            $returnImport = $this->treatImportCsvNew($import,$dateStat, $type);
            $arrayImported = $returnImport['imported'];
            $arrayFailled = $returnImport['failled'];
            $session = $this->get('session');
            $session->set('imported', $arrayImported);
            $session->set('failled', $arrayFailled);
            $session->set('typeTable', $type);
            $session->set('busdt', $busdt);
            return $this->redirect($this->generateUrl('stat_import_status'));
        }

        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'importForm'   => $formImport->createView(),
        );
    }


    /**
     * Lists all Stat entities.
     *
     * @Route("/stat/ask/explain", name="stat_ask_explain")
     * @Method({"GET","POST"})
     * @Template()
    **/
    
    public function askExplainAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $askExpForm = $this->createAskExplainForm();
        $arrayLateStat = [];
        $askExpForm->handleRequest($request);
        if ($request->getMethod() == 'POST') {

            $data = $request->get('form');
            $busDtDebut = $data['debut'];
            $busDtFin = $data['fin'];
            
            $busDtDebut = $busDtDebut['year'].'-'. $busDtDebut['month'] .'-'. $busDtDebut['day'];

            $busDtFin = $busDtFin['year'].'-'. $busDtFin['month'] .'-'. $busDtFin['day'];

            // $login = $data['login'];
            // $animatrice = $data['animatrice'];

            $table = $data['type'];
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            // $animatrice = $em->getRepository('LooninsSuiviBundle:Animatrice')->find(intval($animatrice));
            // $loginAnim =  $em->getRepository('LooninsSuiviBundle:LoginAnim')->find(intval($login));
            // $askExpForm = $this->createAskExplainForm(new \DateTime($busDtDebut),new \DateTime($busDtFin),$typeTable);
            
            $query = $em->createQueryBuilder();

            $query
                ->select('s')
                ->from('LooninsSuiviBundle:Stat', 's')
                ->leftJoin('s.animatrice', 'a')
                ->leftJoin('a.employe', 'e')
                ->where("s.retard = 1")
                ->andwhere("s.dateStat BETWEEN :debut AND :fin")
                ->andwhere("s.del = 0")
                // ->andwhere("a.del = 0")
                ->setParameter('debut', $busDtDebut .' 00:00:00')
                ->setParameter('fin', $busDtFin .' 23:59:59')
                // ->orderBy('s.dateStat', 'ASC')
            ;
            if(!empty($typeTable)){
                $query->andwhere('s.type = :typeTable' );
                $query->setParameter('typeTable', $typeTable->getId());
            }
            $arrayLateStat = $query->getQuery()->getResult();
            //dump($arrayLateStat);
        }
        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'askExpForm'   => $askExpForm->createView(),
            'arrayLateStat'   => $arrayLateStat
        );
    }

    public function sendExplainMailAction(Request $request)
    {
        $list = $request->request->get('list');
        $email_array = array();
        $em = $this->getDoctrine()->getManager();
        if( count($list) > 0 ){
            $respmails = $em->getRepository("LooninsSuiviBundle:RespMail")->findAll();
            $resp_array = [];
            foreach ($respmails as $respmail) {
                $resp_array[] = $respmail->getEmail();
            }
            
            for( $i=0; $i < count($list); $i++ ){
                //$stat = new Stat();
                $stat = $em->getRepository("LooninsSuiviBundle:Stat")->find( $list[$i] );
                
                if( $stat->getAnimatrice() != NULL ){
                    //envoi de mail
                    $email = $stat->getAnimatrice()->getEmploye()->getEmail();
                    $email_array[] = $email;
                    $demande = new DemandeExplication();
                    $demande->setDateDemande(new \DateTime);
                    $demande->setStat( $stat );
                    $demande->setNbrEnvoi(1);
                    $demande->setStatus(10);
                    
                    $envoiDE = new EnvoiDE();
                    $envoiDE->setDate(new \DateTime());
                    $envoiDE->setStat($stat);
                    
                    $em->persist($envoiDE);
                    $em->persist($demande);
                    
                    $html = "Une demande d'explication a été envoyée à ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms();
                    $html .= " à propos de la stat " . " du " . $stat->getDateStat()->format('d-m-Y') . " avec le pseudo " . $stat->getAnimatrice()->getLogin()  ;

                    
                    $html.="
<table border='2'>
" .
"<thead>
    <tr style=\"height:15px;\">
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">BUSDT  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Login  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Animatrice  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Messages/heure</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Total</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Programm&eacute;</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">R&eacute;el</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\"> Prime </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Message/conv </th>
        
    </tr>
</thead>"
. "
<tr>
                <td style=\"padding:0px;vertical-align:middle\">" . $stat->getDateStat()->format('d/m/Y') . "</td>
                <td style=\"padding:0px;vertical-align:middle;\">" . $stat->getAnimatrice()->getLogin() . "</td>
                <td style=\"padding:0px;vertical-align:middle;\">" .  $stat->getAnimatrice()->getEmploye() . "</td>
                <td style= \"padding:0px;vertical-align:middle;text-align:center\" >" . $stat->getMsgParHeure() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getTotal() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getProgrammed() . "</td>
                
                <td  style=\"padding:0px;vertical-align:middle;text-align:right;text-align:center;\"> 
                    <span style=\"padding:0px;border-radius:0px;background-color:  rgba(185, 74, 72, 0.77);color: white;\">" . $stat->getReel() .  "</span>
                </td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getPrime() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getMsgParConv() . "</td>
        </tr>
</table>";
                    

                    GrhContratsController::payeneSfMailerStatic($resp_array, "Pro Web","Une demande d'explication a été envoyée à ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms(), $html, $this  );
            
                    $html = "M/Mme ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms() . " une demande d'explication vous a été envoyée";
                    $html .= " à propos de la stat " . " du " . $stat->getDateStat()->format('d-m-Y') . " avec le pseudo " . $stat->getAnimatrice()->getLogin() . "\n"  ;
                    $html.="
<table border='2'>
" .
"<thead>
    <tr style=\"height:15px;\">
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">BUSDT  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Login  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Animatrice  </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Messages/heure</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Total</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Programm&eacute;</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">R&eacute;el</th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\"> Prime </th>
        <th style=\"padding:0px;vertical-align:middle;text-align: center;\">Message/conv </th>
        
    </tr>
</thead>"
. "
<tr>
                <td style=\"padding:0px;vertical-align:middle\">" . $stat->getDateStat()->format('d/m/Y') . "</td>
                <td style=\"padding:0px;vertical-align:middle;\">" . $stat->getAnimatrice()->getLogin() . "</td>
                <td style=\"padding:0px;vertical-align:middle;\">" .  $stat->getAnimatrice()->getEmploye() . "</td>
                <td style= \"padding:0px;vertical-align:middle;text-align:center\" >" . $stat->getMsgParHeure() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getTotal() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getProgrammed() . "</td>
                
                <td  style=\"padding:0px;vertical-align:middle;text-align:right;text-align:center;\"> 
                    <span style=\"padding:0px;border-radius:0px;background-color:  rgba(185, 74, 72, 0.77);color: white;\">" . $stat->getReel() .  "</span>
                </td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getPrime() . "</td>
                <td style=\"padding:0px;vertical-align:middle;text-align:center;\">" . $stat->getMsgParConv() . "</td>
        </tr>
</table>";



                    GrhContratsController::payeneSfMailerStatic($email_array, "Pro Web","Demande d'explication", $html, $this  );
                    $em->flush();
                    
                }
                
            }
            
    //        $email_array[] = "manouauguste@yahoo.com";
    //        $email_array[] = "denis.kombate@gmail.com";
            return new Response("good");
        }
        else{
            return new Response("bad");
        }
        
    }

    public function createAskExplainForm($busDtDebut=null, $busDtFin = null, $table=null){
        $formBuilder = $this->createFormBuilder();

        if($busDtDebut == null){
            $monthFirstDay = new \DateTime(date('Y-m').'-01');
        }
        else{
            $monthFirstDay = $busDtDebut;
        }
        
        if($busDtFin == null){
            $today = new \DateTime();
        }
        else{
            $today = $busDtFin;
        }
        $formBuilder
                ->add('debut',  DateType::class, array( 'data' =>  $monthFirstDay ,'html5' => false,'widget' => 'choice','format' => 'dd MM yyyy'))
                ->add('fin', DateType::class, array( 'data' => $today,'html5' => false,'widget' => 'choice','format' => 'dd MM yyyy') )
                ->add('submit',SubmitType::class, array('label' => 'Afficher'))
                ;
        if($table == null){
            $formBuilder->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false, 'required'=>false));
        }
        else{
            $formBuilder->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false, 'required'=>false,'data'=> $table));
        }
        
        // dump($login);
        return $formStat = $formBuilder->getForm();
    }


    /**
     * Creates a new Stat entity.
     *
     * @Route("/", name="stat_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:Stat:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Stat();
        $session = $this->get('session');
        $idTable = $session->get('type');

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($idTable);

        $form = $this->createCreateForm($entity,$table);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $retard = $entity->getRetard();
            // if($retard == 'red'){
            //     $entity->setRetard(1);
            // }
            // else{
            //     $entity->setRetard(0);
            // }
  

            
            $dstat =  $session->get('date');
            
            
            if( $dstat instanceOf \DateTime){
                $entity->setDateStat($dstat);
            }
            else{
                $entity->setDateStat(new \DateTime($dstat));
            }
            $entity = $this->timeSeconds($entity);
            $entity->setDel(0);
            $entity->setDateSaisie(new \DateTime());
            $entity->setType($table);
            $this->addToJournal($em, $stat = $entity, $author = $this->getUser(), $action = 'new');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_new'));
           
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
        );
    }


    public function timeSeconds($stat) {

        $reel = $this->explodeTime($stat->getReel());
        $prg = $this->explodeTime($stat->getProgrammed());

        $stat->setProgrammedSeconds($prg);
        $stat->setReelSeconds($reel);
        return $stat;
    }

    public function explodeTime($time){
        $tab = explode(":",  $time);
        $s = 0;
        if(isset($tab[0])){
            $s = intval($tab[0]) * 3600;
        }

        if(isset($tab[1])){
            $s = $s + intval($tab[1]) * 60;
        }
        
        if(isset($tab[2])){
            $s = $s + intval($tab[2]);
        }
        return $s;
    }

    /**
    * Creates a form to create a Stat entity.
    *
    * @param Stat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Stat $entity, $table)
    {
        if($table->getMsgByConvRequired()){
            $statType = 'Loonins\SuiviBundle\Form\StatMsgConvType';
        }
        else{
            $statType = 'Loonins\SuiviBundle\Form\StatType';
        }

        $form = $this->createForm($statType, $entity, array(
            'action' => $this->generateUrl('stat_create'),
            'method' => 'POST',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer'));
        return $form;
    }

    /**
     * Displays a form to create a new Stat entity.
     *
     * @Route("/new", name="stat_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Stat();

        $session = $this->get('session');
        $idTable = $session->get('type');
        $dstat =  $session->get('date');
        $stats = array();
        if($idTable == null || $dstat == null){
            $this->get('session')->getFlashBag()->add('error', 'Cliquez sur le bouton Nouvelle entree statistique');
            return $this->redirect($this->generateUrl('stat'));
        }

        $ds = $dstat;
        $newForm = $this->newStatForm($this->createFormBuilder());

        $em = $this->getDoctrine()->getManager();

        $table = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($idTable);
        
        $query = $em->createQueryBuilder();
        $query
            ->select('s')
            ->from('LooninsSuiviBundle:Stat', 's')
            ->leftJoin('s.animatrice', 'a')
            ->leftJoin('a.employe', 'e')
            ->where("s.dateStat = :ds")
            ->andwhere("s.type = :t")
            ->andwhere("a.del = :del")
            ->andwhere("e.trashed = 0")
            ->andwhere("s.del = :del")
            ->orderBy('s.dateSaisie', 'DESC')
            ->setParameter('ds', $ds)
            ->setParameter('del', 0)
            ->setParameter('t', $table)
        ;
        $stats = $query->getQuery()->getResult();
        
        $nbDemande = [];
        foreach ($stats as $stat ) {
            $nbDemande[$stat->getId()] =  $this->getNbDemande( $stat->getId() );
        }

        $form   = $this->createCreateForm($entity, $table);

        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'entity' => $entity,
            'form'   => $form->createView(),
            'stats'   => $stats,
            'dateStat'   => $dstat,
            'typeTable'   => $table,
            'nbDemande' =>  $nbDemande,
        );
    }

    function getNbDemande($stat_id){
            $em = $this->getDoctrine()->getManager();
            return  $em->createQuery("select count(d.id)"
                                        .  " from LooninsSuiviBundle:DemandeExplication d"
                                        .  " where d.stat = :id"
                                          )
                                        ->setParameter('id', $stat_id)
                                        ->getSingleScalarResult();

    }

    /**
     * Finds and displays a Stat entity.
     *
     * @Route("/{table}/{id}", name="stat_show")
     * @Method("GET")
     * @Template()
    **/
    public function showAction($id,TypeTable $table)
    {
        $em = $this->getDoctrine()->getManager();
        $ds = new \DateTime($id);
        $entities = $em->getRepository('LooninsSuiviBundle:Stat')->findBy(['dateStat'=>$ds]);

        $entities = $em->getRepository('LooninsSuiviBundle:Stat')->createQueryBuilder('s')
            // On joint sur l'attribut employe
            ->leftJoin('s.animatrice', 'a')
            ->where('s.dateStat = :ds')
            ->andWhere('a.del = :del')
            ->andWhere('s.del = :del')
            ->setParameter('ds', $ds)
            ->setParameter('del', 0)
            ->orderBy('a.login','ASC')
            ->getQuery()->getResult();


        $journal = null;//$em->getRepository('LooninsSuiviBundle:Journal')->findOneBy(['dateStat'=>$ds]);
        if(!empty($journal)){
            $session = $this->get('session');
            $session->set('type',$table->getId());
            $session->set('date',$ds);            
        }
        
        $formBuilder = $this->createFormBuilder();
        // $formBuilder
        //     ->add('dateStat', DateType::class, array('widget' => 'single_text'))
        //     ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false));
        // $formStat = $formBuilder->getForm();
        $formStat = $this->createSearchForm();

        // if (!$entities) {
        //     throw $this->createNotFoundException('Unable to find Stat entity.');
        // }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'stats'      => $entities,
            'dateStat'      => $id,
            'table'      => $table,
            'journal'      => $journal,
            'formRech'   => $formStat->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route("/{id}/view", name="stat_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render('LooninsSuiviBundle:Stat:view.html.twig',  array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'entity'   => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route("/stat/update/{id}/{from}", name="stat_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id, $from)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        $session = $request->getSession();

        $ref =  $request->headers->get('referer');
        $origine  =  $ref;
        $session->set('origine', $origine);

        // dump($session->get('origine'));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }
        // var_dump($entity->getRetard());
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'rech' => $from,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'origine' => $origine,
        );
    }

        /**
         * Displays a form to edit an existing Stat entity.
         *
         * @Route("/{id}/edit/", name="stat_edit_details")
         * @Method("GET")
         * @Template()
         */
        public function editDetailsAction(Request $request, $id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

            $session = $request->getSession();

            $ref =  $request->headers->get('referer');
            $origine  =  $ref;
            $session->set('origine', $origine);

            //dump($session->get('origine'));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Stat entity.');
            }

            $editForm = $this->createEditDetailsForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
                'origine' => $origine,
            );
        }

    /**
    * Creates a form to edit a Stat entity.
    *
    * @param Stat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Stat $entity)
    {
        $table = $entity->getType();
        if($table->getMsgByConvRequired()){
            $statType = 'Loonins\SuiviBundle\Form\StatMsgConvType';
        }
        else{
            $statType = 'Loonins\SuiviBundle\Form\StatType';
        }

        $form = $this->createForm($statType, $entity, array(
            'action' => $this->generateUrl('stat_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));


        $form->add('submit',  SubmitType::class, array('label' => 'Sauvegarder'));

        return $form;
    }


    /**
    * Creates a form to edit a Stat entity.
    *
    * @param Stat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditDetailsForm(Stat $entity)
    {
   
        $statType = 'Loonins\SuiviBundle\Form\StatEditDetailType';
        $form = $this->createForm($statType, $entity, array(
            'action' => $this->generateUrl('stat_details_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));


        $form->add('submit',  SubmitType::class, array('label' => 'Sauvegarder'));

        return $form;
    }
    /**
     * Edits an existing Stat entity.
     *
     * @Route("/{id}", name="stat_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Stat:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }
      

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        // var_dump($entity->getRetard());
        if ($editForm->isValid()) {
            
            $session = $request->getSession();
            $origine = $session->get('origine');

            //dump($origine);
            // die('');
            $retard = $entity->getRetard();
            // if($retard == 'red'){
            //     $entity->setRetard(1);
            // }
            // else{
            //     $entity->setRetard(0);
            // }

            $entity = $this->timeSeconds($entity);

            $em->flush();
            $this->addToJournal($em, $stat = $entity, $author = $this->getUser() , $action = "edit");

            $this->get('session')->getFlashBag()->add('info', 'Entree statistique modifiée avec success');

            //$session->set('referer', null);
            // dump($origine);
            // die('');
            return $this->redirect($origine);
        }

        return array(
            'addStatForm' => ($this->newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }



    /**
     * Edits an existing Stat entity.
     *
     * @Route("/{id}", name="stat_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Stat:edit.html.twig")
     */
    public function updateDetailsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }
      

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditDetailsForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            $session = $request->getSession();
            $origine = $session->get('origine');

            //dump($origine);
            // die('');
            // $retard = $entity->getRetard();
            // if($retard == 'red'){
            //     $entity->setRetard(1);
            // }
            // else{
            //     $entity->setRetard(0);
            // }

            $em->flush();
            $this->addToJournal($em, $stat = $entity, $author = $this->getUser() , $action = "details");

            $this->get('session')->getFlashBag()->add('info', 'Entree statistique modifiée avec success');

            //$session->set('referer', null);
            return $this->redirect($origine);
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Stat entity.
     *
     * @Route("/{id}", name="stat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Stat entity.');
            }

            $entity->setDel(1);
            
            $em->merge($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('stat_show',
                array(

                    'id' => $entity->getDateStat()->format('Ymd'),
                    'table' => $entity->getType()->getId(),
                )
            ));
        }
        
        // return $this->redirect($this->generateUrl('stat'));

    }

    /**
     * Creates a form to delete a Stat entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', SubmitType::class, array('label' => 'Supprimer cette statistique'))
            ->getForm()
        ;
    }

    public function addToJournal($em, $stat, $author, $action){
        $journal = new Journal();
        $journal->setLigneStat($stat);
        $journal->setAuteur($author);
        $journal->setAction($action);
        $journal->setDateModif(new \DateTime());
        $em->persist($journal);
        $em->flush();
    }
}
