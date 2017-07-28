<?php

namespace AdminBundle\Controller;


use AdminBundle\Entity\Blog;
use AdminBundle\Form\BlogType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction($page = 1)
    {

        $query = $this->getDoctrine()->getManager()->createQuery("select b,c  from AdminBundle:Blog b join b.category c ");
        $paginator = $this->get('knp_paginator');
        $blogs = $paginator->paginate($query, $page, 5);

        return $this->render('@Admin/Blog/index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function addBlogAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {

                $filesArray = $request->files->get("adminbundle_blog");
                /*@var UploadedFile $image */

                $image = $filesArray['image'];
                $imageCheckService = $this->get("image_checker");
                try {
                    $imageCheckService->check($image);
                } catch (\InvalidArgumentException $ex) {
                    die("Image loading error!!!!");
                }
                $imageName = rand(0000000, 9999999) . "." . $image->getClientOriginalExtension();


                $imageDirPath = "%kernel.root_dir%/../web/images/";
                $root = $this->get('kernel')->getRootDir() . "/../web/images/";
                $image->move($root, $imageName);

                $blog->setImage($imageName);
                $em = $this->getDoctrine()->getManager();
                $em->persist($blog);
                $em->flush();

                return $this->redirectToRoute('blog_admin.homepage');
            }
        }
        return $this->render('@Admin/Blog/addBlog.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView()
        ));
    }

    public function editBlogAction(Request $request, $id)
    {
        $blog = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Blog')->find($id);

        $form = $this->createForm(BlogType::class, $blog);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {


                $em = $this->getDoctrine()->getManager();
                $em->persist($blog);
                $em->flush();

                return $this->redirectToRoute('blog_admin.homepage');
            }
        }
        return $this->render('@Admin/Blog/editBlog.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView()
        ));
    }

    public function removeBlogAction($id)
    {
        $blog = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Blog')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();

        return $this->redirectToRoute('blog_admin.homepage');

    }

    public function singleBlogAction($id)
    {
        $blog = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $em = $this->getDoctrine()->getManager();
        return $this->render('@Admin/Blog/singleBlog.html.twig', array(
            'blog' => $blog
        ));
    }


    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT b FROM AdminBundle:Blog b
                WHERE b.title like :query'
        )->setParameter('query','%'.$request->get('search').'%' );
        $blogs = $query->getResult();

        return $this->render('@Admin/Blog/search.html.twig', array("blogs"=>$blogs));
    }
}