<?php

namespace AppBundle\Entity;

use AppBundle\Lib\Enumeration\IssueStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = IssueStatus::JUST_CREATED;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getPrintTableStatus()
    {
        return IssueStatus::getALL()[$this->status];
    }

    /**
     * @return bool
     */
    public  function isJustCreated()
    {
        return $this->status == IssueStatus::JUST_CREATED;
    }

    /**
     * @return bool
     */
    public  function isDone()
    {
        return $this->status == IssueStatus::DONE;
    }

    /**
     * @return bool
     */
    public  function isInProgress()
    {
        return $this->status == IssueStatus::IN_PROGRESS;
    }
}
