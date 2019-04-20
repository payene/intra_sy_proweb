<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Loonins\SuiviBundle\Entity\ExcelPlanning;

class TestController extends Controller
{
	
    public function indexAction()
    {
        //return $this->render('LooninsSuiviBundle:Test:index.xls.twig', ['data' => ['La', 'Le', 'Lu']]);
    }	

    public function readAction()
    {
    	
            $today = new \DateTime();
            $year = $today->format('Y');
            $week = $today->format('W');;


            // $file = $request->files->get('fichier');
    		// $ext = $file->guessExtension();
      //       $file_name = uniqid() . "." . $ext;
      //       //dump( $file->getPathname() ); exit;
             $dir = $this->getParameter('kernel.root_dir') ."/../web/uploads/excels";
      //       $file->move($dir, $file_name);*
            //dump($dir); exit;
            //$excel = $this->get('hpexcel')->createPHPExcelObject( $dir . '/' . $file_name);
            $donnees = [];
            try {
                $excel = $this->get('phpexcel')->createPHPExcelObject( $dir . '/' . 'test.xlsm');
                $sheet = $excel->getActiveSheet();

                $highestRow = $sheet->getHighestRow(); 
                $highestColumn = $sheet->getHighestColumn();

                /*$firstDay = ($sheet->rangeToArray('A' . '3' . ':' . $sheet->getHighestColumn(3) . '3',
                                                    NULL,
                                                    TRUE,
                                                    FALSE))[0][1];

                dump($firstDay); exit;*/
                $indice = 0;
    			//  Loop through each row of the worksheet in turn
    			for ($row = 6; $row <= $highestRow; $row+=2){ 
                    $timeRow = $row + 1;
    			    //  Read a row of data into an array
    			    $rowData = $sheet->rangeToArray('A' . $row . ':' . $sheet->getHighestColumn($row) . $row,
                                                    NULL,
                                                    TRUE,
                                                    FALSE);
                    $rowTimeData = $sheet->rangeToArray('A' . $timeRow . ':' . $sheet->getHighestColumn($timeRow) . $timeRow,
    			                                    NULL,
    			                                    TRUE,
    			                                    FALSE);
    			    //  Insert row data array into your database of choice here
    			    $donnees[] = array("login" => $rowData[0], "time" => $rowTimeData[0]);
                    $indice++;
                    if ($rowData[0] == "Actions" || $rowTimeData[0] == "Actions"){
                        break;
                    }
    			}
            } 
            catch (\OutOfMemoryException $e) {
                echo "Fichier trop volumineux";
                exit;
            }

            //dump($indice);
            /*dump( count($donnees) );

            exit;*/

            if( $donnees != NULL ){
                $em = $this->getDoctrine()->getManager();
                //dump($donnees); exit();
                foreach ($donnees as $element) {
                    $login = $element['login'];
                    $times = $element['time'];

                    $week_start = new \DateTime();
                    $week_start->setISODate($year, $week);

                    $nom = $login[1];
                    $log = $login [9];
                    
                    for ($i=2; $i <= 8 ; $i++) { 
                        // pour le calcul de la durée
                        $durees = $this->calculDuree($times[$i]); 
                        $activites = $this->getActivites( $login[$i] );

                        if (count($activites) ==  1) {
                            
                            if ( count($durees) == 2  ) {
                                $duree1 = $durees["1"];
                                $duree2 = $durees["2"];

                                $date_courante = $week_start;

                                $plan1 = new ExcelPlanning();

                                $plan1->setNom( $nom );
                                $plan1->setLogin( $log );
                                $plan1->setAnnee( $date_courante->format('Y') );
                                $plan1->setSemaine( $date_courante->format('W') );

                                $plan1->setDate( $week_start );
                                $plan1->setActivites( $login[$i] );
                                $plan1->setDuree( $durees["1"] );
                                
                                $plan1->setDateSaisie( new \DateTime() );

                                if( count( $em->getRepository("LooninsSuiviBundle:ExcelPlanning")->findBy(['nom' => $plan1->getNom(), 'activites' => $plan1->getActivites(), 'duree' => $plan1->getDuree(), 'login' => $plan1->getLogin(), 'annee' => $plan1->getAnnee(), 'semaine' => $plan1->getSemaine(), 'date' => $plan1->getDate() ]) ) == 0 ){
                                        $em->persist($plan1);
                                        $em->flush();
                                }


                                $date_courante->add( new \DateInterval('P1D') );

                                $plan2 = new ExcelPlanning();

                                $plan2->setNom( $nom );
                                $plan2->setLogin( $log );
                                $plan2->setAnnee( $date_courante->format('Y') );
                                $plan2->setSemaine( $date_courante->format('W') );

                                $plan2->setDate( $week_start );
                                $plan2->setActivites( $login[$i] );
                                $plan2->setDuree( $durees["2"] );
                                
                                $plan2->setDateSaisie( new \DateTime() );

                                if( count( $em->getRepository("LooninsSuiviBundle:ExcelPlanning")->findBy(['nom' => $plan2->getNom(), 'activites' => $plan2->getActivites(), 'duree' => $plan2->getDuree(), 'login' => $plan2->getLogin(), 'annee' => $plan2->getAnnee(), 'semaine' => $plan2->getSemaine(), 'date' => $plan2->getDate() ]) ) == 0 ){
                                    
                                    $em->persist($plan2);
                                    $em->flush();
                                }
                            }
                            else{
                                $plan = new ExcelPlanning();

                                $plan->setNom( $nom );
                                $plan->setLogin( $log );
                                $plan->setAnnee( $year );
                                $plan->setSemaine( $week );

                                $plan->setDate( $week_start );
                                $plan->setActivites( $login[$i] );
                                $plan->setDuree( $durees["1"] );
                                
                                $plan->setDateSaisie( new \DateTime() );

                                if( count( $em->getRepository("LooninsSuiviBundle:ExcelPlanning")->findBy(['nom' => $plan->getNom(), 'activites' => $plan->getActivites(), 'duree' => $plan->getDuree(), 'login' => $plan->getLogin(), 'annee' => $plan->getAnnee(), 'semaine' => $plan->getSemaine(), 'date' => $plan->getDate() ]) ) == 0 ){
                                    $em->persist($plan);
                                    $em->flush();
                                }
                            }


                        }
                        elseif ( count($activites) >  1 ) {
                            for ($j=0; $j < count($activites) ; $j++) { 
                                $act = $activites[0];

                                $plan = new ExcelPlanning();

                                $plan->setNom( $nom );
                                $plan->setLogin( $log );
                                $plan->setAnnee( $year );
                                $plan->setSemaine( $week );

                                $plan->setDate( $week_start );
                                $plan->setActivites( $act["activite"] );
                                $plan->setDuree( $act["duree"] );
                                
                                $plan->setDateSaisie( new \DateTime() );

                                if( count( $em->getRepository("LooninsSuiviBundle:ExcelPlanning")->findBy(['nom' => $plan->getNom(), 'activites' => $plan->getActivites(), 'duree' => $plan->getDuree(), 'login' => $plan->getLogin(), 'annee' => $plan->getAnnee(), 'semaine' => $plan->getSemaine(), 'date' => $plan->getDate() ]) ) == 0 ){
                                    $em->persist($plan);
                                    $em->flush();
                                }

                            }
                        }
                            $week_start->add( new \DateInterval('P1D') );
                    }

                }
                //$this->addFlash("info", 'Planning enregisté avec succès');
                //return $this->redirectToRoute('test_excel');
            }

            echo "ok";
            exit;

    }	

    public function displayAction(){

        $html2pdf = $this->get('app.html2pdf');
        $html2pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 15, 10, 15));
        $code = $this->renderView('LooninsSuiviBundle:Test:testpdf.html.twig');
        

       // $code = '<h3></h3>';

        return $html2pdf->generatePdf($code, 'test');
    }

    public function getActivites($activite){
        $activites = [];
        //echo    $activite . "\n";
        if( preg_match("#/#", $activite) and preg_match("#H#", $activite)  ) {
            $datas = explode("/", $activite);

            for ($i=0; $i < count($datas); $i++) { 
                $separe = explode("H ", $datas[$i]);
                //dump($separe);
                if( count($separe) >0 ){
                    $duree = $separe[0];
                    $activ = $separe[1];

                    $activites[$i] = ["activite" => $activ, "duree" => ($duree * 60) ];
                }
            }

        }
        else{
            $activites[0] = ["activite" => $activite];
        }

        return $activites;
    }

    public function calculDuree($duree){
        $durees = [];
        $initial = $duree;
        if( preg_match("#-#", $duree) ){
            $debut = (explode("-", $duree))[0];
            $fin = (explode("-", $duree))[1];

            $hDeb = (explode("H", $debut))[0];
            $mDeb = (explode("H", $debut))[1];

            $hFin = (explode("H", $fin))[0];
            $mFin = (explode("H", $fin))[1];
            $vars["hDeb"] = $hDeb;
            $vars["mDeb"] = $mDeb;
            $vars["hFin"] = $hFin;
            $vars["mFin"] = $mDeb;
            
            if( ($hFin < $hDeb) OR (($hFin == $hDeb) AND ($mFin < $mDeb) ) ) {
                $duree1 = ( (24 * 60) + 0 ) - ( ($hDeb * 60) + $mDeb );

                $duree2 = ( ($hFin * 60) + $mFin );

                $durees = ["1" => $duree1, "2" => $duree2];

            }
            else{

                $duree_minutes = ( ($hFin * 60) + $mFin ) - ( ($hDeb * 60) + $mDeb );

                $duree = $duree_minutes;

                $durees = ["1" => $duree];
            }

            if( ($durees[1]<0) or ($durees[1] != null and $durees[1]<0) ){
                dump($initial);
                dump($duree);
                dump($durees);
                dump($vars);
                exit;
            }

        }
        else{
            $durees = 0;
        }

        return $durees;
    }

    public function findActivites(){

    }

}