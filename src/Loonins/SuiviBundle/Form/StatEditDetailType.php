<?php

namespace Loonins\SuiviBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class StatEditDetailType extends AbstractType //StatType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	   	parent::buildForm($builder, $options);
	   	$builder
        ->add('nbCaractere', IntegerType::class, array('required'=>false))
        ->add('totalRecu', IntegerType::class, array('required'=>false))
        ->add('nbInscTrait', IntegerType::class, array('required'=>false))
        ->add('nbAbonnes', IntegerType::class, array('required'=>false))
        ->add('heureAbs', IntegerType::class, array('required'=>false))
        ->add('noteCoach', TextType::class, array('required'=>false))
        ->add('commentaire', TextareaType::class, array('required'=>false))

	    ;
	}


	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\SuiviBundle\Entity\Stat'
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

?>