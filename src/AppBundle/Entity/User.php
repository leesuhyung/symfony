<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-zA-Z0-9가-힣_]{1,10}$/u", message="이름은 10자 이하의 영문, 숫자, 한글, 언더바까지 가능합니다.")
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(name="remember_token", type="string", length=100, nullable=true)
     */
    private $rememberToken;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var ArrayCollection
     * @Serializer\Groups({"User_Board"})
     * @Serializer\Expose()
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Board", mappedBy="user")
     */
    private $boards;

    /**
     * @Serializer\Groups({"list", "detail"})
     * @Serializer\Expose()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role")
     */
    private $role;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->boards = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param string $rememberToken
     */
    public function setRememberToken(string $rememberToken)
    {
        $this->rememberToken = $rememberToken;
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

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        // 패스워드가 salt 를 사용해 인코드 하고 있지 않는 경우에는 null 리턴이 가능하다.
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Board[]|ArrayCollection
     */
    public function getBoards()
    {
        return $this->boards;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }
}

