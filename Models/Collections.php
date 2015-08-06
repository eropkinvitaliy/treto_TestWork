<?php
/**
 * Created by PhpStorm.
 * User: caa
 * Date: 20.07.2015
 * Time: 9:17
 */

namespace Application\Models;
use Application\Models\ImageProcessor;

class Collections {

    const  LOCALPATHSAVE = __DIR__ . '/../imageload/';
    const  LOCALPATHLOAD = '/testImage/imageload/';
    const  WATERMARKPATH = __DIR__ . '/../img/watermark.png';
    public $collectsrc = [];
    public $collectsrcdisplay = [];
    public $imagespath = [];
    public $countimages;
    public $width = [];
    public $height = [];

    public function addCollect($stringsrc)
    {
        $this->collectsrc = explode(PHP_EOL, $stringsrc);
        if (isset($_SESSION['src'])) {
            $this->collectsrcdisplay = $_SESSION['src'];
            $this->collectsrc = array_diff($this->collectsrc, $this->collectsrcdisplay);
            if (empty($this->collectsrc)) {
                $this->collectsrc = $_SESSION['src'];
                $this->load();
                return $this;
            }
            $this->collectsrc = array_merge($this->collectsrcdisplay, $this->collectsrc);
        }
        $_SESSION['src'] = $this->collectsrc;
        $this->countimages = count($this->collectsrc);
        return $this;
    }

    public function load()
    {
        foreach ($this->collectsrc as $key => $path) {
            $this->setPath($key, $path);
            $this->setSize($key);
        }
        return $this;
    }

    public function resizeImage($height)
    {
        if (!isset($imageCollection)) {
            $imageCollection = new ImageProcessor();
        }
        foreach ($this->collectsrc as $key => $path) {
            $this->setPath($key, $path);
            $imageCollection->getImage($path)
                ->filterResizeToHeight($height)
                ->waterMark(self::WATERMARKPATH)
                ->save($this->filename);
            $this->setSize($key);
        }
        return $this;
    }

    private function setSize($key)
    {
        $imagesize = getimagesize($this->filename);
        $this->width[$key] = $imagesize[0];
        $this->height[$key] = $imagesize[1];
    }

    private function setPath($key, $path)
    {
        $this->filename = self::LOCALPATHSAVE . str_replace('%20', '-', mb_substr($path, strrpos($path, '/') + 1));
        $this->imagespath[$key] = self::LOCALPATHLOAD . mb_substr($path, strrpos($path, '/') + 1);
        $this->imagespath[$key] = str_replace('%20', '-', $this->imagespath[$key]);
    }
}