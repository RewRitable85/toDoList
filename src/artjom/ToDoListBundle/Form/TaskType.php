<?php

namespace artjom\ToDoListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use artjom\ToDoListBundle\Form\Extension\Type\TaskStatusType;
use artjom\ToDoListBundle\Entity\Task;

class TaskType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$properties = array();
		$datePickerProperties = array( //using SCDatetimepickerBundle
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
		);
		if($builder->getData()->getId() != null){
			$properties['disabled'] = true;
			$datePickerProperties['disabled'] = true;
		}
		$builder
				->add('title', 'text', $properties)
				->add('status', new TaskStatusType())
				->add('start_date', 'collot_datetime', $datePickerProperties)
				->add('end_date', 'collot_datetime', $datePickerProperties)
			;
		$properties['required'] = false;
		$builder->add('description', 'textarea', $properties);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'artjom\ToDoListBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artjom_todolistbundle_task';
    }
}
