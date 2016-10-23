<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Stockholm');

chdir('..');

$autoClasses0 =
[
    'App\\'     => '.',
];

$autoClasses4 =
[
];

$loaderClassMap =
[
];

spl_autoload_register(function ($class) use ($autoClasses0, $autoClasses4, $loaderClassMap)
{
    if (strpos($class, '\\') === false && file_exists('boosters/' . $class . '.php'))
    {
        include_once 'boosters/' . $class . '.php';
        return true;
    }

    if (isset ($loaderClassMap[$class]))
    {
        if ($loaderClassMap[$class])
        {
            include_once $loaderClassMap[$class];
        }
        else
        {
            return false;
        }
    }

    foreach ($autoClasses0 as $namespace => $dir)
    {
        if (0 === strpos($class, $namespace))
        {
            if (strpos($class, '_'))
            {
                $class = strtr(strtr($class, $namespace . '_', ''), '_', '/');
            }

            include_once $dir . '/' . strtr($class, '\\', '/') . '.php';
            return true;
        }
    }

    foreach ($autoClasses4 as $namespace => $dir)
    {
        if (0 === strpos($class, $namespace))
        {
            if (is_array($dir))
            {
                foreach ($dir as $dirry)
                {
                    $file = $dirry . strtr(str_replace($namespace, '', $class), '\\', '/') . '.php';
                    if (file_exists($file))
                    {
                        include_once $file;
                        return true;
                    }
                }
            }
            else
            {
                include_once $dir . strtr(str_replace($namespace, '', $class), '\\', '/') . '.php';
                return true;
            }
        }
    }

    return false;

}, true, true);

unset ($autoClasses0, $autoClasses4, $loaderClassMap);

$GLOBALS['-R'] =
[
    'translate' => ['/', '\App\Controller\Index', 'translate'],
    'stash' => ['/stash', '\App\Controller\Index', 'stash'],
    'api-word' => ['/api/word', '\App\Controller\Api\Word', 'index'],
];

return \Sys::run(require_once 'App/config.php');
