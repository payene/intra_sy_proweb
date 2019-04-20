<?php

namespace Loonins\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategorieType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('cat', TextType::class, array('required' => true))
                ->add('description', TextareaType::class, array('required' => false))
                // ->add('catDate')
                // ->add('catCreator', EntityType::class, array('class' => 'LooninsUserBundle:User', 'multiple' => false));

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\WikiBundle\Entity\Categorie'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_wikibundle_categorie';
    }

}
