<?php

namespace Mangati\BaseBundle\Event;

/**
 * CRUD events
 */
final class CrudEvents
{
    
    const PRE_EDIT         = 'crud.pre-edit';
    const POST_EDIT        = 'crud.post-edit';
    const PRE_VALIDATE     = 'crud.pre-validate';
    const PRE_SAVE         = 'crud.pre-save';
    const POST_SAVE        = 'crud.post-save';
    const ERROR            = 'crud.error';
    const FORM_RENDER      = 'crud.form-render';
    
}
