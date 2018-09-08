<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;

class ImageScaleTest extends TestCase
{
    /**
     * test if the image scales and creates the thumb
     * 
     */
    public function testImageScaled()
    {
        $service = new \App\Service\ImageScale("test.jpg");
        $service->scale();
        $this->assertTrue(is_readable($service->imagethumb));
    }
}