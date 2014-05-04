<?php

namespace artjom\ToDoListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use artjom\ToDoListBundle\Entity\Task;
use artjom\ToDoListBundle\Entity\Search\TaskSearch;
use artjom\ToDoListBundle\Form\TaskType;
use artjom\ToDoListBundle\Form\Search\TaskSearchType;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{

    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $taskRepo = $this->getDoctrine()->getEntityManager()->getRepository('artjomToDoListBundle:Task');
		$qb = $taskRepo->createQueryBuilder('t')->orderBy('t.end_date', 'ASC');
		
		//Create task search form
		$taskSearch = new TaskSearch();
		$taskSearchForm = $this->createForm(new TaskSearchType(), $taskSearch, array(
			'action' => $this->generateUrl('task_list')
		));
		$taskSearchForm->bind($request);
		//Adding task search form filters, if needed
		if($taskSearchForm->isValid()){
			if($taskSearch->getTitle())
				$qb->andWhere(
					$qb->expr()->eq('t.title', 
										$qb->expr()->literal($taskSearch->getTitle())
				));
			if($taskSearch->getStatus())
				$qb->andWhere(
					$qb->expr()->eq('t.status', $taskSearch->getStatus())
				);
			if($taskSearch->getDate())
				$qb->andWhere(
					$qb->expr()->eq('t.end_date', 
										$qb->expr()->literal(
													$taskSearch->getDate()->format('Y-m-d H:i:s')
				)));
		}
		
		//Getting task totals
		$taskTotals = $taskRepo->createQueryBuilder('c')
			->select('count(c) as total, c.status')
			->groupBy('c.status')
			->getQuery()
			->getResult();
			
		//Pagination (10 tasks per page)
		$pagination = $this->get('knp_paginator')->paginate( //using KnpPaginatorBundle
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1),
            10
        );

        return array(
            'entities' => $pagination,
			'form' => $taskSearchForm->createView(),
			'task_totals' => $taskTotals,
        );
    }
    /**
     * Creates a new Task entity.
     *
     * @Route("/create", name="task_create")
     * @Method("POST")
     * @Template("artjomToDoListBundle:Task:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Task();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_list'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/edit/{id}", name="task_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('artjomToDoListBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}", name="task_update")
     * @Method("PUT")
     * @Template("artjomToDoListBundle:Task:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('artjomToDoListBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('task_list'));
		}

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/delete/{id}", name="task_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('artjomToDoListBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('task_list'));
    }
}
