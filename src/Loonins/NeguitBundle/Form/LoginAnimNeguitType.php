<?php

namespace Loonins\NeguitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Util\LegacyFormHelper;
class LoginAnimNeguitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('employe',LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsGrhBundle:GrhEmployes',  'multiple' => false, 'mapped' => false, 'query_builder' => function ($er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.trashed = 0')
                        ->orderBy('e.prenoms', 'ASC')
                        ->orderBy('e.nom', 'ASC');
            }))
            // ->add('createdAt')
            // ->add('del')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\NeguitBundle\Entity\LoginAnimNeguit'
        ));
    }
}
