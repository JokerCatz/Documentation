<?php
require_once 'functions.php';
require_once 'lib/zip.php';

$_GET['disable_download'] = true;

class Scanner {

    protected $doc;
    public $links;
    public function __construct() {
        $this->doc = new DOMDocument;
    }

    public function scan($link = '/home') {
        $contents = $this->get_contents($link);
        $this->links[ltrim($link, '/')] = $contents;
        $links = [];

        libxml_use_internal_errors(true);
        $this->doc->loadHTML($contents);
        libxml_clear_errors();

        foreach ([
            'a' => 'href'
        ] as $tag => $prop) {
            $elements = $this->doc->getElementsByTagName($tag);

            for ($i = 0, $l = $elements->length; $i < $l; $i++) {
                $ele = $elements->item($i);
                $links[] = $ele->getAttributeNode($prop)->value;
            }
        }

        $links = array_filter($links, function($link) {
            return !empty($link) && substr($link, 0, strlen('http')) !== 'http' && $link[0] !== '#' && $link[0] !== '?' && (!isset(pathinfo($link)['extension']) || pathinfo($link)['extension'] !== 'php');
        });

        foreach ($links as $link) {

            $link = trim($link, '/');

            if (!isset($this->links[$link])) {
                $this->links[$link] = $this->get_contents('/' . $link);
            }
        }

        foreach (recursive_glob('public/*.*') as $file) {
            if (!isset($this->links[$file])) {
                $this->links[$file] = file_get_contents($file);
            }
        }

        return $this;
    }

    public function get_contents($url = '/home') {

        ob_start();
        $_GET['file'] = $url;
        require 'index.php';
        unset($_GET['file']);

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }

}
$scanner = new Scanner;
$links = $scanner->scan()->links;

$keys = array_map(function($link) {
    if (!isset(pathinfo($link)['extension'])) {
        $link .= '.html';
    }

    $link = 'scaffold_docs/' . $link;

    return $link;
}, array_keys($links));

$links = array_combine($keys, array_values($links));

$zip = new zipfile();

foreach ($links as $link => $contents) {
    $zip->addFile($contents, $link);
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=scaffold_docs.zip");

echo $zip->file();