<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryView;
use App\Entity\SellingContact;
use App\Repository\CategoryRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Entity\ProductView;
use App\Form\SellingContactType;

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
    function viewCategory(int $id, CategoryRepository $categoryRepository, ProductRepository $prodRepository, Category $category) {
        
        $request = Request::createFromGlobals();
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $prodRepository->getActiveProductsByCategory($id), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
            );
        
        
        return $this->render('frontend/category_window.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'category' => $category,
            'products' => $pagination
        ]);
    }
    
    /**
     *
     * @Route("/cat/{url}", name="frontend_view_category_url")
     *
     * @param string $url
     * @param CategoryRepository $categoryRepository
     */
    function viewCategoryByUrl(string $url, CategoryRepository $categoryRepository, ProductRepository $prodRepository, Category $category) {
        
        $res = $categoryRepository->findBy(['url' => $url]);
        if (count($res) <= 0 ) {
            throw $this->createNotFoundException("The category wasn't found. $url");
        }
        $category = $res[0];
        
        $request = Request::createFromGlobals();
        $headers = $request->headers->all();
        
        // product view saves access to a product
        $catview = new CategoryView();
        $catview->setIpaddress($request->getClientIp());
        $catview->setCategory($category);
        if (array_key_exists("user-agent", $headers)) {
            $catview->setUseragent($headers['user-agent'][0]);
        }
        
        if (array_key_exists("referer", $headers)) {
            $catview->setReferer($headers['referer'][0]);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($catview);
        $em->flush();
        
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $prodRepository->getActiveProductsByCategoryUrl($url), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
            );
        
        
        return $this->render('frontend/category_window.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'category' => $category,
            'products' => $pagination
        ]);
    }
        
    /**
     * @Route("/search", name="frontend_view_search", methods="GET")
     */
    public function indexSearch(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $request = Request::createFromGlobals();
        $paginator  = $this->get('knp_paginator');
        
        //$product = $request->request->get("searchtext");
        $product = $request->query->get("searchtext");
        
        $pagination = $paginator->paginate(
            $productRepository->search($product), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
            );
        
        $keywords = "";
        if (count($pagination) > 0 ) {
            for ($i = 0 ; $i < count($pagination); $i++) {
                $keywords .= " " . $pagination[$i]->getName() . " " .$pagination[$i]->getKeywords();
            }
        }
        
        return $this->render('frontend/index_search.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $pagination,
            'search' => $product,
            'keywords' => $keywords
        ]);
    }
    
    /**
     * @Route("/page/{name}", name="frontend_view_page_name")
     */
    function viewPageByName(string $name , PageRepository $pageRepository, CategoryRepository $categoryRepository) {
        
        $page = $pageRepository->getActiveByUrl($name);
        if (count($page) <= 0 ) {
            throw $this->createNotFoundException("Item não encontrado. $name ");
        }
        
        return $this->render('frontend/view_page.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'page' => $page[0]
        ]);
    }

    /**
     * @Route("/quero-vender", name="frontend_view_quero_vender", methods="GET|POST")
     */
    function QueroVender(CategoryRepository $categoryRepository) {
        
        $request = Request::createFromGlobals();
        $sellingContact = new SellingContact();
        $form = $this->createForm(SellingContactType::class, $sellingContact);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sellingContact);
            $em->flush();
            
            return $this->render('frontend/selling_contact_new.html.twig',[
                'categories' => $categoryRepository->findAll(),
                'selling_contact' => $sellingContact,
                'form' => $form->createView(),
                'state' => true
            ]);
        }
        
        return $this->render('frontend/selling_contact_new.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'selling_contact' => $sellingContact,
            'form' => $form->createView(),
            'state' => false
        ]);
    }
    
}
