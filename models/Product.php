<?php
include('ProductImage.php');
class Product
{
    public $id;
    public $name;
    public $quantity;
    public $description;
    public $dollar_price;
    public $rial_price;
    public $images;
    public $category_id;
    public $user_id;
    public $is_active;
    public $rating;
    private $database;
    private $productImages;


    function __construct($db)
    {
        $this->database = $db;
        $this->productImages = new ProductImage($this->database);
        $this->rating = new Comments($this->database);
    }

    public function getProducts()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from products
             where is_active=true
              ORDER BY id DESC LIMIT 10");
            //on success
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                    $product->rating = $this->rating->getRating($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getProductsForManage()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from products ORDER BY id DESC");
            //on success
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getUserProducts($user_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from products where user_id=?");
            //on success
            if ($query->execute([$user_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getProductsByCategory($category_id)
    {
        $response = new Response();

        $query = $this->database->prepare("SELECT * from products
         where category_id=? and is_active=true");
        try {
            if ($query->execute([$category_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                    $product->rating = $this->rating->getRating($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getProduct($id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to get product";
        $query = $this->database->prepare("SELECT * from products where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
                //get images and put them inside product data
                if (isset($response->data->id))
                    $response->data->images =
                        $this->productImages->getImages($response->data->id);
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    private function getLastProductId()
    {
        $response = new Response();

        $query = $this->database->prepare("SELECT MAX(id) as id from products");
        try {
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
                $response->data = $response->data->id;
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get id";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function addProduct()
    {
        $response = new Response();

        try {
            $query = $this->database->prepare("INSERT into  
            `products`(`name`, `quantity`, `description`, `rial_price`,
             `dollar_price`, `category_id`, `user_id`)
             VALUES (?,?,?,?,?,?,?)");
            if ($query->execute([
                $this->name,
                $this->quantity,
                $this->description,
                $this->rial_price,
                $this->dollar_price,
                $this->category_id,
                $this->user_id
            ])) {
                $idResponse = $this->getLastProductId();
                $response->case = true;
                $response->data = $idResponse->data;
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

    public function updateProductCase($id, $is_active)
    {
        $response = new Response();
        try {
            $response->case = false;
            $response->data = "fieled to update product";
            $query = $this->database->prepare("UPDATE `products` SET `is_active`=? WHERE id=?");
            if ($query->execute([
                $is_active,
                $id
            ])) {
                $response->case = true;
                $response->data = "suceess update product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function updateProduct($id)
    {
        $response = new Response();
        $product = $this->getProduct($id)->data;
        ($this->name != null) ? $product->name =  $this->name : "";
        ($this->quantity != null) ? $product->quantity =  $this->quantity : "";
        ($this->description != null) ? $product->description =  $this->description : "";
        ($this->rial_price != null) ? $product->rial_price =  $this->rial_price : "";
        ($this->dollar_price != null) ? $product->dollar_price =  $this->dollar_price : "";
        ($this->category_id != null) ? $product->category_id =  $this->category_id : "";
        ($this->user_id != null) ? $product->user_id =  $this->user_id : "";
        try {
            $response->case = false;
            $response->data = "fieled to update product";
            $query = $this->database->prepare("UPDATE `products` SET 
            `name`=?,
            `quantity`=?,
            `description`=?,
            `rial_price`=?,
            `dollar_price`=?,
            `category_id`=?,
            `user_id`=?
             WHERE id = ?");
            if ($query->execute([
                $product->name,
                $product->quantity,
                $product->description,
                $product->rial_price,
                $product->dollar_price,
                $product->category_id,
                $product->user_id,
                $id
            ])) {
                $response->case = true;
                $response->data = $id;
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function deleteProduct($id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete product";
        $query = $this->database->prepare("DELETE from products where id=?");
        try {
            if ($query->execute([$id])) {
                //delete images product
                // if ($this->productImages->deleteImages($id)) {
                $response->case = true;
                $response->data = "delete product successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
