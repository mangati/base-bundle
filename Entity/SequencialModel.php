<?php

namespace Mangati\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Rogério Lino <rogeriolino@gmail.com>
 * 
 * @ORM\MappedSuperclass
 */
abstract class SequencialModel implements Model
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
