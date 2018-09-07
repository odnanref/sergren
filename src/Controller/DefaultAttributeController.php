<?php

namespace App\Controller;

use App\Entity\DefaultAttribute;
use App\Form\DefaultAttributeType;
use App\Repository\DefaultAttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/default/attribute")
 */
class DefaultAttributeController extends Controller
{
    /**
     * @Route("/", name="default_attribute_index", methods="GET")
     */
    public function index(DefaultAttributeRepository $defaultAttributeRepository): Response
    {
        
        return $this->render('default_attribute/index.html.twig', ['default_attributes' => $defaultAttributeRepository->findAll()]);
    }

    /**
     * @Route("/new", name="default_attribute_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $defaultAttribute = new DefaultAttribute();
        $form = $this->createForm(DefaultAttributeType::class, $defaultAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($defaultAttribute);
            $em->flush();

            return $this->redirectToRoute('default_attribute_index');
        }

        return $this->render('default_attribute/new.html.twig', [
            'default_attribute' => $defaultAttribute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="default_attribute_show", methods="GET")
     */
    public function show(DefaultAttribute $defaultAttribute): Response
    {
        return $this->render('default_attribute/show.html.twig', ['default_attribute' => $defaultAttribute]);
    }

    /**
     * @Route("/{id}/edit", name="default_attribute_edit", methods="GET|POST")
     */
    public function edit(Request $request, DefaultAttribute $defaultAttribute): Response
    {
        $form = $this->createForm(DefaultAttributeType::class, $defaultAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('default_attribute_edit', ['id' => $defaultAttribute->getId()]);
        }

        return $this->render('default_attribute/edit.html.twig', [
            'default_attribute' => $defaultAttribute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="default_attribute_delete", methods="DELETE")
     */
    public function delete(Request $request, DefaultAttribute $defaultAttribute): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defaultAttribute->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($defaultAttribute);
            $em->flush();
        }

        return $this->redirectToRoute('default_attribute_index');
    }
}
