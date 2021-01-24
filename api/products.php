<?php
include('../models/Product.php');
include('../models/Response.php');

$product = new Product();
$method = $_SERVER['REQUEST_METHOD'];
$response = new Response();
//post
if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    // update
    if (isset($_GET['is_active'])) {
        $response = $product->updateProductCase($_GET['id'], $_GET['is_active']);
        // add
    } elseif (isset($_GET['id'])) {
        $product->name = $data->name;
        $product->description = $data->description;
        $product->quantity = $data->quantity;
        $product->dollar_price = $data->dollar_price;
        $product->rial_price = $data->rial_price;
        $product->category_id = $data->category_id;
        $product->user_id = $data->user_id;
        $response = $product->updateProduct($_GET['id']);
    } else {
        $product->name = $data->name;
        $product->description = $data->description;
        $product->quantity = $data->quantity;
        $product->dollar_price = $data->dollar_price;
        $product->rial_price = $data->rial_price;
        $product->category_id = $data->category_id;
        $product->user_id = $data->user_id;

        $response = $product->addProduct();
    }
} elseif ($method == "DELETE") {
    $response = ($product->deleteProduct($_GET['id']));
} else {
    if (isset($_GET['id'])) {
        $response = $product->getProduct($_GET['id']);
    } elseif (isset($_GET['user_id'])) {
        $response = $product->getUserProducts($_GET['user_id']);
    } elseif (isset($_GET['category_id'])) {
        $response = $product->getProductsByCategory($_GET['category_id']);
    } elseif (isset($_GET['manage'])) {
        $response = $product->getProductsForManage();
    } else {
        $response = $product->getProducts();
    }
}
echo json_encode($response->data);
