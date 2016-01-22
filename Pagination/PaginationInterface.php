<?php

namespace Mangati\BaseBundle\Pagination;

use Doctrine\ORM\Query;

interface PaginationInterface
{
    
    /**
     * 
     * @param Query $query
     * @param int $results
     * @param int $offset
     * @param bool $arrayResult
     * @return array
     */
    public function paginate(Query $query, $results, $offset, $arrayResult = true);
    
}