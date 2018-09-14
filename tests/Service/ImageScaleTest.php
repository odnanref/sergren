<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;
use App\Entity\Media;

class ImageScaleTest extends WebTestCase
{
    /**
     * test if the image scales and creates the thumb
     * 
     */
    public function testImageScaled()
    {
        self::bootKernel();
        $export_dir = self::$kernel->getContainer()->getParameter('media_directory');
        
        $medium = new Media();
        $medium->setPath("test.jpg");
        
        $service = new \App\Service\ImageScale($medium, $export_dir . DIRECTORY_SEPARATOR );
        $service->scale();
        $this->assertTrue(is_readable($service->imagethumb));
    }
}