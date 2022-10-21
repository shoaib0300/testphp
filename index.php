<?php
include_once __DIR__ .'/includes/headers.php';
include_once 'controllers/HomeController.php';

global $conn;

$homeController = new HomeController($conn);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .links a {
            padding: 0;
        }
    </style>
</head>
<body>

<div class="links">
    <a href="<?php echo BASE_URL; ?>/index.php">View</a>
    <a href="<?php echo BASE_URL; ?>/create.php">Create</a>
</div>

<?php

$data = $homeController->index();

$customer = $product = $price = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer = $_POST['customer'] ?? '';
    $product = $_POST['product'] ?? '';
    $price = $_POST['price'] ?? '';
    $arr = ['price' => $price, 'product' => $product, 'customer' => $customer];
    $data = $homeController->filter($arr);
}

?>


<div style="margin-top: 10px;">
    <form method="post">
        <input type="text" name="customer" placeholder="Customer" value="<?php echo $customer; ?>">
        <input type="text" name="product" placeholder="Product" value="<?php echo $product; ?>">
        <input type="text" name="price" placeholder="Price" value="<?php echo $price; ?>">
        <input type="submit" name="submit" value="Filter" >
    </form>
</div>

<table style="margin-top: 10px;" border="1">
    <tr>
        <td>Product</td>
        <td>Price</td>
        <td>Customer</td>
    </tr>
    <?php
        $total = 0;
        foreach($data as $value):
            $total += $value['price'];
    ?>
        <tr>
            <td><?php echo $value['productName']; ?></td>
            <td><?php echo $value['price']; ?></td>
            <td><?php echo $value['customerName']; ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2">
            <strong>Total:</strong>
        </td>
        <td>
            <?php echo $total; ?>
        </td>
    </tr>
</table>

</body>
</html>






