<?php
include_once __DIR__ . '/includes/headers.php';
include_once __DIR__ . '/controllers/HomeController.php';

global $conn;

$homeController = new HomeController($conn);

?>

<div class="links">
    <a href="<?php echo BASE_URL; ?>/index.php">BACK</a>
</div>

<?php echo $homeController->create(); ?>






