<?php

namespace Loonins\GrhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Util\LegacyFormHelper;

class GrhContratsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsGrhBundle:GrhType', 'multiple' => false))
            // ->add('date', 'date',array('read_only'=>'true','widget' => 'single_text'))
            ->add('debut', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'),array('widget' => 'single_text'))
//            ->add('finReel', 'date',array('widget' => 'single_text'))
            // ->add('motifRupture','hidden')
            ->add('commentaire')
            // ->add('status','hidden')
            ->add('employe',LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsGrhBundle:GrhEmployes',  'multiple' => false, 'query_builder' => function ($er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.prenoms', 'ASC')
                        ->orderBy('e.nom', 'ASC');
                }))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\GrhBundle\Entity\GrhContrats'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_grhbundle_grhcontrats';
    }
}
