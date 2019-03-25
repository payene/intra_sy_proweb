<?php

namespace Loonins\SuiviBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnimatriceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('employe')
            ->add('employe', 'entity', ['class' => 'LooninsGrhBundle:GrhEmployes',
                    'required' => false,
                    'query_builder' => function ($er) {
                        return $er->createQueryBuilder('e')
                                ->orderBy('e.prenoms', 'ASC')
                                ->orderBy('e.nom', 'ASC');
                    }
                ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\SuiviBundle\Entity\Animatrice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_suivibundle_animatrice';
    }
}
