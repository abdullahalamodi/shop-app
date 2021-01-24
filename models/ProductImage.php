<?php
include('../services/uploade_image.php');
class ProductImage
{

    public $id;
    public $path;
    public $product_id;
    private $database;
    private $response;

    function __construct($db)
    {
        $this->database = $db;
        $this->response = new Response();
    }

    public function getImages($product_id)
    {
        $query = $this->database->prepare("SELECT path from product_images where product_id=?");
        try {
            if ($query->execute([$product_id])) {
                $this->response->case = true;
                $this->response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $this->response->case = false;
                $this->response->data = "fieled to get images";
            }
        } catch (PDOException $e) {
            $this->response->case = false;
            $this->response->data = "request fieled cuse : $e";
        }
        return $this->response->data;
    }

    public function getImageById($id)
    {
        $query = $this->database->prepare("SELECT 
         from product_images where id=?");
        try {
            if ($query->execute([$id])) {
                $this->response->case = true;
                $this->response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $this->response->case = false;
                $this->response->data = "fieled to get image";
            }
        } catch (PDOException $e) {
            $this->response->case = false;
            $this->response->data = "request fieled cuse : $e";
        }
        return $this->response->data;
    }



    public function addImage($imageFile)
    {
        $this->response = UploadeImage::save($imageFile);
        if ($this->response->case) {
            $this->path = $this->response->data;
            try {
                $query = $this->database->prepare("INSERT INTO `product_images`(`path`, `product_id`) VALUES (?,?)");
                if ($query->execute([
                    $this->path,
                    $this->product_id
                ])) {
                    $this->response->case = true;
                    $this->response->data = "image add succesfuly";
                } else {
                    $this->response->case = false;
                    $this->response->data = "filed to save image path in database";
                }
            } catch (Exception $e) {
                $this->response->case = false;
                $this->response->data = "filed to save image path in database : $e";
            }
        }
        return $this->response;
    }

    //delete images for product
    public function deleteImages($product_id)
    {
        $case = false;
        try {
            $q  = $this->database->prepare("SELECT path FROM product_images WHERE product_id=?");
            if ($q->execute([$product_id])) {
                $url = $q->fetch(PDO::FETCH_OBJ)->path;
                $this->response = UploadeImage::remove($url);
                if ($this->response->case) {
                    $query = $this->database->prepare("DELETE FROM product_images WHERE product_id=?");
                    $query->execute([$product_id]);
                    $case =  true;
                }
            }
        } catch (PDOException $e) {
            $case =  false;
        }

        return $case;
    }

    public function deleteImage($id)
    {
        try {
            $query = $this->database->prepare("DELETE FROM product_images WHERE id=?");
            $query->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
