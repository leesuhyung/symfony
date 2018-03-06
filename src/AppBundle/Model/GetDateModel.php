<?php
namespace AppBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class GetDateModel
{
    /**
     * @Serializer\Type("string")
     */
    private $getDate;

    /**
     * GetDateModel constructor.
     */
    public function __construct()
    {
        $this->getDate = new \DateTime();
    }
}