<?php

namespace Mangati\BaseBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Mangati\BaseBundle\Event\CrudEvent;
use Mangati\BaseBundle\Event\CrudEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract CRUD controller
 */
abstract class CrudController extends EntityController {
    
    const MSG_REGISTRO_SALVO = 'Registro salvo com sucesso';

    private $dispatcher;
    
    /**
     * 
     * @return EventDispatcher
     */
    protected function getDispatcher() {
        if (!$this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }
        return $this->dispatcher;
    }
    
    /**
     * 
     * @param string   $eventName The name of the event to listen to.
     * @param callable $listener  The listener to execute.
     * @param int      $priority  The priority of the listener. Listeners
     *                            with a higher priority are called before
     *                            listeners with a lower priority.
     * @return CrudController
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->getDispatcher()->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * Manipula uma requisição do jQuery DataTable e devolve o JSON necessário
     * para exibir a tabela.
     * 
     * @param Request $request
     * @param Query $query
     * @return JsonResponse
     */
    protected function dataTable(Request $request, Query $query, $arrayResult = true) {
        $maxResults = (int) $request->get('length');
        if ($maxResults <= 0 || $maxResults > 1000) {
            $maxResults = 10;
        }
        $offset = (int) $request->get('start');
        if ($offset < 0) {
            $offset = 0;
        }
        $paginator = new Paginator($query, false);
        $query
                ->setFirstResult($offset)
                ->setMaxResults($maxResults)
        ;
        if ($arrayResult) {
            $result = $query->getArrayResult();
        } else {
            $result = $query->getResult();
        }
        $content = array(
            'recordsTotal' => sizeof($paginator),
            'recordsFiltered' => sizeof($paginator),
            'data' => $result
        );
        return new JsonResponse($content);
    }

    /**
     * Manipula a requisição para resgatar ou salvar uma entidade na página de edição da entidade.
     * 
     * @param Request $request
     * @param integer $id
     * @return array
     */
    protected function edit($template, Request $request, $id = 0) {
        $em = $this->getDoctrine()->getManager();
        $entity = null;
        if ($id > 0) {
            $entity = $em->find($this->entityName, $id);
        }
        if (!$entity) {
            $entity = $this->createEntity();
        }

        $form = $this->createEditForm($request, $entity);
        
        $this->getDispatcher()
                ->dispatch(CrudEvents::PRE_EDIT, new CrudEvent($entity, $request, $form));

        // evita de manipular a requisição quando nao for POST (como GET por exemplo)
        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            try {
                $this->getDispatcher()
                        ->dispatch(CrudEvents::PRE_VALIDATE, new CrudEvent($entity, $request, $form));
                
                if ($form->isValid()) {
                    
                    $this->saveOrUpdate($entity, $request, $form);
                    
                    // redireciona a pagina para evitar reenvio do post
                    return $this->redirect($this->editUrl($request, $entity));
                }
            } catch (Exception $e) {
                $this->getDispatcher()
                        ->dispatch(CrudEvents::ERROR, new CrudEvent($e));
                // flash message
                $this->addFlash('error', $e->getMessage());
            }
        }
        
        $this->getDispatcher()
                ->dispatch(CrudEvents::POST_EDIT, new CrudEvent($entity, $request, $form));
        
        $params = new ArrayCollection([
            'entity' => $entity,
            'form' => $form->createView()
        ]);
        
        $this->getDispatcher()
                ->dispatch(CrudEvents::FORM_RENDER, new CrudEvent($params, $request, $form));
        
        // render view
        return $this->render($template, $params->toArray());
    }
    
    /**
     * 
     * Insere ou atualiza a entidade no banco. Esse método pode ser sobrescrito
     * caso haja necessidade de utilizar algum serviço de regras de negócio
     * @param mixed $entity
     * @param Form $form
     */
    protected function saveOrUpdate($entity, Request $request, Form $form) {
        $this->getDispatcher()
                ->dispatch(CrudEvents::PRE_SAVE, new CrudEvent($entity, $request, $form));
        
        $em = $this->getDoctrine()->getManager();
        
        $em->persist($entity);
        $em->flush();
        
        $this->getDispatcher()
                ->dispatch(CrudEvents::POST_SAVE, new CrudEvent($entity, $request, $form));
        
        // flash message
        $this->addFlash('success', self::MSG_REGISTRO_SALVO);
    }

    /**
     * Monta a URL de edição a partir da entidade. OBS: esse método só pode ser chamado dentro do edit()
     * @param Request $request
     * @param type $entity
     */
    protected function editUrl(Request $request, $entity) {
        $route = $request->get('_route');
        return $this->generateUrl($route, ['id' => $entity->getId()]);
    }

    /**
     * Deve retornar o FormType do cadastro
     * @param mixed $entity A entidade do cadastro
     * @return FormTypeInterface
     */
    protected abstract function createFormType();
    
    /**
     * Cria o FormType de edição do formulário
     * @param Request $request
     * @param mixed $entity
     * @return FormInterface
     */
    protected function createEditForm(Request $request, $entity) {
        $this->getDispatcher()
                ->dispatch(CrudEvents::PRE_CREATE_FORM, new CrudEvent($entity, $request));
        
        return $this->createForm($this->createFormType(), $entity, [
            'action' => $this->editUrl($request, $entity),
            'method' => 'post'
        ]);
    }
}
