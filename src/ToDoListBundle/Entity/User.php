<?php

namespace ToDoListBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
     */

    private $tasks;

    public function __construct()
    {
        parent::__construct();
        $this->tasks = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /**
     * Add tasks
     *
     * @param \ToDoListBundle\Entity\Task $tasks
     * @return User
     */
    public function addTask(\ToDoListBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \ToDoListBundle\Entity\Task $tasks
     */
    public function removeTask(\ToDoListBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
