<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use AppBundle\Validator\Constraints as Constraints;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Board
 *
 * @ORM\Table(name="board", indexes={@ORM\Index(name="fk_board_user_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="BoardRepository")
 * @Constraints\BoardConstraint
 */
class Board
{
    const BOARD_VALIDATOR = '제목이나 내용';

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=45, nullable=false)
     * @Serializer\Groups({"list", "detail"})
     */
    private $entity = 'info';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list", "detail"})
     * @Assert\Length(min=5, minMessage="제목은 최소 5자 이상 입력해주세요.")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="contents", type="text", length=65535, nullable=false)
     * @Serializer\Groups({"list", "detail"})
     * @Assert\Length(min=10, minMessage="내용은 최소 10자 이상 입력해주세요.")
     */
    private $contents;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Serializer\Groups({"list", "detail"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Groups({"list", "detail"})
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="boards")
     * @Serializer\Groups({"list", "detail"})
     */
    private $user;

    /**
     * Board constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


}

