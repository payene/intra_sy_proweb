<?php

namespace Loonins\WikiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Util\LegacyFormHelper;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artTitre',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextType'),array('required' => true))
            // ->add('artDateCreate')
            ->add('artEditable',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\CheckboxType'),array('label'=>'Article en lecture seule','required'=>false))
            // ->add('artDel')
            // ->add('artCreator', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType') , array('read_only' => true ,'class' => 'LooninsUserBundle:User', 'property' => 'id', 'multiple' => false))
            ->add('artRub', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType') , array('class' => 'LooninsWikiBundle:Rubrique', 'multiple' => false))
            // ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Créer'));
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\WikiBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'loonins_wikibundle_article';
    }
}
