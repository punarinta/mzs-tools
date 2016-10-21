<?php

namespace App\Controller;

class Error
{
    /**
     * Here you get when trying to access unknown URI.
     *
     * @param $uri
     */
    static function errRoute($uri)
    {
        \View::html('container', ['content' => \View::make('error-404', ['uri' => $uri])]);
    }

    /**
     * Here you get when an exception was caught.
     *
     * @param $exception
     */
    static function errRuntime($exception)
    {
        \View::html('container', ['content' => \View::make('error-500', ['message' => $exception->getMessage()])]);
    }
}
