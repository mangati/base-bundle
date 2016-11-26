<?php

namespace Mangati\BaseBundle\Form\Type;

/**
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class EntityTextType extends AbstractEntityInputType
{
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }
}