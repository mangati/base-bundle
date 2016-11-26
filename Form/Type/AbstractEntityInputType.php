<?php

namespace Mangati\BaseBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Mangati\BaseBundle\Form\DataTransform\EntityToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
abstract class AbstractEntityInputType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $om = $options['objectManager'];
        $entityClass = $options['class'];
        
        $modelTransformer = new EntityToIdTransformer($om, $entityClass);
        
        $builder->addModelTransformer($modelTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver
            ->setRequired([
                'class', 'objectManager'
            ])
            ->setAllowedTypes('objectManager', [ObjectManager::class]);
    }
}
