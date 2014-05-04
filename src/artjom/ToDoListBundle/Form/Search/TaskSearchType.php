<?php

namespace artjom\ToDoListBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use artjom\ToDoListBundle\Form\Extension\Type\TaskStatusType;
use artjom\ToDoListBundle\Entity\Task;

class TaskSearchType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
				->add('title', 'text', array('required' => false))
				->add('status', new TaskStatusType(), array('empty_value' => 'All', 'required' => false))
				->add('date', 'collot_datetime', array( //using SCDatetimepickerBundle
						'label' => 'Due date', 
						'pickerOptions' => array(
							'format' => Task::DATETIME_FORMAT,
							'weekStart' => 1,
							'minuteStep' => 1,
							'autoclose' => true,
							'todayBtn' => true,
							'startView' => 'month',
							'minView' => 'hour',
							'language' => 'en',
							'pickerPosition' => 'bottom-right',
						)
				))
				->add('search', 'submit')
			;
    }
	
	/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'artjom\ToDoListBundle\Entity\Search\TaskSearch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artjom_todolistbundle_task_search';
    }
}