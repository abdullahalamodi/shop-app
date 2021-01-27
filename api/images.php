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
    if (isset($_POST["product_id"])) {
        $productImage->product_id = $_POST["product_id"];
        if (isset($_FILES["image1"]))
            $response = $productImage->addImage($_FILES["image1"]);
        if (isset($_FILES["image2"]))
            $response = $productImage->addImage($_FILES["image2"]);
        if (isset($_FILES["image3"]))
            $response = $productImage->addImage($_FILES["image3"]);
    }
}
echo json_encode($response->data);
