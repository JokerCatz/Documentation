<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0;">

    <title><?php echo $title; ?> - Scaffold Documentation</title>

    <link rel="stylesheet" href="<?php echo url($route); ?>public/css/style.css">
    <link rel="shortcut icon" href="<?php
    $path = 'public/img/favicon.png';
    if (!$download) 
        echo 'data:image/png;base64,' . base64_encode(file_get_contents($path));
    else
        echo url($route) . $path;
    ?>">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo url($route); ?>public/js/pretty.js"></script>
    <script src="<?php echo url($route); ?>public/js/script.js"></script>
</head>
<body>

    <header class="menu">
        <div class="wrap">
            <a href="<?php echo url($route); ?>"><h1 class="logo">Scaffold</h1></a>

            <ul class="buttons">
                <?php if ($download): ?>
                <li>
                    <a href="<?php echo url($route); ?>download.php" class="button">
                        <span class="icon">&#xF0BD;</span>
                        <span class="text">Download</span>
                    </a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="http://github.com/Scaffold/Scaffold" target="_new" class="button">
                        <span class="icon-github">&#xF093;</span>
                        <span class="text">GitHub</span>
                    </a>
                </li>

                <li>
                    <a href="#" class="full_screen button">
                        <span class="icon open">&#xF0A4;</span>
                        <span class="icon close">&#xF0A6;</span>
                    </a>
                </li>
            </ul>
        </div>
    </header>

    <nav class="menu">
        <div class="wrap">
            <?php echo build(url($route), $tree, array_keys($crumbs), true, function($ul) {
                return '<div class="arrow"></div><ul>' . $ul . '</ul>';
            }, $error); ?>
            <div class="clear"></div>
        </div>
    </nav>

    <div class="wrap">

        <?php if (!$error): ?>
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
        <?php endif; ?>

        <div class="body">
            <?php

            if ($error) $body = '<h1>Uh, oh</h1><p>Sorry, the documentation you were looking for could not be found.</p>';
            else if (isset($page_tree)) $body = '<h1>Contents</h1></div><ul class="page_tree">' . build(url($route), $page_tree) . '</ul>';
            
            echo '<div class="doc">' . $body . '</div>';
            ?>
        </div>
    </div>

    <?php if (!$error): ?>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-35201099-2', 'nath.is');
          ga('send', 'pageview');

        </script>
    <?php endif; ?>
</body>
</html>