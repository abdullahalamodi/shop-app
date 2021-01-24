<?php
class Complains
{
    public $id;
    public $title;
    private $database;

    function __construct($db)
    {
        $this->database = $db;
    }

    public function getComplains()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from complains");
            //on success
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get complains";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getComplain($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from complains where id=?");
            //on success
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get complains";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }




    public function addComplain()
    {
        $response = new Response();

        try {
            $query = $this->database->prepare("INSERT into  
            `complains`(`title`)
             VALUES (?)");
            if ($query->execute([
                $this->title,
            ])) {
                $response->case = true;
                $response->data = "complains add successfuly";
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add report";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }


    public function deleteReports($id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete complains";
        $query = $this->database->prepare("DELETE from complains where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = "delete complains successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
