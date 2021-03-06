<?php

namespace Mangati\BaseBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract entity controller.
 */
abstract class EntityController extends Controller
{
    protected $entityName;

    public function __construct($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * Retorna o repositório da entidade do controlador.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->entityName);
    }

    /**
     * @return mixed
     */
    public function createEntity(Request $request)
    {
        return new $this->entityName();
    }
}
