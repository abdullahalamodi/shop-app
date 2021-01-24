<?php
include('../models/Category.php');
include('../models/Response.php');
include('../Database/Database.php');


$db = new Database();
$category = new Category($db->connect());
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $category->name = $data->name;
    //update
    if (isset($_GET['id'])) {
        $response = $category->updateCategory($_GET['id']);
    } else {
        // add
        $response = $category->addCategory();
    }
} elseif ($method == "DELETE") {
    $response = $category->deleteCategory($_GET['id']);
} else {
    if (isset($_GET['id'])) {
        $response = $category->getCategoryById($_GET['id']);
    } else {
        $response = $category->getCategories();
    }
}
echo json_encode($response->data);
