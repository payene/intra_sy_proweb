<?php

namespace Loonins\GesCongeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DemandeCongeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut',DateType::class, array('widget' => 'single_text'))
            //->add('fin', 'date')
            //->add('statut')
            ->add('motif',TextareaType::class)
            //->add('employe')
            //->add('validateur')
            ->add('typeDemande',EntityType::class,array(
                'class'=>'Loonins\GesCongeBundle\Entity\TypeDemande',
                'choice_label'=>'libelle',
                ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\GesCongeBundle\Entity\DemandeConge'
        ));
    }
}
