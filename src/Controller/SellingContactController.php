<?php

namespace App\Controller;

use App\Entity\SellingContact;
use App\Form\SellingContactType;
use App\Repository\SellingContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/selling/contact")
 */
class SellingContactController extends Controller
{
    /**
     * @Route("/", name="selling_contact_index", methods="GET")
     */
    public function index(SellingContactRepository $sellingContactRepository): Response
    {
        return $this->render('selling_contact/index.html.twig', ['selling_contacts' => $sellingContactRepository->findAll()]);
    }

    /**
     * @Route("/new", name="selling_contact_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $sellingContact = new SellingContact();
        $form = $this->createForm(SellingContactType::class, $sellingContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sellingContact);
            $em->flush();

            return $this->redirectToRoute('selling_contact_index');
        }

        return $this->render('selling_contact/new.html.twig', [
            'selling_contact' => $sellingContact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="selling_contact_show", methods="GET")
     */
    public function show(SellingContact $sellingContact): Response
    {
        return $this->render('selling_contact/show.html.twig', ['selling_contact' => $sellingContact]);
    }

    /**
     * @Route("/{id}/edit", name="selling_contact_edit", methods="GET|POST")
     */
    public function edit(Request $request, SellingContact $sellingContact): Response
    {
        $form = $this->createForm(SellingContactType::class, $sellingContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('selling_contact_edit', ['id' => $sellingContact->getId()]);
        }

        return $this->render('selling_contact/edit.html.twig', [
            'selling_contact' => $sellingContact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="selling_contact_delete", methods="DELETE")
     */
    public function delete(Request $request, SellingContact $sellingContact): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sellingContact->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sellingContact);
            $em->flush();
        }

        return $this->redirectToRoute('selling_contact_index');
    }
}
