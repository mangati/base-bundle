<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Mangati\BaseBundle\Controller\CrudController;
use {entityName} as Entity;
use {formType} as EntityType;

/**
 * 
 * @author MangatiBuilder <mangati.com>
 * 
 * @Route("{routePrefix}")
 */
class {controllerName}Controller extends CrudController
{
    
    public function __construct()
    {
        parent::__construct(Entity::class);
    }
    
    /**
     * @Route("", name="{routeNamePrefix}_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('{templatePath}/index.html.twig', [
        ]);
    }
    
    /**
     * @Route("/search.json", name="{routeNamePrefix}_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em
                ->createQueryBuilder()
                ->select('e')
                ->from(Entity::class, 'e')
                ->getQuery();
        
        return $this->dataTable($request, $query, true);
    }
    
    /**
     * @Route("/edit/{id}", name="{routeNamePrefix}_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id = 0)
    {
        return $this->edit('{templatePath}/edit.html.twig', $request, $id);
    }
    
    protected function createFormType()
    {
        return EntityType::class;
    }

}