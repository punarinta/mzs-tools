<?php

namespace App\Controller;

class Index
{
    static function index()
    {
        $content = \View::make('index');
        \View::html('container', ['content' => $content, 'js' => ['js/index']]);
    }
}
