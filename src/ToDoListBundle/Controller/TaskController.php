<?php

namespace ToDoListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ToDoListBundle\Entity\Task;
use ToDoListBundle\Entity\User;
use ToDoListBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class TaskController extends Controller
{

    /**
     * Main page.
     *
     * @Route("/", name="main_page")
     * @Method("GET")
     */


    public function mainAction()
    {
        return $this->redirectToRoute('task_index');
        //return $this->render('index.html.twig', array());

    }

    /**
     * Lists all Task entities by userID.
     *
     * @Route("/task", name="task_index")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */


    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $userId = $this->getUser()->getId();

        $tasks = $em->getRepository('ToDoListBundle:Task')->findByUser($userId);

        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    /**
     * Lists all Task entities.
     *
     * @Route("/task/all", name="all_tasks")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     */


    public function allTasksAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userId = $this->getUser()->getId();

        $tasks = $em->getRepository('ToDoListBundle:Task')->findAll();

        return $this->render('task/all.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/task/new", name="task_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm('ToDoListBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        $task->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('task/new.html.twig', array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("task/{id}", name="task_show")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("task/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('ToDoListBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("task/{id}", name="task_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a Task entity.
     *
     * @param Task $task The Task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
