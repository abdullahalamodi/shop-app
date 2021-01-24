<?php
class Report
{
    public $id;
    public $from_id;
    public $to_id;
    public $product_id;
    public $complain_id;
    public $date;
    private $database;


    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getUserReports($to_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from reports where to_id=?");
            //on success
            if ($query->execute([$to_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get reports";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
    public function getProductReports($product_id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from reports where product_id=?");
            //on success
            if ($query->execute([$product_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get reports";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getReport($id)
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("SELECT * from reports where id=?");
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


    public function addReport()
    {
        $response = new Response();
        $date = date('Y-m-d H:i');
        try {
            $query = $this->database->prepare("INSERT into  
            `reports`(`from_id`, `to_id`, `product_id`, `complain_id`, `date`)
             VALUES (?,?,?,?,?)");
            if ($query->execute([
                $this->from_id,
                $this->to_id,
                $this->product_id,
                $this->complain_id,
                $date
            ])) {
                $response->case = true;
                $response->data = "report add successfuly";
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


    public function deleteReports($to_id)
    {
        $response = new Response();
        $response->case = false;
        $response->data = "fieled to delete reports";
        $query = $this->database->prepare("DELETE from reports where to_id=?");
        try {
            if ($query->execute([$to_id])) {
                $response->case = true;
                $response->data = "delete reports successfuly";
                // }
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}
