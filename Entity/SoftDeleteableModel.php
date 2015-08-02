<?php
namespace Mangati\BaseBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of SoftDeleteable
 *
 * @author rogerio
 * 
 * @ORM\MappedSuperclass
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class SoftDeleteableModel extends SequencialModel 
{
    
    /**
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     * @var DateTime
     */
    protected $deleted;
        
    public function getDeleted() {
        return $this->deleted;
    }

    public function setDeleted(DateTime $deleted) {
        $this->deleted = $deleted;
    }
    
}
