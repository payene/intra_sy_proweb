<?php

namespace Loonins\GrhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Util\LegacyFormHelper;

class GrhTypeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('duree',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\IntegerType'),array('required'=>  true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\GrhBundle\Entity\GrhType'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_grhbundle_grhtype';
    }
}
