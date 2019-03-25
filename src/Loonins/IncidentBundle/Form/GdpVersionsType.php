<?php

namespace Loonins\IncidentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GdpVersionsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            // ->add('date', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array())
            // ->add('updateDate', 'datetime')
            // ->add('incident', 'entity', array('read_only' => true, 'class' => 'LooninsIncidentBundle:GdpIncident', 'property' => 'id', 'multiple' => false))
            // ->add('proprio', 'entity', array('read_only' => true, 'class' => 'LooninsUserBundle:User', 'property' => 'id', 'multiple' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\IncidentBundle\Entity\GdpVersions'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_incidentbundle_gdpversions';
    }
}
