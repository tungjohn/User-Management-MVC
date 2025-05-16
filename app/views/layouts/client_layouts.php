<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?></title>
    <link rel="stylesheet" href="<?php echo _ASSET_DIR ?>/clients/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _ASSET_DIR ?>/clients/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
</head>
<body>
    <?php $this->render('block/header'); ?>
    
    <main class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <?php $this->render('block/sidebar'); ?>      
                </div>
                <div class="col-9">
                    <?php $this->render($content, $params); ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php $this->render('block/footer'); ?>
</body>
<script src="<?php echo _PUBLIC_ROOT . '/assets/clients/js/' ?>script.js"></script>
</html>