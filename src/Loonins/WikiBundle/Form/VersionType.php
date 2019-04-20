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

class VersionType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options, $imgsrc = null) {
        $builder
        ->add('verTitre', TextType::class, array('required' => true))
        ->add('verContent', TextareaType::class, array('required' => false))
        ->add('verArticle', EntityType::class, array('class' => 'LooninsWikiBundle:Article', 'multiple' => false))
        ->add('verDate')
        ->add('verEditable')
        ->add('verDel')
        ->add('attachement', FileType::class , array('data_class'=> null,'required' => false,'data' => $imgsrc))
        ->add('verCreator', EntityType::class, array( 'class' => 'LooninsUserBundle:User','multiple' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\WikiBundle\Entity\Version'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_wikibundle_version';
    }

}
