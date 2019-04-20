<?php
namespace Loonins\GrhBundle\Validator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AntiNull
 *
 * @author programmer
 */
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class AntiNull extends Constraint {

//    public $message = 'Il existe déjà un contrat en cours pour le même employé.';
    public $message_not_null = 'Ce champs est obligatoire';
}
