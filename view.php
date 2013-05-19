<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0; user-scalable=no">

    <title><?php echo $title; ?> - Scaffold Documentation</title>

    <link rel="stylesheet" href="<?php echo url($route); ?>public/css/style.css">
    <link rel="shortcut icon" href="data:image/png;base64,<?php echo base64_encode(file_get_contents('public/img/favicon.png')); ?>">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo url($route); ?>public/js/pretty.js"></script>
    <script src="<?php echo url($route); ?>public/js/script.js"></script>
</head>
<body>

    <header>
        <div class="wrap">
            <a href="<?php echo build_url(url($route) . 'home'); ?>"><h1 class="logo">Scaffold</h1></a>

            <ul class="buttons">
                <?php if ($download): ?>
                <li>
                    <a href="<?php echo url($route); ?>download.php" class="button">Download</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <nav>
        <div class="wrap">
            <?php echo build(url($route), $tree, array_keys($crumbs), true, function($ul) {
                return '<ul>' . $ul . '</ul>';
            }, $error); ?>
            <div class="clear"></div>
        </div>
    </nav>

    <div class="wrap">

        <div class="body">
            <ul class="crumbs">
                <?php foreach ($crumbs as $title => $path): ?>
                    <li>
                        <a href="<?php echo build_url(url($route) . $path); ?>"><?php echo $title; ?></a>
                    </li>
                <?php endforeach; ?>
                <div class="clear"></div>
            </ul>
        </div>

        <div class="body">
            <?php

            if ($error) $body = '<h1>Uh, oh</h1><p>Sorry, the documentation you were looking for could not be found.</p>';
            
            if (isset($page_tree)) {
                echo '<div class="doc"><h1>Contents</h1></div><ul class="page_tree">' . build(url($route), $page_tree) . '</ul>';
            } else {
                echo '<div class="doc">' . $body . '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>