<?php

namespace App\Service;

use App\Repository\ProductViewRepository;
use App\Repository\ProductReportViewRepository;
use App\Entity\ProductReportView;

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
    
    public function __construct(ProductViewRepository $pviewRepo, ProductReportViewRepository $reportRepo)
    {
        $this->productViewRepo = $pviewRepo;
        $this->reportRepo = $reportRepo;
    }
    
    function build($year, $month, $product) : self
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
}

