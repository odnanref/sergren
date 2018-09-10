<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductReportViewRepository;

class ProductReportViewController extends Controller
{
    /**
     * @Route("/admin/productreport/view", name="product_report_view")
     */
    public function index(ProductReportViewRepository $reportRepo)
    {
        $request = Request::createFromGlobals();
        $paginator  = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $reportRepo->findBy([], ['totalViews' => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
            );
        
        return $this->render('product_report_view/index.html.twig', [
            'reports' => $pagination,
        ]);
    }
}
