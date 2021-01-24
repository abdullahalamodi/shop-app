<?php

// Create connection

class UploadeImage
{
    static public function save($imageFile)
    {
        $resposne = new Response();
        $imageName = uniqid(); //generate uniq is for image
        $imageExtention = pathinfo($imageFile["name"], PATHINFO_EXTENSION);
        $imagePath = "../assets/images/$imageName." . $imageExtention;
        $imageUrl = "assets/images/$imageName." . $imageExtention;
        // $ServerURL = "https://androidjsonblog.000webhostapp.com/$ImagePath";
        $serverURL = "http://localhost/profitable_shopping_api/$imageUrl";
        try {
            $saved = move_uploaded_file($imageFile["tmp_name"], $imagePath);
            if ($saved) {
                $resposne->case = true;
                $resposne->data = $serverURL;
            } else {
                $resposne->case = false;
                $resposne->data = "filed to save image";
            }
        } catch (Exception $e) {
            $resposne->case = false;
            $resposne->data = "filed to save image : $e";
        }
        return $resposne;
    }

    static public function remove($my_url)
    {
        $resposne = new Response();
        // $ServerURL = "https://androidjsonblog.000webhostapp.com/$ImagePath";
        $path = substr($my_url, strrpos($my_url, '/') + 1) . "\n";
        $path = dirname(__FILE__, 2) . "\\assets\images\\" . $path;
        try {
            $removed = unlink($path);
            if ($removed) {
                $resposne->case = true;
                $resposne->data = "removed";
            } else {
                $resposne->case = false;
                $resposne->data = "filed to save image";
            }
        } catch (Exception $e) {
            $resposne->case = false;
            $resposne->data = "filed to save image : $e";
        }
        return $resposne;
    }
}
