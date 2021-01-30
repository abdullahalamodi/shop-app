<?php
include('CategoryImage.php');
class Category
{
    public $id;
    public $name;
    public $image;
    public $is_active;

    private $database;

    function __construct($db)
    {
        $this->database = $db;
    }

    public function getCategories()
    {
        return $this->executeFunction(
            "SELECT * from categories where is_active=true",
            null,
            true,
            true
        );
    }

    public function getCategoryById($id)
    {
        return $this->executeFunction(
            "SELECT * from categories where id=?",
            [$id],
            true
        );
    }

    public function getCategoryByTitle($title)
    {
        return $this->executeFunction(
            "SELECT * from categories where name like ?",
            [$title],
            true
        );
    }

    private function getLastCategoryId()
    {
        $response = new Response();

        $query = $this->database->prepare("SELECT MAX(id) as id from categories");
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

    public function addCategory()
    {
        $response = $this->executeFunction(
            "INSERT INTO `categories`(`name`) VALUES (?)",
            [$this->name]
        );
        if ($response->case) {
            return $this->getLastCategoryId();
        }
    }

    public function updateCategory($id)
    {
        $response = $this->getCategoryById($id);
        if ($response->case) {
            ($this->name != null) ? $response->data->name =  $this->name : null;
            return
                $this->executeFunction(
                    "UPDATE `categories` SET `name`=? WHERE id = ?",
                    [$response->data->name, $id]
                );
        }
        return $response;
    }
    public function updateCategoryCase($id, $is_active)
    {
        return
            $this->executeFunction(
                "UPDATE `categories` SET `is_active`=? WHERE id = ?",
                [$is_active, $id]
            );
    }

    public function deleteCategory($id)
    {
        // $url = $this->executeFunction(
        //     "SELECT * from categories where id=?",
        //     [$id],
        //     true
        // )->data->path;
        // $this->categoryImage->deleteImage($url);
        return
            $this->executeFunction(
                "DELETE FROM categories WHERE id=?",
                [$id]
            );
    }

    private function executeFunction(
        String $queryText,
        array $params = null,
        bool $isData = false,
        bool $isList = false,
        string $fieledMessage = "request fieled",
        string $successMessage = "request success"
    ) {
        $response = new Response();
        try {
            $query = $this->database->prepare($queryText);
            if ($query->execute($params)) {
                $response->case = true;
                if ($isData) {
                    if ($isList) {
                        $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                    } else {
                        $response->data = $query->fetch(PDO::FETCH_OBJ);
                    }
                } else
                    $response->data = $successMessage;
            } else {
                $response->case = false;
                $response->data = $fieledMessage;
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "$fieledMessage cuse : $e";
        }
        return $response;
    }
}
