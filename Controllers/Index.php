<?php
/**
 * Created by PhpStorm.
 * User: caa
 * Date: 20.07.2015
 * Time: 8:58
 */
namespace Application\Controllers;

use Application\Models\View;
use Application\Models\Collections;

class Index
{
    protected $data = [];

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function actionDefault()
    {
        $view = new View();
        $file_n = __DIR__ . '/../Views/Default.php';
        $view->display($file_n);
    }

    public function actionResize()
    {
        session_start();
        $collectsrc = $_POST['src'];
        if (!isset($collection)) {
            $collection = new Collections();
        }
        if (!$collection->addCollect($collectsrc)) {
            echo(json_encode($collection));
            return false;
        };
        $collection->resizeImage(200);
        echo(json_encode($collection));
//        $view = new View();
//        $view->items = $collection->collectsrc;
//        $file_n = __DIR__ . '/../Views/images.php';
//        $view->display($file_n);
    }

    public function actionResetSession()
    {
        session_start();
        unset($_SESSION['src']);
    }

    public function actionParser()
    {
        include __DIR__ . '/../Models/Parser.php';
        $html = file_get_html($_POST['src'] . $_POST['galery']);
        $i = 0;
        $arrsrc = [];
        foreach ($html->find('img') as $element) {
            $arrsrc[$i] = $_POST['src'] . $element->src;
            ++$i;
        }
        $view = new View();
        $view->items = $arrsrc;
        $file_n = __DIR__ . '/../Views/images.php';
        $view->display($file_n);
    }
}