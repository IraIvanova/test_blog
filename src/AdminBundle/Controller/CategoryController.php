<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if($request->isMethod("POST")){
            $form->handleRequest($request);
            if($form->isSubmitted()){

                $em= $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('blog_admin.homepage');
            }
        }
        return $this->render('@Admin/Category\add.html.twig', array(
            'category' => $category,
            "form" => $form->createView()
        ));
    }

    public function editAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Category')->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('blog_admin.homepage');
            }
        }
        return $this->render('@Admin/Category\edit.html.twig', array(
            'category' => $category,
            "form" => $form->createView()
        ));
    }

    public function removeAction($id){
        $category = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Category')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_blog.homepage');

    }

    public function listAction(){
        $categories = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Category')->findAll();

        return $this->render('@Admin\Category\list.html.twig', array(
            'categories'=> $categories
        ));
    }

}
