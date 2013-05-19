<?php

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('ROOT') or define('ROOT', dirname(__FILE__) . DS);

date_default_timezone_set('Europe/London');

/**
 * Recursive glob
 */
function recursive_glob($pattern, $flags = 0) {
    $files = glob($pattern, $flags);

    foreach (glob(dirname($pattern) . DS . '*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge(
            $files,
            recursive_glob($dir . DS . basename($pattern), $flags)
        );
    }

    return $files;
}

function recursive_scan_dir($dir, $filetype = false) {
    $files = [];
    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item[0] == '_' || $item[0] == '.') continue;

        $item = $dir . DS . $item;

        if (is_dir($item)) {
            $files = array_merge($files, recursive_scan_dir($item, $filetype));
        } else {
            if ($filetype) {
                if (end(explode('.', $item)) !== $filetype) continue;
            }

            $files[] = $item;
        }
    }

    return $files;
}

/**
 * Absolute to relative path
 *
 * @param string $path The absolute path
 * @param string $base The base, to base it on. Defaults to ROOT
 */
function abs2rel($path, $base = ROOT) {
    $path = explode('/', $path);
    $base = explode('/', $base);
    $rel = $path;

    foreach ($base as $depth => $dir) {
        if ($dir === $path[$depth]) {
            array_shift($rel);
        } else {
            $remaining = count($base) - $depth;

            if ($remaining > 1) {
                $padlen = (count($rel) + $remaining - 1) * -1;
                $rel = array_pad($rel, $padlen, '..');
                break;
            }
        }
    }

    return implode('/', $rel);
}


function remove_dash($name) {

    if (strpos($name, '/')) {
        $name = implode('/', array_map('remove_dash', explode('/', $name)));
    } else {
        $name = implode(' - ', array_slice(explode(' - ', $name), 1));   
    }

    return $name;
}

function fixname($name) {
    $name = remove_dash($name);

    if (strpos($name, '.')) $name = implode('.', array_slice(explode('.', $name), 0, -1));

    $name = ucwords($name);

    return $name;
}
function is_hash($arr) {
    if (!is_array($arr)) return false;

    $keys = range(0, count($arr) - 1);

    foreach ($keys as $key) {
        if (!isset($arr[$key])) {
            return true;
        }
    }

    return false;
}

function build($url, $arr, $crumbs = false, $deep = false, $nest = null, $level = 0, $error = false) {

    $out = '';

    foreach ($arr as $title => $route) {
        $out .= '<li class="';

        if (!$error && $crumbs && isset($crumbs[$level]) && $title === $crumbs[$level]) {
            $out .= ' current';
        }

        
        $pages = null;

        if (is_array($route)) {
            $pages = $route[1];
            $route = $route[0];
        }

        if ($deep && $pages) {
            $out .= ' nested';
        }

        $out .= '">';

        $route = ltrim($route, '/');

        $out .= '<a href="' . $url . build_url($route) . '">' . $title . '</a>';

        if ($deep && $pages) {
            $nested = build($url, $pages, $crumbs, $deep, $nest, $level + 1);

            if (is_callable($nest)) $nested = call_user_func($nest, $nested);

            $out .= $nested;
        }

        $out .= '</li>';
    }

    return $out;
}


function url($route) {

    $rel = '';
    $parts = array_slice(explode('/', trim($route, '/')), 1);

    foreach ($parts as $part) {
        $rel .= '../';
    }

    return $rel;
}

function build_url($url) {
    $url = trim($url, '/');
    if (isset($_GET['disable_download'])) $url .= '.html';

    return $url;
}

function array_last($arr) {
    return $arr[count($arr) - 1];
}

function title2part($title) {
    $part = str_replace(' ', '-', $title);
    $part = strtolower($part);

    return $part;
}

function part2title($part) {
    $title = str_replace('-', ' ', $part);
    $title = ucwords($title);

    return $title;
}

function parts($file) {
    $parts = explode('/', trim($file, '/'));
    $parts = array_map(function($part) {
        if (!strpos($part, '.')) return $part;

        $parts = explode('.', $part);

        return implode('.', array_slice($parts, 0, -1));
    }, $parts);
    $parts = array_filter($parts, function($part) {
        return $part !== 'index';
    });

    return $parts;
}