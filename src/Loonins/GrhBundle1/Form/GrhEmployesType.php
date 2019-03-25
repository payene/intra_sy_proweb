<?php

namespace Loonins\GrhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class GrhEmployesType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('mle')
                ->add('nom')
                ->add('prenoms')
                ->add('email', EmailType::class )
                ->add('phone',  TextType::class, array('required'=>true))
                ->add('dateentree',  BirthdayType::class, array('label' => "Date d'entrée", 'widget' => 'single_text', 'required' => true))
                ->add('assurance', CheckboxType::class, array('label' => 'Assurance','required' => false))
                ->add('datenaiss', BirthdayType::class , array('widget' => 'single_text', 'required' => true))
                ->add('lieuNaiss')
                ->add('sexe',  LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsGrhBundle:Sexe', 'multiple' => false))
                ->add('sitMat',  LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsGrhBundle:Matrimonial', 'multiple' => false))
                ->add('departement',  LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsGrhBundle:GrhDepartement', 'multiple' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\GrhBundle\Entity\GrhEmployes'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_grhbundle_grhemployes';
    }

}
