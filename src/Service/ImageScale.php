<?php
namespace App\Service;

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
    
    // TODO - Insert your code here
    public function __construct($image)
    {
        $upload_folder = __DIR__ . "/../../public/uploads/media/";
        $this->image = $upload_folder . $image;
        $tmp = explode(".", $image);
        if ( count($tmp) <=1 ) {
            throw new \Exception("Unable to see extension in filename");
        }
        
        $extension = $tmp[count($tmp)-1];
        $image_thumb = \str_replace($extension, "_thumb" . $extension, $image);
        
        $this->imagethumb = $upload_folder . $image_thumb;
        
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

