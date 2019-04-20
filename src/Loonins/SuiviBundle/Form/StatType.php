<?php

namespace Loonins\SuiviBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class StatType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('msgParHeure')
            ->add('total')
            ->add('programmed')
            ->add('reel')
            ->add('prime', IntegerType::class, array('required'=>false))
            ->add('animatrice', EntityType::class, array('class' => 'LooninsSuiviBundle:Animatrice',
                'required' => true,
                'query_builder' => function ($er) {
                    return 
                        $er->createQueryBuilder('a')
                            ->join('a.employe', 'e')
                            ->join('a.login', 'l')
                            ->where('a.finAffectation IS NULL')
                            ->orwhere('a.finAffectation  =  :null')
                            ->setParameter('null', '0000-00-00 00:00:00')
                            // ->orderBy('e.prenoms', 'ASC')
                            // ->orderBy('e.nom', 'ASC')
                            ->orderBy('l.login', 'ASC')
                            // ->groupBy('a.employe')
                            // ->where('a.del = :del')
                            // ->setParameter('empty', NULL)
                            ;
                }
                ,
            ))
            ->add('retard',HiddenType::class)
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\SuiviBundle\Entity\Stat',
            'target' => '/dump',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_suivibundle_stat';
    }
}
