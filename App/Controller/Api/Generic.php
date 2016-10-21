<?php

namespace App\Controller\Api;

class Generic
{
    /**
     * API entry point
     *
     * @throws \Exception
     */
    static public function index()
    {
        // check if method is provided
        if (!$method = \Input::json('method'))
        {
            // maybe it was passed via HTTP GET
            if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !$method = \Input::get('method'))
            {
                throw new \Exception('No payload found or no method specified.');
            }
        }

        // check if this method exists
        if (!method_exists(get_called_class(), $method))
        {
            throw new \Exception('Method \'' . $method . '\' does not exist.', 404);
        }

        \View::json(@forward_static_call([get_called_class(), $method]));
    }
}
