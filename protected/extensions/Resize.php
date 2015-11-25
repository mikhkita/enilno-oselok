<?

Class Resize {

    private $image;
    private $width;
    private $height;
    private $imageResized;
    
    function __construct($fileName) {

        $this->image = $this->openImage($fileName);
        // die($this->image );
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    private function openImage($file) {
        $extension = strtolower(strrchr($file, '.'));
     
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }

    public function resizeImageAvito($option="auto", $newWidth = NULL, $newHeight = NULL) {
     
        // *** Get optimal width and height - based on $option
        $img_ratio = $this->width/$this->height;
        $newWidth = rand(960,1280);
        $newHeight = round($newWidth*$img_ratio);
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
     
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
     
        // *** Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
     
        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }

    public function resizeImage($newWidth, $newHeight, $option="auto") {
     
        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
     
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
     
        // *** Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
     
        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }

    public function setWatermark() {
     
        $stamp = imagecreatefrompng('upload/watermark.png');

        // Установка полей для штампа и получение высоты/ширины штампа
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        $this->imageResized = $this->image;

        // Копирование изображения штампа на фотографию с помощью смещения края
        // и ширины фотографии для расчета позиционирования штампа. 
        imagecopy($this->imageResized, $stamp, (imagesx($this->imageResized) - $sx)/2, (imagesy($this->imageResized) - $sy)/2, 0, 0, $sx, $sy);
    }

    private function getDimensions($newWidth, $newHeight, $option) {
 
        switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function getSizeByFixedHeight($newHeight) {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }
     
    private function getSizeByFixedWidth($newWidth) {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }
     
    private function getSizeByAuto($newWidth, $newHeight) {

        // *** Image to be resized is wider (landscape)
        if ($this->height < $this->width) {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }

        // *** Image to be resized is taller (portrait)
        elseif ($this->height > $this->width) {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }

        // *** Image to be resizerd is a square
        else {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }
     
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
     
    private function getOptimalCrop($newWidth, $newHeight) {
     
        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;
     
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
     
        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;
     
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {
        // *** Find center - this will be used for the crop
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
     
        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);
     
        // *** Now crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }

    public function saveImage($savePath, $imageQuality="100") {

        // $stamp = imagecreatefrompng('upload/watermark.png');

        // // Установка полей для штампа и получение высоты/ширины штампа
        // $marge_right = 10;
        // $marge_bottom = 10;
        // $sx = imagesx($stamp);
        // $sy = imagesy($stamp);

        // // Копирование изображения штампа на фотографию с помощью смещения края
        // // и ширины фотографии для расчета позиционирования штампа. 
        // imagecopy($this->imageResized, $stamp, imagesx($this->imageResized) - $sx - $marge_right, imagesy($this->imageResized) - $sy - $marge_bottom, 0, 0, $sx, $sy);

        // *** Get extension
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);
     
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, $imageQuality);
                }
                break;
     
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;
     
            case '.png':
                // *** Scale quality from 0-100 to 0-9
                $scaleQuality = round(($imageQuality/100) * 9);
     
                // *** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;
     
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;
     
            default:
                // *** No extension - No save.
                break;
        }
     
        imagedestroy($this->imageResized);
    }
}

?>