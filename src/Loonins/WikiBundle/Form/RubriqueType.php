<?php

namespace Loonins\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RubriqueType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre', TextType::class, array('required' => true))
                // ->add('rubDate')
                ->add('rubCat', EntityType::class, array('class' => 'LooninsWikiBundle:Categorie', 'multiple' => false))
                // ->add('rubCreator', EntityType::class, array('read_only' => true, 'class' => 'LooninsUserBundle:User', 'multiple' => false));
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\WikiBundle\Entity\Rubrique'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_wikibundle_rubrique';
    }

}
