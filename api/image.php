<?php
include('../models/Response.php');
include('../models/CategoryImage.php');
include('../Database/Database.php');

$db = new Database();
$database = $db->connect();

$categoryImage = new CategoryImage($database);
$response = new Response();
$response->data = "wrong request";
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    if (isset($_FILES["image"]) && isset($_POST["category_id"])) {
        $categoryImage->category_id = $_POST["category_id"];
        $response = $categoryImage->addImage($_FILES["image"]);
    }
}
echo json_encode($response->data);
