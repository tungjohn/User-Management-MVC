<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?></title>
    <link rel="stylesheet" href="<?php echo _PUBLIC_ROOT . '/assets/clients/css/' ?>style.css">
</head>
<body>
    <?php $this->render('block/header'); ?>
    <?php $this->render($content, $params); ?>
    <?php $this->render('block/footer'); ?>
</body>
<script src="<?php echo _PUBLIC_ROOT . '/assets/clients/js/' ?>script.js"></script>
</html>