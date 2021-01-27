<?php
class Favorite
{
    public $id;
    public $user_id;
    public $product_id;
    public $product;
    private $database;

    function __construct($db)
    {
        $this->database = $db;
        $this->product = new Product($db);
    }

    public function getFavorites($user_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from favorites where user_id=?");
            //on success
            if ($query->execute([$user_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $favorite) {
                    //get products for each favorite and put them inside fafvorite data
                    $favorite->product = $this->product->getProduct($favorite->product_id)->data;
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get favorite ";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getfavorite($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from favorites where id=?");
            //on success
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get favorite";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getFavoriteByProductId($product_id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "not exist";
        try {
            $query = $this->database->prepare("SELECT id from favorites where product_id=?");
            //on success
            if ($query->execute([$product_id])) {
                if ($query->rowCount() > 0) {
                    $response->case = true;
                    $response->data = "exist";
                }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
    public function getLastFavorite()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from favorites ORDER BY id DESC LIMIT 1");
            //on success
            if ($query->execute()) {
                $data = $query->fetch(PDO::FETCH_OBJ);

                $response->case = true;
                $response->data = $data->id;
            } else {
                //on failure
                $response->case = false;
                $response->data = "-1";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function addFavorite()
    {
        $response = new Response();
        $response->case = false;
        $response->data = "exist before";
        if (!$this->getFavoriteByProductId($this->product_id)->case) {
            try {
                $query = $this->database->prepare("INSERT INTO `favorites`(`user_id`, `product_id`) 
            VALUES (?,?)");
                if ($query->execute([
                    $this->user_id,
                    $this->product_id
                ])) {
                    $response->case = true;
                    $response->data = "success";
                } else {
                    //on failure
                    $response->case = false;
                    $response->data = "failed";
                }
            } catch (PDOException $e) {
                $response->case = false;
                $response->data = "exption";
            }
        }
        return $response;
    }




    public function deleteFavorite($id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "delete favorite fieled";
        $query = $this->database->prepare("DELETE from favorites where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = "delete favorite successfuly";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
