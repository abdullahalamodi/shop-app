<?php
include('../models/Response.php');
include('../models/ProductImage.php');
include('../Database/Database.php');

$db = new Database();
$database = $db->connect();

$productImage = new ProductImage($database);
$response = new Response();
$response->data = "wrong request";
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    if (isset($_FILES["image"]) && isset($_POST["product_id"])) {
        $productImage->product_id = $_POST["product_id"];
        $response = $productImage->addImage($_FILES["image"]);
    }
}
echo json_encode($response->data);
