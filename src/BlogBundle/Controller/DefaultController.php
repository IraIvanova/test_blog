<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction($page = 1)
    {

        $query = $this->getDoctrine()->getManager()->createQuery("select b,c  from AdminBundle:Blog b join b.category c ");
        $paginator = $this->get('knp_paginator');
        $blogs = $paginator->paginate($query, $page, 5);

        return $this->render('@Blog/Blog/index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function singleBlogAction($id)
    {
        $blog = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $this->render('@Blog/Blog/singleBlog.html.twig', array(
            'blog' => $blog
        ));
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT b FROM AdminBundle:Blog b
                WHERE b.title like :query
                '
        )->setParameter('query', '%' . $request->get('query') . '%');
        $blogs = $query->getResult();

        return $this->render('@Blog/Blog/search.html.twig', array("blogs" => $blogs));
    }


}