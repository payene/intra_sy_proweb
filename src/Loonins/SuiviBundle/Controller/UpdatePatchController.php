<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\SuiviBundle\Entity\LoginAnim;
use Loonins\SuiviBundle\Entity\Animatrice;

/**
 * UPDATE PATCH controller.
 *
 * @Route("/patch")
 */

class UpdatePatchController extends Controller
{
    
    private $employes = array(
      array('mle' => 'Mle','phone' => 'Contact'),
      array('mle' => '1','phone' => '90888795'),
      array('mle' => '2','phone' => '90877574'),
      array('mle' => '3','phone' => '92392443'),
      array('mle' => '4','phone' => '91734855'),
      array('mle' => '5','phone' => '91869143'),
      array('mle' => '6','phone' => '99999999'),
      array('mle' => '7','phone' => '90851728'),
      array('mle' => '8','phone' => '92930556'),
      array('mle' => '9','phone' => '92410198'),
      array('mle' => '10','phone' => '99999999'),
      array('mle' => '11','phone' => '91745675'),
      array('mle' => '12','phone' => '90174381'),
      array('mle' => '13','phone' => '91633248'),
      array('mle' => '14','phone' => '90364247'),
      array('mle' => '15','phone' => '91344996'),
      array('mle' => '16','phone' => '90158919'),
      array('mle' => '17','phone' => '90154505'),
      array('mle' => '18','phone' => '91781408'),
      array('mle' => '19','phone' => '90352581'),
      array('mle' => '20','phone' => '90386175'),
      array('mle' => '21','phone' => '90027926/98089848'),
      array('mle' => '22','phone' => '91809464'),
      array('mle' => '23','phone' => '92185674'),
      array('mle' => '24','phone' => '91436379'),
      array('mle' => '25','phone' => '92315398'),
      array('mle' => '26','phone' => '90072342'),
      array('mle' => '27','phone' => '90262418'),
      array('mle' => '28','phone' => '90288823'),
      array('mle' => '29','phone' => '90355097'),
      array('mle' => '30','phone' => '98645588'),
      array('mle' => '31','phone' => '92851309'),
      array('mle' => '32','phone' => '92614935'),
      array('mle' => '33','phone' => '97500879'),
      array('mle' => '34','phone' => '90244058'),
      array('mle' => '35','phone' => '99999999'),
      array('mle' => '36','phone' => '90074882'),
      array('mle' => '37','phone' => '91972584'),
      array('mle' => '38','phone' => '90006799'),
      array('mle' => '39','phone' => '90123667'),
      array('mle' => '40','phone' => '90074799'),
      array('mle' => '41','phone' => '96400098'),
      array('mle' => '34','phone' => '99999999'),
      array('mle' => '35','phone' => '90949939'),
      array('mle' => '36','phone' => '91769778'),
      array('mle' => '37','phone' => '99999999'),
      array('mle' => '38','phone' => '99999999'),
      array('mle' => '39','phone' => '91418509'),
      array('mle' => '41','phone' => '99999999'),
      array('mle' => '42','phone' => '90152229'),
      array('mle' => '43','phone' => '91963947'),
      array('mle' => '44','phone' => '91896149'),
      array('mle' => '45','phone' => '99999999'),
      array('mle' => '46','phone' => '92419889')
    );

    public function phoneAction()
    {
        $em = $this->getDoctrine()->getManager();

        $listeContacts = $this->employes;
        $nbr = 0;

        foreach ($listeContacts as $key => $contact) {
            $mle = $contact['mle'];
            $phone = $contact['phone'];

            if(strlen($mle) == 1){
                $mle = "000".$mle;
            }
            if(strlen($mle) == 2){
                $mle = "00".$mle;
            }


            $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->findOneBy(['mle' => $mle]);
            if(!empty($employe)){
                $employe->setPhone($phone);
                $em->persist($employe);
                $nbr++;
            }
            // else{
            //   echo "<br/>". $mle . "</br/>";
            // }
        }
        $em->flush();        
        // dump(count($listeContacts));
        die('end' . $nbr);
        // return $this->render('LooninsSuiviBundle:Default:index.html.twig', array('name' => $name));
    }

    public function setloginIdAction()
    {
        $em = $this->getDoctrine()->getManager();

        $arrayLogin = $em->getRepository('LooninsSuiviBundle:LoginAnim')->findAll();
        foreach ($arrayLogin as $key => $login) {
        	$arrayAnimatrices = $em->getRepository('LooninsSuiviBundle:Animatrice')->findBy(['login' => $login->getLogin()]);
        	foreach ($arrayAnimatrices as $key => $animatrice) {
        		$animatrice->setLogin($login);
        		$em->persist($animatrice);
        	}
        }
        $em->flush();

        die('end');
        // return $this->render('LooninsSuiviBundle:Default:index.html.twig', array('name' => $name));
    }
}
