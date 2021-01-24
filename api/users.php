<?php
include('../models/User.php');
$user = new User();
$method = $_SERVER['REQUEST_METHOD'];
//post
if (isset($_POST) && !empty($_POST)) {
    //update
    if ($_GET['id'] >= 0) {
        $user->name = $_POST['name'];
        $user->location = $_POST['location'];
        $user->phone = $_POST['phone'];

        if ($user->updateUser($_GET['id'])) {
            echo "user updated successfuly ^_9";
        } else {
            echo "filed to update user !!";
        }
        // add
    } else {
        $user->id = $_POST['id'];
        $user->name = $_POST['name'];
        $user->location = $_POST['location'];
        $user->phone = $_POST['phone'];

        if ($user->addUser()) {
            echo "user added successfuly ^_9";
        } else {
            echo "filed to add user !!";
        }
    }
} elseif ($method == "DELETE") {
    if ($user->deleteUser($_GET['id'])) {
        echo "user deleted successfuly ^_9";
    } else {
        echo "filed to delete user !!";
    }
} else {
    if (isset($_GET['id'])) {
        $data = $user->getUser($_GET['id']);
    } else {
        $data = $user->getUsers();
    }
    echo json_encode($data->data);
}
