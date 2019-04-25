<?php

namespace Loonins\NeguitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AffectFantomeNeguitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debutAffect',DateType::class, array('widget' => 'single_text'))
            ->add('affectLogintNeguit',LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),
                    array('class' => 'Loonins\NeguitBundle\Entity\AffectLoginNeguit', 'multiple' => false,
                    'query_builder' => function ($er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.finAffectation is null');
            }))
            ->add('profilVirtuel',EntityType::class,array(
                'class'=>'Loonins\NeguitBundle\Entity\ProfilVirtuel',
                'choice_label'=>'pseudo',
                ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\NeguitBundle\Entity\AffectFantomeNeguit'
        ));
    }
}
