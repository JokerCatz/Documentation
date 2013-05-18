<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Documentation</title>
</head>
<body>

    <?php

        function build($arr) {

            $out = '<ul>';

            foreach ($arr as $title => $pages) {
                $out .= '<li>';

                $url = URL . (is_string($pages[1]) ? $pages[1] : $pages[0]);

                $out .= '<a href="' . $url . '">' . $title . '</a>';

                if (is_array($pages[1])) $out .= build($pages[1]);

                $out .= '</li/>';
            }

            $out .= '</ul>';

            return $out;
        }

        echo build($tree);
    ?>

    <div class="body">
    <?php if ($error): ?>
            Sorry, the documentation you were looking for cannot be found.
    <?php elseif (!$page): ?>
        <?php echo build(current($branch)[1]); ?>
    <?php else: ?>
        <?php echo file_get_contents('docs/' . $branch[0]); ?>
    <?php endif; ?>
    </div>
</body>
</html>