<?php
include('../services/uploade_image.php');
class CategoryImage
{
    public $category_id;
    public $path;
    private $database;
    private $response;

    function __construct($db)
    {
        $this->database = $db;
        $this->response = new Response();
    }

    public function addImage($imageFile)
    {
        $this->response = UploadeImage::save($imageFile);
        if ($this->response->case) {
            $this->path = $this->response->data;
            try {
                $query = $this->database->prepare("UPDATE `categories` SET `path` = ? where id=?");
                if ($query->execute([
                    $this->path,
                    $this->category_id
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

    public function deleteImage($url)
    {
        $this->response = UploadeImage::remove($url);

        return $this->response;
    }
}
