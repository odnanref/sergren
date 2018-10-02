<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\ProductRepository;
use App\Service\BuildReport;
use phpDocumentor\Reflection\Types\Null_;
use App\Repository\CategoryRepository;


class BuildreportCommand extends Command
{
    protected static $defaultName = 'app:buildreport';

    /**
     * access the products table
     * @var \App\Repository\ProductRepository
     */
    private $productRepo;
    
    /**
     * access the categories table
     * @var \App\Repository\CategoryRepository
     */
    private $catRepo;
    
    /**
     * build the report
     * 
     * @var \App\Service\BuildReport
     */
    private $buildReport;
    
    /**
     * 
     * @var LoggerInterface
     */
    private $logger;
    
    
    function __construct(ProductRepository $productRepo, CategoryRepository $catRepo, BuildReport $buildReport, LoggerInterface $logger)
    {
        $this->productRepo = $productRepo;
        $this->catRepo = $catRepo;
        $this->buildReport = $buildReport;
        $this->logger = $logger;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Run monthly report on products')
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        /*if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/
        $year = \date("Y");
        $month = \date("m");
        
        $products = $this->productRepo->findAll();
        for($i = 0; $i < count($products); $i++) {
            $this->buildReport->buildProductReport($year, $month, $products[$i]);
        }
        
        $categories = $this->catRepo->findAll();
        for($i = 0; $i < count($categories); $i++) {
            $this->buildReport->buildCategoryReport($year, $month, $categories[$i]);
        }
        
        
        $this->logger->info('Report run for month ' . \date('m') . " and year " . \date("Y") );
        $io->success('Report run for month ' . \date('m') . " and year " . \date("Y") );
    }
}
