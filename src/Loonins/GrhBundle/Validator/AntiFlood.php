<?php
namespace Loonins\GrhBundle\Validator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AntiFlood
 *
 * @author programmer
 */
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class AntiFlood extends Constraint {

//    public $message = 'Il existe déjà un contrat en cours pour le même employé.';
    public $message = 'Vous ne pouvez pas enregistrer d\'employé de moins de 18 ans';
}
