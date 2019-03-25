<?php

namespace Loonins\IncidentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GdpIncidentType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextType'), array('required' => true))
                ->add('description',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextareaType'), array('required' => true))
                ->add('date',  LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\DateType'), array('widget' => 'single_text'))
                ->add('duree', TextType::class, array('label' => 'Duree de l\'incident ', 'required'=>false))
                // ->add('updateDate')
//                ->add('employe', 'entity', array('required' => false, 'class' => 'LooninsGrhBundle:GrhEmployes', 'multiple' => false))
                // ->add('status', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('read_only' => true, 'class' => 'LooninsIncidentBundle:GdpStatus', 'multiple' => false))
                ->add('categorie', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('class' => 'LooninsIncidentBundle:TypeIncident', 'multiple' => false))
                ->add('employe', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), ['class' => 'LooninsGrhBundle:GrhEmployes',
                    'required' => false,
                    'query_builder' => function ($er) {
                        return $er->createQueryBuilder('e')
                                ->orderBy('e.prenoms', 'ASC')
                                ->orderBy('e.nom', 'ASC');
                    }
                ])
                // ->add('proprio', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'), array('read_only' => true, 'class' => 'LooninsUserBundle:User', 'multiple' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Loonins\IncidentBundle\Entity\GdpIncident'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'loonins_incidentbundle_gdpincident';
    }

}
