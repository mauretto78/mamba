<?php

namespace Mamba\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mamba\Entity\Demo
 *
 * @ORM\Table(name="demo")
 * @ORM\Entity(repositoryClass="Mamba\Repository\DemoRepository")
 */
class Demo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="l_name", type="string", length=255, nullable=true)
     */
    private $l_name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLName()
    {
        return $this->l_name;
    }

    /**
     * @param mixed $l_name
     */
    public function setLName($l_name)
    {
        $this->l_name = $l_name;
    }
}