<?php
include('Product.php');
class OrderDetails
{
    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $size;
    public $color;
    public $price;
    public $total_price;
    public $product;
    private $database;


    function __construct($db)
    {
        $this->database = $db;
        $this->product = new Product($db);
    }

    public function getOrederDetails($order_id, $user_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT order_details.* 
            from order_details,orders 
            where order_details.order_id = orders.id 
            and order_details.order_id=? 
            and orders.user_id=?");
            //on success
            if ($query->execute([$order_id, $user_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $details) {
                    //get images for each product and put them inside product data
                    $details->product = $this->product->getProduct($details->product_id)->data;
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get order_details";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getOneOrederDetails($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from order_details where id=?");
            //on success
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get order_details";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }




    public function addOrderDetails()
    {
        $response = new Response();
        $total = ($this->price * $this->quantity);
        try {
            $query = $this->database->prepare("INSERT into  
            `order_details`(`order_id`, `product_id`, `quantity`, `size`, `color`, `price`,`total_price`)
             VALUES (?,?,?,?,?,?,?)");
            if ($query->execute([
                $this->order_id,
                $this->product_id,
                $this->quantity,
                $this->size,
                $this->color,
                $this->price,
                $total
            ])) {
                $response->case = true;
                $response->data = "order_details add successfuly";
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add order_details";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }


    public function deleteOrderDetails($order_id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete order_details";
        $query = $this->database->prepare("DELETE from order_details where order_id=?");
        try {
            if ($query->execute([$order_id])) {
                $response->case = true;
                $response->data = "delete order_details successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
