<?php

define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') or define('ROOT', dirname(__FILE__) . DS);
define('URL', getenv('url'));

require_once 'functions.php';

$files = array_map(function($file) {
    return substr($file, strlen('docs/'));
}, recursive_glob('docs/*.md'));

$tree = [];

foreach ($files as $file) {
    $parts = explode('/', $file);

    if (count($parts) > 1) {
        $branch =& $tree;

        $path = '';

        while (count($parts) > 1) {
            $branch[$index = fixname($part = array_shift($parts))] = [];
            $path .= strtolower($index) . '/';
            $branch =& $branch[$index];
            $branch[0] = $path;
            $branch[1] = [];
            $branch =& $branch[1];
        }

        $branch[fixname($parts[0])] = [$file, strtolower(fixname($file))];
    } else {
        $tree[fixname($parts[0])] = [$file, strtolower(fixname($file))];
    }
}

unset($branch);

$route = empty($_GET['file']) ? 'home' : $_GET['file'];
$parts = explode('/', ltrim($route, '/'));
$error = false;
$page = true;
$branch = $tree;

foreach ($parts as $part) {
    $part = ucwords($part);

    if (!isset($branch[$part])) {
        $error = true;
        break;
    }

    $branch = $branch[$part];

    if (!is_string($branch[1])) {
        $branch = $branch[1];
    }
}


if (is_hash($branch)) {
    $page = false;
}


$crumbs = array_map('ucwords', $parts);

require_once 'view.php';