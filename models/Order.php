<?php
include('../Database/Database.php');
include('OrderDetails.php');
class Order
{
    public $id;
    public $date;
    public $user_id;
    public $is_paid;
    private $orderDetails;
    private $database;

    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
        $this->orderDetails = new OrderDetails();
    }

    public function getOrders($user_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from orders where user_id=?");
            //on success
            if ($query->execute([$user_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get orders";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getOrder($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from orders where id=?");
            //on success
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get order";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getLastOrder()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from orders ORDER BY id DESC LIMIT 1");
            //on success
            if ($query->execute()) {
                $data = $query->fetch(PDO::FETCH_OBJ);
                if ($data->is_paid) {
                    $this->addOrder();
                    $this->getLastOrder();
                } else {
                    $response->case = true;
                    $response->data = $data->id;
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get order";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }




    public function addOrder()
    {
        $response = new Response();
        $date = date('Y-m-d H:i');
        try {
            $query = $this->database->prepare("INSERT into  
            `orders`( `date`,`user_id`,`is_paid`)
             VALUES (?,?,?)");
            if ($query->execute([
                $date,
                $this->user_id,
                false
            ])) {
                $response->case = true;
                $response->data = $this->getLastOrder();
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add order";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response->data;
    }

    public function pay($id)
    {
        $response = new Response();

        try {
            $query = $this->database->prepare("UPDATE `orders` SET `is_paid`=true WHERE id = ?");
            if ($query->execute([
                $id
            ])) {
                $response->case = true;
                $response->data = "order add successfuly";
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add order";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }


    public function deleteOrder($id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete order";
        $this->orderDetails->deleteOrderDetails($id);
        $query = $this->database->prepare("DELETE from orders where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = "delete order successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
