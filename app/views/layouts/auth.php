<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?></title>
    <link rel="stylesheet" href="<?php echo _ASSET_DIR ?>/clients/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _ASSET_DIR ?>/clients/css/style.css">
    <link rel="stylesheet" href="<?php echo _ASSET_DIR ?>/clients/css/auth.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
</head>
<body>
    
    <main class="py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <?php $this->render($content, $params); ?>
                </div>
            </div>
        </div>
    </main>
    
</body>
<script src="<?php echo _PUBLIC_ROOT . '/assets/clients/js/' ?>script.js"></script>
</html>