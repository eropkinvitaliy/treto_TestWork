<?php
/**
 * Created by PhpStorm.
 * User: caa
 * Date: 20.07.2015
 * Time: 9:09
 */
namespace Application\Models;

class ImageProcessor
{
    private $stamp;
//    public function __construct($path)
//    {
//        $this->path = $path;
//        $imgdata = file_get_contents($this->path);
//        $this->image = imagecreatefromstring($imgdata);
//    }

    public function waterMark($path)
    {
        $this->stamp = imagecreatefrompng($path);
        $marge_right = 5;
        $marge_bottom = 5;
        $sx = imagesx($this->stamp);
        $sy = imagesy($this->stamp);
        imagecopy($this->image, $this->stamp, /*imagesx($this->image) - $sx -*/ $marge_right,
            imagesy($this->image) - $sy - $marge_bottom, 0, 0,
            imagesx($this->stamp), imagesy($this->stamp));
        return $this;

    }


    public function getImage($path)
    {
        $this->path = $path;
        if (@fopen($this->path, "r")) {
            $imgdata = file_get_contents($this->path);
            $this->image = imagecreatefromstring($imgdata);
        }
        return $this;
    }

    public function filterResize($width, $height)
    {
        $newimage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newimage, $this->image, 0, 0, 0, 0, $width, $height,
            $this->getWidth(), $this->getHeight());
        $this->image = $newimage;
        return $this;
    }

    public function filterResizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->filterResize($width, $height);
        return $this;
    }

    public function filterResizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->filterResize($width, $height);
        return $this;
    }

    public function filterZoom($zoom)
    {
        $width = $this->getWidth() * $zoom / 100;
        $height = $this->getHeight() * $zoom / 100;
        $this->filterResize($width, $height);
        return $this;
    }

    public function save($localpath, $imagetype = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
    {
        $this->localpath = $localpath;
        if ($imagetype == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $this->localpath, $compression);
        } elseif ($imagetype == IMAGETYPE_GIF) {
            imagegif($this->image, $this->localpath);
        } elseif ($imagetype == IMAGETYPE_PNG) {
            imagepng($this->image, $this->localpath);
        }
        if ($permissions != null) {
            chmod($this->localpath, $permissions);
        }
        return $this;
    }

    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }
}