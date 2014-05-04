<?php

namespace artjom\ToDoListBundle\Entity\Search;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints as Assert;
use artjom\ToDoListBundle\Form\Extension\Type\TaskStatusChoices;
use artjom\ToDoListBundle\Entity\Task;

/**
 * TaskSearch
 */
class TaskSearch
{

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $status;
	
	public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
		$metadata->addPropertyConstraint('title', new Length(array('min' => Task::MIN_TITLE_LENGTH, 'max' => Task::MAX_TITLE_LENGTH)));
		$metadata->addPropertyConstraint('status', new Choice(array('choices' => TaskStatusChoices::getChoicesKeys())));
		$metadata->addPropertyConstraint('date', new Assert\DateTime());
	}

    /**
     * Set title
     *
     * @param string $title
     * @return TaskSearch
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
     * Set date
     *
     * @param \DateTime $date
     * @return TaskSearch
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return TaskSearch
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
}
