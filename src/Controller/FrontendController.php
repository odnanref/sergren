<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\ProductView;

class FrontendController extends Controller
{
    /**
     * @Route("/", name="frontend")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        
        return $this->render('frontend/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'productView' => $productRepository->getWindowProducts()
        ]);
    }
    
    /**
     * @Route("/ver/{id}", name="frontend_view_car")
     */
    function viewCar(int $id , ProductRepository $productRepository, CategoryRepository $categoryRepository) {
        
        $product = $productRepository->getActive($id);
        if (count($product) <= 0 ) {
            throw $this->createNotFoundException("Item não encontrado. $id ");
        }
        
        $request = Request::createFromGlobals();
        $headers = $request->headers->all();
        
        // product view saves access to a product
        $productview = new ProductView();
        $productview->setIpaddress($request->getClientIp());
        $productview->setProduct($product[0]);
        if (array_key_exists("user-agent", $headers)) {
            $productview->setUseragent($headers['user-agent'][0]);
        }
        
        if (array_key_exists("referer", $headers)) {
            $productview->setReferer($headers['referer'][0]);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($productview);
        $em->flush();
        
        return $this->render('frontend/view_car.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'product' => $product[0]
        ]);
    }
    
    /**
     * @Route("/exibir/{url}", name="frontend_view_car_exibir")
     */
    function viewCarByUrl(string $url , ProductRepository $productRepository, CategoryRepository $categoryRepository) {
        
        $product = $productRepository->getActiveByUrl($url);
        if (count($product) <= 0 ) {
            throw $this->createNotFoundException("Item não encontrado. $url ");
        }
        
        $request = Request::createFromGlobals();
        $headers = $request->headers->all();
        
        // product view saves access to a product
        $productview = new ProductView();
        $productview->setIpaddress($request->getClientIp());
        $productview->setProduct($product[0]);
        if (array_key_exists("user-agent", $headers)) {
            $productview->setUseragent($headers['user-agent'][0]);
        }
        
        if (array_key_exists("referer", $headers)) {
            $productview->setReferer($headers['referer'][0]);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($productview);
        $em->flush();
        
        return $this->render('frontend/view_car.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'product' => $product[0]
        ]);
    }
    
    /**
     * 
     * @Route("/category/{id}", name="frontend_view_category")
     * 
     * @param int $id
     * @param CategoryRepository $categoryRepository
     */
    function viewCategory(int $id, CategoryRepository $categoryRepository, Category $category) {
        return $this->render('frontend/category_window.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'category' => $category,
            'products' => $categoryRepository->getActiveProducts($id)
        ]);
    }
    
    /**
     * @Route("/search", name="frontend_view_search_get", methods="GET")
     */
    public function indexGetSearch(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $request = Request::createFromGlobals();
        $paginator  = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            [], /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
            );
        
        return $this->render('frontend/index_search.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $pagination
        ]);
    }
    
    /**
     * @Route("/search", name="frontend_view_search", methods="POST")
     */
    public function indexSearch(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $request = Request::createFromGlobals();
        $paginator  = $this->get('knp_paginator');
        
        $product = $request->request->get("searchtext");
        
        $pagination = $paginator->paginate(
            $productRepository->search($product), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
            );
        
        return $this->render('frontend/index_search.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $pagination
        ]);
    }
}
