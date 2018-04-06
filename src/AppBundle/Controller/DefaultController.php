<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Lib\Enumeration\IssueStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        $task = new Task();
        $form_builder = $this->createFormBuilder($task);
        $form_builder->add('title', TextType::class,[
            'label'=>'Добавит новую задачу',
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Надо сделать'
            ]
        ]);
        $form_builder->add('save', SubmitType::class,[
            'label'=>"Добавить"
        ]);
        $form = $form_builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute("homepage");
        }
        return $this->render("@App/default/index.html.twig",[
            'form' => $form->createView(),
            'tasks'=>$tasks
        ]);
    }

    /**
     * @Route("/task/set_in_progress/{id}",name="taskSetInProgress")
     * @Method({"GET"})
     * @param $id integer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setInProgressAction(int $id)
    {
        $this->changeStatus($id,IssueStatus::IN_PROGRESS);
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/task/set_done/{id}",name="taskSetDone")
     * @Method({"GET"})
     * @param $id integer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDoneAction(int $id)
    {
        $this->changeStatus($id,IssueStatus::DONE);
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/task/delete/{id}",name="taskDelete")
     * @Method({"GET"})
     * @param $id integer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if ($task){
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/task/delete_done_all",name="taskDeleteDoneAll")
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDoneAllAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy(['status' => IssueStatus::DONE]);
        if ($tasks){

            $em = $this->getDoctrine()->getManager();
            foreach ($tasks as $task){
                $em->remove($task);
            }
            $em->flush();
        }

        return $this->redirectToRoute("homepage");
    }

    /**
     * @param int $id
     * @param int $status
     */
    private function changeStatus(int $id,int $status)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if ($task){
            $task->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
    }
}
