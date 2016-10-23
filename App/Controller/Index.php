<?php

namespace App\Controller;

class Index
{
    static function translate()
    {
        \View::html('container',
        [
            'page'      => 'translate',
            'content'   => \View::make('translate'),
            'js'        => ['js/translate'],
        ]);
    }

    static function stash()
    {
        \View::html('container',
        [
            'page'      => 'stash',
            'content'   => \View::make('stash'),
            'js'        => ['js/stash'],
        ]);
    }
}
