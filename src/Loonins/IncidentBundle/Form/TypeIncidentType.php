<?php

namespace Loonins\IncidentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TypeIncidentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('requireEmployee', CheckboxType::class ,array('label' => "Employe obligatoire", 'required'=> false))
            ->add('requireTime', CheckboxType::class ,array('label' => "Duree obligatoire", 'required'=> false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\IncidentBundle\Entity\TypeIncident'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_incidentbundle_typeincident';
    }
}
