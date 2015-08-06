<?php

namespace Application\Models;

class View
{
    protected $data = [];
    public function __set($k, $v)
    {
        $this->data[$k] = $v;
    }
    public function display($template)
    {
        foreach ($this->data as $key => $val) {
            $$key = $val;
        }
        if (file_exists($template)) {
            include $template;
        } else {
            die('Файл не найден!!!');
        }
    }
}