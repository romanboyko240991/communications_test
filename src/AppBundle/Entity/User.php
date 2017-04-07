<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $privilegy;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Field")
     * @ORM\JoinTable(name="fos_user_field",
     *      joinColumns={@ORM\JoinColumn(name="fos_user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $fields;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    public function getPrivilegy()
    {
        return $this->privilegy;
    }

    public function setPrivilegy($privilegy)
    {
        if(in_array($privilegy, ['delete', 'edit', 'read'])) {
            $this->privilegy = $privilegy;
            return true;
        }

        return false;
    }

    /**
     * Add field
     *
     * @param \AppBundle\Entity\Field $field
     *
     * @return User
     */
    public function addField(\AppBundle\Entity\Field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Remove field
     *
     * @param \AppBundle\Entity\Field $field
     */
    public function removeField(\AppBundle\Entity\Field $field)
    {
        $this->fields->removeElement($field);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function cleanFields()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __construct()
    {
        parent::__construct();
        $this->privilegy = 'none';
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
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
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}