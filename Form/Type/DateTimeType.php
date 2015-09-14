<?php

namespace Mangati\BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class DateTimeType extends AbstractType
{
    const DATE = 'yyyy-MM-dd';
    const DATETIME = 'yyyy-MM-dd HH';

    const DATE_US = 'MM/dd/yyyy';
    const DATETIME_US = 'MM/dd/yyyy';

    const DATE_BR = 'dd/MM/yyyy';
    const DATETIME_BR = 'dd/MM/yyyy';

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults([
            'widget' => 'single_text',
            'format' => self::DATETIME,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mangati_datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'datetime';
    }
}
