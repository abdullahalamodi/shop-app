<?php
include('../models/Order.php');
include('../models/Response.php');

$order = new Order();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $order->user_id = $data->user_id;
    //pay
    if (isset($_GET['id'])) {
        $response = $order->pay($_GET['id']);
    } else {
        // add if last is paid
        $response = $order->addOrder();
    }
    // add
} elseif ($method == "DELETE") {
    $response = $order->deleteOrder($_GET['id']);
} else {
    if (isset($_GET['id'])) {
        $response = $order->getOrder($_GET['id']);
    } else {
        $response = $order->getOrders($_GET['user_id']);
    }
}
echo json_encode($response->data);