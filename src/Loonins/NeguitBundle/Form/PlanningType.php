<?php

namespace Loonins\NeguitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PlanningType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heureDebut')
            ->add('heureFin')
            ->add('datePlanning', DateType::class, array('widget' => 'single_text'))
            // ->add('createdAt')
            // ->add('login')
            ->add('fantome')
            ->add('typeAnim')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\NeguitBundle\Entity\Planning'
        ));
    }
}
