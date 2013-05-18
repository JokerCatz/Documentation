<?php
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
