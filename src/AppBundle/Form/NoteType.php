<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\TagsToTextTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NoteType extends AbstractType
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'Tytuł'))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
            ))
            ->add('code', TextareaType::class, array(
                    'label' => 'Kod',
                    'attr' => array('class' => 'form-control', 'rows' => 10)
                )
            )
            ->add('tags', TextType::class)
            ->add('position', NumberType::class, array('label' => 'Pozycja'))
        ;

        $builder->get('tags')
            ->addModelTransformer(new TagsToTextTransformer($this->manager))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Note',
            'attr' => array('novalidate'=>'novalidate')
        ));
    }
}
