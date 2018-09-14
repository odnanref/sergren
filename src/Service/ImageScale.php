<?php
namespace App\Service;

use App\Entity\Media;

class ImageScale
{

    /**
     * location of the image to scale
     * 
     * @var string
     */
    public $image;
    
    /**
     * Thumb of the image scaled down
     * 
     * @var string
     */
    public $imagethumb;
    
    /**
     * Prep for image scale
     * 
     * directory can be empty if in the same location of the image
     * 
     * @param Media $image
     * @param string $directory
     */
    public function __construct(Media $image, $directory = "")
    {
        $this->image = $directory . $image->getPath();
        
        $this->imagethumb = $directory . $image->getThumb();
        
    }
    
    function scale()
    {
        if (is_readable($this->imagethumb)) {
            return true;
        }
        
        list($width, $height, $image_type, $attr) = getimagesize($this->image);
        switch ($image_type) {
            case IMAGETYPE_JPEG: 
                $resource = \imagecreatefromjpeg($this->image);
            break;
            case IMAGETYPE_PNG: 
                $resource = \imagecreatefrompng($this->image);
            break;
            
            case IMAGETYPE_GIF:
                $resource = \imagecreatefromgif($this->image);
                break;
                
            case IMAGETYPE_BMP:
                $resource = \imagecreatefrombmp($this->image);
                break;                
        }
        
        
        $output = imagescale($resource, 120);
        $res = imagejpeg($output, $this->imagethumb);
        if ($res === false) {
            // log 
            throw new \Exception("Unable to write resized image. " . $this->image);
        }
        
        return true;
    }
}

