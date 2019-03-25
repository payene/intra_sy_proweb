<?php

namespace Loonins\SuiviBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AnimatriceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', EntityType::class, ['class' => 'LooninsSuiviBundle:LoginAnim',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('l')
                            ->orderBy('l.login', 'ASC');
                }
            ])
            ->add('employe', EntityType::class, ['class' => 'LooninsGrhBundle:GrhEmployes',
                'required' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('e')
                            ->orderBy('e.prenoms', 'ASC')
                            ->orderBy('e.nom', 'ASC');
                }
            ])
            ->add('debutAffectation', DateType::class, array('html5' => false,'widget' => 'single_text','format' => 'dd MM yyyy'))
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
