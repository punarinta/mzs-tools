<?php

class View
{
    /**
     * Fetches an pHTML file.
     *
     * @param $path
     * @param array $V
     * @return string
     */
    static function make($path, $V = [])
    {
        $content = file_get_contents('App/views/' . $path . '.html');

        foreach ($V as $k => $v)
        {
            $content = str_replace('{' . $k . '}', $v, $content);
        }

        return $content;
    }

    /**
     * Renders an pHTML file.
     *
     * @param $path
     * @param null $V
     * @return mixed
     */
    static function html($path, $V = null)
    {
        return require_once 'App/views/' . $path . '.phtml';
    }

    /**
     * Renders JSON data.
     *
     * @param $data
     * @param null $callback
     */
    static function json($data, $callback = null)
    {
        header('Content-Type: application/json');

        if (!$callback)
        {
            echo json_encode($data);
        }
        else
        {
            echo $callback . '(' . json_encode($data) . ');';
        }

        exit;
    }
}
