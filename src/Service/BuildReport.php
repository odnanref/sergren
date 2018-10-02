<?php

namespace App\Service;

use App\Repository\CategoryReportViewRepository;
use App\Repository\CategoryViewRepository;
use App\Repository\ProductViewRepository;
use App\Repository\ProductReportViewRepository;
use App\Entity\ProductReportView;
use App\Entity\CategoryReportView;


class BuildReport
{

    /**
     * Product View access information
     * 
     * @var ProductViewRepository
     */
    private $productViewRepo;
    
    /**
     * access and manage reports
     * 
     * @var ProductReportViewRepository
     */
    private $reportRepo;
    
    /**
     * 
     * @var \App\Repository\CategoryViewRepository
     */
    private $cviewRepo;
    
    /**
     * 
     * @var CategoryReportViewRepository
     */
    private $reportCatRepo;
    
    public function __construct(ProductViewRepository $pviewRepo, ProductReportViewRepository $reportRepo,
        CategoryViewRepository $cviewRepo, CategoryReportViewRepository $reportCatRepo
        )
    {
        $this->productViewRepo = $pviewRepo;
        $this->reportRepo = $reportRepo;
        $this->cviewRepo = $cviewRepo;
        $this->reportCatRepo = $reportCatRepo;
    }
    
    function buildProductReport($year, $month, $product) : self
    {
        $views = $this->productViewRepo->getAll($year, $month, $product);
        
        // keeps the already seen Ip's
        $seenIp = array();
        
        $totalViews = 0;
        $totalViewsByDistinctIp = 0;
        /** @var \App\Entity\ProductView $view */
        foreach ($views as $view) {
            $totalViews++;
            if (!in_array($view->getIpaddress(), $seenIp)) {
                $totalViewsByDistinctIp++;
                $seenIp[] = $view->getIpaddress();
            }
        }
        // Clear old reports for this time frame
        $current = $this->reportRepo->findBy(['year' => $year, 'month' => $month, 'Product' => $product]);
        foreach ($current as $cur ) {
            $this->reportRepo->removeOld($cur);
        }
        
        // Create a new report for the time frame
        $report = new ProductReportView();
        $report->setMonth($month);
        $report->setYear($year);
        $report->setProduct($product);
        $report->setTotalByDisctinctIp($totalViewsByDistinctIp);
        $report->setTotalViews($totalViews);
        
        $this->reportRepo->save($report);
        
        return $this;
    }
    
    function buildCategoryReport($year, $month, $category) : self
    {
        $views = $this->cviewRepo->getAll($year, $month, $category);
        
        // keeps the already seen Ip's
        $seenIp = array();
        
        $totalViews = 0;
        $totalViewsByDistinctIp = 0;
        /** @var \App\Entity\ProductView $view */
        foreach ($views as $view) {
            $totalViews++;
            if (!in_array($view->getIpaddress(), $seenIp)) {
                $totalViewsByDistinctIp++;
                $seenIp[] = $view->getIpaddress();
            }
        }
        // Clear old reports for this time frame
        $current = $this->reportCatRepo->findBy(['year' => $year, 'month' => $month, 'Category' => $category]);
        foreach ($current as $cur ) {
            $this->reportCatRepo->removeOld($cur);
        }
        
        // Create a new report for the time frame
        $report = new CategoryReportView();
        $report->setMonth($month);
        $report->setYear($year);
        $report->setCategory($category);
        $report->setTotalByDisctinctIp($totalViewsByDistinctIp);
        $report->setTotalViews($totalViews);
        
        $this->reportCatRepo->save($report);
        
        return $this;
    }
}

