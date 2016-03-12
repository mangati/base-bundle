<?php

namespace Mangati\BaseBundle\Builder;

/**
 * 
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class CrudControllerBuilder
{
    
    public function controller(array $params)
    {
        $prototype = file_get_contents(__DIR__ . '/../Resources/prototype/crud/controller.php');
        
        return $this->parse($prototype, $params);
    }
    
    public function indexTemplate(array $params)
    {
        $prototype = file_get_contents(__DIR__ . '/../Resources/prototype/crud/index.html.twig');
        
        return $this->parse($prototype, $params);
    }
    
    public function editTemplate(array $params)
    {
        $prototype = file_get_contents(__DIR__ . '/../Resources/prototype/crud/edit.html.twig');
        
        return $this->parse($prototype, $params);
    }
    
    private function parse($prototype, array $params)
    {
        foreach ($params as $k => $v) {
            $prototype = str_replace("{{$k}}", $v, $prototype);
        }
        
        return $prototype;
    }
    
}