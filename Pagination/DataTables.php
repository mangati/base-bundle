<?php

namespace Mangati\BaseBundle\Pagination;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DataTables implements PaginationInterface
{
    
    private $maxResults = 1000;
    private $defaultResults = 10;
    
    /**
     * {@inheritdoc}
     */
    public function paginate(Query $query, $results, $offset, $arrayResult = true)
    {
        if ($results <= 0 || $results > $this->maxResults) {
            $results = $this->defaultResults;
        }
        if ($offset < 0) {
            $offset = 0;
        }
        
        $paginator = new Paginator($query, false);
        
        $query
                ->setFirstResult($offset)
                ->setMaxResults($results);
        
        if ($arrayResult) {
            $result = $query->getArrayResult();
        } else {
            $result = $query->getResult();
        }
        
        return [
            'recordsTotal'    => count($paginator),
            'recordsFiltered' => count($paginator),
            'data'            => $result,
        ];
    }
    
}