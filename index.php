<?php
// define('URL', getenv('url'));
// 
require_once 'functions.php';
require_once 'lib/markdown_extended.php';

$files = array_map(function($file) {
    return substr($file, strlen('docs/'));
}, recursive_glob('docs/*.md'));

// var_dump(recursive_glob('docs/*.md'));

$routes = [];
$tree = [];

foreach ($files as $file) {
    $parts = explode('/', $file);
    $route = '/' . ltrim(implode('/', $parts), '/');
    $route = implode('.', array_slice(explode('.', $route), 0, -1));
    $routes[$route] = $file;

    $path = '';

    foreach ($parts as $part) {
        $path .= '/' . pathinfo($part)['filename'];

        if ($path !== $route) $routes[$path] = ltrim($path, '/');
    }

    $branch =& $tree;

    if (count($parts) > 1) {

        $path = '';

        while (count($parts) > 1) {

            $index = ucwords(pathinfo($part = array_shift($parts))['filename']);
            $path .= '/' . $part;

            if (!isset($branch[$index])) {

                $branch[$index] = [
                    $path, []
                ];
            }
            $branch =& $branch[$index][1];
        }
    }

    $part = array_shift($parts);

    $branch[ucwords(pathinfo($part)['filename'])] = array_search($file, $routes);
}

unset($branch);
$route = '/' . ltrim(empty($_GET['file']) ? 'home' : $_GET['file'], '/');

if (substr($route, -1) === '/') {
    header('Location: ../' . array_last(explode('/', trim($route, '/'))));
    die;
}

if (strpos($route, '.')) {
    $route = implode('.', array_slice(explode('.', $route), 0, -1));
}

$error = false;
$download = !isset($_GET['disable_download']);

if (isset($routes[$route])) {

    if (is_dir('docs/' . $routes[$route])) {

        $parts = explode('/', $routes[$route]);

        $page_tree = $tree;
        $page_branch =& $page_tree;

        while (count($parts) > 0) {
            $page_tree = $page_tree[ucwords(array_shift($parts))][1];
        }

    } else {
        $body = MarkdownExtended(file_get_contents('docs/' . $routes[$route]));
    }
} else {
    $error = true;
}

$path = '';

$crumbs = [];
$parts = explode('/', trim($route, '/'));

foreach ($parts as $part) {
    $path .= $part . '/';
    $crumbs[ucwords($part)] = $path;
}

$title = array_last(array_keys($crumbs));

require 'view.php';