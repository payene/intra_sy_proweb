<?php

namespace Loonins\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningHebdoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $week=[];
         for ($i = 1; $i <= 52; $i++)
         {
            $week[$i]=$i;
         }

        $weekNum=idate('W');
        $builder
            ->add('numWeek',ChoiceType::class, 
                array('choices' => $week,
                    'data'=>$weekNum))
            ->add('mois')
            ->add('annee')
            //->add('source')
            //->add('createdAt', 'datetime')
            //->add('del')
            //->add('createdBy')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\CalendarBundle\Entity\PlanningHebdo'
        ));
    }
}
