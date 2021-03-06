<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductReportView;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use App\Repository\DefaultAttributeRepository;
use App\Entity\ProductAttribute;
use App\Repository\ProductAttributeRepository;
use App\Entity\DefaultAttribute;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ProductReportViewRepository;

/**
 * @Route("/admin/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $request = Request::createFromGlobals();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
            );
        
        return $this->render('product/index.html.twig', ['products' => $pagination]);
    }
    
    /**
     * @Route("/search", name="product_index_search_get", methods="GET")
     */
    public function indexGetSearch(ProductRepository $productRepository): Response
    {
        $request = Request::createFromGlobals();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            [], /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
            );
        
        return $this->render('product/index.html.twig', ['products' => $pagination]);
    }
    
    /**
     * @Route("/search", name="product_index_search", methods="POST")
     */
    public function indexSearch(ProductRepository $productRepository): Response
    {
        $request = Request::createFromGlobals();
        
        $product = $request->request->get("searchtext");
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productRepository->search($product, false), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
            );
        
        return $this->render('product/index.html.twig', ['products' => $pagination]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     */
    public function new(Request $request, TranslatorInterface $translator, ProductRepository $prodRepo): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($prodRepo->existsUrl($product->getUrl())) {
            $this->addFlash("error", $translator->trans("Already exists Url"));
            
            return $this->render('product/new.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($product->getCategories() as $cat ){
                if (!$cat->getProducts()->contains($product)) {
                    $cat->getProducts()->add($product);
                }
            }
            $em->persist($product);
            $em->persist($cat);
            $em->flush();
            
            $this->addFlash("success", $translator->trans("Success saving") . ".");

            return $this->redirectToRoute('product_index');
            
        } elseif ( $form->isSubmitted() && !$form->isValid()) {
            $errors = "";
            /** @var \Symfony\Component\Form\FormError $error */
            foreach ($form->getErrors() as $error ) {
                $errors .= $error->getMessage() . PHP_EOL;
            }
            $this->addFlash("error", $translator->trans("Error saving") . $errors);
        }
        

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * Checks if a attribute exists by name
     * 
     * @param DefaultAttribute $default
     * @param ArrayCollection $collection
     * @return bool
     */
    private function hasDefaultProperty($default, $collection) : bool {
        foreach ($collection as $col) {
            if ($col->getName() === $default->getName()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Product $product, DefaultAttributeRepository $defaultAttributeRepo, 
        ProductAttributeRepository $paRepo,
        ProductReportViewRepository $reportRepo
        ): Response
    {
        $attributes = $defaultAttributeRepo->findAll();
        /** @var \Doctrine\Common\Collections\ArrayCollection $current_attributes */
        $current_attributes = $product->getProductAttributes();
        if (count($current_attributes->toArray()) <= 0 ) { // if it doesn't have attributes it saves all default attributes
            foreach ($attributes as $key => $default) {
                $pa = new ProductAttribute();
                $pa->setName($default->getName());
                $pa->setValue($default->getValue());
                $pa->setType($default->getType());
                $pa->setProduct($product);
                $paRepo->save($pa);
            }
        } else { // if it already has attributes it checks to see if it has all the default ones
            foreach($attributes as $default) {
                if (!$this->hasDefaultProperty($default, $current_attributes)) {
                    $pa = new ProductAttribute();
                    $pa->setName($default->getName());
                    $pa->setValue($default->getValue());
                    $pa->setType($default->getType());
                    $pa->setProduct($product);
                    $paRepo->save($pa);
                    $current_attributes->add($pa);
                }
            }
        }
        
        $media = $product->getMedias();
        return $this->render('product/show.html.twig', [
            'product' => $product, 
            'attributes' => $current_attributes,
            'media' => $media,
            'reports' => $reportRepo->findBy(['Product' => $product], ['totalViews' => "DESC"])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product, TranslatorInterface $translator, ProductRepository $prodRepo): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($prodRepo->existsUrl($product->getUrl(), $product->getId())) {
            $this->addFlash("error", $translator->trans("Already exists Url"));
            return $this->render('product/edit.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
            ]);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($product->getCategories() as $cat ){
               if (!$cat->getProducts()->contains($product)) {
                $cat->getProducts()->add($product);
               }
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", $translator->trans("Success saving") . ".");
            
            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
            
        } elseif ( $form->isSubmitted() && !$form->isValid()) {
            $errors = "";
            /** @var \Symfony\Component\Form\FormError $error */
            foreach ($form->getErrors() as $error ) {
                $errors .= $error->getMessage() . PHP_EOL;
            }
            $this->addFlash("error", $translator->trans("Error saving") . $errors);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product, TranslatorInterface $translator ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            
            $prvRepo = $em->getRepository(ProductReportView::class);
            $reports = $prvRepo->findBy(["Product" => $product->getId()]);
            for ($i=0; $i < count($reports); $i++) {
                $em->remove($reports[$i]);
            }
            
            $pattributes = $product->getProductAttributes();
            for ($i=0; $i < count($pattributes); $i++) {
                $em->remove($pattributes[$i]);
            }
            
            $medias = $product->getMedias();
            for ($i=0; $i < count($medias); $i++) {
                /** @var \App\Entity\Media $media */
                $media = $medias[$i];
                if (trim($media->getPath()) != "") {
                    $path = $this->getParameter('media_directory') . DIRECTORY_SEPARATOR . $media->getPath();
                    if (is_readable($path)) {
                        \unlink($path);
                    }
                    $thumb = $this->getParameter('media_directory') . DIRECTORY_SEPARATOR . $media->getThumb();
                    if (is_readable($thumb)) {
                        \unlink($thumb);
                    }
                }
            }
            
            $em->remove($product);
            $em->flush();
            $this->addFlash("success", $translator->trans("Success deleting") . ".");
        }

        return $this->redirectToRoute('product_index');
    }
}
