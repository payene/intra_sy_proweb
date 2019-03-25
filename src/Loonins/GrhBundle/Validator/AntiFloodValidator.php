<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of AntiFloodValidator
 *
 * @author programmer
 */

namespace Loonins\GrhBundle\Validator;

use Doctrine\ORM\EntityRepository as empRepos;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Loonins\GrhBundle\Entity\GrhEmployes;

//use Loonins\GrhBundle\Entity\EmployeRepository as empRepos;

class AntiFloodValidator extends ConstraintValidator {

    public function validate($employe, Constraint $constraint) {
// Pour l'instant, on considère comme flood tout employe ayan au moins un contrat en cours 
//        $repositoty = new empRepos();
//        $em = $this->getDoctrine()->getManager();
//        $query = $em->createQueryBuilder();
//        $query
//                ->select('c')
//                ->from('LooninsGrhBundle:GrhContrats', 'c')
//                ->where("c.employes = :employe")
//                ->where("c.status != 3")
//                ->andwhere("c.status != 4")
//                ->setParameter('employe', $employe->getId());
//        $listeContrats = $query->getQuery()->getResult();
//        $listeContrats = $repositoty->floodContrat($employe , \Loonins\GrhBundle\Entity\GrhEmployeRepository);
//        $listeContrats = isset($employes->getListeContratsEncours()) ? $employes->getListeContratsEncours():array();
//        $ep = new GrhEmployes();
        $naiss = date_format($employe, "y-m-d");
        $min = date("Y-m-d", strtotime("+18 years", strtotime($naiss)));
        if ($min > date("Y-m-d")) {
            $this->context->addViolation($constraint->message);
        }

//        $listeContrats = $employe->getListeContratsEncours();
//        if(!empty($listeContrats)){
//            $this->context->addViolation($constraint->message);
//        }
    }

}
