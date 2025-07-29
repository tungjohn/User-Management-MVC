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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<script type="text/javascript">
    // sau khi render xong view thì sẽ chạy đoạn này
    document.addEventListener('DOMContentLoaded', function() {
        let actionAlert = "<?php echo $action ?>";
        if (actionAlert != '') {
            let icon = "<?php echo $icon ?>";
            let message = "<?php echo $message ?>";
            
            if (icon != '' && message != '') {
                Swal.fire({
                    icon: icon,
                    title: actionAlert,
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
        }
    });
    
</script>