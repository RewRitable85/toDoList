<?php

namespace artjom\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use artjom\ToDoListBundle\Form\Extension\Type\TaskStatusChoices;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Task
{
	const OPEN = 1; 
	const PROGRESS = 2;
	const CLOSE = 3;
	
	const MIN_TITLE_LENGTH = 3;
	const MAX_TITLE_LENGTH = 64;
	const MAX_DESCRIPTION_LENGTH = 256;
	const DATETIME_FORMAT = 'dd-mm-yyyy hh:ii';
		  
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $start_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $end_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
	
	public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
		$metadata->addPropertyConstraint('title', new Assert\NotBlank());
		$metadata->addPropertyConstraint('title', new Length(array('min' => self::MIN_TITLE_LENGTH, 'max' => self::MAX_TITLE_LENGTH)));
		$metadata->addPropertyConstraint('status', new Assert\NotBlank());
		$metadata->addPropertyConstraint('status', new Choice(array('choices' => TaskStatusChoices::getChoicesKeys())));
		$metadata->addConstraint(new Assert\Callback('validate'));
		$metadata->addPropertyConstraint('start_date', new Assert\NotBlank());
		$metadata->addPropertyConstraint('start_date', new Assert\DateTime());
		$metadata->addPropertyConstraint('end_date', new Assert\NotBlank());
		$metadata->addPropertyConstraint('end_date', new Assert\DateTime());
		$metadata->addPropertyConstraint('description', new Length(array('max' => self::MAX_DESCRIPTION_LENGTH)));
	}
	
	/**
     * Extra validation
     *
     */
	public function validate(ExecutionContextInterface $context)
    {
		//validate that start_date is not bigger than end_date
		if($this->start_date && $this->end_date){
			if($this->start_date->getTimestamp() > $this->end_date->getTimestamp()){
				$context->addViolationAt(
					'start_date',
					'The starting date must be anterior than the ending date',
					array(),
					null
				);
			}
		}
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
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
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set status
     *
     * @param integer $status
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
}
