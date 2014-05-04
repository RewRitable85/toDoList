<?php

namespace artjom\ToDoListBundle\Form\Extension\Type;

use artjom\ToDoListBundle\Entity\Task;

class TaskStatusChoices
{
	private static $choices = array(
			Task::OPEN => 'Opened',
            Task::PROGRESS => 'In Progress',
			Task::CLOSE => 'Closed',
	);
	
	public static function getChoices(){
		return self::$choices;
	}
	
	public static function getChoicesKeys(){
		return array_keys(self::$choices);
	}
	
	public static function getValueByChoice($key){
		return self::$choices[$key];
	}
}