<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

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
        
        $service = new \App\Service\ImageScale( $export_dir . DIRECTORY_SEPARATOR . "test.jpg");
        $service->scale();
        $this->assertTrue(is_readable($service->imagethumb));
    }
}