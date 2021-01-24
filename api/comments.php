<?php
include('../models/Comments.php');
include('../models/Response.php');

$comment = new Comments();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $comment->title = $data->title;
    $comment->rate = $data->rate;
    $comment->user_id = $data->user_id;
    $comment->product_id = $data->product_id;
    // add
    $response = $comment->addComment();
} elseif ($method == "DELETE") {
    $response = $comment->deleteComments($_GET['product_id']);
} else {
    if (isset($_GET['id'])) {
        $response = $comment->getComment($_GET['id']);
    } else {
        $response = $comment->getComments($_GET['product_id']);
    }
}
echo json_encode($response->data);
