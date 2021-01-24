<?php
include('../models/Report.php');
include('../models/Response.php');

$report = new Report();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $report->from_id = $data->from_id;
    $report->to_id = $data->to_id;
    $report->product_id = $data->product_id;
    $report->complain_id = $data->complain_id;
    // add
    $response = $report->addReport();
} elseif ($method == "DELETE") {
    $response = $report->deleteReports($_GET['to_id']);
} else {
    if (isset($_GET['id'])) {
        $response = $report->getReport($_GET['id']);
    } else if (isset($_GET['to_id'])) {
        $response = $report->getUserReports($_GET['to_id']);
    } else if (isset($_GET['product_id'])) {
        $response = $report->getProductReports($_GET['product_id']);
    }
}
echo json_encode($response->data);
