<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Loonins\SuiviBundle\Entity\DemandeExplication;
use Loonins\SuiviBundle\Entity\EnvoiDE;
use Loonins\SuiviBundle\Entity\Stat;
use Loonins\SuiviBundle\Form\DemandeExplicationType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Loonins\GrhBundle\Controller\GrhContratsController;
/**
 * DemandeExplication controller.
 *
 */
class DemandeExplicationController extends Controller
{
    /**
     * Lists all DemandeExplication entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $demandeExplications = $em->getRepository('LooninsSuiviBundle:DemandeExplication')->findAll();
        
        $searchForm = $this->createSearchForm();
  
        if( $request->isMethod("POST") ){
            
            $searchForm->handleRequest($request);
            $data = $request->get('form');
            $busDtDebut = $data['debut'];
            $busDtFin = $data['fin'];
            
            $busDtDebut = $busDtDebut['year'].'-'. $busDtDebut['month'] .'-'. $busDtDebut['day'];

            $busDtFin = $busDtFin['year'].'-'. $busDtFin['month'] .'-'. $busDtFin['day'];

            // $login = $data['login'];
            // $animatrice = $data['animatrice'];

            $table = $data['type'];
            $animatrice = $data['animatrice'];
            
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            $animatrice = $em->getRepository('LooninsSuiviBundle:Animatrice')->find(intval($animatrice));
            // $loginAnim =  $em->getRepository('LooninsSuiviBundle:LoginAnim')->find(intval($login));
            // $askExpForm = $this->createAskExplainForm(new \DateTime($busDtDebut),new \DateTime($busDtFin),$typeTable);
            
            $query = $em->createQueryBuilder();

            $query
                ->select('d')
                ->from('LooninsSuiviBundle:DemandeExplication', 'd')
                ->leftJoin('d.stat', 's')
                ->leftJoin('s.animatrice', 'a')
                ->leftJoin('a.employe', 'e')
                ->where("s.retard = 1")
                ->andwhere("d.dateDemande BETWEEN :debut AND :fin")
                ->andwhere("s.del = 0")
                // ->andwhere("a.del = 0")
                ->setParameter('debut', $busDtDebut .' 00:00:00')
                ->setParameter('fin', $busDtFin .' 23:59:59')
                // ->orderBy('s.dateStat', 'ASC')
            ;
            if(!empty($animatrice)){
                $query->andwhere('a = :animatrice' );
                $query->setParameter('animatrice', $animatrice->getId());
            }
            if(!empty($typeTable)){
                $query->andwhere('s.type = :typeTable' );
                $query->setParameter('typeTable', $typeTable->getId());
            }
            $demandeExplications = $query->getQuery()->getResult();
            
        }
        
        return $this->render('LooninsSuiviBundle:DemandeExplication:demande_explication.html.twig', array(
            'addStatForm' => $this->newStatForm($this->createFormBuilder())->createView(),
            'demandeExplications' => $demandeExplications,
            'searchForm' => $searchForm->createView(),
        ));
    }
    
    public static function newStatForm($newFormBuilder){
        // $newFormBuilder = $this->createFormBuilder();
        $newFormBuilder
            ->add('dateStat', DateType::class, array('widget' => 'single_text'))
            ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
        ;
        return $newFormBuilder->getForm();
    }
    
    public function resendAction(Request $request){
        $list = $request->request->get('list');
        $email_array = array();
        $em = $this->getDoctrine()->getManager();
        
        if( count($list) == 0 ){
            return new Response("bad");
        }
        
        //$grhmails = $em->getRepository("LooninsGrhBundle:GrhMail")->findAll();
        $respmails = $em->getRepository("LooninsSuiviBundle:RespMail")->findAll();
        $resp_array = [];
        
        foreach ($respmails as $respmails) {
            
            $resp_array[] = $respmails->getEmail();
            
        }
       
//        foreach ($respmails as $respmail) {
//            $resp_array[] = $respmail->getEmail();
//        }
        //var_dump($resp_array);
        for( $i=0; $i < count($list); $i++ ){
            
            $demande = $em->getRepository("LooninsSuiviBundle:DemandeExplication")->find( $list[$i] );
            
                //envoi de mail
                $email = $demande->getStat()->getAnimatrice()->getEmploye()->getEmail();
                $email_array[] = $email;
                $demande->setNbrEnvoi( $demande->getNbrEnvoi() + 1 );
                
                $envoiDE = new EnvoiDE();
                $envoiDE->setDate(new \DateTime());
                $envoiDE->setStat( $demande->getStat() );
                $stat = $demande->getStat();
                
                $html = "Une demande d'explication a été envoyée à ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms();

                $html .= " à propos de la stat " . " du " . $stat->getDateStat()->format('d-m-Y') . " avec le pseudo " . $stat->getAnimatrice()->getLogin() . "  pour la " . $demande->getNbrEnvoi() ."ème fois"  ;

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


                GrhContratsController::payeneSfMailerStatic($resp_array, "Pro Web","Une demande d'explication a été renvoyée à ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms(), $html, $this  );
                
                $em->persist($demande);            
                
                $html = "M/Mme ". $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms() . " une demande d'explication vous a été renvoyée";
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


                GrhContratsController::payeneSfMailerStatic($email_array, "Pro Web","Demande d'explication pour la " . $demande->getNbrEnvoi() ."ème fois", $html, $this  );
                $em->flush();
        }
        
        //$email_array[] = "manouauguste@yahoo.com";
//        $email_array[] = "denis.kombate@gmail.com";
        
        
        return new Response("good");
    }
    
    public function respondAction(Request $request){
        $list = $request->request->get('list');
        $email_array = array();
        $em = $this->getDoctrine()->getManager();
        
        if( count($list) == 0 ){
            return new Response("bad");
        }
        
        for( $i=0; $i < count($list); $i++ ){
            
            $demande = $em->getRepository("LooninsSuiviBundle:DemandeExplication")->find( $list[$i] );
            $demande->setStatus( 20 );
            $em->persist($demande);            
            $respmails = $em->getRepository("LooninsSuiviBundle:RespMail")->findAll();
            $resp_array = [];
            
            foreach ($respmails as $respmails) {
                
                $resp_array[] = $respmails->getEmail();
                
            }
            

            $stat = $demande->getStat();
                    
                    $html = $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms();

                    $html .= " a repondu à la demande d'explication sur la stat " . " du " . $stat->getDateStat()->format('d-m-Y') . " avec le pseudo " . $stat->getAnimatrice()->getLogin();

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


                    GrhContratsController::payeneSfMailerStatic($resp_array, "Pro Web", $stat->getAnimatrice()->getEmploye()->getNom()." ".$stat->getAnimatrice()->getEmploye()->getPrenoms() . " a répondu à une demande d'explication.", $html, $this  );
        }
        


        //GrhContratsController::payeneSfMailerStatic($email_array, "Pro Web","Demande d'explication", "test", $this  );
        $em->flush();
        
        return new Response("good");
    }
    
    public function detailAction(Stat $stat) {
        
        $em = $this->getDoctrine()->getManager();
        $envoiDEs = $em->getRepository("LooninsSuiviBundle:EnvoiDE")->findByStat($stat);
        
        return $this->render('LooninsSuiviBundle:DemandeExplication:detail.html.twig', array(
                "envoiDEs" => $envoiDEs,
                'addStatForm' => $this->newStatForm($this->createFormBuilder())->createView(),
            ));
        
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
        
        $em = $this->getDoctrine()->getManager();
        $animatrices = $em->getRepository("LooninsSuiviBundle:Animatrice")->findAll();
        $listDelete = [];
        $indexList = 0;
        foreach ($animatrices as $anim ) {
            $indexList++;
            if( $anim->getDel() == 1 ){
                $listDelete[''.$indexList] = array("class" => "option-deleted");
            }
        }
        
        if( $animatrice == null ){
            $formBuilder->add('animatrice', EntityType::class, array('class' => 'LooninsSuiviBundle:Animatrice',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                            ->join('a.employe', 'e')
                            //->where('a.finAffectation IS NULL or a.finAffectation = :nullDateTime')
                            ->orderBy('e.prenoms', 'ASC')
                            ->orderBy('e.nom', 'ASC')
                            // ->groupBy('a.employe')
                            // ->where('a.del = :del')
                            //->setParameter('nullDateTime', "0000-00-00 00:00:00")
                            ;
                }
                ,
                'choice_attr' => $listDelete,
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

    
}
