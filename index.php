<?php
// define('URL', getenv('url'));
// 
require_once 'functions.php';
require_once 'lib/markdown_extended.php';

$files = array_map(function($file) {
    return substr($file, strlen('docs/'));
}, recursive_glob('docs/*.md'));

$routes = [];
$tree = [];

foreach ($files as $file) {
    $parts = parts($file);

    $routes['/' . implode('/', $parts)] = $file;
}

foreach ($routes as $route => $path) {
    $parts = parts($path);
    
    $parts = array_map(function($part) {
        return ucwords(str_replace('-', ' ', $part));
    }, $parts);

    $branch =& $tree;

    $used = [];

    while ($part = array_shift($parts)) {

        $used[] = $part;

        if (!isset($branch[$part])) {

            if (is_string($branch)) {
                $branch = [$branch, []];
                $branch =& $branch[1];
            }

            $branch[$part] = '/' . implode('/', array_map('title2part', $used));

            $branch =& $branch[$part];

            if (count($parts)) {
                $branch = [$branch, []];
                $branch =& $branch[1];
            }
        } else {
            $branch =& $branch[$part];

            if (isset($branch[1]) && is_array($branch[1])) {
                $branch =& $branch[1];
            }
        }
    }

    unset($branch);
}

$route = '/' . ltrim(empty($_GET['file']) ? '/' : $_GET['file'], '/');

if ($route === '/') {
    $route = '/introduction';
}

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

    $file = $routes[$route];

    if (is_dir('docs/' . $file)) {

        $parts = explode('/', $file);

        $page_tree = $tree;
        $page_branch =& $page_tree;

        while (count($parts) > 0) {
            $page_tree = $page_tree[ucwords(array_shift($parts))][1];
        }

    } else {
        $body = MarkdownExtended(file_get_contents('docs/' . $file));
    }

} else {
    if ($download) header('HTTP/1.1 404 Not Found');
    $error = true;
}

$path = '';

$crumbs = [];
$parts = array_map('part2title', explode('/', trim($route, '/')));

foreach ($parts as $part) {
    $path .= title2part($part) . '/';
    $crumbs[ucwords($part)] = $path;
}

$title = $error ? "Uh, oh" : array_last(array_keys($crumbs));

require 'view.php';