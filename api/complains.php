<?php
include('../models/Complains.php');
include('../models/Response.php');

$complain = new Complains();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $complain->complain_id = $data->text;
    // add
    $response = $complain->addComplain();
} elseif ($method == "DELETE") {
    // $response = $complain->deleteComplains($_GET['to_id']);
} else {
    if (isset($_GET['id'])) {
        $response = $complain->getComplain($_GET['id']);
    } else {
        $response = $complain->getComplains();
    }
}
echo json_encode($response->data);
