<?php
include('../models/Favorite.php');
include('../models/Response.php');
include('../Database/Database.php');
include('../models/Product.php');
include('../models/Comments.php');


$db = new Database();
$favorite = new Favorite($db->connect());
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $favorite->user_id = $data->user_id;
    $favorite->product_id = $data->product_id;
    $response = $favorite->addFavorite();
} elseif ($method == "DELETE") {
    $response = $favorite->deleteFavorite($_GET['id']);
} elseif (isset($_GET['product_id'])) {
    $response = $favorite->getFavorite($_GET['product_id'], $_GET['user_id']);
} else {
    $response = $favorite->getFavorites($_GET['user_id']);
}
echo json_encode($response->data);
