<?php

namespace App\Controller;

use App\Entity\ProductAttribute;
use App\Form\ProductAttributeType;
use App\Repository\ProductAttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/admin/productattribute")
 */
class ProductAttributeController extends Controller
{
    /**
     * @Route("/", name="product_attribute_index", methods="GET")
     */
    public function index(ProductAttributeRepository $productAttributeRepository): Response
    {
        return $this->render('product_attribute/index.html.twig', ['product_attributes' => $productAttributeRepository->findAll()]);
    }

    /**
     * @Route("/new", name="product_attribute_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $productAttribute = new ProductAttribute();
        $form = $this->createForm(ProductAttributeType::class, $productAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productAttribute);
            $em->flush();

            return $this->redirectToRoute('product_attribute_index');
        }

        return $this->render('product_attribute/new.html.twig', [
            'product_attribute' => $productAttribute,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_attribute_show", methods="GET")
     */
    public function show(ProductAttribute $productAttribute): Response
    {
        return $this->render('product_attribute/show.html.twig', ['product_attribute' => $productAttribute]);
    }

    /**
     * @Route("/{id}/edit", name="product_attribute_edit", methods="GET|POST")
     */
    public function edit(Request $request, ProductAttribute $productAttribute): Response
    {
        $form = $this->createForm(ProductAttributeType::class, $productAttribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_attribute_edit', ['id' => $productAttribute->getId()]);
        }

        return $this->render('product_attribute/edit.html.twig', [
            'product_attribute' => $productAttribute,
            'form' => $form->createView(),
        ]);
    }

    //product_attribute_remove
    /**
     * @Route("/{id}", name="product_attribute_remove", methods="DELETE")
     */
    public function remove(Request $request, ProductAttribute $productAttribute, ProductAttributeRepository $repo): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($productAttribute === null ) {
            throw $this->createNotFoundException('The product attribute does not exist');
        }
        
        $em->remove($productAttribute);
        $em->flush();
        return JsonResponse::fromJsonString("{ \"status\": \"ok\", \"message\": \"deleted\"}", 200);
    }
    
    /**
     * @Route("/{id}", name="product_attribute_delete", methods="DELETE")
     */
    public function deleteNoJson(Request $request, ProductAttribute $productAttribute): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productAttribute->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productAttribute);
            $em->flush();
        }

        return $this->redirectToRoute('product_attribute_index');
    }
    
    //product_attribute_put
    /**
     * @Route("/{id}/{value}", name="product_attribute_put", methods="PUT")
     */
    public function put(Request $request, ProductAttribute $productAttribute, ProductAttributeRepository $repo): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pa = $repo->find($productAttribute->getId());
        if ($pa === null ) {
            throw $this->createNotFoundException('The product attribute does not exist');
        }
        
        $tmp = $request->get("value", 0);
        $pa->setValue($tmp);
        $em->persist($pa);
        $em->flush();
        $repr = json_encode($pa);
        return JsonResponse::fromJsonString($repr, 200);
    }
    
}
