<?php
include('../Database/Database.php');
class Comments
{
    public $id;
    public $title;
    public $rate;
    public $user_id;
    public $product_id;
    public $date;
    private $database;


    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getComments($product_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from comments where product_id=?");
            //on success
            if ($query->execute([$product_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get comments";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getComment($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from comments where id=?");
            //on success
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get comments";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }




    public function addComment()
    {
        $response = new Response();
        $date = date('Y-m-d H:i');
        try {
            $query = $this->database->prepare("INSERT into  
            `comments`(`title`, `rate`, `user_id`, `product_id`, `date`)
             VALUES (?,?,?,?,?)");
            if ($query->execute([
                $this->title,
                $this->rate,
                $this->user_id,
                $this->product_id,
                $date
            ])) {
                $response->case = true;
                $response->data = "coments add successfuly";
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    // public function updateProduct($id)
    // {
    //     $response = new Response();
    //     $product = $this->getCommnet($id)->data;
    //     ($this->name != null) ? $product->name =  $this->name : "";
    //     ($this->quantity != null) ? $product->quantity =  $this->quantity : "";
    //     ($this->description != null) ? $product->description =  $this->description : "";
    //     ($this->rial_price != null) ? $product->rial_price =  $this->rial_price : "";
    //     ($this->dollar_price != null) ? $product->dollar_price =  $this->dollar_price : "";
    //     ($this->category_id != null) ? $product->category_id =  $this->category_id : "";
    //     ($this->user_id != null) ? $product->user_id =  $this->user_id : "";
    //     try {
    //         $response->case = false;
    //         $response->data = "fieled to update product";
    //         $query = $this->database->prepare("UPDATE `products` SET 
    //         `name`=?,
    //         `quantity`=?,
    //         `description`=?,
    //         `rial_price`=?,
    //         `dollar_price`=?,
    //         `category_id`=?,
    //         `user_id`=?
    //          WHERE id = ?");
    //         if ($query->execute([
    //             $product->name,
    //             $product->quantity,
    //             $product->description,
    //             $product->rial_price,
    //             $product->dollar_price,
    //             $product->category_id,
    //             $product->user_id,
    //             $id
    //         ])) {
    //             $response->case = true;
    //             $response->data = $id;
    //         }
    //     } catch (PDOException $e) {
    //         $response->case = false;
    //         $response->data = "request fieled cuse : $e";
    //     }
    //     return $response;
    // }

    public function deleteComments($product_id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete comments";
        $query = $this->database->prepare("DELETE from comments where product_id=?");
        try {
            if ($query->execute([$product_id])) {
                //delete images product
                // if ($this->productImages->deleteImages($id)) {
                $response->case = true;
                $response->data = "delete comments successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
