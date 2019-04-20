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
class AntiFloodded extends Constraint {

//    public $message = 'Il existe déjà un contrat en cours pour le même employé.';
    public $message_floodded = 'La date d\'entrée ne peut être ultérieire à la date de creation de la societe';
}
