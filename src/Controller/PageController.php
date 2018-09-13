<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/admin/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="page_index", methods="GET")
     */
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('page/index.html.twig', ['pages' => $pageRepository->findAll()]);
    }

    /**
     * @Route("/new", name="page_new", methods="GET|POST")
     */
    public function new(Request $request, TranslatorInterface $translator, PageRepository $pageRepo): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($pageRepo->existsUrl($page->getUrl())) {
            $this->addFlash("error", $translator->trans("Already exists Url"));
            
            return $this->render('page/new.html.twig', [
                'page' => $page,
                'form' => $form->createView(),
            ]);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page_index');
        }

        return $this->render('page/new.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="page_show", methods="GET")
     */
    public function show(Page $page): Response
    {
        return $this->render('page/show.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/{id}/edit", name="page_edit", methods="GET|POST")
     */
    public function edit(Request $request, Page $page, TranslatorInterface $translator, PageRepository $pageRepo): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($pageRepo->existsUrl($page->getUrl(), $page->getId())) {
            $this->addFlash("error", $translator->trans("Already exists Url"));
            return $this->render('page/edit.html.twig', [
                'page' => $page,
                'form' => $form->createView(),
            ]);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('page_edit', ['id' => $page->getId()]);
        }

        return $this->render('page/edit.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="page_delete", methods="DELETE")
     */
    public function delete(Request $request, Page $page): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
        }

        return $this->redirectToRoute('page_index');
    }
}
