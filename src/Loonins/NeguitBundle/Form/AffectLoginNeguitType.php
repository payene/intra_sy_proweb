<?php

namespace Loonins\NeguitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class AffectLoginNeguitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employe',LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsGrhBundle:GrhEmployes','placeholder' => '-selectionner-',  'multiple' => false, 'mapped' => false, 'required' => false, 'choice_value' => 'id', 'query_builder' => function ($er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.trashed = 0')
                        ->orderBy('e.prenoms', 'ASC')
                        ->orderBy('e.nom', 'ASC');
            }))
            ->add('loginAnimNeguit',EntityType::class,array(
                'class'=>'Loonins\NeguitBundle\Entity\LoginAnimNeguit',
                'choice_label'=>'pseudo',
                )
            )
            ->add('debutAffectation',DateType::class, array('widget' => 'single_text'))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\NeguitBundle\Entity\AffectLoginNeguit'
        ));
    }
}
