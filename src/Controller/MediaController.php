<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/media")
 */
class MediaController extends Controller
{
    /**
     * @Route("/", name="media_index", methods="GET")
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        $request = Request::createFromGlobals();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $mediaRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
            );
        
        return $this->render('media/index.html.twig', ['media' => $pagination]);
    }

    /**
     * @Route("/new", name="media_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $path = $request->files->get("media");
            if (empty($path) || (!is_array($path) || !array_key_exists("path", $path))) {
                $response = new Response('Sem imagem', 500);
                return $response;
            }
            $file = $path['path'];
            if ($file === null ) {
                $response = new Response('Sem nome da imagem', 500);
                return $response;
            }
            
            $fileName = $file->getClientOriginalName();
            if (empty($fileName)) {
                $response = new Response('Sem nome da imagem', 500);
                return $response;
            }
            
            $em = $this->getDoctrine()->getManager();
            
            try {
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                    );
                
                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $medium->setPath($fileName);
                
                $em->persist($medium);
                $em->flush();
            
                $imagescale = new \App\Service\ImageScale($medium, $this->getParameter("media_directory") . DIRECTORY_SEPARATOR );
                $imagescale->scale();
                
            } catch (\Exception $e) {
                $em->remove($medium);
                $em->flush();
                                
                $response = new Response('Falha a redimensionar imagem para thumbs ' . $e->getMessage(), 500);
                return $response;
            }
            
            return $this->redirectToRoute('media_index');
        }

        return $this->render('media/new.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/newbyhidden", name="media_new_by_hidden", methods="POST")
     */
    public function newByHidden(Request $request): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $path = $request->files->get("media");
            if (empty($path) || (!is_array($path) || !array_key_exists("path", $path))) {
                $response = new Response('Sem imagem', 500);
                return $response;
            }
            $file = $path['path'];
            if ($file === null ) {
                $response = new Response('Sem nome da imagem', 500);
                return $response;
            }
            
            $fileName = $file->getClientOriginalName();
            if (empty($fileName)) {
                $response = new Response('Sem nome da imagem', 500);
                return $response;
            }
            
            $em = $this->getDoctrine()->getManager();
            
            try {
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('media_directory'),
                    $fileName
                    );
                
                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $medium->setPath($fileName);
                
                $em->persist($medium);
                $em->flush();
                
                $imagescale = new \App\Service\ImageScale($medium, $this->getParameter("media_directory") . DIRECTORY_SEPARATOR);
                $imagescale->scale();
                
            } catch (\Exception $e) {
                $em->remove($medium);
                $em->flush();
                
                $response = new Response('Falha a redimensionar imagem para thumbs ' . $e->getMessage(), 500);
                return $response;
            }
            return JsonResponse::fromJsonString('{"status": "ok", "message":"Saved", "image": "' . $fileName . '"}', 200);
        }
        
        $errors = "";
        foreach ($form->getErrors(false) as $error) {
            $errors .= $error->getMessage(). PHP_EOL;
        }
        $errors = \json_encode($errors);
        return JsonResponse::fromJsonString('{"status": "fail", "message": '.$errors . '}', 500);
    }

    /**
     * @Route("/{id}", name="media_show", methods="GET")
     */
    public function show(Media $medium): Response
    {
        return $this->render('media/show.html.twig', ['medium' => $medium]);
    }

    /**
     * @Route("/{id}/edit", name="media_edit", methods="GET|POST")
     */
    public function edit(Request $request, Media $medium): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('media_edit', ['id' => $medium->getId()]);
        }

        return $this->render('media/edit.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="media_delete", methods="DELETE")
     */
    public function delete(Request $request, Media $medium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
            
            $dir = $this->getParameter("media_directory");
            if (is_writable($dir . DIRECTORY_SEPARATOR . $medium->getPath())) {
                $res = \unlink($dir . DIRECTORY_SEPARATOR . $medium->getPath());
                if ($res === false) {
                    $this->createNotFoundException("Falha a remover o item.");// Failed removing the item.
                }
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($medium);
            $em->flush();
        }

        return $this->redirectToRoute('media_index');
    }
}
