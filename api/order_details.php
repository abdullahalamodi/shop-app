<?php
include('../models/OrderDetails.php');
include('../models/Response.php');
include('../Database/Database.php');


$db = new Database();
$orderDetails = new OrderDetails($db->connect());
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $orderDetails->order_id = $data->order_id;
    $orderDetails->product_id = $data->product_id;
    $orderDetails->quantity = $data->quantity;
    $orderDetails->size = $data->size;
    $orderDetails->color = $data->color;
    $orderDetails->price = $data->price;
    // add
    $response = $orderDetails->addOrderDetails();
} elseif ($method == "DELETE") {
    $response = $orderDetails->deleteOrderDetails($_GET['order_id']);
} else {
    if (isset($_GET['id'])) {
        $response = $orderDetails->getOneOrederDetails($_GET['id']);
    } else {
        $response = $orderDetails->getOrederDetails($_GET['order_id']);
    }
}
echo json_encode($response->data);
