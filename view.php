<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Documentation</title>

    <link rel="stylesheet" href="<?php echo URL; ?>css/style.css">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo URL; ?>js/pretty.js"></script>
    <script src="<?php echo URL; ?>js/script.js"></script>
</head>
<body>

    <header>
        <div class="wrap">
            <a href="<?php echo URL; ?>"><h1 class="logo">Scaffold</h1></a>

            <ul class="buttons">
                <li>
                    <a href="#" class="button">Download</a>
                </li>
            </ul>
        </div>
    </header>

    <nav>
        <div class="wrap">
            <?php echo build($tree, $crumbs, true, function($ul) {
                return '<ul>' . $ul . '</ul>';
            }, $error); ?>
            <div class="clear"></div>
        </div>
    </nav>

    <div class="wrap">
        <div class="body">
            <?php

            if ($error) $body = '<h1>Uh, oh</h1><p>Sorry, the documentation you were looking for could not be found.</p>';
            
            if (isset($page_tree)) {
                echo '<ul class="page_tree">' . build($page_tree) . '</ul>';
            } else {
                echo '<div class="doc">' . $body . '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>