<?php
include('../Database/Database.php');
class User
{
    public $id;
    public $name;
    public $location;
    public $phone;
    private $database;


    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getUsers()
    {
        $query = $this->database->prepare("select * from users");
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getUser($id)
    {
        $query = $this->database->prepare("select * from users where id=?");
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_OBJ);
        return $data;
    }

    public function addUser()
    {
        try {
            $query = $this->database->prepare("insert into users values(?,?,?)");
            $query->execute([
                $this->name,
                $this->location,
                $this->phone]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateUser($id)
    {
        $user = $this->getUser($id);
        ($this->title != null) ? $user->name =  $this->name : "";
        ($this->image != null) ? $user->location =  $this->location : "";
        ($this->details != null) ? $user->phone =  $this->phone : "";
        try {
            $query = $this->database->prepare("UPDATE `users` SET
             `name`=?,
             `location`=?,
             `phone`=?
             WHERE id = ?");
            $query->execute([
                $user->name,
                $user->location,
                $user->phone,
                $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteUser($id)
    {
        try {
            $query = $this->database->prepare("delete from users where id=?");
            $query->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
