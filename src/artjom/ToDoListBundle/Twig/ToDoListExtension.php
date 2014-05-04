<?php

namespace artjom\ToDoListBundle\Twig;

use artjom\ToDoListBundle\Form\Extension\Type\TaskStatusChoices;

class ToDoListExtension extends \Twig_Extension
{
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'tostatusname' => new \Twig_Function_Method($this, 'toStatusName')
        );
    }

    /**
     * Converts a string to time
     *
     * @param string $string
     * @return int
     */
    public function toStatusName ($integer)
    {
        return TaskStatusChoices::getValueByChoice($integer);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'to_do_list_extension';
    }
}