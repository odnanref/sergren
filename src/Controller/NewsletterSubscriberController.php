<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterSubscriberType;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newsletter/subscriber")
 */
class NewsletterSubscriberController extends Controller
{
    /**
     * @Route("/", name="newsletter_subscriber_index", methods="GET")
     */
    public function index(NewsletterSubscriberRepository $newsletterSubscriberRepository): Response
    {
        return $this->render('newsletter_subscriber/index.html.twig', ['newsletter_subscribers' => $newsletterSubscriberRepository->findAll()]);
    }

    /**
     * @Route("/new", name="newsletter_subscriber_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $newsletterSubscriber = new NewsletterSubscriber();
        $form = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newsletterSubscriber);
            $em->flush();

            return $this->redirectToRoute('newsletter_subscriber_index');
        }

        return $this->render('newsletter_subscriber/new.html.twig', [
            'newsletter_subscriber' => $newsletterSubscriber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="newsletter_subscriber_show", methods="GET")
     */
    public function show(NewsletterSubscriber $newsletterSubscriber): Response
    {
        return $this->render('newsletter_subscriber/show.html.twig', ['newsletter_subscriber' => $newsletterSubscriber]);
    }

    /**
     * @Route("/{id}/edit", name="newsletter_subscriber_edit", methods="GET|POST")
     */
    public function edit(Request $request, NewsletterSubscriber $newsletterSubscriber): Response
    {
        $form = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('newsletter_subscriber_edit', ['id' => $newsletterSubscriber->getId()]);
        }

        return $this->render('newsletter_subscriber/edit.html.twig', [
            'newsletter_subscriber' => $newsletterSubscriber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="newsletter_subscriber_delete", methods="DELETE")
     */
    public function delete(Request $request, NewsletterSubscriber $newsletterSubscriber): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsletterSubscriber->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($newsletterSubscriber);
            $em->flush();
        }

        return $this->redirectToRoute('newsletter_subscriber_index');
    }
}
