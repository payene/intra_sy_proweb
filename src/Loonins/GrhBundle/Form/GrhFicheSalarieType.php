<?php

namespace Loonins\GrhBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Util\LegacyFormHelper;

class GrhFicheSalarieType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre')
                ->add('contenu')
                //->add('date', 'birthday', array('widget' => 'single_text'))
                ->add('type', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsGrhBundle:GrhtypeFiche', 'multiple' => false))
//                ->add('employe', 'entity', array('class' => 'LooninsGrhBundle:GrhEmployes', 'multiple' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\GrhBundle\Entity\GrhFicheSalarie'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_grhbundle_grhfichesalarie';
    }

}
