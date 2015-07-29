<?php

namespace Mangati\BaseBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * CRUD event
 */
class CrudEvent extends Event
{

    /**
     * @var mixed
     */
    private $data;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FormInterface
     */
    private $form;
    
    
    public function __construct($data, Request $request = null, FormInterface $form = null)
    {
        $this->data = $data;
        $this->form = $form;
        $this->request = $request;
    }
    
    public function getData()
    {
        return $this->data;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getRequest()
    {
        return $this->request;
    }
    
}
